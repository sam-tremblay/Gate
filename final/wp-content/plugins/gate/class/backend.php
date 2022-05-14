<?php

	class gateBackend{

		function __construct(){


			/*
			* Admin init
			*/
			add_action('admin_init', function(){
				// Remove basics box on dashboard
				$this->clean_dashboard();

				/*
				* Remove Basics elements like Hello Plugin,
				* Akismet Plugin, post, page, comment, Gutenburg etc.
				*/
				$this->remove_basics();
			});




			/*
			* Top bar
			*/
			add_action('admin_bar_menu', function(){

				// Remove not wanted elements
				$this->clean_top_bar();

				// Add menu new elements
				$this->add_to_top_bar();

			}, 100);
			

			/*
			* Clean Left menu
			*/
			add_action('admin_menu', [$this, 'clean_left_menu']);


			/*
			* Allow SVG
			*/
			$this->mimes();
			

			/*
			* Remove Gutenberg for post type
			*/
			add_filter('use_block_editor_for_post_type', '__return_false', 10);


			/*
			* Add Styles
			*/
			add_action('admin_head', function(){
        		$this->admin_styles();
			});


			/*
			* ACF init
			*/
			if(class_exists('acf')){

				add_action('init', [$this, 'add_option_in_option_page']);

				add_action('acf/init', function(){

					// Add options pages
					$this->add_option_page();

					// Add options in Options Pages
					$this->acf();

					// Add Register menus
					$this->register_menus();

				});
			}
		}



		function clean_dashboard(){
			remove_action('welcome_panel', 'wp_welcome_panel');
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
			remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
			remove_meta_box( 'dlm_popular_downloads', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
		}


		function clean_left_menu(){
			remove_menu_page('tools.php');
			remove_menu_page('upload.php');
			remove_menu_page('themes.php');
			remove_menu_page('plugins.php');
			remove_menu_page('edit-comments.php');
			remove_menu_page('users.php');
			remove_menu_page('edit.php?post_type=acf-field-group');
			if(!is_plugin_active('advanced-custom-fields-pro/acf.php') || gc::field('gate_blog') === 'deactivate' || empty(gc::field('gate_blog')))
				remove_menu_page('edit.php');

			remove_submenu_page('options-general.php', 'options-privacy.php');
			remove_submenu_page('options-general.php', 'options-media.php');
			remove_submenu_page('options-general.php', 'options-writing.php');
			remove_submenu_page('options-general.php', 'options-discussion.php');
		}


		function remove_basics(){
			// On supprime plugins, pages, articles et commentaires créé lors de l'installation
			// Basic pugins
			if (file_exists(WP_PLUGIN_DIR.'/hello.php') || file_exists(WP_PLUGIN_DIR.'/akismet/akismet.php')) {
		        require_once(ABSPATH.'wp-admin/includes/plugin.php');
		        require_once(ABSPATH.'wp-admin/includes/file.php');
		        delete_plugins(array('hello.php', 'akismet/akismet.php'));
		    }

		    // Basics page and post
		    // Le commentaire de base est supprimé avec l'article.
		    $ids = array('1', '2', '3');
			foreach ($ids as $id) {
				wp_delete_post($id);
			}

			// On supprime Gutenburg et WYSIWYG des pages et articles
			global $_wp_post_type_features;

			$post_type=array("page", "post");
			$feature = "editor";
			foreach ($post_type as $p) {
				if ( isset($_wp_post_type_features[$p][$feature]) ){
					unset($_wp_post_type_features[$p][$feature]);
				}
			}
		}


		function clean_top_bar(){
			global $wp_admin_bar;
			$user = wp_get_current_user();
			$roleArray = $user->roles;
			$userRole = isset($roleArray[0]) ? $roleArray[0] : '';

			$wp_admin_bar->remove_node( 'wp-logo' );
			$wp_admin_bar->remove_node( 'site-name' );
			$wp_admin_bar->remove_node( 'comments' );
			$wp_admin_bar->remove_node( 'new-content' );

			if(!in_array($userRole, array('administrator', 'developer')))
				$wp_admin_bar->remove_node( 'updates' );
		}


		function add_to_top_bar(){
			global $wp_admin_bar;
			$user = wp_get_current_user();
			$roleArray = $user->roles;
			$userRole = isset($roleArray[0]) ? $roleArray[0] : '';

			$site_url = get_site_url();
			$admin_url = $site_url . '/wp-admin/';


			/*
			* On ajoute un lien vers l'accueil du site
			*/
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


			/*
			* On ajoute un lien vers la gestion des menus seulement
			* s'il y a des menus dans "Gate pour les développeurs.euses"
			*/
			$args = array(
				'id' => 'gest-menus',
				'title' => 'Navigations',
				'href' => $admin_url . 'nav-menus.php',
				'meta' => array(
					'class' => 'gest-menus',
					'title' => 'Gestionnaire des menus'
				)
			);
			if(gc::field('gate_menu') && current_user_can('manage_options'))
				$wp_admin_bar->add_node($args);


			/*
			* On ajoute un lien vers la gestion des fichiers
			*/
			$args = array(
				'id' => 'gest-files',
				'title' => 'Images & fichiers',
				'href' => $admin_url . 'upload.php',
				'meta' => array(
					'class' => 'gest-files',
					'title' => 'Gestionnaire des fichiers et des images'
				)
			);
			if(current_user_can('upload_files'))
				$wp_admin_bar->add_node($args);


			/*
			* On ajoute un lien vers la liste des utilisateurs
			*/
			$args = array(
				'id' => 'gest-users-list',
				'title' => 'Utilisateurs',
				'href' => $admin_url . 'users.php',
				'meta' => array(
					'class' => 'gest-users-list',
					'title' => 'Liste des utilisateurs'
				)
			);
			if(current_user_can('list_users'))
				$wp_admin_bar->add_node($args);

			/*
			* On ajoute un lien vers le profil du membre connecté avec l'onglet utilisateurs
			*/
			$args = array(
				'id' => 'gest-users-profile',
				'title' => 'Votre profil',
				'href' => $admin_url . 'profile.php',
				'parent' => 'gest-users-list',
				'meta' => array(
					'class' => 'gest-users-profile',
					'title' => 'Votre profile'
				)
			);
			$wp_admin_bar->add_node($args);

			if(in_array($userRole, array('developer'))){
				/*
				* On ajoute l'onglet pour les développeurs.euses
				*/
				$args = array(
					'id' => 'gate-dev',
					'title' => __('Gate pour les développeurs', 'gate'),
					'meta' => array(
						'class' => 'gate-dev',
						'title' => 'Regroupement d\'outils pour les développeurs'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet 'Général'
				*/
				$args = array(
					'id' => 'global-configs',
					'title' => 'Général',
					'href' => $admin_url . 'admin.php?page=gate-settings',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'global-configs',
						'title' => 'Configurations générales'
					)
				);
				if(is_plugin_active('advanced-custom-fields-pro/acf.php'))
					$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet des Custom Post Types
				*/
				$args = array(
					'id' => 'configs-custom-post-types',
					'title' => 'Custom post types',
					'href' => $admin_url . 'edit.php?post_type=gate_cpt',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-custom-post-types',
						'title' => 'Configurations des types de posts'
					)
				);
				if(is_plugin_active('advanced-custom-fields-pro/acf.php'))
					$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet des Rôles
				*/
				$args = array(
					'id' => 'configs-roles',
					'title' => 'Rôles',
					'href' => $admin_url . 'edit.php?post_type=gate_role',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-roles',
						'title' => 'Configurations des rôles'
					)
				);
				if(is_plugin_active('advanced-custom-fields-pro/acf.php'))
					$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet themes
				*/
				$args = array(
					'id' => 'configs-themes',
					'title' => 'Themes',
					'href' => $admin_url . 'themes.php',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-themes',
						'title' => 'Configurations des themes'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet editeur dans themes
				*/
				$args = array(
					'id' => 'configs-themes-editor',
					'title' => 'Éditeur',
					'href' => $admin_url . 'theme-editor.php',
					'parent' => 'configs-themes',
					'meta' => array(
						'class' => 'configs-themes-editor',
						'title' => 'Éditeur des themes'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet extensions
				*/
				$args = array(
					'id' => 'configs-extensions',
					'title' => 'Extensions',
					'href' => $admin_url . 'plugins.php',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-extensions',
						'title' => 'Configurations des extensions'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet editeur dans extenstion
				*/
				$args = array(
					'id' => 'configs-exts-editor',
					'title' => 'Éditeur',
					'href' => $admin_url . 'plugin-editor.php',
					'parent' => 'configs-extensions',
					'meta' => array(
						'class' => 'configs-exts-editor',
						'title' => 'Éditeur des extensions'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet ACF
				*/
				if(is_plugin_active('advanced-custom-fields-pro/acf.php')){
					$args = array(
						'id' => 'configs-acf',
						'title' => 'ACF',
						'href' => $admin_url . 'edit.php?post_type=acf-field-group',
						'parent' => 'gate-dev',
						'meta' => array(
							'class' => 'configs-acf',
							'title' => 'Gestion des groupes ACF'
						)
					);
					$wp_admin_bar->add_node($args);
				}


				if(is_plugin_active('sitepress-multilingual-cms/sitepress.php')){


					$languages = icl_get_languages('skip_missing=0&orderby=code');
					
					$target = $languages ? 'tm/menu/main.php' : 'sitepress-multilingual-cms/menu/setup.php';
					$args = array(
						'id' => 'configs-wpml',
						'title' => 'WPML',
						'href' => $admin_url . 'admin.php?page=' . $target,
						'parent' => 'gate-dev',
						'meta' => array(
							'class' => 'configs-wpml',
							'title' => 'Configuration de WPML'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-translate',
						'title' => 'Gestion de traduction',
						'href' => $admin_url . 'admin.php?page=tm/menu/main.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-translate',
							'title' => 'Gestion de traduction'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-languages',
						'title' => 'Langues',
						'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/languages.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-languages',
							'title' => 'Cet écran affiche les paramètres de langue de votre site.'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-theme-localization',
						'title' => 'Localisation du thème et des plugins',
						'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/theme-localization.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-theme-localization',
							'title' => 'Localisation du thème et des plugins'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-translations-queue',
						'title' => 'Traductions',
						'href' => $admin_url . 'admin.php?page=tm/menu/translations-queue.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-translations-queue',
							'title' => 'Traductions en attente'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-sync-menus',
						'title' => 'Synchroniser les menus WP',
						'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/menu-sync/menus-sync.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-sync-menus',
							'title' => 'La synchronisation de menu synchronisera la structure de menu de la langue par défaut de Français vers les langues secondaires.'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-string-translation',
						'title' => 'Tranduction des chaînes',
						'href' => $admin_url . 'admin.php?page=wpml-string-translation/menu/string-translation.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-string-translation',
							'title' => 'Tranduction des chaînes.'
						)
					);
					if(is_plugin_active('wpml-string-translation/plugin.php'))
						$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-tax-translation',
						'title' => 'Traduction de taxonomie',
						'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/taxonomy-translation.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-tax-translation',
							'title' => 'Traduction de taxonomie'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-paq',
						'title' => 'Paquetages',
						'href' => $admin_url . 'admin.php?page=wpml-package-management',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-paq',
							'title' => 'Gestion des paquetages'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-parameters',
						'title' => 'Paramètres',
						'href' => $admin_url . 'admin.php?page=tm/menu/settings',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-parameters',
							'title' => 'Paramètres'
						)
					);
					$wp_admin_bar->add_node($args);


					$args = array(
						'id' => 'gest-support',
						'title' => 'Assistance technique',
						'href' => $admin_url . 'admin.php?page=sitepress-multilingual-cms/menu/support.php',
						'parent' => 'configs-wpml',
						'meta' => array(
							'class' => 'gest-support',
							'title' => 'Assistance technique'
						)
					);
					$wp_admin_bar->add_node($args);
				}


				/*
				* On ajoute le sous onglet importer
				*/
				$args = array(
					'id' => 'configs-import',
					'title' => 'Importer',
					'href' => $admin_url . 'import.php',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-import',
						'title' => 'Configurations et utilisation des outils d\'importation'
					)
				);
				$wp_admin_bar->add_node($args);


				/*
				* On ajoute le sous onglet exporter
				*/
				$args = array(
					'id' => 'configs-export',
					'title' => 'Exporter',
					'href' => $admin_url . 'export.php',
					'parent' => 'gate-dev',
					'meta' => array(
						'class' => 'configs-export',
						'title' => 'Outils d\'exportation'
					)
				);
				$wp_admin_bar->add_node($args);

			}
		}


		function mimes(){
			add_filter('upload_mimes', function($mimes){
				$mimes['svg'] = 'image/svg+xml';
				return $mimes;
			});

			add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes) {
				global $wp_version;

				if( $wp_version == '4.7' || ( (float) $wp_version < 4.7 ) ) {
					return $data;
				}

				$filetype = wp_check_filetype( $filename, $mimes );

				return [
					'ext'             => $filetype['ext'],
					'type'            => $filetype['type'],
					'proper_filename' => $data['proper_filename']
				];
				
			}, 10, 4);
		}


		function admin_styles() {
		  echo '<style>
			#toplevel_page_gate-settings,
		  	#toplevel_page_tm-menu-main,
		  	#toplevel_page_sitepress-multilingual-cms-menu-setup{
				display: none !important;
			}
		  </style>';
		}


		function add_option_page(){

	        // Register options page.  
	        $option_page = acf_add_options_page(array(
	            'page_title' => __('Gate pour les développeurs.euse', 'gate'),
	            'menu_title' => __('Gate', 'gate'),
	            'menu_slug' => 'gate-settings',
	            'capability' => 'edit_posts',
	            'redirect' => false
	        ));
		}


		function add_option_in_option_page(){
			if(gc::field('gate_pages_options')){
	        	foreach (gc::field('gate_pages_options') as $page) {
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
		}


		function register_menus(){
			$location_array = array();
			if(gc::field('gate_menu')){
				foreach(gc::field('gate_menu') as $menu){
					$titre = $menu['name'];
					$slug = $menu['slug'];
					$location_array[$slug] = $titre;
				}
				register_nav_menus($location_array);
			}
		}


		function acf(){
			acf_add_local_field_group(array(
				'key' => 'group_6180ce0d60567',
				'title' => 'Gate pour les développeurs.euse',
				'fields' => array(
					array(
						'key' => 'field_6180d8c894462',
						'label' => 'Documentation',
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
						'message' => '<a href="https://docs.gateforwp.com" target="_blank">https://docs.gateforwp.com</a>',
						'new_lines' => '',
						'esc_html' => 0,
					),
					array(
						'key' => 'field_618108079486a',
						'label' => 'Blogue',
						'name' => 'gate_blog',
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
					array(
						'key' => 'field_6181089b9486b',
						'label' => 'En construction',
						'name' => 'gate_in_construction',
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
					array(
						'key' => 'field_6180d68d95ab1',
						'label' => 'SEO',
						'name' => 'gate_seo',
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
					),
					array(
						'key' => 'field_6180ce519121c',
						'label' => 'Header',
						'name' => 'gate_header',
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
								'key' => 'field_6180d07691221',
								'label' => 'Logo',
								'name' => 'logo',
								'type' => 'group',
								'instructions' => 'Vous pouvez appelez le logo comme ceci: <code>&lt;?= gc::logo(\'header\'); ?&gt;</code>. Si vous utilisez un fichier et voulez récupérer l\'url: <code>&lt;?= gc::logo(\'header\', \'url\'); ?&gt;</code>.',
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
										'key' => 'field_6180ced99121d',
										'label' => 'Fichier',
										'name' => 'file',
										'type' => 'image',
										'instructions' => '',
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
										'min_width' => '',
										'min_height' => '',
										'min_size' => '',
										'max_width' => '',
										'max_height' => '',
										'max_size' => '',
										'mime_types' => '',
									),
									array(
										'key' => 'field_6180cfad9121f',
										'label' => 'Code SVG',
										'name' => 'svg',
										'type' => 'textarea',
										'instructions' => 'Si un fichier et un code SVG est en place, le code SVG sera priorisé.',
										'required' => 0,
										'conditional_logic' => 0,
										'wrapper' => array(
											'width' => '33.3333333333',
											'class' => '',
											'id' => '',
										),
										'default_value' => '',
										'placeholder' => '',
										'maxlength' => '',
										'rows' => 4,
										'new_lines' => '',
									),
									array(
										'key' => 'field_6180d00c91220',
										'label' => 'Attribut alt',
										'name' => 'attr_alt',
										'type' => 'text',
										'instructions' => 'Seulement si vous voulez remplacer l\'attribut alt de l\'image.',
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
						),
					),
					array(
						'key' => 'field_6180d478286f4',
						'label' => 'Footer',
						'name' => 'gate_footer',
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
								'key' => 'field_6180d478286f5',
								'label' => 'Logo',
								'name' => 'logo',
								'type' => 'group',
								'instructions' => 'Vous pouvez appelez le logo comme ceci: <code>&lt;?= gc::logo(\'footer\'); ?&gt;</code>. Si vous utilisez un fichier et voulez récupérer l\'url: <code>&lt;?= gc::logo(\'footer\', \'url\'); ?&gt;</code>.',
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
										'key' => 'field_6180d478286f6',
										'label' => 'Fichier',
										'name' => 'file',
										'type' => 'image',
										'instructions' => '',
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
										'min_width' => '',
										'min_height' => '',
										'min_size' => '',
										'max_width' => '',
										'max_height' => '',
										'max_size' => '',
										'mime_types' => '',
									),
									array(
										'key' => 'field_6180d478286f7',
										'label' => 'Code SVG',
										'name' => 'svg',
										'type' => 'textarea',
										'instructions' => 'Si un fichier et un code SVG est en place, le code SVG sera priorisé.',
										'required' => 0,
										'conditional_logic' => 0,
										'wrapper' => array(
											'width' => '33.3333333333',
											'class' => '',
											'id' => '',
										),
										'default_value' => '',
										'placeholder' => '',
										'maxlength' => '',
										'rows' => 4,
										'new_lines' => '',
									),
									array(
										'key' => 'field_6180d478286f8',
										'label' => 'Attribut alt',
										'name' => 'attr_alt',
										'type' => 'text',
										'instructions' => 'Seulement si vous voulez remplacer l\'attribut alt de l\'image.',
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
								'key' => 'field_6180d47f286f9',
								'label' => 'Copyright',
								'name' => 'copyright',
								'type' => 'text',
								'instructions' => 'Vous pouvez appelez le copyright comme ceci: <code>&lt;?= gc::field(\'gate_footer_copyright\'); ?&gt;</code> ou <code>&lt;?= get_field(\'gate_footer_copyright\', \'option\'); ?&gt;</code>.',
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
						'key' => 'field_6180d6ca95ab2',
						'label' => 'Réseaux sociaux',
						'name' => 'gate_social_network',
						'type' => 'repeater',
						'instructions' => 'Vous pouvez appelez la liste comme ceci: <code>&lt;?= gc::sn($format); ?&gt;</code>. Les formats possibles sont icon, text ou i-t.',
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
						'name' => 'gate_menu',
						'type' => 'repeater',
						'instructions' => 'Vous pouvez appeler un menu comme ceci: <code>&lt;?= gc::menu($slug, $args); ?&gt;</code>. Pour les arguments possibles ($args):',
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
						'name' => 'gate_pages_options',
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
							'value' => 'gate-settings',
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
		}


		function post_type_sorting_system(){

			$post_type = $_POST['post_type'];
			$new_list = $_POST['new_list'];

			
			foreach ($new_list as $key => $post_id) {
				wp_update_post([
					'ID' => $post_id,
					'menu_order' => $key
				]);
			}
			
			exit;
		}

		function terms_sorting_system(){

			global $wpdb;

			$taxonomy = $_POST['taxonomy'];
			$new_list = $_POST['new_list'];

         	if(empty($wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = %s AND COLUMN_NAME = %s ", $wpdb->prefix.'terms', 'term_order')))){
				$wpdb->query('ALTER TABLE '. $wpdb->prefix .'terms ADD term_order INT( 4 ) NULL DEFAULT "0"');
         	}

			foreach($new_list as $item_key => $term_id) {
				$wpdb->query('UPDATE '. $wpdb->prefix .'terms SET term_order='. ($item_key + 1) .' WHERE term_id='.$term_id);
			}

			exit;
		}

		function display_terms($columns){
			$columns['term_order'] = "term_order";

			//print_r($columns);
			return $columns;
		}

	}

	new gateBackend();

?>