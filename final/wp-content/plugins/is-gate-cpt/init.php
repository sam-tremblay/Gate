<?php
/*
Plugin Name: Gate CPT
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Création rapide de Custom Post Type et leurs taxonomies.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-cpt
*/

if (!defined('ABSPATH')) exit;


if (!defined('IGC_VERSION')) define('IGC_VERSION', '1.0');


if (!class_exists('isGateCPT')){
	class isGateCPT{

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
						__('L\'extension "Gate Core" est manquante.', 'is-gate-cpt'),
						__('Une erreur est survenue', 'is-gate-cpt'),
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


			add_action('acf/init', function(){

				/*
				* Add CPT Manager
				*/
				$labels = array(
					'name' => __( 'Custom post types', 'gate' ),
			        'singular_name' => __( 'Custom post type', 'gate' )
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
				register_post_type("is_gate_cpt", $args);


				/*
				* Add Options to Role
				*/

				$seo_module = class_exists('isGateSEO') ? array(
					'key' => 'field_618247709caba',
					'label' => 'SEO',
					'name' => 'seo',
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
				) : null;

				acf_add_local_field_group(array(
					'key' => 'group_618202e0e6d21',
					'title' => 'Gate CPT',
					'fields' => array(
						array(
							'key' => 'field_6182031923c5d',
							'label' => 'Labels',
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
							'key' => 'field_6182033123c5e',
							'label' => 'Labels',
							'name' => 'labels',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'row',
							'sub_fields' => array(
								array(
									'key' => 'field_618206c123c62',
									'label' => 'Paramètres',
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
									'message' => 'Les paramètres avec une étoile rouge sont obligatoires. Les autres utilisent un texte par défaut pouvant être changé.
				Pour avoir la description de chacun des paramètres: https://developer.wordpress.org/reference/functions/get_post_type_labels/#description',
									'new_lines' => 'wpautop',
									'esc_html' => 0,
								),
								array(
									'key' => 'field_6182039a23c5f',
									'label' => 'name',
									'name' => 'name',
									'type' => 'text',
									'instructions' => 'Nom générale du type de publication. généralement au pluriel.',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618204b823c60',
									'label' => 'singular_name',
									'name' => 'singular_name',
									'type' => 'text',
									'instructions' => 'Nom général, mais au singulier. Utilisé par plusieurs plugins dont ACF. <br />
				Si aucun nom au singulier est donné, le nom général sera prit.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_6182055f23c61',
									'label' => 'add_new',
									'name' => 'add_new',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_6182080e23c63',
									'label' => 'add_new_item',
									'name' => 'add_new_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618209e423c64',
									'label' => 'edit_item',
									'name' => 'edit_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618209f223c65',
									'label' => 'new_item',
									'name' => 'new_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618209fb23c66',
									'label' => 'view_item',
									'name' => 'view_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a2523c67',
									'label' => 'view_items',
									'name' => 'view_items',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a3423c68',
									'label' => 'search_items',
									'name' => 'search_items',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a3e23c69',
									'label' => 'not_found',
									'name' => 'not_found',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a4a23c6a',
									'label' => 'not_found_in_trash',
									'name' => 'not_found_in_trash',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a5923c6b',
									'label' => 'parent_item_colon',
									'name' => 'parent_item_colon',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a6823c6c',
									'label' => 'all_items',
									'name' => 'all_items',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a7223c6d',
									'label' => 'archives',
									'name' => 'archives',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a7a23c6e',
									'label' => 'attributes',
									'name' => 'attributes',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a8623c6f',
									'label' => 'insert_into_item',
									'name' => 'insert_into_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a9823c70',
									'label' => 'uploaded_to_this_item',
									'name' => 'uploaded_to_this_item',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820a9f23c71',
									'label' => 'featured_image',
									'name' => 'featured_image',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820af923c72',
									'label' => 'set_featured_image',
									'name' => 'set_featured_image',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b0b23c73',
									'label' => 'remove_featured_image',
									'name' => 'remove_featured_image',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b0f23c74',
									'label' => 'use_featured_image',
									'name' => 'use_featured_image',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b1623c75',
									'label' => 'menu_name',
									'name' => 'menu_name',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b3623c76',
									'label' => 'filter_items_list',
									'name' => 'filter_items_list',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b3923c77',
									'label' => 'filter_by_date',
									'name' => 'filter_by_date',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b4623c78',
									'label' => 'items_list_navigation',
									'name' => 'items_list_navigation',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b5c23c79',
									'label' => 'items_list',
									'name' => 'items_list',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b6823c7a',
									'label' => 'item_published',
									'name' => 'item_published',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b7023c7b',
									'label' => 'item_published_privately',
									'name' => 'item_published_privately',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b7323c7c',
									'label' => 'item_reverted_to_draft',
									'name' => 'item_reverted_to_draft',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b7923c7d',
									'label' => 'item_scheduled',
									'name' => 'item_scheduled',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b8023c7e',
									'label' => 'item_updated',
									'name' => 'item_updated',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b8d23c7f',
									'label' => 'item_link',
									'name' => 'item_link',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61820b9523c80',
									'label' => 'item_link_description',
									'name' => 'item_link_description',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
							'key' => 'field_61820e685e3fa',
							'label' => 'Arguments',
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
							'key' => 'field_61820e965e3fb',
							'label' => 'Arguments',
							'name' => 'args',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'row',
							'sub_fields' => array(
								array(
									'key' => 'field_61820ea65e3fc',
									'label' => 'Paramètres',
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
									'message' => 'Les paramètres avec une étoile rouge sont obligatoires.
				Pour avoir la description de chacun des paramètres: https://developer.wordpress.org/reference/functions/register_post_type/#parameters',
									'new_lines' => 'wpautop',
									'esc_html' => 0,
								),
								array(
									'key' => 'field_61820fe65e3fd',
									'label' => 'post_type',
									'name' => 'post_type',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618210cb5e3fe',
									'label' => 'description',
									'name' => 'description',
									'type' => 'textarea',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'maxlength' => '',
									'rows' => 3,
									'new_lines' => '',
								),
								array(
									'key' => 'field_6182111e5e3ff',
									'label' => 'public',
									'name' => 'public',
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
									'key' => 'field_618211345e400',
									'label' => 'hierarchical',
									'name' => 'hierarchical',
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
									'key' => 'field_6182120d5e401',
									'label' => 'exclude_from_search',
									'name' => 'exclude_from_search',
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
									'key' => 'field_618212165e402',
									'label' => 'publicly_queryable',
									'name' => 'publicly_queryable',
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
									'key' => 'field_6182124e5e403',
									'label' => 'show_ui',
									'name' => 'show_ui',
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
									'key' => 'field_618212655e405',
									'label' => 'show_in_menu',
									'name' => 'show_in_menu',
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
									'key' => 'field_6182133a5e406',
									'label' => 'show_in_menu (custom)',
									'name' => 'show_in_menu_custom',
									'type' => 'text',
									'instructions' => 'Si ce paramètre est rempli, show_in_menu juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de show_in_menu, veuillez regarder la description des paramètres proposée plus haut.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618213da5e407',
									'label' => 'show_in_nav_menus',
									'name' => 'show_in_nav_menus',
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
									'key' => 'field_6182144f5e409',
									'label' => 'show_in_admin_bar',
									'name' => 'show_in_admin_bar',
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
									'key' => 'field_6182145d5e40a',
									'label' => 'show_in_rest',
									'name' => 'show_in_rest',
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
									'key' => 'field_618214675e40b',
									'label' => 'rest_base',
									'name' => 'rest_base',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618214795e40c',
									'label' => 'rest_controller_class',
									'name' => 'rest_controller_class',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'WP_REST_Posts_Controller',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_618214a65e40d',
									'label' => 'menu_position',
									'name' => 'menu_position',
									'type' => 'number',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => 0,
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'min' => 0,
									'max' => '',
									'step' => '',
								),
								array(
									'key' => 'field_618214cb5e40e',
									'label' => 'menu_icon',
									'name' => 'menu_icon',
									'type' => 'text',
									'instructions' => 'https://developer.wordpress.org/resource/dashicons/',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'dashicons-chart-pie',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_618215425e40f',
									'label' => 'capability_type',
									'name' => 'capability_type',
									'type' => 'button_group',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'choices' => array(
										'post' => 'post',
										'page' => 'page',
									),
									'allow_null' => 0,
									'default_value' => '',
									'layout' => 'horizontal',
									'return_format' => 'value',
								),
								array(
									'key' => 'field_618217345e410',
									'label' => 'capabilities',
									'name' => 'capabilities',
									'type' => 'text',
									'instructions' => 'Séparez chaque résultat par une virgule.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'manage_options',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_6182175c5e411',
									'label' => 'map_meta_cap',
									'name' => 'map_meta_cap',
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
									'default_value' => 1,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_618217d85e412',
									'label' => 'supports',
									'name' => 'supports',
									'type' => 'checkbox',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'choices' => array(
										'title' => 'title',
										'editor' => 'editor',
										'comments' => 'comments',
										'revisions' => 'revisions',
										'trackbacks' => 'trackbacks',
										'author' => 'author',
										'excerpt' => 'excerpt',
										'page-attributes' => 'page-attributes',
										'thumbnail' => 'thumbnail',
										'custom-fields' => 'custom-fields',
										'post-formats' => 'post-formats',
									),
									'allow_custom' => 0,
									'default_value' => array(
										0 => 'title',
									),
									'layout' => 'vertical',
									'toggle' => 0,
									'return_format' => 'value',
									'save_custom' => 0,
								),
								array(
									'key' => 'field_618218715e413',
									'label' => 'register_meta_box_cb',
									'name' => 'register_meta_box_cb',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_618218d65e414',
									'label' => 'has_archive',
									'name' => 'has_archive',
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
									'key' => 'field_618218f75e415',
									'label' => 'has_archive (custom)',
									'name' => 'has_archive_custom',
									'type' => 'text',
									'instructions' => 'Si ce paramètre est rempli, has_archive juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de has_archive, veuillez regarder la description des paramètres proposée plus haut.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61821a5e5e416',
									'label' => 'rewrite',
									'name' => 'rewrite',
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
									'default_value' => 1,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61821a6b5e417',
									'label' => 'rewrite (custom)',
									'name' => 'rewrite_custom',
									'type' => 'group',
									'instructions' => 'Si ce paramètre est rempli, rewrite juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de rewrite, veuillez regarder la description des paramètres proposée plus haut.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'layout' => 'table',
									'sub_fields' => array(
										array(
											'key' => 'field_61821abc5e418',
											'label' => 'slug',
											'name' => 'slug',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61821ad55e419',
											'label' => 'with_front',
											'name' => 'with_front',
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
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61821ae45e41a',
											'label' => 'feeds',
											'name' => 'feeds',
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
											'key' => 'field_61821aea5e41b',
											'label' => 'pages',
											'name' => 'pages',
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
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
									),
								),
								array(
									'key' => 'field_61821b565e41c',
									'label' => 'query_var',
									'name' => 'query_var',
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
									'key' => 'field_61821b865e41d',
									'label' => 'query_var (custom)',
									'name' => 'query_var_custom',
									'type' => 'text',
									'instructions' => 'Si ce paramètre est rempli, query_var juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de query_var, veuillez regarder la description des paramètres proposée plus haut.',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61821bbd5e41e',
									'label' => 'can_export',
									'name' => 'can_export',
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
									'default_value' => 1,
									'ui' => 0,
									'ui_on_text' => '',
									'ui_off_text' => '',
								),
								array(
									'key' => 'field_61821c295e41f',
									'label' => 'delete_with_user',
									'name' => 'delete_with_user',
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
									'key' => 'field_61821c405e420',
									'label' => 'template',
									'name' => 'template',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61821c8b5e421',
									'label' => 'template_lock',
									'name' => 'template_lock',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
									'key' => 'field_61821c9b5e422',
									'label' => '_builtin',
									'name' => '_builtin',
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
									'key' => 'field_61821caf5e424',
									'label' => '_edit_link',
									'name' => '_edit_link',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
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
							'key' => 'field_618238e0d943f',
							'label' => 'Taxonomies',
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
							'key' => 'field_61823b2e166ad',
							'label' => 'Taxonomies',
							'name' => 'taxonomies',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => 'field_6182392fd9442',
							'min' => 0,
							'max' => 0,
							'layout' => 'block',
							'button_label' => 'Ajouter une taxonomie',
							'sub_fields' => array(
								array(
									'key' => 'field_6182392fd9442',
									'label' => 'Labels',
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
									'key' => 'field_618238f7d9440',
									'label' => 'Labels',
									'name' => 'labels',
									'type' => 'group',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'layout' => 'row',
									'sub_fields' => array(
										array(
											'key' => 'field_61823900d9441',
											'label' => 'Paramètres',
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
											'message' => 'Les paramètres avec une étoile rouge sont obligatoires.
				Pour avoir la description de chacun des paramètres: https://developer.wordpress.org/reference/functions/get_taxonomy_labels/#return',
											'new_lines' => 'wpautop',
											'esc_html' => 0,
										),
										array(
											'key' => 'field_61823992d9443',
											'label' => 'name',
											'name' => 'name',
											'type' => 'text',
											'instructions' => '',
											'required' => 1,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_6182399ed9444',
											'label' => 'singular_name',
											'name' => 'singular_name',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239a3d9445',
											'label' => 'search_items',
											'name' => 'search_items',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239a7d9446',
											'label' => 'popular_items',
											'name' => 'popular_items',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239add9447',
											'label' => 'all_items',
											'name' => 'all_items',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239b2d9448',
											'label' => 'parent_item',
											'name' => 'parent_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239b7d9449',
											'label' => 'parent_item_colon',
											'name' => 'parent_item_colon',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239cbd944a',
											'label' => 'edit_item',
											'name' => 'edit_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239cfd944b',
											'label' => 'view_item',
											'name' => 'view_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239d5d944c',
											'label' => 'update_item',
											'name' => 'update_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239d8d944d',
											'label' => 'add_new_item',
											'name' => 'add_new_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618239ff926c7',
											'label' => 'new_item_name',
											'name' => 'new_item_name',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a12926c8',
											'label' => 'separate_items_with_commas',
											'name' => 'separate_items_with_commas',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a1c926c9',
											'label' => 'add_or_remove_items',
											'name' => 'add_or_remove_items',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a20926ca',
											'label' => 'choose_from_most_used',
											'name' => 'choose_from_most_used',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a2e926cb',
											'label' => 'not_found',
											'name' => 'not_found',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a34926cc',
											'label' => 'no_terms',
											'name' => 'no_terms',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a37926cd',
											'label' => 'filter_by_item',
											'name' => 'filter_by_item',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a3e926ce',
											'label' => 'items_list_navigation',
											'name' => 'items_list_navigation',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a43926cf',
											'label' => 'items_list',
											'name' => 'items_list',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a47926d0',
											'label' => 'most_used',
											'name' => 'most_used',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a4b926d1',
											'label' => 'back_to_items',
											'name' => 'back_to_items',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a50926d2',
											'label' => 'item_link',
											'name' => 'item_link',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823a58926d3',
											'label' => 'item_link_description',
											'name' => 'item_link_description',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
									'key' => 'field_61823a8c33ccc',
									'label' => 'Arguments',
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
									'key' => 'field_61823c0d166ae',
									'label' => 'Arguments',
									'name' => 'args',
									'type' => 'group',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'layout' => 'row',
									'sub_fields' => array(
										array(
											'key' => 'field_61823a8f33ccd',
											'label' => 'Paramètres',
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
											'message' => 'Les paramètres avec une étoile rouge sont obligatoires.
				Pour avoir la description de chacun des paramètres: https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters',
											'new_lines' => 'wpautop',
											'esc_html' => 0,
										),
										array(
											'key' => 'field_61823d64be236',
											'label' => 'Callback',
											'name' => 'callback',
											'type' => 'text',
											'instructions' => '',
											'required' => 1,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'default_value' => '',
											'placeholder' => '',
											'prepend' => '',
											'append' => '',
											'maxlength' => 32,
										),
										array(
											'key' => 'field_61823d8dbe237',
											'label' => 'description',
											'name' => 'description',
											'type' => 'textarea',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'default_value' => '',
											'placeholder' => '',
											'maxlength' => '',
											'rows' => 3,
											'new_lines' => '',
										),
										array(
											'key' => 'field_61823db8be238',
											'label' => 'public',
											'name' => 'public',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 0,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823dfebe239',
											'label' => 'publicly_queryable',
											'name' => 'publicly_queryable',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 0,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e07be23a',
											'label' => 'hierarchical',
											'name' => 'hierarchical',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 0,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e0ebe23b',
											'label' => 'show_ui',
											'name' => 'show_ui',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e2abe23c',
											'label' => 'show_in_menu',
											'name' => 'show_in_menu',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e56be23d',
											'label' => 'show_in_nav_menus',
											'name' => 'show_in_nav_menus',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e65be23e',
											'label' => 'show_in_rest',
											'name' => 'show_in_rest',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 0,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823e79be23f',
											'label' => 'rest_base',
											'name' => 'rest_base',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823e7ebe240',
											'label' => 'rest_controller_class',
											'name' => 'rest_controller_class',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'default_value' => 'WP_REST_Terms_Controller',
											'placeholder' => '',
											'prepend' => '',
											'append' => '',
											'maxlength' => '',
										),
										array(
											'key' => 'field_61823e8ebe241',
											'label' => 'show_tagcloud',
											'name' => 'show_tagcloud',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823eacbe242',
											'label' => 'show_in_quick_edit',
											'name' => 'show_in_quick_edit',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823eb3be243',
											'label' => 'show_admin_column',
											'name' => 'show_admin_column',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 0,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823f7fbe247',
											'label' => 'capabilities',
											'name' => 'capabilities',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61823fb0be248',
											'label' => 'rewrite',
											'name' => 'rewrite',
											'type' => 'true_false',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'message' => '',
											'default_value' => 1,
											'ui' => 0,
											'ui_on_text' => '',
											'ui_off_text' => '',
										),
										array(
											'key' => 'field_61823fd9be249',
											'label' => 'rewrite (custom)',
											'name' => 'rewrite_custom',
											'type' => 'group',
											'instructions' => 'Si ce paramètre est rempli, rewrite juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de rewrite, veuillez regarder la description des paramètres proposée plus haut.',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'layout' => 'table',
											'sub_fields' => array(
												array(
													'key' => 'field_61823fd9be24a',
													'label' => 'slug',
													'name' => 'slug',
													'type' => 'text',
													'instructions' => '',
													'required' => 0,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '',
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
													'key' => 'field_61823fd9be24b',
													'label' => 'with_front',
													'name' => 'with_front',
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
													'default_value' => 1,
													'ui' => 0,
													'ui_on_text' => '',
													'ui_off_text' => '',
												),
												array(
													'key' => 'field_61823fd9be24c',
													'label' => 'hierarchical',
													'name' => 'hierarchical',
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
										array(
											'key' => 'field_61824054be24e',
											'label' => 'query_var',
											'name' => 'query_var',
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
											'key' => 'field_61824057be24f',
											'label' => 'query_var (custom)',
											'name' => 'query_var_custom',
											'type' => 'text',
											'instructions' => 'Si ce paramètre est rempli, query_var juste au dessus ne sera pas utilisé. Pour comprendre le paramètre custom de query_var, veuillez regarder la description des paramètres proposée plus haut.',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_6182407bbe250',
											'label' => 'update_count_callback',
											'name' => 'update_count_callback',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_61824099be251',
											'label' => 'default_term',
											'name' => 'default_term',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618240e6be252',
											'label' => 'sort',
											'name' => 'sort',
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
											'key' => 'field_618240fdbe254',
											'label' => 'args',
											'name' => 'args',
											'type' => 'text',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
											'key' => 'field_618240f9be253',
											'label' => '_builtin',
											'name' => '_builtin',
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
						),
						array(
							'key' => 'field_618247569cab8',
							'label' => 'Modules',
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
							'key' => 'field_6182475e9cab9',
							'label' => 'Modules',
							'name' => 'modules',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'row',
							'sub_fields' => array(
								$seo_module
							),
						),
					),
					'location' => array(
						array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'is_gate_cpt',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'seamless',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => true,
					'description' => '',
				));


				/*
				* Register CPT
				*/
				$cpt = new WP_Query([
					'post_type' => 'is_gate_cpt',
					'posts_per_page' => -1
				]);

				if($cpt->have_posts()){
					while($cpt->have_posts()) : $cpt->the_post();

						if(get_field('labels')){
							/*
							* Get Labels and arguments without empties
							*/
							$labels = array_filter(get_field('labels'));
							$args = get_field('args');
							unset($args['']);


							/*
							* Add Labels in Arguments
							*/
							$args['labels'] = $labels;


							/*
							* Get post_type from Arguments
							*/
							$post_type = $args['post_type'];


							/*
							* Now you have the post_type, remove it from Arguments
							* Because it's not an arguments
							*/
							unset($args['post_type']);


							/*
							* Transform show_in_menu
							*/
							if(!empty($args['show_in_menu_custom']))
								$args['show_in_menu'] = $args['show_in_menu_custom'];
							
							unset($args['show_in_menu_custom']);


							/*
							* Transform has_archive
							*/
							if(!empty($args['has_archive_custom']))
								$args['has_archive'] = $args['has_archive_custom'];
							
							unset($args['has_archive_custom']);


							/*
							* Transform rewrite
							*/
							if(!empty($args['rewrite_custom']['slug']))
								$args['rewrite'] = $args['rewrite_custom'];

							unset($args['rewrite_custom']);


							/*
							* Transform query_var
							*/
							if(!empty($args['query_var_custom']))
								$args['query_var'] = $args['query_var_custom'];
							
							unset($args['query_var_custom']);


							/*
							* Remove cap
							*/
							if(empty($args['capabilities']))
								unset($args['capabilities']);
							else
								$args['capabilities'] = str_replace(', ', ',', explode(',', $args['capabilities']));


							/*
							* Remove register_meta_box_cb
							*/
							if(empty($args['register_meta_box_cb']))
								unset($args['register_meta_box_cb']);


							/*
							* Remove _edit_link
							*/
							if(empty($args['_edit_link']))
								unset($args['_edit_link']);


							/*
							* Remove template
							*/
							if(empty($args['template']))
								unset($args['template']);


							/*
							* Remove rest_base
							*/
							if(empty($args['rest_base']))
								unset($args['rest_base']);


							/*
							* Transform supports
							*/
							if(empty($args['supports']))
								$args['supports'] = false;


							/*
							* Transform template_lock
							*/
							if(empty($args['template_lock']))
								$args['template_lock'] = false;


							/*
							* Now all is set, register the post type
							* and their taxonomies
							*/
							register_post_type($post_type, $args);
							

							// Taxonomy
							if(get_field('taxonomies', get_the_ID())){
								foreach(get_field('taxonomies', get_the_ID()) as $tax){
									/*
									* Get Labels and arguments without empties
									*/
									$labels = array_filter($tax['labels']);
									$args = $tax['args'];
									unset($args['']);


									/*
									* Add Labels in Arguments
									*/
									$args['labels'] = $labels;


									/*
									* Get post_type from Arguments
									*/
									$callback = $args['callback'];


									/*
									* Now you have the post_type, remove it from Arguments
									* Because it's not an arguments
									*/
									unset($args['callback']);



									/*
									* Transform rewrite
									*/
									if(!empty($args['rewrite_custom']['slug']))
										$args['rewrite'] = $args['rewrite_custom'];

									unset($args['rewrite_custom']);


									/*
									* Transform query_var
									*/
									if(!empty($args['query_var_custom']))
										$args['query_var'] = $args['query_var_custom'];
									
									unset($args['query_var_custom']);


									/*
									* Remove cap
									*/
									if(empty($args['capabilities']))
										unset($args['capabilities']);
									else
										$args['capabilities'] = str_replace(', ', ',', explode(',', $args['capabilities']));


									/*
									* Remove rest_base
									*/
									if(empty($args['rest_base']))
										unset($args['rest_base']);


									/*
									* Remove update_count_callback
									*/
									if(empty($args['update_count_callback']))
										unset($args['update_count_callback']);


									/*
									* Remove default_term
									*/
									if(empty($args['default_term']))
										unset($args['default_term']);


									/*
									* Remove args
									*/
									if(empty($args['args']))
										unset($args['args']);


									/*
									* Now all is set, register taxonomy
									*/
									register_taxonomy($callback, array($post_type), $args);
								}
							}
						}

					endwhile; wp_reset_postdata();
				}
			});

		}

	}

	new isGateCPT();
}


?>