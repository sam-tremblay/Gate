<?php get_header();
	while(have_posts()) : the_post();
		$coming_path = gc::tp('coming-soon.php');
		$front_path = gc::tp('front-page.php');

		$include = $front_path;


		if(gc::field('gate_in_construction') === 'activate')
			$include = $coming_path;

		include $include;

	endwhile;
get_footer(); ?>