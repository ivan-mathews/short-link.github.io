<?php

	define('TIMER', microtime(true));
	define('ROOT',	dirname(__FILE__));
	
	error_reporting(E_ALL); 
	
	require ROOT ."/loader.php";
	
	$core = Core::I();
	$temp = Temp::I();
	
	$core->Router($_SERVER['REQUEST_URI']);
	
	$temp->RenderPage('sys.temp.php');
	
	