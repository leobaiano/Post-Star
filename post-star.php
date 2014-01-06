<?php
/**
 * Plugin Name: Post Star
 * Plugin URI: http://lbideias.com.br
 * Description: This plugin allows users to rate your post and generates an average to determine the quality of content.
 * Author: leobaiano
 * Author URI: http://lbideias.com.br/
 * Version: 1.0
 * License: GPLv2 or later
 * Text Domain: lb_ps
 */

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;

	// Sets the plugin path.
	define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

	/**
	 * Class Post Star
	 * @version 1.0
	 * @author Leo Baiano <leobaiano@lbideias.com.br>
	 */
	class PostStar {
		
		public function __construct() {
			add_action( 'init', array( $this, 'createTable' ) );
			add_action( 'wp_footer', array( $this, 'loadScriptRating' ) );
			add_action( 'wp_ajax_add_rate', 'ajax_add_rate' );
			add_action( 'wp_ajax_nopriv_add_rate', 'ajax_add_rate' );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );
		}

		/**
		 * Create table Rating
		 */
		public function createTable() {	
			global $wpdb;
			$tableRating = $wpdb->prefix . 'lb_ps_rating';

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$tableRating'" ) != $tableRating ) {
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				$sql = "CREATE TABLE IF NOT EXISTS `$tableRating` (
				  `id` int(11) NOT NULL,
				  `post_id` int(11) NOT NULL,
				  `rating` int(1) NOT NULL,
				  `user_ip` varchar(13) NOT NULL,
				  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `status` int(1) NOT NULL
				);";

				dbDelta( $sql );
			}
		}

		/**
		 * Load scripts
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'lb_ps', plugins_url( 'libs/jrating/jRating.jquery.css', __FILE__ ), array(), null, 'all' );
			wp_enqueue_script( 'lb_ps', plugins_url( 'libs/jrating/jRating.jquery.js', __FILE__ ), array( 'jquery' ), null, true );
		}

		public function checkVote( $postID, $userIP ) {
			global $wpdb;
			$tableRating = $wpdb->prefix . 'lb_ps_rating';
			$check = $wpdb->get_row("SELECT * FROM $tableRating WHERE post_id = '$postID' AND user_ip = '$userIP'");
			return $check;
		}

		public static function addRating( $postID, $rating ) {
			global $wpdb;
			$tableRating = $wpdb->prefix . 'lb_ps_rating';
			$userIP = $_SERVER['REMOTE_ADDR'];

			$check = self::checkVote( $postID, $userIP );
			if ( empty( $check ) ) {
				$wpdb->insert( $tableRating, array( 
						'post_id'	=> $postID,
						'rating'	=> $rating,
						'user_ip'	=> $userIP,
						'status'	=> 1
					)
				);
				return true;
			}
			else{
				return false;
			}
		}

		public static function getScorePost( $postID ) {
			global $wpdb;
			$tableRating = $wpdb->prefix . 'lb_ps_rating';

			$rates = $wpdb->get_row( "SELECT *, avg(rating) rate FROM $tableRating WHERE post_id = '$postID'" );
			if( empty( $rates->rate ) )
				return 0;
			else
				return round( $rates->rate );
		}

		public function loadScriptRating() {
			$disable = 'false';
			if( is_single() ){
				$postID = get_the_ID();
				$userIP = $_SERVER['REMOTE_ADDR'];
				$check = self::checkVote( $postID, $userIP );
				if ( !empty( $check ) )
					$disable = 'true';
			}
			echo '
						<script>
							jQuery(document).ready(function(){
								jQuery(".lb_ps_rating").jRating({
							         length : 5,
							         bigStarsPath : "' . plugins_url("libs/jrating/icons/stars.png", __FILE__ ) . '",
							         phpPath : "' . plugins_url("post-star/post_star.php", __FILE__ ) . '",
							         step : true,
							         rateMax : 5,
							         decimalLength : 0,
							         isDisabled : ' . $disable . '
								});
							});
						</script>
					';
		}
	}
	new PostStar;

	function displayPostStar( $postID) {
		$urlAjaxAdmin = admin_url('admin-ajax.php');
		$score = PostStar::getScorePost( $postID );
		$view .= '<section class="lb_ps_container">';
			$view .= '<div class="lb_ps_rating" data-average="' . $score . '" data-id="' . $postID . '" data-urlAdmin="'. $urlAjaxAdmin . '"></div>';
		$view .= '</section>';
		echo $view;
	}

	function ajax_add_rate() {
		$postID = $_GET['postID'];
		$rating = $_GET['rating'];
		$addVote = PostStar::addRating( $postID, $rating );
		if( $addVote )
			$status[] = array(
				"status" => 1,
				"menssage" => "Success: Your vote has been counted."
			);
		else
			$status[] = array(
				"status" => 0,
				"menssage" => "Error: This IP has already voted for this post."
			);
		echo json_encode($status);
		die();
	}















