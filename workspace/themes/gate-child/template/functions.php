<?php

	class helloGate {

		function __construct(){

			// Custom Styles
			add_action('wp_head', [$this, 'custom_styles']);

			// Theme supports
			//add_action('after_setup_theme', [$this, 'theme_supports']);
		}

		function custom_styles(){
			//include gc::inc('custom-styles.php');
		}

		function theme_supports(){
			add_theme_support('post-thumbnails');
		}
	}

	new helloGate();
?>