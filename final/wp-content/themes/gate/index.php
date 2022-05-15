<?php
/*
* Used if home.php is not set or if you don't set WordPress to use a static front page
*/

get_header();
while(have_posts()) : the_post();


	$coming_path = isTheme::tp('coming-soon.php');
	$front_path = isTheme::tp('front-page.php');

	$include = $front_path;


	$user = wp_get_current_user();
	$roleArray = $user->roles;
	$userRole = isset($roleArray[0]) ? $roleArray[0] : '';

	if(isTheme::field('is_plugin_in_construction') === 'activate' && !in_array($userRole, ['administrator']))
		$include = $coming_path;


	include $include;

endwhile; get_footer(); ?>