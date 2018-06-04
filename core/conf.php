<?

	class Conf{
		
		private static $i;
	
		private $ready   = false;
		private $data    = array();
		private $dynamic = array();
		
		public static function I() {
			if (self::$i === null) {
				self::$i = new self;
			}
			return self::$i;
		}
	
		public static function get($key){
			return self::I()->$key;
		}

		public function __construct($cfg_file = 'conf.php'){
			
			if($this->setData($cfg_file)){
				$this->ready = true;
			}

		}

		public function isReady(){
			return $this->ready;
		}

		public function set($key, $value){
			$this->data[$key] = $value;
			$this->dynamic[] = $key;
		}

		public function getAll(){
			return $this->data;
		}

		public function __get($name) {
			if (!isset($this->data[$name])){ return false; }
			return $this->data[$name];
		}

		public function __isset($name) {
			return isset($this->data[$name]);
		}

		public function setData($cfg_file) {

			$this->data = $this->load(Core::PathToFile('conf/' . $cfg_file));
			if(!$this->data){ return false; }

			return true;

		}
		
		public function load($cfg_file){

			if(!is_readable($cfg_file)){
				return false;
			}

			return include $cfg_file;

		}

		public function save($cfg_file, $values){

			$dump = "<?php\n\n" .
					"\t return array(\n\n";

			foreach($values as $key=>$value){

				if (in_array($key, $this->dynamic)){ continue; }
//				if ($key == 'HTTP_USER_AGENT'){ continue; }
//				if ($key == 'HTTP_COOKIE'){ continue; }

				$value = var_export($value, true);

				$tabs = 7 - ceil((mb_strlen($key)+3)/4);

				$dump .= "\t\t'{$key}'";
				$dump .= str_repeat("\t", $tabs);
				$dump .= "=> $value,\n";

			}

			$dump .= "\n\t);\n";

			$file = $cfg_file;
			$path_info = pathinfo($cfg_file);
			if(!is_dir($path_info['dirname'])){ mkdir($path_info['dirname'],0777,true); }

			$success = false;

			if(is_writable($file) OR !is_file($file)){
				$success = file_put_contents($file, $dump);
				if (function_exists('opcache_invalidate')) { @opcache_invalidate($file, true); }
			}

			return $success;

		}

		public function FileDir(){
			return $this->root.$this->upload;
		}
		
		public function update($key, $value, $cfg_file){

			$data = $this->load($cfg_file);
			$data[$key] = $value;

			return $this->save($cfg_file,$data);

		}
		
		
		
		
		
		
		
		
		
		

	}