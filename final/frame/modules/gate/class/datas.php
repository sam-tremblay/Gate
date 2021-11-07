<?php

	class gateManageDatas{

		public $acf_path;

		function __construct(){

			$this->acf_path = get_stylesheet_directory() . '/datas/acf';

			if(class_exists('acf'))
				$this->acf();
		}


		function acf(){

			add_filter('acf/settings/save_json', function($path){
				return $this->acf_path;
			});

			add_filter('acf/settings/load_json', function($paths){
				// Remove original path
				unset( $paths[0] );

				// Append our new path
				$paths[] = $this->acf_path;

				return $paths;
			});
		}

	}

	new gateManageDatas();
?>