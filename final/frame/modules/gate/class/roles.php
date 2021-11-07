<?php

	class gateManageRoles{
		
		function __construct(){

			/*
			* Init
			*/
			add_action('init', function(){
				// add roles
				$this->add();
			});

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

		}
		
	}

	new gateManageRoles();

?>