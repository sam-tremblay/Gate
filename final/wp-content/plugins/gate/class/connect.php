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


		function connect_plugins(){
			$plugin_to_activate = array(
				'advanced-custom-fields-pro/acf.php',
			);

			foreach ($plugin_to_activate as $plugin) {
				if(file_exists(ABSPATH.'/frame/modules/'.$plugin) && !is_plugin_active($plugin))
					activate_plugin($plugin);
			}
		}

	}

	new gateConnect();
?>