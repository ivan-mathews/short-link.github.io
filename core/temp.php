<?php

	class Temp{
		
		private $conf,$data;
		
		private static $i;
		
		public static function I() {
			if (self::$i === null) {
				self::$i = new self;
			}
			return self::$i;
		}
		
		public function __construct(){
			
			$this->conf = Conf::I();
		}
		
		public function RenderPage($page){
			
			$path = Core::PathToFile("temp/".$this->conf->DEFAULT_THEME."/{$page}");
			
			if(is_readable($path)){
				
				include $path;
				
			}
			
		}
		
		public function RenderAction($temp_file,$data=array(),$module=false){
			
			if(!$module){ $module = Core::I()->module; }
			
			$path = Core::PathToFile("temp/".$this->conf->DEFAULT_THEME."/mods/{$module}/{$temp_file}.temp.php");
			
			ob_start();

			extract($data); 
			include($path);

			$data = ob_get_clean();
			$this->data .= $data;

			return $this->data;
			
		}
		
		public function RenderErrorPage($temp_file){
			
			$path = Core::PathToFile("temp/".	$this->conf->DEFAULT_THEME	."/{$temp_file}.temp.php");
			include($path);
			
		}
		
		public function isBody(){
			return !empty($this->data);
		}

		public function body(){
			print $this->data;
		}
		
		public function renderAsset($temp_file, $data=array()){

			$temp_file = Core::PathToFile("temp/".	$this->conf->DEFAULT_THEME	."/{$temp_file}.temp.php");

			extract($data); include($temp_file);

		}

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}