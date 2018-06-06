<?php

	class Main{
		
		private $core,$temp;
		
		public function __construct(){
			
			$this->core = Core::I();
			$this->temp = Temp::I();
		}
		
		public function Index(){ //екшон формы ввода ссылки
			
			if(!$this->core->user->logged){ Core::Go('auth','index'); }
			
			$link 		= htmlspecialchars(Core::Get('link'));
			$name 		= htmlspecialchars(Core::Get('name'));
			$errors 	= array();
			
			if(!$link AND $_POST){ $errors['link'] = FIELD .' "'. LINK .'" '. MUST_BE; }
			
			if(!empty($link AND !$errors)){
			
				$time 	= number_format(microtime(true),4);
				$id 	= str_replace(array(',','.'),array('',''),$time);
				
				$data = array(
					'link'			=> $link,
					'uri'			=> Core::Short(crypt(uniqid().md5(time()), substr(uniqid().md5(time()), 0, 2))),
					'module'		=> 'Main',
					'action'		=> 'Get',
					'name'			=> $name,
					'user_id'		=> $this->core->user->id,
					'date_create'	=> time(),
					'date_follow'	=> time(),
				);
				
				$data = $this->core->base->Insert('aliases',$data,false,$id);
				
				if(!$data){
					$errors['user'] = ERROR .' '. LINK .' '. IS_SUSHES .' '. TRY_NOW;
				}else{
					Core::Go('main','links');
				}
			}
			
			$this->temp->RenderAction('index', 
				array(
					'link'		=>	$link,
					'name'		=>	$name,
					'errors'	=>	$errors,
					'url'		=>	$this->core->url,
				));
		}

		public function Run($id){ // статистика для владельца ссылки (/main/[id])
			
			if(!$this->core->user->logged){ Core::Go('auth','index'); }
			
			$admin = '';
			if(!$this->core->user->admin){ $admin = " AND `user_id` = {$this->core->user->id}"; }
			
			$data = $this->core->base->GetRows('stat',"`link_id`='{$id}' {$admin}",'*','id DESC');

			$this->temp->RenderAction('item',array('data'=>$data));
		}

		public function Links(){ //екшон список ссылок
			
			if(!$this->core->user->logged){ Core::Go('auth','index'); }
			
			$data = $this->core->base->GetRows('aliases',"`user_id`='{$this->core->user->id}'",'*','id DESC');
			
			$this->temp->RenderAction('links',array('data'=>$data));
		}

		public function Get($id){ //екшон редиректа
			
			$id = $this->core->item;
			
			if(empty($id)){ Core::Error404(); }
			
			$linx = $this->core->base->GetRow('aliases',"`id`='{$id}'");
			
			if(empty($linx)){ Core::Error404(); }
		
			if(!$this->core->IsAJAX()){
				
				$sid	= User::SessionIDCreate(256);
				$hash	= User::Hash($sid,true);
				
				$this->core->user->SessUpd('sess',array('sid'=>$sid,'hash'=>$hash));
				$this->core->user->SessUpd('date',time());
			}else{
				$sid	= $this->core->user->sess['sid'];
				$hash	= $this->core->user->sess['hash'];
			}
			
			if($this->core->IsAJAX()){
				
				$date 	= strtotime(date('Y-m-d'));
				$datas 	= $this->core->base->GetRow('stat',"`link_id`='{$id}' AND `date_follow`='{$date}'");

				if(!empty($linx) AND $linx['user_id'] != $this->core->user->id AND 
					$this->core->user->sess['sid'] 	== htmlspecialchars(Core::Get('sid')) AND 
					$this->core->user->sess['hash'] == htmlspecialchars(Core::Get('hash')) AND $linx['link'] AND 
					$this->core->user->date + $this->core->conf->TIME_OUT <= time()){
					
					$data = array(
						'link_id'		=> $id,
						'date_follow'	=> $date,
						'user_id'		=> $linx['user_id'],
						'link_uri'		=> $linx['uri'],
						'link_name'		=> $linx['name'],
						'link_link'		=> $linx['link'],
						'date'			=> time(),
						'count'			=> '1'
					);
		
					if(empty($datas)){
						$this->core->base->Insert('stat',$data,true);
					}else{
						$this->core->base->Update('stat',"`id`={$datas['id']} AND `date_follow`='{$date}'",
						array('count'=>$datas['count']+1,'user_id'=>$linx['user_id']),true);
					}
				}
				if($this->core->user->sess['sid'] 	== htmlspecialchars(Core::Get('sid')) AND 
					$this->core->user->sess['hash'] == htmlspecialchars(Core::Get('hash')) AND $linx['link']
					AND $this->core->user->date + $this->core->conf->TIME_OUT <= time()){
	
					return $this->core->RenderJSON(array('link'=>$linx['link']));
				}else{
					return $this->core->RenderJSON(array('error'=> ERROR .' '. REQUESTED .' URL '. NOT_FOUND));
				}
			}
			$this->temp->RenderAction('get',array('sid'=>$sid,'hash'=>$hash,'data'=>$linx,'url'=>$this->core->url));
		}
		
		public function Edit($id){ //екшон редактирования ссылки
			
			if(!$this->core->user->logged){ Core::Go('auth','index'); }
			
			$link 		= htmlspecialchars(Core::Get('link'));
			$name 		= htmlspecialchars(Core::Get('name'));
			$updated	= false;
			$errors 	= array();
			
			if(!$link AND $_POST){ $errors['link'] = FIELD .' "'. LINK .'" '. MUST_BE; }
			if(!$name AND $_POST){ $errors['name'] = FIELD .' "'. NAME .'" '. MUST_BE; }
			
			$id = $this->core->item;
			
			if(empty($id)){ Core::Error404(); }
			
			$admin = '';
			if(!$this->core->user->admin){ $admin = " AND `user_id` = {$this->core->user->id}"; }
			
			$linx = $this->core->base->GetRow('aliases',"`id`='{$id}' {$admin}");
			
			if(empty($linx)){ Core::Error404(); }
			
			if($linx['user_id'] == $this->core->user->id AND ($link AND $name) AND !$errors){

				$updated = $this->core->base->Update('aliases',"`id`='{$id}' {$admin}",array('link'=>$link,'name'=>$name),true);
			}
			
			if($updated){
				Core::Go('main','links');
			}
			
			$this->temp->RenderAction('edit',array(
				'link'=>$linx['link'],
				'name'=>$linx['name'],
				'url'=>$this->core->url,
				'errors'=>$errors
			));
		}
		
		public function Del($id){ //екшон удаления ссылки и статистики
			
			if(!$this->core->user->logged){ Core::Go('auth','index'); die;}
			
			$deleted	= false;
			
			$id = $this->core->item;
			
			if(empty($id)){ Core::Error404(); }
			
			$admin = '';
			if(!$this->core->user->admin){ $admin = " AND `user_id` = {$this->core->user->id}"; }
			
			$link = $this->core->base->GetRow('aliases',"`id`='{$id}' {$admin}");
			$data = $this->core->base->GetRows('stat',"`link_id`='{$id}' {$admin}");
			
			if(empty($link)){ Core::Error404(); }
			
			if($link['user_id'] == $this->core->user->id){

				$deleted = $this->core->base->delete('aliases',"`id`={$id}");
				
				if($data){

					$imp = false;
					foreach($data as $impl){
						$imp .= "'{$impl['id']}',";
					}

					$imp = trim($imp,',');
					$deleted = $this->core->base->delete('stat',"`id` IN ({$imp})");
				}
			}
			if($deleted){
				Core::Go('main','links');
			}
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
