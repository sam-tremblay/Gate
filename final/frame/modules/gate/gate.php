<?php
/*
Plugin Name: Gate
Plugin URI: https://gateforwp.com/
Description: Accélérez votre développement avec des outils étoffés et offrez à vos clients une administration professionnelle.
Version: 4.2
Author: deuxparquatre inc.
Author URI: https://deuxparquatre.com/
Copyright: deuxparquatre
Text Domain: gcore
*/

if (!defined( 'ABSPATH' )) exit;


define('GATE_PLUGIN_VERSION', '4.2');


if (!class_exists('gateCore_plug')){
	class gateCore_plug {
	    
		static $gateCore_settings = array(
			"plugin-enabled" => true,
		);


		// class components
		static $gateCore_classComponents = array(
			//"class/update.php",
			"class/datas.php",
			"class/connect.php",
			"class/backend.php",
			"class/frontend.php",
			"class/seo.php",
			"class/cpt.php",
			"class/roles.php",
			"class/callback.php",
		);


		function __construct(){

			if (!gateCore_plug::$gateCore_settings["plugin-enabled"]) return;

			// Incluons tout les components
			foreach (gateCore_plug::$gateCore_classComponents as $key => $component) {
				include_once $component;
			}

		}

	}

	new gateCore_plug();
}

?>