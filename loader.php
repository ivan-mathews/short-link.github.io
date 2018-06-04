<?php

	function __autoload($class){
		
		$path = ROOT ."/core/{$class}.php";
		
		if(is_readable($path)){
			include $path;
		}else{
			Core::Error404();
		}
	}
	