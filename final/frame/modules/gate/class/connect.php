<?php
	class gateConnect{


		function __construct(){
			add_action('admin_init', function(){
				$this->connect_plugins();
				$this->connect_theme();
			});
		}



		function connect_theme(){
			$theme = wp_get_theme();

			if($theme->name !== 'Gate Child' && file_exists(ABSPATH.'/frame/themes/gate-child/style.css'))
				switch_theme('gate-child');

		}

	}

	new gateConnect();
?>