<?php

	class gateManageDatas{


		static $acf_path = __DIR__ . '/../datas/acf';

		function __construct(){
			if(class_exists('acf'))
				$this->acf();
		}


		function acf(){

			add_filter('acf/settings/save_json', function($path){
				return gateManageDatas::$acf_path;
			});

			add_filter('acf/settings/load_json', function($paths){
				// Remove original path
				unset( $paths[0] );

				// Append our new path
				$paths[] = gateManageDatas::$acf_path;

				return $paths;
			});
		}

	}

	new gateManageDatas();
?>