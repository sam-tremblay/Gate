<?php get_header();
	while(have_posts()) : the_post();
		$coming_path = 'inc/template-parts/coming-soon.php';
		$front_path = 'inc/template-parts/front-page.php';

		$include = $front_path;


		if(get_field('comingsoon_active', 'options') === 'Oui')
			$include = $coming_path;

		include $include;

	endwhile;
get_footer(); ?>