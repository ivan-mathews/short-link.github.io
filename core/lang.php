<?php

	/*
	работа приостановлена
	*/
	
	/*
	class Lang{
		
		private static $i;
		
		public $lang, $conf, $user;
		
		public function __get($name) {
			if (!isset($this->lang[$name])){ return false; }
			return $this->lang[$name];
		}

		public static function I(){
			
			if(self::$i === null){
				self::$i = new self;
			}
			return self::$i;
		}
		
		public function __construct(){
			
			$this->conf = Conf::I();
			$this->user = User::I();
			
			$this->LoadLang($this->user->lang);
		}
		
		public function LoadLang($lang = false){
			
			if(!$lang){ $lang = $this->conf->lang_lc; }
			
			$path = Core::PathToFile("lang/{$lang}/main_lang.php");
			
			if(is_readable($path)){
				
				$this->lang = $this->conf->load($path);
			}
		}
		
		public function LoadModuleLang($lang = false, $module = false){
			
			if(!$lang){ $lang = $this->conf->lang_lc; }
			if(!$module){ $module = Core::I()->module; }
			
			$path = Core::PathToFile("lang/{$lang}/{$module}.php");
			
			if(is_readable($path)){
				
				$this->lang = $this->conf->load($path);
			}
		}
		
	}
	
	*/