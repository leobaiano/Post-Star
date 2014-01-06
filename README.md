# Post Star #
**Contributors:** leobaiano  
**Donate link:** http://lbideias.com.br/donate  
**Tags:** rating, rates, post rates, post star, star post, vote  
**Requires at least:** 3.8  
**Tested up to:** 3.8  
**Stable tag:** 1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Plugin allows users to mark the number of stars of each post and display the average.

## Description ##

The Post Star plugin aims to classify the posts with 1, 2, 3, 4 or 5 stars based on the average classification defined by site visitors.

To monitor and improve the classification plugin checks the IP of users before voting.

### Credits ###

* jRating [alpixel](https://github.com/alpixel/jRating)

### Contribute ###

You can contribute to the source code in our [GitHub](https://github.com/leobaiano/Post-Star) page.

## Installation ##

To install just follow the installation steps of most WordPress plugin's:

e.g.

1. Download the file lb-back-to-top.zip;
2. Unzip the file on your computer;
3. Upload folder post-ranking-view, you just unzip to `/wp-content/plugins/` directory;
4. Activate the plugin through the `Plugins` menu in WordPress;
5. Be happy.

### Showing the ranking of posts ###

Add the code below where you want the stars to appear:

1 - Let the plugin generate the HTML
`<?php
if ( function_exists( 'displayPostStar' ) ) {
	displayPostStar( $post->ID );
}
?>`

## Screenshots ##

###1. Plugin Post Star in action###
![Plugin Post Star in action](https://raw.github.com/leobaiano/Post-Star/master/screenshot-1.png)


## Changelog ##

### 1.0 2013-01-06 ###

* Creation of the plugin, the initial version.