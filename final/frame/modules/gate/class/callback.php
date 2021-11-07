<?php

	class gc{

		static function bodyClass(){
			global $post;
			$class = 'is-front';
			if(is_single()){
				$post_type = get_post_type();
				$class = $post->post_name . ' single single-'. $post_type;
			}
			elseif(is_author())
				$class = 'is-author';
			elseif(!is_front_page())
				$class = get_page_template_slug();

			$class = str_replace('.php', '', $class);


			return $class;
		}

		static function logo($location = 'header', $type = null){

			
			if(in_array($location, ['header', 'footer'])){
				
				$alt = !empty(gc::field('gate_'.$location.'_logo_attr_alt')) ? gc::field('gate_'.$location.'_logo_attr_alt') : gs::title();

				if($type === 'url' && !empty(gc::field('gate_'.$location.'file')))
					$result = gc::field('gate_'. $location .'file');

				elseif(!empty(gc::field('gate_'. $location .'_logo_svg')))
					$result = gc::field('gate_'. $location .'_logo_svg');

				elseif(!empty(gc::field('gate_'. $location .'file')))
					$result = '<img src="'. $result .'" alt="'. $alt .'">';

			}


			return isset($result) ? $result : null;
		}


		static function sn($format = 'icon'){

			if(in_array($format, ['icon', 'text', 'i-t']) && gc::field('gate_social_network')){
				
				$result = '<ul>';
					foreach (gc::field('gate_social_network') as $sn) {

						$result .= '<li>';
							if($format === 'icon')
								$result .= '<a href="'. $sn['url'] .'" title="'.$sn['name'] .'" class="fab fa-'. $sn['fa_class'] .' redirect" target="_blank"></a>';

							elseif($format === 'text')
								$result .= '<a href="'. $sn['url'] .'" title="'.$sn['name'] .'" class="redirect" target="_blank">'.$sn['name'] .'</a>';

							elseif($format === 'i-t')
								$result .= '<a href="'. $sn['url'] .'" title="'.$sn['name'] .'" class="redirect" target="_blank"><i class="fab fa-'. $sn['fa_class'] .'"></i>'.$sn['name'] .'</a>';

						$result .= '</li>';
					}
				$result .= '</ul>';

			}


			return isset($result) ? $result : null;
		}


		static function field($field_slug = null, $id = null){

			if($field_slug && $id)
				$result = get_field($field_slug, $id);

			elseif($field_slug)
				$result = !empty(get_field($field_slug, 'options')) ? get_field($field_slug, 'options') : get_field($field_slug);


			return isset($result) ? $result : null;
		}


		static function lang($format = null){

			if (function_exists('icl_get_languages')) {

				$languages = icl_get_languages('skip_missing=0&orderby=code');
				
				$result = '';
				if($format === 'list'){
					$result .= '<ul>';
						foreach($languages as $l){
							if(!$l['active'])
								$result .= '<li><a href="'.$l['url'].'">'. $l['native_name'] . '</a></li>';
						}
					$result .= '</ul>';
				} else {
					foreach($languages as $l){
						if(!$l['active'])
							$result .= '<a href="'.$l['url'].'">'. $l['native_name'] . '</a>';
					}
				}

			}


			return isset($result) ? $result : null;
		}


		static function menu($theme_location = null, $args = []){

			$parameters = array( 
				'menu' => '',
				'container' => false,
				'container_class' => '', 
				'container_id' => '', 
				'menu_class' => '',
				'menu_id' => '',
				'echo' => false, 
				'fallback_cb' => 'wp_page_menu', 
				'before' => '', 
				'after' => '', 
				'link_before' => '',
				'link_after' => '', 
				'items_wrap' => '<ul>%3$s</ul>', 
				'item_spacing' => 'preserve',
				'depth' => 0,
				'walker' => ''
			);

			if(!empty($args)){
				foreach($args as $arg_key => $arg){
					$parameters[$arg_key] = $arg;
				}
			}

			if(isset($parameters['add_mobile_bars']) && (int)$parameters['add_mobile_bars'] > 0){

				$html = '<div class="ham-menu">';
				for ($i=0; $i < (int)$parameters['add_mobile_bars']; $i++) { 
					$html .= '<span></span>';
				}
				$html .= '</div>';

				$parameters['items_wrap'] = $parameters['items_wrap'] . $html;
			}


			$parameters['theme_location'] = $theme_location;


			$result = wp_nav_menu($parameters);


			return $result;
		}


		static function cpt($post_type = 'post', $args = []){

			$parameters = array(
				'posts_per_page' => -1,
				'paged' => 1,
			);

			if(!empty($args)){
				foreach($args as $arg_key => $arg){
					$parameters[$arg_key] = $arg;
				}
			}

			$parameters['post_type'] = $post_type;

			$result = new WP_Query($parameters);


			return $result;

		}

		static function button($text = 'Aucun texte.', $args = ['href' => null, 'class' => null, 'attr' => null, 'before' => null, 'after' => null]){

			$href = !empty($args['href']) ? ' data-href="'. $args['href'] .'"' : null;
			$class = !empty($args['class']) ? ' class="'. $args['class'] .'"' : null;
			$attr = !empty($args['attr']) ? ' '. $args['attr'] : null;
			$before = !empty($args['before']) ? ' '. $args['before'] : null;
			$after = !empty($args['after']) ? ' '. $args['after'] : null;

			$result = '<button'. $class . $href . $attr .'>';
				$result .= $before;
					$result .= '<span>'. $text .'</span>';
				$result .= $after;
			$result .= '</button>';

			return $result;
		}


		static function id(){
			
			$construct_code = 'abcdefghijABCDEFGHIJ';
			$shuffle_code = str_shuffle($construct_code);
			$code = substr($shuffle_code, 0, 4);


			return 'g_id-' . $code;
		}


		static function inc($file_path = null){
			return get_stylesheet_directory().'/inc/' . $file_path;
		}

		static function tp($file_path = null){
			return get_stylesheet_directory().'/inc/template-parts/' . $file_path;
		}

		static function assets($file_path = null){
			return get_bloginfo('stylesheet_directory') . '/assets/' . $file_path;
		}
	}

	new gc();
?>