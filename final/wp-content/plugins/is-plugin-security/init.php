<?php
/*
Plugin Name: Gate Security
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Crée un ID unique pour le visiteur et permet de vérifier si celui-ci Spam ou non.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-security
*/


if (!defined('ABSPATH')) exit;


if (!defined('IGSEC_VERSION')) define('IGSEC_VERSION', '1.0');

if (!class_exists('gateSecurity')){
	class gateSecurity{

		private $length = 64;
		private $min = 0;
		private $max = 9999999999999;
		private $requesters_file = __DIR__ . '/datas/requesters.json';

		
		function __construct(){

			add_action('init', function(){

				/*
				* If visitor has id set, but no requesters file found
				*/
				if($this->visitor_id() && !file_exists($this->requesters_file))
					unset($_COOKIE['gate_visitor_id']);
				elseif($this->visitor_id()){

					/*
					* Get requesters file datas
					*/
					$requesters = json_decode(file_get_contents($this->requesters_file));

					/*
					* If visitor id not found in requesters file
					*/
					$found = false;
					
					foreach ($requesters as $requester) {
						if($this->visitor_id() === $requester->code){
							$found = true;
							break;
						}
					}

					if(!$found)
						unset($_COOKIE['gate_visitor_id']);
				}
				
				if(!$this->visitor_id())
					$this->create_visitor_id();
			});


			/*
			* Refresh requesters list each hour on next page load
			*/
			add_action('requesters_list_updater', function(){
				
				if(!file_exists($this->requesters_file)) return;
				$requesters = json_decode(file_get_contents($this->requesters_file));

				if(empty($requesters) || !is_array($requesters)) return;


				foreach ($requesters as $requester_key => $requester) {

					if($this->visitor_id() === $requester->code && $requester->created_time < MONTH_IN_SECONDS)
						unset($requesters[$requester_key]);

				}


				file_put_contents($this->requesters_file, json_encode(array_values($requesters)));


			});

			if(!wp_next_scheduled('requesters_list_updater'))
				wp_schedule_event(time(), 'hourly', 'requesters_list_updater');



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


		private function create_visitor_id(){
			$code = 'b-'.substr(str_shuffle(random_int($this->min, $this->max) . 'abcdDefg' . bin2hex(random_bytes($this->length)) . 'hijklmnop' . bin2hex(openssl_random_pseudo_bytes($this->length)) . 'qrstuvVwxyz'), 0, 32);

			/*
			* Save Code in cookie
			*/
			setcookie('gate_visitor_id', $code, time() + MONTH_IN_SECONDS, '/', str_replace(['https', 'http', '/www.', '/', ':'], '', get_bloginfo('url')));


			/*
			* In the same time, save code in a json file
			*/
			if(!file_exists($this->requesters_file))
				$file_created = fopen($this->requesters_file, 'w');

			$requesters = json_decode(file_get_contents($this->requesters_file));
			$requesters = !empty($requesters) ? $requesters : [];
			
			$requesters = array_merge($requesters, [['code' => $code, 'created_time' => time()]]);


			file_put_contents($this->requesters_file, json_encode($requesters));

		}


		private function visitor_id(){

			return isset($_COOKIE['gate_visitor_id']) ? $_COOKIE['gate_visitor_id'] : null;

		}


		function check_requester(){

			
			/*
			* If visitor id not set
			*/
			if(!$this->visitor_id()) return false;
			

			/*
			* If requesters file not found
			*/
			if(!file_exists($this->requesters_file)) return false;
			$requesters = json_decode(file_get_contents($this->requesters_file));
			
			
			/*
			* If requesters file doesn't what expected.
			*/
			if(empty($requesters) || !is_array($requesters)) return false;
			

			/*
			* If visitor id not found in requesters file
			*/
			$found = false;
			$visitor_id_key = null;
			foreach ($requesters as $requester_key => $requester) {
				if($this->visitor_id() === $requester->code){
					$found = true;
					$visitor_id_key = $requester_key;
					break;
				}
			}

			if(!$found) return false;

			


			/*
			* Ok, now that you know the visitor is good,
			* limit his actions:
			* 
			* - Request 1 time per minute
			* - Request 3 times maximum per hour.
			*/
			if(isset($requesters[$visitor_id_key]->last_post_time) && isset($requesters[$visitor_id_key]->post_times)){
				$diff = time() - $requesters[$visitor_id_key]->last_post_time;

				if($requesters[$visitor_id_key]->post_times < 3 && $diff < MINUTE_IN_SECONDS)
					return false;
				elseif($requesters[$visitor_id_key]->post_times === 3 && $diff < HOUR_IN_SECONDS)
					return false;
				else {
					$requesters[$visitor_id_key]->last_post_time = time();

					if($requesters[$visitor_id_key]->post_times === 3)
						$requesters[$visitor_id_key]->post_times = 1;
					else
						$requesters[$visitor_id_key]->post_times = $requesters[$visitor_id_key]->post_times + 1;
				}
			} else {
				$requesters[$visitor_id_key]->last_post_time = time();
				$requesters[$visitor_id_key]->post_times = 1;
			}

			file_put_contents($this->requesters_file, json_encode($requesters));

			


			return true;
		}
	}

	new gateSecurity();
}
?>