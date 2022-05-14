<?php

	class gs{

		function __construct(){

			if(class_exists('acf')){
				add_action('acf/init', function(){

					/*
					* Add SEO Management on Pages, Posts
					* and Custom Post Types
					*/
					$this->acf();

				});
			}
		}

		static function title(){
			$from_options = get_field('gate_seo_title_search_meta', 'options');
			$from_view = get_field('gate_seo_title_search_meta');
			
			if(is_category())
				$result = single_cat_title('', false) . ' - ' . get_bloginfo('name');
			elseif(is_author()){
				$author = get_queried_object();
				$author = get_userdata($author->ID);

				$author_name = $author->first_name . ' ' . $author->last_name;

				$result = $author_name . ' - ' . get_bloginfo('name');
			} elseif(is_tax()){
				$tax = get_queried_object();
				$result = $tax->name . ' - ' . get_bloginfo('name');
			} elseif(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = get_the_title() . ' - ' . $from_options;
			else
				$result = get_the_title() . ' - ' . get_bloginfo('name');

			return $result;
		}

		static function desc(){
			$from_options = get_field('gate_seo_description_search_meta', 'options');
			$from_view = get_field('gate_seo_description_search_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;


			return $result;
		}

		static function title_sn(){
			$from_options = get_field('gate_seo_title_sn_meta', 'options');
			$from_view = get_field('gate_seo_title_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = gs::title();


			return $result;
		}

		static function desc_sn(){
			$from_options = get_field('gate_seo_description_sn_meta', 'options');
			$from_view = get_field('gate_seo_description_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;

			return $result;
		}

		static function img_sn(){
			$from_options = get_field('image_sn_meta', 'options');
			$from_view = get_field('image_sn_meta');

			if(!empty($from_view))
				$result = $from_view;
			elseif(!empty($from_options))
				$result = $from_view;
			else
				$result = null;

			return $result;
		}

		static function analytics(){
			$result = !empty(get_field('gate_seo_ga_code', 'options')) ? get_field('gate_seo_ga_code', 'options') : null;

			return $result;
		}

		static function index(){
			return get_field('gate_seo_index');
		}

		static function favicon(){

			$result = !empty(get_field('gate_seo_favicon', 'options')) ? get_field('gate_seo_favicon', 'options') : null;

			return $result;
		}

		function acf(){
			acf_add_local_field_group(array(
				'key' => 'group_618248b78c805',
				'title' => 'SEO',
				'fields' => array(
					array(
						'key' => 'field_618248d79bdaf',
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


			$cpt = gc::cpt('gate_cpt');

			if($cpt->have_posts()){
				while($cpt->have_posts()) : $cpt->the_post();
					if(gc::field('modules_seo')){
						$location[] = array(
							array(
								'param' => 'post_type',
								'operator' => '==',
								'value' => gc::field('args_post_type'),
							),
						);
					}
				endwhile; wp_reset_postdata();
			}

			return $location;
		}
		
	}

	new gs();
?>