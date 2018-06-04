<?php

	class Auth{
		
		public $core,$temp;
		
		public function __construct(){
			
			$this->core = Core::I();
			$this->temp = Temp::I();
		}

		public function Index(){
			
			if(!$this->core->user->logged){
				
				$email 		= htmlspecialchars(Core::Get('email',true));
				$password 	= Core::Get('password');
				$member 	= htmlspecialchars(Core::Get('member',true));
				$errors 	= array();
				
				if(!empty($email) AND !empty($password)){
					
					$user = $this->core->base->GetRow('users',"`email`='{$email}'");
					
					if(strtoupper($user['email']) == strtoupper($email) AND $user['password'] == User::Hash($password)){
						$auth = $this->core->user->Auth($user,$member);
					}else{
						$errors['user'] = USER.' '.NOT_FOUND;
					}
				
					if(!empty($auth)){
						Core::GoBack('back');
					}
				}
				
				$this->temp->RenderAction('auth', 
					array(
						'email'		=>	htmlspecialchars($email),
						'password'	=>	$password,
						'member'	=>	htmlspecialchars($member),
						'errors'	=>	$errors,
						'url'		=>	$this->core->url,
					));
			}else{
				Core::GoBack('back');
			}
		}
		//!@#$%^&*()_+=-{}[]:;"'?/>.<,|\ 
		public function Reg(){

			if(!$this->core->user->logged){

				$email 		= htmlspecialchars(Core::Get('email',true));
				$password 	= Core::Get('password');
				$f_name 	= htmlspecialchars(Core::Get('f_name',true));
				$l_name 	= htmlspecialchars(Core::Get('l_name',true));
				$login 		= strstr(htmlspecialchars($email),'@',true);
				$errors 	= array();

				if($_POST){
					if(!$email){ 	$errors['email'] 	= FIELD .' '. EMAIL 	.' '. MUST_BE; }
					if(!$password){ $errors['password'] = FIELD .' '. PASSWORD 	.' '. MUST_BE; }
					if(!$f_name){ 	$errors['f_name'] 	= FIELD .' '. F_NAME 	.' '. MUST_BE; }
					if(!$l_name){ 	$errors['l_name'] 	= FIELD .' '. L_NAME 	.' '. MUST_BE; }
					
					if(strlen($password) < 6){ 
						$errors['pass_small'] = PASS_SMALL; 
					}
					if(!$login){ 
						$errors['pass_small'] = NOT_COR_MAIL; 
					}
				}

				if(!empty($email) AND !empty($password) AND !$errors){

					$data = array(
						'email'		=> $email,
						'login'		=> $login,
						'password'	=> User::Hash($password),
						'f_name'	=> $f_name,
						'l_name'	=> $l_name,
						'date_log'	=> time(),
						'date_reg'	=> time(),
					);

					$user = $this->core->base->Insert('users',$data); 

					if($user){
						$user = $this->core->base->GetRow('users',"`email`='{$email}'");
						$auth = $this->core->user->Auth($user,true);
					}else{
						$errors['user'] = EMAIL .' '. IS_ZANIAT;
					}

					if(!empty($auth)){
						Core::GoBack('back');
					}
				}

				$this->temp->RenderAction('reg', 
					array(
						'email'		=> htmlspecialchars($email),
						'password'	=> $password,
						'f_name'	=> htmlspecialchars($f_name),
						'l_name'	=> htmlspecialchars($l_name),
						'errors'	=> $errors,
						'url'		=> $this->core->url,
					));
			}else{
				Core::GoBack('back');
			}
		}
		
		public function LogOut(){
			
			if($this->core->user->logged){
				$this->core->user->SessUPD('logged',false);
			}
			
			Core::GoBack('back');
		}
		
		public function Fast(){

			if(!$this->core->user->logged){
				$this->core->user->SessUPD('logged',true);
			}
			
			Core::GoBack('back');
		}
		
/*		
		public function AllSess(){
			
			$dirs = pathinfo($this->core->user->sess_file)['dirname'];
			
			foreach(scandir($dirs) as $dir){
				
				if($dir == '.' OR $dir == '..'){
					
					print
				}
			}
		}
*/		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}