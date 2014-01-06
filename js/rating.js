(function ( $ ) {
	$(".lb_ps_rating").jRating({
         length : 5,
         bigStarsPath : 'wp-content/plugins/post-star/libs/jrating/icons/stars.png',
         phpPath : 'wp-content/plugins/post-star/post_star.php',
         step : true,
         rateMax : 5,
         decimalLength : 0
	});
}(jQuery));