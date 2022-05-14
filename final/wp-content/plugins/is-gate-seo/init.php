<?php
/*
Plugin Name: Gate SEO
Author: Sam Tremblay
Author URI: https://sam-tremblay.com
Description: Vous donne le contrôle de votre SEO WordPress.
Requires at least: 5.9
Tested up to: 5.9
Requires PHP: 7.3
Version: 1.0
License: MIT License
License URI: https://github.com/sam-tremblay/Gate/blob/master/LICENSE
Text Domain: is-gate-seo
*/

if (!defined('ABSPATH')) exit;


if (!defined('IGS_VERSION')) define('IGS_VERSION', '1.0');


if (!class_exists('isGateSEO')){
	class isGateSEO{

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


			add_action('acf/init', function(){

				/*
				* Add options
				*/
				acf_add_local_field_group(array(
					'key' => 'group_618248b78c805',
					'title' => 'SEO',
					'fields' => array(
						array(
							'key' => 'field_618248d79bdaf',
							'label' => 'SEO',
							'name' => 'is_plugin_seo',
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
									'key' => 'field_618248d79bdb2',
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
									'key' => 'field_618249946993b',
									'label' => 'Indexer',
									'name' => 'index',
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
									'key' => 'field_618248d79bdb3',
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
									'key' => 'field_618248d79bdb4',
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
									'key' => 'field_618248d79bdb5',
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
									'key' => 'field_618248d79bdb6',
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
									'key' => 'field_618248d79bdb7',
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
									'key' => 'field_618248d79bdb8',
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
					),
					'location' => $this->locations(),
					'menu_order' => 0,
					'position' => 'normal',
					'style' => 'seamless',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => true,
					'description' => '',
				));

			});


			/*
			* Add SEO Meta to wp_head()
			*/
			add_action('wp_head', function(){
				$html = '<meta charset="'. get_bloginfo('charset') .'">';
				$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
				$html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">';
				$html .= '<meta name="theme-color" content="#000">';

				$html .= '<title>'. self::title() .'</title>';

				if(self::desc())
					$html .= '<meta name="description" content="'. self::desc() .'">';
				
				if(!self::index())
					$html .= '<meta name="robots" content="noindex,follow">';

				if(self::title_sn())
					$html .= '<meta property="og:title" content="'. self::title_sn() .'">';

				$html .= '<meta property="og:site_name" content="'. get_bloginfo('name') .'">';

				if(self::desc_sn())
					$html .= '<meta property="og:description" content="'. self::desc_sn() .'">';

				$html .= '<meta property="og:locale" content="'. get_locale() .'">';

				if(self::img_sn())
					$html .= '<meta property="og:image" content="'. self::img_sn() .'">';
				

				// og:type
				$og_type = '<meta property="og:type" content="website" />';
				if(is_singular('post')){

					global $post;

					$author = $post->post_author;
					$author_posts_url = get_author_posts_url($author);
					$publish_date = get_the_date('Y-m-d');
					$tags = get_the_tags();
					$recap_tags = array();
					if(is_array($tags)){
						foreach ($tags as $tag) {
							$recap_tags[] = $tag->name;
						}
					}
					$tags = implode(',', $recap_tags);

					$og_type = '<meta property="og:type" content="article" />';
					$og_type .= '<meta property="article:author" content="'. $author_posts_url .'" />';
					$og_type .= '<meta property="article:published_time" content="'. $publish_date .'" />';
					
					if($recap_tags)
						$og_type .= '<meta property="article:tags" content="'. $tags .'" />';

				} elseif(is_author()){

					$author = get_queried_object();
					$author = get_userdata($author->ID);

					$og_type = '<meta property="og:type" content="profile" />';
					$og_type .= '<meta property="profile:first_name" content="'. $author->first_name .'" />';
					$og_type .= '<meta property="profile:last_name" content="'. $author->last_name .'" />';
					$og_type .= '<meta property="profile:username" content="'. $author->user_login .'" />';

				}

				$html .= $og_type;

				if(self::favicon()){
					$html .= '<link rel="apple-touch-icon" sizes="180x180" href="'. self::favicon() .'">';
					$html .= '<link rel="icon" type="image/png" sizes="32x32" href="'. self::favicon() .'">';
					$html .= '<link rel="icon" type="image/png" sizes="16x16" href="'. self::favicon() .'">';
					$html .= '<meta name="msapplication-TileColor" content="#0000">';
				}


				echo $html;
			}, 2);

		}

		function locations(){
			$location = array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					),
				),
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					),
				),
			);


			$cpt = new WP_Query([
				'post_type' => 'is_plugin_cpt'
			]);

			if($cpt->have_posts()){
				while($cpt->have_posts()) : $cpt->the_post();
					if(get_field('modules_seo')){
						$location[] = array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => get_field('args_post_type'),
							),
						);
					}
				endwhile; wp_reset_postdata();
			}

			return $location;
		}


		static function title(){
			$from_options = get_field('is_plugin_seo_title_search_meta', 'options');
			$from_view = get_field('is_plugin_seo_title_search_meta');
			
			if(is_tax() || is_tag() || is_category()){

				$term = get_queried_object();

				$result = $term->name . ' - ' . get_bloginfo('name');

			} elseif(is_author()){
				$author = get_queried_object();
				$author = get_userdata($author->ID);

				$author_name = $author->first_name . ' ' . $author->last_name;

				$result = $author_name . ' - ' . get_bloginfo('name');
			} elseif(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = get_the_title() . ' - ' . $from_options;
			else
				$result = get_the_title() . ' - ' . get_bloginfo('name');

			return $result;
		}

		static function desc(){
			$from_options = get_field('is_plugin_seo_description_search_meta', 'options');
			$from_view = get_field('is_plugin_seo_description_search_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;


			return $result;
		}

		static function title_sn(){
			$from_options = get_field('is_plugin_seo_title_sn_meta', 'options');
			$from_view = get_field('is_plugin_seo_title_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = self::title();


			return $result;
		}

		static function desc_sn(){
			$from_options = get_field('is_plugin_seo_description_sn_meta', 'options');
			$from_view = get_field('is_plugin_seo_description_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;

			return $result;
		}

		static function img_sn(){
			$from_options = get_field('is_plugin_seo_image_sn_meta', 'options');
			$from_view = get_field('is_plugin_seo_image_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;

			return $result;
		}

		static function analytics(){
			$result = !empty(get_field('is_plugin_seo_ga_code', 'options')) ? get_field('is_plugin_seo_ga_code', 'options') : null;

			return $result;
		}

		static function index(){
			return get_field('is_plugin_seo_index');
		}

		static function favicon(){

			$result = !empty(get_field('is_plugin_seo_favicon', 'options')) ? get_field('is_plugin_seo_favicon', 'options') : null;

			return $result;
		}

	}

	new isGateSEO();
}