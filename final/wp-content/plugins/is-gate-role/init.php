<?php
/*
Plugin Name: Gate Role
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Création ou suppression des rôles. Restauration des rôles par défaut.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-role
*/

if (!defined('ABSPATH')) exit;


if (!defined('IGR_VERSION')) define('IGR_VERSION', '1.0');


if (!class_exists('isGateROLE')){
	class isGateROLE{

		function __construct(){

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
						__('L\'extension "Gate Core" est manquante.', 'is-gate-role'),
						__('Une erreur est survenue', 'is-gate-role'),
						[
							'back_link' => true
						]
					);

				}

			});


			add_action('admin_init', function(){

				global $wp_roles;


				if(!function_exists('populate_roles'))
					require_once(ABSPATH . 'wp-admin/includes/schema.php');


				/*
				* If "Gate" is deactivated
				*/
				if(!file_exists(WP_PLUGIN_DIR . '/is-gate-core/init.php') || !is_plugin_active('is-gate-core/init.php'))
					deactivate_plugins(plugin_basename(__FILE__));


				/*
				* Reset Roles
				*/
				$get = isset($_GET['role']) ? $_GET['role'] : null;

				if($get === 'reset' && current_user_can('manage_options')){

					$all_roles = $wp_roles->roles;
					$no_reset = [
						'administrator',
						'editor',
						'author',
						'contributor',
						'subscriber'
					];

					foreach($all_roles as $k => $v){
						if(!in_array($k, $no_reset))
							remove_role($k);
			    	}

					populate_roles();

				}


				/*
				* Delete default roles
				*/
				if(isset($_POST['activate']) && $_POST['activate'] === 'edit-role-to-delete' && current_user_can('manage_options')){

					populate_roles();

					$is = $_POST['is'];
					$checked = $_POST['checked'];
					$roles = json_decode(file_get_contents(__DIR__ . '/datas/default-roles.json'));
					$roles->{$is}->selected = $checked === 'true' ? true : false;
					file_put_contents(__DIR__ . '/datas/default-roles.json', json_encode($roles));

					foreach ($roles as $role_key => $role_value) {
						if($role_value->selected)
							remove_role($role_key);
					}

					exit;
				}


				
			});



			add_action('acf/init', function(){

				/*
				* Add Role manager
				*/
				$labels = array(
					'name' => __('Roles', 'is-gate-role'),
			        'singular_name' => __('Role', 'is-gate-role')
				);
				$args  = array(
					'labels' => $labels,
					'description' => '',
			        'public' => false,
			        'publicly_queryable' => false,
			        'show_ui' => true,
			        'show_in_menu' => false,
			        'show_in_nav_menus' => false,
			        'query_var' => false,
			        'capability_type' => 'post',
			        'has_archive' => false,
			        'hierarchical' => false,
			        'menu_position' => null,
			        'supports' => array('title'),
				);
				register_post_type("is_gate_role", $args);


				/*
				* Add Options to Role
				*/
				acf_add_local_field_group(array(
					'key' => 'group_61ccece56cda4',
					'title' => 'Gestion d\'un rôle',
					'fields' => array(
						array(
							'key' => 'field_61ccf40ca9846',
							'label' => 'Informations',
							'name' => '',
							'type' => 'message',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'message' => 'Pour plus de détails sur les rôles et capabilités: https://wordpress.org/support/article/roles-and-capabilities/#capability-vs-role-table',
							'new_lines' => '',
							'esc_html' => 0,
						),
						array(
							'key' => 'field_61cced0abb767',
							'label' => 'Labels',
							'name' => 'labels',
							'type' => 'group',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_61cced58bb768',
									'label' => 'Nom',
									'name' => 'name',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '33.3333333333',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_61cced8fbb769',
									'label' => 'Slug',
									'name' => 'slug',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '33.3333333333',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
							),
						),
						array(
							'key' => 'field_61ccede2bb76a',
							'label' => 'Capabilités',
							'name' => 'capabilities',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_61ccee1abb76b',
									'label' => 'Super Admin',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccee7fbb76c',
									'label' => 'create_sites',
									'name' => 'create_sites',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61cceec9bb76d',
									'label' => 'delete_sites',
									'name' => 'delete_sites',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61cceee3bb76e',
									'label' => 'manage_network',
									'name' => 'manage_network',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61cceee7bb76f',
									'label' => 'manage_sites',
									'name' => 'manage_sites',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61cceef9bb770',
									'label' => 'manage_network_users',
									'name' => 'manage_network_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccef05bb771',
									'label' => 'manage_network_plugins',
									'name' => 'manage_network_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccef0dbb772',
									'label' => 'manage_network_themes',
									'name' => 'manage_network_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccef13bb773',
									'label' => 'manage_network_options',
									'name' => 'manage_network_options',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccefd4bb776',
									'label' => 'upload_plugins',
									'name' => 'upload_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccefdbbb777',
									'label' => 'upload_themes',
									'name' => 'upload_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccef1bbb774',
									'label' => 'upgrade_network',
									'name' => 'upgrade_network',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccef23bb775',
									'label' => 'setup_network',
									'name' => 'setup_network',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf06fbb778',
									'label' => 'Admin',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccf07dbb779',
									'label' => 'activate_plugins',
									'name' => 'activate_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf08dbb77a',
									'label' => 'create_users',
									'name' => 'create_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf095bb77b',
									'label' => 'delete_plugins',
									'name' => 'delete_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf0adbb77c',
									'label' => 'delete_themes',
									'name' => 'delete_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf0d3bb77d',
									'label' => 'delete_users',
									'name' => 'delete_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf0d9bb77e',
									'label' => 'edit_files',
									'name' => 'edit_files',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf0ecbb77f',
									'label' => 'edit_plugins',
									'name' => 'edit_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf0f2bb780',
									'label' => 'edit_theme_options',
									'name' => 'edit_theme_options',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf100bb781',
									'label' => 'edit_themes',
									'name' => 'edit_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf118bb782',
									'label' => 'edit_users',
									'name' => 'edit_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf11ebb783',
									'label' => 'export',
									'name' => 'export',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf123bb784',
									'label' => 'import',
									'name' => 'import',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf142bb785',
									'label' => 'install_plugins',
									'name' => 'install_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf147bb786',
									'label' => 'install_themes',
									'name' => 'install_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf14bbb787',
									'label' => 'list_users',
									'name' => 'list_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf155bb788',
									'label' => 'manage_options',
									'name' => 'manage_options',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf15abb789',
									'label' => 'promote_users',
									'name' => 'promote_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf15dbb78a',
									'label' => 'remove_users',
									'name' => 'remove_users',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf168bb78b',
									'label' => 'switch_themes',
									'name' => 'switch_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf178bb78c',
									'label' => 'update_core',
									'name' => 'update_core',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf17bbb78d',
									'label' => 'update_plugins',
									'name' => 'update_plugins',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf186bb78e',
									'label' => 'update_themes',
									'name' => 'update_themes',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf19cbb78f',
									'label' => 'edit_dashboard',
									'name' => 'edit_dashboard',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1a3bb790',
									'label' => 'customize',
									'name' => 'customize',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1aebb791',
									'label' => 'delete_site',
									'name' => 'delete_site',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1c5bb792',
									'label' => 'Editor',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccf1d4bb793',
									'label' => 'moderate_comments',
									'name' => 'moderate_comments',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1e4bb794',
									'label' => 'manage_categories',
									'name' => 'manage_categories',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1eabb795',
									'label' => 'manage_links',
									'name' => 'manage_links',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1eebb796',
									'label' => 'edit_others_posts',
									'name' => 'edit_others_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf1f5bb797',
									'label' => 'edit_pages',
									'name' => 'edit_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf216bb798',
									'label' => 'edit_others_pages',
									'name' => 'edit_others_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf21abb799',
									'label' => 'edit_published_pages',
									'name' => 'edit_published_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf221bb79a',
									'label' => 'publish_pages',
									'name' => 'publish_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf227bb79b',
									'label' => 'delete_pages',
									'name' => 'delete_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf234bb79c',
									'label' => 'delete_others_pages',
									'name' => 'delete_others_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf240bb79d',
									'label' => 'delete_published_pages',
									'name' => 'delete_published_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf245bb79e',
									'label' => 'delete_others_posts',
									'name' => 'delete_others_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf24bbb79f',
									'label' => 'delete_private_posts',
									'name' => 'delete_private_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf255bb7a0',
									'label' => 'edit_private_posts',
									'name' => 'edit_private_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf25dbb7a1',
									'label' => 'read_private_posts',
									'name' => 'read_private_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf262bb7a2',
									'label' => 'delete_private_pages',
									'name' => 'delete_private_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf26abb7a3',
									'label' => 'edit_private_pages',
									'name' => 'edit_private_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf272bb7a4',
									'label' => 'read_private_pages',
									'name' => 'read_private_pages',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf290bb7a5',
									'label' => 'unfiltered_html',
									'name' => 'unfiltered_html',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf296bb7a6',
									'label' => 'Author',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccf2b9bb7a7',
									'label' => 'edit_published_posts',
									'name' => 'edit_published_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf2c8bb7a8',
									'label' => 'upload_files',
									'name' => 'upload_files',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf2d0bb7a9',
									'label' => 'publish_posts',
									'name' => 'publish_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf2d3bb7aa',
									'label' => 'delete_published_posts',
									'name' => 'delete_published_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf2dabb7ab',
									'label' => 'Contributor',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccf2f5bb7ac',
									'label' => 'edit_posts',
									'name' => 'edit_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf322bb7ad',
									'label' => 'delete_posts',
									'name' => 'delete_posts',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61ccf32abb7ae',
									'label' => 'Subscriber',
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								),
								array(
									'key' => 'field_61ccf33cbb7af',
									'label' => 'read',
									'name' => 'read',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'message' => 'Oui',
									'default_value' => 0,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
							),
						),
					),
					'location' => array(
						array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'is_gate_role',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => true,
					'description' => '',
				));


				/*
				* Register Roles
				*/
				$roles = new WP_Query([
					'post_type' => 'is_gate_role',
					'posts_per_page' => -1
				]);

				if($roles->have_posts()){
					while($roles->have_posts()) : $roles->the_post();
						$caps = !empty(get_field('capabilities')) ? array_filter(get_field('capabilities')) : [];
						add_role(get_field('labels_slug'), get_field('labels_name'), $caps);
					endwhile;
				}
			});


			/*
			* Add content before Roles List
			*/
			add_filter('views_edit-is_gate_role', function($views){
				
				$default_datas = '{"editor":{"name":"Editor","selected":false},"author":{"name":"Author","selected":false},"contributor":{"name":"Contributor","selected":false},"subscriber":{"name":"Subscriber","selected":false}}';
				
				$file = __DIR__ . '/datas/default-roles.json';

				if(!file_exists($file) || empty(file_get_contents($file)))
					file_put_contents($file, $default_datas);

				$roles = json_decode(file_get_contents($file));
				

				$html = '<style type="text/css">#basic-roles ul{display: flex; alig-items: center;}#basic-roles ul li+li{margin-left: 25px;}</style>';

				$html .= '<div class="is-theme-configs">';
					
					$html .= '<div id="basic-roles">';
						$html .= '<h3>Supprimer les rôles par défaut</h3>';
						$html .= '<ul>';
						foreach($roles as $role_key => $role_value){

							$checked = $role_value->selected ? ' checked' : null;
							$html .= '<li data-is="'. $role_key .'"><label><input type="checkbox" id="role-'. $role_key .'" name="role-'. $role_key .'" value="1" class="" autocomplete="off"'. $checked .'><span class="message">'. $role_value->name .'</span></label></li>';
						}
						$html .= '</ul>';
					$html .= '</div>';
				$html .= '</div>';

				$html .= '<script type="text/javascript">
					jQuery(document).ready(function($){
						$("#basic-roles ul li input").on("change", function(){
							var is = $(this).parents("li").data("is");
							$.post(window.location.href, {
								activate: "edit-role-to-delete",
								is: is,
								checked: ($(this).is(":checked") ? "true" : "false")
							});
						});
					});
				</script>';

				echo $html;
				return $views;
			});

		}

	}

	new isGateROLE();
}
?>