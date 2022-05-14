<?php
/*
Plugin Name: Gate Core
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Requis pour le bon fonctionne des extensions Gate. Allège le panneau d'administration. Donne accès à l'onglet "Dev" et à des outils gérant les bases.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-core
*/

if (!defined('ABSPATH')) exit;


if (!defined('IGCO_VERSION')) define('IGCO_VERSION', '1.0');


if (!class_exists('isGateCORE')){
	class isGateCORE{

		function __construct(){

			/*
			* On plugin activation
			*/
			register_activation_hook(__FILE__, function(){

				/*
				* Active ACF Pro
				*/
				if(file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php') && !is_plugin_active('advanced-custom-fields-pro/acf.php'))
					activate_plugin('advanced-custom-fields-pro/acf.php');
				elseif(!file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php')){

					/*
					* If ACF Pro not There
					*/

					wp_die(
						__('L\'extension "Advanced Custom Fields PRO" est manquante.', 'is-gate-core'),
						__('Une erreur est survenue', 'is-gate-core'),
						[
							'back_link' => true
						]
					);

				}

			});


			add_action('admin_init', function(){

				/*
				* If ACF Pro is deactivated
				*/
				if(!file_exists(WP_PLUGIN_DIR . '/advanced-custom-fields-pro/acf.php') || !is_plugin_active('advanced-custom-fields-pro/acf.php'))
					deactivate_plugins('is-gate-core/init.php');


				/*
				* Clean Dashboard
				*/
				remove_action('welcome_panel', 'wp_welcome_panel');
				remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
				remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
				remove_meta_box('dashboard_primary', 'dashboard', 'side');
				remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
				remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
				remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
				remove_meta_box('dashboard_activity', 'dashboard', 'normal');
				remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');
				remove_meta_box('dlm_popular_downloads', 'dashboard', 'normal');
				remove_meta_box('dashboard_site_health', 'dashboard', 'normal');

			});


			/*
			* Manage top bar
			*/
			add_action('admin_bar_menu', function(){

				global $wp_admin_bar;

				$admin_url = admin_url();

				/*
				* Remove not wanted elements
				*/
				$wp_admin_bar->remove_node('wp-logo');
				$wp_admin_bar->remove_node('site-name');
				$wp_admin_bar->remove_node('comments');
				$wp_admin_bar->remove_node('new-content');

				if(!current_user_can('update_core') || !current_user_can('update_plugins') || !current_user_can('update_themes'))
					$wp_admin_bar->remove_node( 'updates' );

				
				/*
				* Add elements to top bar
				*/

				// On ajoute un lien vers l'accueil du site
				$args = array(
					'id' => 'goto-website',
					'title' => get_bloginfo('name'),
					'href' => $site_url,
					'target' => '_blank',
					'meta' => array(
						'class' => 'goto-website',
						'title' => 'Visiter le site web'
					)
				);
				$wp_admin_bar->add_node($args);


				// On ajoute un lien vers la gestion des menus
				$args = array(
					'id' => 'gest-menus',
					'title' => __('Navigations', 'is-gate-core'),
					'href' => $admin_url . 'nav-menus.php',
					'meta' => array(
						'class' => 'gest-menus',
						'title' => 'Gestionnaire des menus'
					)
				);
				if(current_user_can('edit_theme_options'))
					$wp_admin_bar->add_node($args);


				// On ajoute un lien vers les fichiers
				$args = array(
					'id' => 'gest-files',
					'title' => __('Images & fichiers', 'is-gate-core'),
					'href' => $admin_url . 'upload.php',
					'meta' => array(
						'class' => 'gest-files'
					)
				);
				if(current_user_can('upload_files'))
					$wp_admin_bar->add_node($args);


				// On ajoute un lien vers les utilisateurs
				$args = array(
					'id' => 'gest-users-list',
					'title' => __('Utilisateurs', 'is-gate-core'),
					'href' => $admin_url . 'users.php',
					'meta' => array(
						'class' => 'gest-users-list'
					)
				);
				if(current_user_can('list_users'))
					$wp_admin_bar->add_node($args);


				// On ajoute un lien vers le profil de l'utilisateur
				$args = array(
					'id' => 'gest-users-profile',
					'title' => __('Votre profil', 'is-gate-core'),
					'href' => $admin_url . 'profile.php',
					'parent' => 'gest-users-list',
					'meta' => array(
						'class' => 'gest-users-profile',
						'title' => 'Votre profile'
					)
				);
				$wp_admin_bar->add_node($args);


				if(current_user_can('edit_theme_options')){

					// On ajoute l'onglet Dev
					$args = array(
						'id' => 'is-theme-dev',
						'title' => __('Dev', 'is-gate-core'),
						'meta' => array(
							'class' => 'is-theme-dev'
						)
					);
					$wp_admin_bar->add_node($args);


					// On ajoute l'onglet Général
					$args = array(
						'id' => 'is-theme-general-configs',
						'title' => __('Général', 'is-gate-core'),
						'href' => $admin_url . 'admin.php?page=is_gate_settings',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-general-configs'
						)
					);
					$wp_admin_bar->add_node($args);


					// On ajoute l'onglet des Custom Post Type
					$args = array(
						'id' => 'is-theme-cpt',
						'title' => __('Custom post types', 'is-gate-core'),
						'href' => $admin_url . 'edit.php?post_type=is_gate_cpt',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-cpt'
						)
					);
					if(class_exists('isGateCPT'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'onglet Rôle
					$args = array(
						'id' => 'is-theme-roles',
						'title' => __('Rôles', 'is-gate-core'),
						'href' => $admin_url . 'edit.php?post_type=is_gate_role',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-roles'
						)
					);
					if(class_exists('isGateRole'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'onglet Theme
					$args = array(
						'id' => 'is-theme-themes',
						'title' => __('Thèmes', 'is-gate-core'),
						'href' => $admin_url . 'themes.php',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-themes'
						)
					);
					if(current_user_can('switch_themes'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'éditeur de thème
					$args = array(
						'id' => 'is-theme-themes-editor',
						'title' => __('Éditeur', 'is-gate-core'),
						'href' => $admin_url . 'theme-editor.php',
						'parent' => 'is-theme-themes',
						'meta' => array(
							'class' => 'is-theme-themes-editor'
						)
					);
					if(current_user_can('edit_themes'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'onglet extensions
					$args = array(
						'id' => 'is-theme-extensions',
						'title' => __('Extensions', 'is-gate-core'),
						'href' => $admin_url . 'plugins.php',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-extensions'
						)
					);
					if(current_user_can('activate_plugins'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'éditeur d'extension
					$args = array(
						'id' => 'is-theme-exts-editor',
						'title' => __('Éditeur', 'is-gate-core'),
						'href' => $admin_url . 'plugin-editor.php',
						'parent' => 'is-theme-extensions',
						'meta' => array(
							'class' => 'is-theme-exts-editor'
						)
					);
					if(current_user_can('edit_plugins'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'onglet ACF
					$args = array(
						'id' => 'is-theme-acf',
						'title' => __('ACF', 'is-gate-core'),
						'href' => $admin_url . 'edit.php?post_type=acf-field-group',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-acf'
						)
					);
					$wp_admin_bar->add_node($args);


					// On ajoute WPML
					if(is_plugin_active('sitepress-multilingual-cms/sitepress.php')){

						$languages = icl_get_languages('skip_missing=0&orderby=code');
						
						$target = $languages ? 'tm/menu/main.php' : 'sitepress-multilingual-cms/menu/setup.php';
						$args = array(
							'id' => 'configs-wpml',
							'title' => __('WPML', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=' . $target,
							'parent' => 'is-theme-dev',
							'meta' => array(
								'class' => 'configs-wpml'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-translate',
							'title' => __('Gestion de traduction', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=tm/menu/main.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-translate'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-languages',
							'title' => __('Langues', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/languages.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-languages'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-theme-localization',
							'title' => __('Localisation du thème et des plugins', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/theme-localization.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-theme-localization'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-translations-queue',
							'title' => __('Traductions', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=tm/menu/translations-queue.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-translations-queue'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-sync-menus',
							'title' => __('Synchroniser les menus WP', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/menu-sync/menus-sync.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-sync-menus'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-string-translation',
							'title' => __('Tranduction des chaînes', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=wpml-string-translation/menu/string-translation.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-string-translation'
							)
						);
						if(is_plugin_active('wpml-string-translation/plugin.php'))
							$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-tax-translation',
							'title' => __('Traduction de taxonomie', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/taxonomy-translation.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-tax-translation'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-paq',
							'title' => __('Paquetages', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=wpml-package-management',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-paq'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-parameters',
							'title' => __('Paramètres', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=tm/menu/settings',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-parameters'
							)
						);
						$wp_admin_bar->add_node($args);


						$args = array(
							'id' => 'gest-support',
							'title' => __('Assistance technique', 'is-gate-core'),
							'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/support.php',
							'parent' => 'configs-wpml',
							'meta' => array(
								'class' => 'gest-support'
							)
						);
						$wp_admin_bar->add_node($args);
					}


					// On ajoute l'onglet importer
					$args = array(
						'id' => 'is-theme-import',
						'title' => __('Importer', 'is-gate-core'),
						'href' => $admin_url . 'import.php',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-import'
						)
					);
					if(current_user_can('import'))
						$wp_admin_bar->add_node($args);


					// On ajoute l'onglet exporter
					$args = array(
						'id' => 'is-theme-export',
						'title' => __('Exporter', 'is-gate-core'),
						'href' => $admin_url . 'export.php',
						'parent' => 'is-theme-dev',
						'meta' => array(
							'class' => 'is-theme-export'
						)
					);
					if(current_user_can('export'))
						$wp_admin_bar->add_node($args);

				}
			}, 99);
			

			add_action('admin_menu', function(){

				/*
				* Clean left menu
				*/
				remove_menu_page('tools.php');
				remove_menu_page('upload.php');
				remove_menu_page('themes.php');
				remove_menu_page('plugins.php');
				remove_menu_page('edit-comments.php');
				remove_menu_page('users.php');
				remove_menu_page('edit.php?post_type=acf-field-group');

				remove_submenu_page('options-general.php', 'options-privacy.php');
				remove_submenu_page('options-general.php', 'options-media.php');
				remove_submenu_page('options-general.php', 'options-writing.php');
				remove_submenu_page('options-general.php', 'options-discussion.php');

			});



			/*
			* Allow SVG to be uploaded
			*/
			add_filter('upload_mimes', function($mimes){
				$mimes['svg'] = 'image/svg+xml';
				return $mimes;
			});

			add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes) {
				global $wp_version;

				if($wp_version == '4.7' || ((float)$wp_version < 4.7 )) return $data;

				$filetype = wp_check_filetype($filename, $mimes);

				return [
					'ext' => $filetype['ext'],
					'type' => $filetype['type'],
					'proper_filename' => $data['proper_filename']
				];
				
			}, 10, 4);


			add_action('admin_head', function(){
	    		
				/*
				* Add Custom Styles
				*/
				echo '<style type="text/css">#toplevel_page_is_gate_settings,#toplevel_page_tm-menu-main,#toplevel_page_sitepress-multilingual-cms-menu-setup{display: none !important;}</style>';

			});


			add_action('acf/init', function(){

				/*
				* Add options page
				*/
				$option_page = acf_add_options_page(array(
		            'page_title' => __('Outils de développement', 'is-gate-core'),
		            'menu_title' => __('Dev', 'is-gate-core'),
		            'menu_slug' => 'is_gate_settings',
		            'capability' => 'edit_posts',
		            'redirect' => false
		        ));


		        /*
		        * Add Field to options page
		        */
		        $seo_module = class_exists('isGateSEO') ? array(
					'key' => 'field_6180d68d95ab1',
					'label' => 'SEO',
					'name' => 'is_gate_seo',
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
							'key' => 'field_6180db4bb3e63',
							'label' => 'Favicon',
							'name' => 'favicon',
							'type' => 'image',
							'instructions' => 'Insérez un favicon plus grand ou égal à 260px par 260px',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '33.3333333333',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'url',
							'preview_size' => 'full',
							'library' => 'all',
							'min_width' => 260,
							'min_height' => 260,
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => '',
						),
						array(
							'key' => 'field_6180dbb0b3e64',
							'label' => 'Code Google Analytics',
							'name' => 'ga_code',
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
							'placeholder' => 'UA-XXXXXXXXX-X ou G-XXXXXXXXXX',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_6180dc7dfca22',
							'label' => 'Moteurs de recherche',
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
							'key' => 'field_6180ddf44ed36',
							'label' => 'Titre',
							'name' => 'title_search_meta',
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
							'key' => 'field_6180de224ed37',
							'label' => 'Description',
							'name' => 'description_search_meta',
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
							'rows' => '',
							'new_lines' => '',
						),
						array(
							'key' => 'field_6180dcb9fca23',
							'label' => 'Réseaux sociaux',
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
							'key' => 'field_6180deba4ed38',
							'label' => 'Titre',
							'name' => 'title_sn_meta',
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
							'key' => 'field_6180debd4ed39',
							'label' => 'Description',
							'name' => 'description_sn_meta',
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
							'rows' => '',
							'new_lines' => '',
						),
						array(
							'key' => 'field_6180dec44ed3a',
							'label' => 'Image',
							'name' => 'image_sn_meta',
							'type' => 'image',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'url',
							'preview_size' => 'full',
							'library' => 'all',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => '',
						),
					),
				) : null;

		        acf_add_local_field_group(array(
					'key' => 'group_6180ce0d60567',
					'title' => 'Outils de développement',
					'fields' => array(
						array(
							'key' => 'field_6181089b9486b',
							'label' => 'En construction',
							'name' => 'is_gate_in_construction',
							'type' => 'select',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '33.3333333333',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
								'activate' => 'Activer',
								'deactivate' => 'Désactiver',
							),
							'default_value' => 'deactivate',
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'ajax' => 0,
							'placeholder' => '',
						),
						$seo_module,
						array(
							'key' => 'field_6180d6ca95ab2',
							'label' => 'Réseaux sociaux',
							'name' => 'is_gate_social_network',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'table',
							'button_label' => 'Ajouter un réseau',
							'sub_fields' => array(
								array(
									'key' => 'field_6180d6f895ab3',
									'label' => 'Nom',
									'name' => 'name',
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
									'key' => 'field_6180d6fe95ab4',
									'label' => 'Class FontAwesome',
									'name' => 'fa_class',
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
									'key' => 'field_6180d71095ab5',
									'label' => 'URL',
									'name' => 'url',
									'type' => 'url',
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
								),
							),
						),
						array(
							'key' => 'field_6180e80274d26',
							'label' => 'Menus',
							'name' => 'is_gate_menu',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'table',
							'button_label' => 'Ajouter un menu',
							'sub_fields' => array(
								array(
									'key' => 'field_6180fccb45a0d',
									'label' => 'Nom',
									'name' => 'name',
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
									'key' => 'field_6180fcd345a0e',
									'label' => 'Slug',
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
							),
						),
						array(
							'key' => 'field_6180fe5742a88sda',
							'label' => 'Pages d\'options',
							'name' => 'is_gate_pages_options',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'block',
							'button_label' => 'Ajouter une page',
							'sub_fields' => array(
								array(
									'key' => 'field_6180fe8242a89',
									'label' => 'Titre général',
									'name' => 'main_title',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
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
									'key' => 'field_6180fe9d42a8a',
									'label' => 'Titre pour le menu',
									'name' => 'menu_title',
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
									'key' => 'field_6180feb142a8b',
									'label' => 'Slug du menu',
									'name' => 'menu_slug',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
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
									'key' => 'field_6180fec642a8c',
									'label' => 'Capabilité',
									'name' => 'capability',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '33.3333333333',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'edit_posts',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_6180fee442a8d',
									'label' => 'URL de l\'icone',
									'name' => 'icon_url',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '33.3333333333',
										'class' => '',
										'id' => '',
									),
									'default_value' => 'dashicons-admin-tools',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_6180ff0442a8e',
									'label' => 'Redirect',
									'name' => 'redirect',
									'type' => 'true_false',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '33.3333333333',
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
								'param' => 'options_page',
								'operator' => '==',
								'value' => 'is_gate_settings',
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
					'modified' => 16359283999,
				));


				/*
				* Add Options Pages
				*/
				if(get_field('is_gate_pages_options', 'options')){
		        	foreach (get_field('is_gate_pages_options', 'options') as $page) {
		        		$page_title = $page['main_title'];
		        		$menu_title = !empty($page['menu_title']) ? $page['menu_title'] : $page['main_title'];
		        		$menu_slug = $page['menu_slug'];
		        		$capability = $page['capability'];
	                	$icon_url = $page['icon_url'];
	                	$redirect = $page['redirect'];


						$option_page = acf_add_options_page(array(
							'page_title' => $page_title,
							'menu_title' => $menu_title,
							'menu_slug' => $menu_slug,
							'capability' => $capability,
							'icon_url' => $icon_url,
							'redirect' => $redirect
						));
		        	}
		        }


		        /*
				* Add Menu Locations
				*/
				$location_array = array();
				if(get_field('is_gate_menu', 'options')){
					foreach(get_field('is_gate_menu', 'options') as $menu){
						$titre = $menu['name'];
						$slug = $menu['slug'];
						$location_array[$slug] = $titre;
					}
					register_nav_menus($location_array);
				}


			});


			/*
			* In construction
			*/
			add_action('wp_head', function(){
				$user = wp_get_current_user();
				$roleArray = $user->roles;
				$userRole = isset($roleArray[0]) ? $roleArray[0] : '';
				if(class_exists('isGateCORE') && get_field('is_gate_in_construction', 'options') === 'activate' && !is_front_page() && !in_array($userRole, ['administrator'])){
					header('location: ' . get_bloginfo('url'));
					exit;
				}
			}, 1);


		}

	}

	new isGateCORE();
}

?>