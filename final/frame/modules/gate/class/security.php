<?php

	class gateSecurity{

		private $length = 64;
		private $min = 0;
		private $max = 9999999999999;
		
		function __construct(){

			if(!isset($_COOKIE['gate_visitor_id']))
				$this->create_visitor_id();

		}


		private function create_visitor_id(){
			$code = substr(str_shuffle(random_int($this->min, $this->max) . 'abcdDefg' . bin2hex(random_bytes($this->length)) . 'hijklmnop' . bin2hex(openssl_random_pseudo_bytes($this->length)) . 'qrstuvVwxyz'), 0, 32);

			setcookie('gate_visitor_id', $code, time() + MONTH_IN_SECONDS, '/', str_replace(['https', 'http', '/www.', '/', ':'], '', get_bloginfo('url')));
		}


		private function visitor_id(){

			return isset($_COOKIE['gate_visitor_id']) ? $_COOKIE['gate_visitor_id'] : null;

		}


		static function form_check(){

			if(!$this->visitor_id()) return false;


			return true;
		}
	}

	new gateSecurity();

?>