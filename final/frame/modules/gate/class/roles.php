<?php

	class gateManageRoles{
		
		function __construct(){

			/*
			* ACF Init
			*/
			if(class_exists('acf')){
				add_action('acf/init', function(){
					// add Role Management
					$this->manager();

					// Add option in Role Management
					$this->acf();

					// Add Roles created from Role Management
					$this->add();
				});
			}

		}


		function manager(){
			$labels = array(
				'name'                     => __( 'Roles', 'gate' ),
		        'singular_name'            => __( 'Role', 'gate' )
			);
			$args  = array(
				'labels'             => $labels,
				'description'		 => '',
		        'public'             => false,
		        'publicly_queryable' => false,
		        'show_ui'            => true,
		        'show_in_menu'       => false,
		        'show_in_nav_menus'  => false,
		        'query_var'          => false,
		        'capability_type'    => 'post',
		        'has_archive'        => false,
		        'hierarchical'       => false,
		        'menu_position'      => null,
		        'supports'           => array('title'),
			);
			register_post_type("gate_role", $args);
		}

		function add(){
			global $wp_roles;

			/*
			* Add dev role
			*
			* But before, get admin role to clone
			*/
			$admin_role = $wp_roles->get_role('administrator');

			// Add
			$wp_roles->add_role('developer', 'Dev', $admin_role->capabilities);


			/*
			* Take Roles created with manager and add it to WordPress
			*/
			$roles = gc::cpt('gate_role');
			if($roles->have_posts()){
				while($roles->have_posts()) : $roles->the_post();
					$caps = !empty(gc::field('capabilities')) ? array_filter(gc::field('capabilities')) : [];
					add_role(gc::field('labels_slug'), gc::field('labels_name'), $caps);
				endwhile;
			}

		}


		function acf(){

		}
		
	}

	new gateManageRoles();

?>