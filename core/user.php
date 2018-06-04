<?php

	class User{
		
		private static $i, $keyword;
		private $user, $dirs;
		public $conf, $sess_file;
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function I(){
			if(self::$i === null){
				self::$i = new self;
			}
			return self::$i;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function __get($name) {
			if (!isset($this->user[$name])){ return false; }
			return $this->user[$name];
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function __construct(){
			
			$this->conf = Conf::I();
			
			if(!empty($this->conf->SESS_DIR)){
				$this->dirs = "../".$this->conf->SESS_DIR_NAME."/";
			}else{
				$this->dirs = Core::PathToFile($this->conf->SESS_DIR_NAME."/");
			}
			
			self::$keyword = $this->conf->KEY_WORD;
			
			$user = $this->GetSessFile(self::Cookie('ID'),self::Cookie('SESS'));
			
			if($user){	$this->user = $user; }else{ $this->SessionStart(); }

			if($this->logged AND !$this->guest AND !$user['deleted']){
				
				if($this->date_log+$this->conf->ONLINE_TIME < time()){

					$this->SessUpd('date_log',''.time().'');
					MBase::I()->update('users',"`id`='{$this->id}'",array('date_log'=>time()),true);
				}
			}

		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function CreateSessionDir($id){
			
			$id = self::Hash($id);
			
			if(!is_dir($this->dirs.$id)){
				
				mkdir($this->dirs.$id,0644,true);
			}
			
			return $id;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function Auth($data=array(),$member=false){
			
			if(self::Cookie('MEMBER')){
				self::UnsetCookie('MEMBER',$this->conf->sess_time);
			}
			
			$sid	= self::SessionIDCreate(256);
			$hash	= self::Hash($sid,true);
			
			$value 		= self::SessionIDCreate(1024);
			$file_name 	= self::Hash(base64_decode($value),true);
			$SESS_TIME 	= 0;
			
			if(!empty($member)){
				
				$SESS_TIME 	= $this->conf->sess_time;
				self::SetCookie('MEMBER','1',$SESS_TIME);
				$file_name 	= 'MEMBER_'.$file_name;
			}
			
			$pass = self::Hash($data['password'],true);
			
			self::SetCookie('ID',	$data['id'],$SESS_TIME);
			self::SetCookie('SESS',	$value,		$SESS_TIME);
			self::SetCookie('SEC',	$pass,		$SESS_TIME);
			
			$dir = $this->CreateSessionDir($data['id']);
			$this->conf->save($this->dirs."{$dir}/{$file_name}.php",array_merge($data,array('date'=>time(),'sess'=>array('sid'=>$sid,'hash'=>$hash),'logged'=>true,'qki'=>$value),$_SERVER));
			
			return true;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function GetSessFile($id,$sess){
			
			$sess = self::Hash(base64_decode($sess),true); // true - user_aget must be stable
			
			if(self::Cookie('MEMBER') == '1'){
				$sess = 'MEMBER_'.$sess;
			}
			if(self::Cookie('ID') == '0'){
				$sess = 'GID_'.$sess;
			}
			
			$id		= self::Hash($id);
			$dir	= $this->dirs."{$id}/";
			$this->sess_file = $this->dirs."{$id}/{$sess}.php";

			if(is_dir($dir)){
				if(is_readable($this->sess_file)){
					return $this->conf->load($this->sess_file);
				}
			}
			
			return false;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function SessionStart(){
			
			$sid	= self::SessionIDCreate(256);
			$hash	= self::Hash($sid,true);
			
			self::UnsetCookie('MEMBER',	$this->conf->sess_time);
			self::UnsetCookie('SEC',	$this->conf->sess_time);
			
			$id		= self::Hash('0');
			$value 	= self::SessionIDCreate(1024);
			$file_name = self::Hash(base64_decode($value),true);
			
			self::SetCookie('ID',	'0',	$this->conf->sess_time);
			self::SetCookie('SESS',	$value,	$this->conf->sess_time);
			
			$this->conf->save($this->dirs."{$id}/GID_{$file_name}.php",array_merge(array('date'=>time(),'sess'=>array('sid'=>$sid,'hash'=>$hash),'guest'=>true,'qki'=>$value),$_SERVER));
			
			header('location:'.$_SERVER['REQUEST_URI']);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Hash($hash,$ua=false){
			
			$secret = self::$keyword;
			$ua = $ua ? $_SERVER['HTTP_USER_AGENT'] : null;
			
			return md5(md5($hash).sha1($ua).md5($secret).sha1($hash.$ua.$secret));
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function Cookie($cookie){
			if(isset($_COOKIE[$cookie])){
				return $_COOKIE[$cookie];
			}else{
				return false;
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function SetCookie($key, $value, $time=false, $path='/', $http_only=true, $domain = null){
			$time = !$time ? 0 : time()+$time; 
			setcookie($key, $value, $time, $path, $domain, false, $http_only);
			return;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function UnsetCookie($key, $time){
			setcookie($key, '', time()-$time, '/');
			return;
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public static function SessionIDCreate($lenght){
			
			$array = array(
				'0','1','2','3','4','5','6','7','8','9','q','w','e','r','t','y','u','i','o',
				'p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m'
			);
			
			$keys = array_keys($array);
			$cookie = '';
			
			for($q=0;$q<$lenght;$q++){
				$cookie .= $array[rand(min($keys),max($keys))];
			}
			
			return strtoupper($cookie);
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		public function SessUpd($field,$value){
			
			if(is_readable($this->sess_file)){
				
				$this->conf->update($field,$value,$this->sess_file);
				return true;
			}
		}
/*----------------------------------------------------------------------------------------------------------------------*/
		
		
		
		
		
		
		
	}
	
	
	
	
	