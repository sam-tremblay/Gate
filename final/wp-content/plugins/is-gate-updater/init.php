<?php
/*
Plugin Name: Gate Updater
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Donne accès aux mises des plugins Gate.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-updater
*/


if (!defined('ABSPATH')) exit;


if (!defined('IGU_VERSION')) define('IGU_VERSION', '1.0');


if (!class_exists('isGateUpdater')){
	class isGateUpdater {


		function __construct(){

			add_action('admin_init', function(){
				include_once 'updaters/updater.php';

				if(file_exists(WP_PLUGIN_DIR . '/is-gate-core/init.php') && is_plugin_active('is-gate-core/init.php'))
					include_once 'updaters/core.php';

				if(file_exists(WP_PLUGIN_DIR . '/is-gate-cpt/init.php') && is_plugin_active('is-gate-cpt/init.php'))
					include_once 'updaters/cpt.php';

				if(file_exists(WP_PLUGIN_DIR . '/is-gate-role/init.php') && is_plugin_active('is-gate-role/init.php'))
					include_once 'updaters/role.php';

				if(file_exists(WP_PLUGIN_DIR . '/is-gate-seo/init.php') && is_plugin_active('is-gate-seo/init.php'))
					include_once 'updaters/seo.php';

				if(file_exists(WP_PLUGIN_DIR . '/is-gate-security/init.php') && is_plugin_active('is-gate-security/init.php'))
					include_once 'updaters/security.php';
			});

			/*
			* On plugin activation
			*/
			register_activation_hook(__FILE__, function(){

				/*
				* Active Core Plugin
				*/
				if(file_exists(WP_PLUGIN_DIR . '/is-gate-core/init.php') && !is_plugin_active('is-gate-core/init.php'))
					activate_plugin('is-gate-core/init.php');
				elseif(!file_exists(WP_PLUGIN_DIR . '/is-gate-core/init.php')){

					/*
					* If Core Plugin not There
					*/

					wp_die(
						__('L\'extension "Gate Core" est manquante.', 'is-gate-seo'),
						__('Une erreur est survenue', 'is-gate-seo'),
						[
							'back_link' => true
						]
					);

				}


			});


			add_action('admin_init', function(){

				/*
				* If "Gate" is deactivated
				*/
				if(!file_exists(WP_PLUGIN_DIR . '/is-gate-core/init.php') || !is_plugin_active('is-gate-core/init.php'))
					deactivate_plugins(plugin_basename(__FILE__));

				
			});

		}

	}

	new isGateUpdater();
}

?>