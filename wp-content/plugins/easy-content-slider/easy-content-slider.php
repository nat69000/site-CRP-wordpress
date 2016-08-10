<?php
/*
Plugin Name: Easy Content Slider
Plugin URI: #
Description: Easy Content Slider plugin is a wordpress plugin designed to create interactive content slider functionality with category in to your wordpress website with responsive.
Version: 1.3
Author: Amit Patel
Author URI: http://www.facebook.com/patel1304
License: GPLv2 or later
*/
define('SLIDER_DIR', dirname(__FILE__));
define('SLIDER_INC_DIR', SLIDER_DIR . "/inc");
define('SLIDER_URL', WP_PLUGIN_URL . "/" . basename(SLIDER_DIR));
define('EC_CONTENT_VERSION', '1.1');


//All ShortCode are show
add_shortcode('easy_slider', 'ecs_easy_slider');

//Method And Action Are Call
add_action('wp_enqueue_scripts', 'ecs_scripts');

//All js and Css Are call
function ecs_scripts() {
	//wp_enqueue_script('ecs_content_scripts',SLIDER_URL.'/js/jquery.min.js', array('jquery'), EC_CONTENT_VERSION); 
	wp_enqueue_script('ecs_content_scripts',SLIDER_URL.'/js/script.js', array('jquery'), EC_CONTENT_VERSION); 
	wp_enqueue_script('ecs_slider_scripts',SLIDER_URL.'/js/ecslider.js', array('jquery'), EC_CONTENT_VERSION); 
	wp_enqueue_script('ecs_easy_scripts',SLIDER_URL.'/js/ecslider.min.js', array('jquery'), EC_CONTENT_VERSION); 
	wp_enqueue_style('slider_style', SLIDER_URL . "/css/ecslider.css");
	wp_enqueue_style('slider_style', SLIDER_URL . "/css/ecslider.min.css");
	
}

function ecs_easy_slider($param){
//content slider
include("inc/slider.php");
}
?>