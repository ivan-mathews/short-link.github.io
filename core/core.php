<?php

	class Core{
		
		private static $i;
		
		public $uri, $url, $action, $module, $query, $conf, $base, $user, $item, $alias;
		
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function I(){
			if(self::$i === null){
				self::$i = new self;
			}
			return self::$i;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function __construct(){
			
			$this->user = User::I();
			$this->base = MBase::I();
			$this->conf = Conf::I();
//			$this->lang = Lang::I();
			
			$this->LoadLang($this->user->lang);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function __destruct(){
			die;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function Router($uri){
			
			if(!$uri){ return; }
		
			$this->url = $uri;
			
			$uri = trim(urldecode($uri));
			$uri = mb_substr($uri, mb_strlen($this->conf->DELIMITER));
		
			$pos_que  = mb_strpos($uri, '?');
			if ($pos_que !== false){

				$query_data = array();
				$query_str  = mb_substr($uri, $pos_que+1);

				$uri = mb_substr($uri, 0, $pos_que);

				parse_str($query_str, $query_data);

				$this->query = $query_data;

				$_REQUEST = array_merge($query_data, $_REQUEST);

			}
			
			$this->uri = $uri;
			
			$this->Aliases($this->uri);
			
			$this->LoadModule($this->module,$this->action);

		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function Aliases($uri){
			
			$links = $this->base->getRow('aliases',"`uri`='{$uri}'");

			if(!empty($links) AND $links['uri'] === $uri){
				$module = $links['module'];
				$action = $links['action'];
			}
				
			if(!empty($module) AND !empty($action)){
				$this->module 	= $module;
				$this->action 	= $action;
				$this->item		= $links['id'];
				$this->alias	= $uri;
			}else{
				$this->SubAlias($uri);
			//	$this->ParseURI($uri);
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/		
		public function SubAlias($uri){
			
			$links = $this->conf->load(self::PathToFile('conf/aliases.php'));
			
			foreach($links as $key=>$value){
				if($key === $uri){
					$module = $value['module'];
					$action = $value['action'];
				}
			}
			if(!empty($module) AND !empty($action)){
				$this->module 	= $module;
				$this->action 	= $action;
			}else{
				$this->ParseURI($uri);
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function ParseURI($uri){
			
			$delimiter = mb_strpos($uri, $this->conf->DELIMITER);
			
			if($delimiter !== false){
				
				$uri_arr = array();
				$uri_arr = explode($this->conf->DELIMITER,$uri);
				
				$module = $uri_arr[0];
				$action = $uri_arr[1];
				
				if(!empty($uri_arr[2])){
					$this->item = $uri_arr[2];
				}else
					if(!empty($_REQUEST['id'])){
					$this->item = $_REQUEST['id'];
				}
				
			}else{
				
				preg_match_all("/[A-Za-z]/",$uri,$module);
				preg_match_all("/[0-9]/",$uri,$action);
				
				$module = implode('',$module[0]);
				$action = implode('',$action[0]);
				
				if(!empty($_REQUEST['id'])){
					$this->item = $_REQUEST['id'];
				}
			}
			
			$this->module = !empty($module) ? $module : $this->conf->DEF_MODULE;
			$this->action = !empty($action) ? $action : $this->conf->DEF_ACTION;
			
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function LoadModule($module,$action,$params=false){
			
			$path = self::PathToFile("core/mods/{$module}/{$module}.php");
			
			if(is_readable($path)){
				
				include_once $path;
				
				$module = new $module;
				
				if(method_exists($module,$action)){
					
					return call_user_func(array($module,$action),$params);
					
				}else if(is_numeric($action) AND method_exists($module,$this->conf->RUN_ACTION)){
					
					return call_user_func(array($module,$this->conf->RUN_ACTION),$action);
					
				}else{
					self::Error404();
				}
			}else{
				self::Error404();
			}
			
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Error404(){
			
			header("HTTP/1.0 404 Not Found");
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			
			Temp::I()->RenderErrorPage('assets/error404');
			
			die();
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function error($message, $details='', $die=false){

			if(ob_get_length()) { ob_end_clean(); }

			if($die){

				header('HTTP/1.0 503 Service Unavailable');
				header('Status: 503 Service Unavailable');

				Temp::I()->renderAsset('assets/error', array(
					'message'=>$message,
					'details'=>$details
				));
				
				die();
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function PathToFile($file){
			
			return ROOT.'/'.$file;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function KeyWordGenerate($key=false){
			
			$key = $key ? $_SERVER['HTTP_USER_AGENT'] : null;
			
			$keyword = '';
			
			for($a=0;$a<=9;$a++){
				
				$keyword .= md5(microtime(true).$key);
				
				usleep(rand(5000,50000));
				
			}
			
			return base64_encode($keyword);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function LoadLang($lang=false){
			
			if(empty($lang)){
				
				$HTTP_ACCEPT_LANGUAGE = explode('-',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
				$lang = $HTTP_ACCEPT_LANGUAGE[0];
				
				if(!is_dir(self::PathToFile("lang/{$lang}/"))){ 
					$lang = $this->conf->lang_lc;
				}
			}
			
			if(mb_strpos($lang, '_') !== false){			
				$lang = explode('_',$lang);
				$lang = $lang[0];
			}
			
			$lang_dir = scandir(self::PathToFile("lang/{$lang}/"));
			
			foreach($lang_dir as $file){
				
				if($file == '.' OR $file == '..'){ continue; }
				
				if(is_readable(self::PathToFile("lang/{$lang}/{$file}"))){
					include_once self::PathToFile("lang/{$lang}/{$file}");
				}
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Get($key,$filter=false){
			
			$request = !empty($_REQUEST[$key]) ? $_REQUEST[$key] : false;
			
			if($filter){ 
				$request = preg_replace('/[^ a-zа-яё\@\.\-\_\:\:\;\/\,\!\№\$\%\^\&\*\(\)\+\=\?\d]/ui', '',$request); 
			}
			
			return $request;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Short($key){
			
			$key = preg_replace('/[^a-zа-яё]/ui', '',$key); 			
			return $key;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function BackURL($back){
			
			if(isset($_SERVER['HTTP_REFERER']) AND !isset($_REQUEST[$back])){
				$url = parse_url($_SERVER['HTTP_REFERER']);
				header('location:/'.$this->uri.'?'.$back.'='.$url['path']); 
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function GoBack($back){
			$back = isset($_REQUEST[$back]) ? $_REQUEST[$back] : '/'; 
			header('location:'.$back);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Go($module, $action=false, $params=false){
			
			$module = '/'.$module;
			$action = !empty($action) ? '/'.$action : '';
			$params = !empty($params) ? '?'.$params : '';
			
			if($params){
				$params = urldecode(http_build_query($params));
			}
			
			header('location:'.$module.$action.$params);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function isAjax(){
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
				return true;
			}
			return false;
		}
		
		public function renderJSON($data=array(), $with_header=false){

			if(ob_get_length()) { ob_end_clean(); }

			if ($with_header) {
				header('Content-type: application/json; charset=utf-8');
			}

			print json_encode($data);
			
			die;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	}