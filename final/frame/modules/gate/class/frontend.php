<?php

	class gateFrontend{
		
		function __construct(){
			/*
			* Remove Admin Bar
			*/
			add_filter('show_admin_bar', '__return_false');


			/*
			* Remove Head Sources
			*/
			add_action('init', [$this, 'clean_wp_head']);


			/*
			* Add Head Sources
			*/
			add_action('wp_head', [$this, 'addto_wp_head']);


			/*
			* Manage Footer Sources
			*/
			add_action('wp_footer', function(){
				// Remove wp-embed
				wp_deregister_script('wp-embed');

				// Add Footer Sources
				$this->addto_wp_footer();
			});

		}


		function clean_wp_head(){
			global $sitepress;
			remove_action('wp_head', 'rsd_link');
			remove_action('wp_head', 'wlwmanifest_link');
			remove_action('wp_head', 'wp_generator');
			remove_action('wp_head', 'start_post_rel_link');
			remove_action('wp_head', 'index_rel_link');
			remove_action('wp_head', 'adjacent_posts_rel_link');
			remove_action('wp_head', 'rest_output_link_wp_head');
			remove_action('wp_head', 'wp_oembed_add_discovery_links');
			remove_action('wp_head', 'wp_resource_hints', 2);
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_action('wp_head', 'rel_canonical');
			remove_action('wp_head', 'wp_shortlink_wp_head', 10);
			remove_action( 'template_redirect', 'wp_shortlink_header', 11);
			remove_action( 'wp_head', array( $sitepress, 'meta_generator_tag' ) );
		}


		function addto_wp_head(){
			$html = '<meta charset="'. get_bloginfo('charset') .'">';
			$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
			$html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">';
			$html .= '<meta name="theme-color" content="#000">';

			$html .= '<title>'. gs::title() .'</title>';

			if(gs::desc())
				$html .= '<meta name="description" content="'. gs::desc() .'">';
			
			if(gs::index())
				$html .= '<meta name="robots" content="noindex,follow">';

			if(gs::title_sn())
				$html .= '<meta property="og:title" content="'. gs::title_sn() .'">';

			$html .= '<meta property="og:site_name" content="'. get_bloginfo('name') .'">';

			if(gs::desc_sn())
				$html .= '<meta property="og:description" content="'. gs::desc_sn() .'">';

			$html .= '<meta property="og:locale" content="'. get_locale() .'">';

			if(gs::img_sn())
				$html .= '<meta property="og:image" content="'. gs::img_sn() .'">';
			

			// og:type
			$og_type = '<meta property="og:type" content="website" />';
			if(is_single(get_the_ID())){

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

			if(gs::favicon()){
				$html .= '<link rel="apple-touch-icon" sizes="180x180" href="'. gs::favicon() .'">';
				$html .= '<link rel="icon" type="image/png" sizes="32x32" href="'. gs::favicon() .'">';
				$html .= '<link rel="icon" type="image/png" sizes="16x16" href="'. gs::favicon() .'">';
				$html .= '<meta name="msapplication-TileColor" content="#0000">';
			}
		
			$html .= '<link rel="stylesheet" type="text/css" href="https://cdn.gateforwp.com/V4.1/css/base.min.css">';

			$html .= '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').'/assets/css/main.min.css">';

			$html .= '<script src="https://cdn.gateforwp.com/V4.1/js/head.min.js"></script>';
			


			if(gs::analytics()){
				$html .= '<script async src="https://www.googletagmanager.com/gtag/js?id='. gs::analytics() .'"></script>';
				$html .= '<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "'. gs::analytics() .'");</script>';
			}


			echo $html;

			if(gc::field('gate_in_construction') === 'deactivate' && !is_front_page() && !current_user_can('administrator')){
				header('location: ' . get_bloginfo('url'));
				exit;
			}
		}


		function addto_wp_footer(){
			
			$html = '<script src="https://cdn.gateforwp.com/V4.1/js/footer.min.js"></script>';
			

			$html .= '<script src="'. get_bloginfo('stylesheet_directory') .'/assets/js/main.min.js"></script>';


			$html .= '<!-- Développé avec WP Gate - https://gateforwp.com -->';


			echo $html;
		}
	}

	new gateFrontend();

?>