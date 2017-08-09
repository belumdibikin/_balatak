<?php
	ini_set('error_reporting', E_ALL);
	require_once("engine/Database.php");
	require_once("engine/Controller.php");
	require_once("engine/View.php");

	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$explode_ = explode("?",$actual_link);
	DEFINE("BASE_URL", $explode_[0]."?");
	$_COOKIE['BASE_URL'] = BASE_URL; 
	if(count($explode_) > 1){
		$_parser = $explode_[1];
		$_uri = explode("/", $_parser);	
	}else{
		$_uri = array("");
	}

	if(file_exists("controller/".$_uri[0].".php")){
		require_once("controller/".$_uri[0].".php");
		$_controller = new $_uri[0];
		if(count($_uri) > 1){
			$arrParam = array();
			for($_count = 2; $_count < count($_uri); $_count++){
				array_push($arrParam, $_uri[$_count]);
			}
			call_user_func_array(array($_controller, $_uri[1]), $arrParam);
		}else{
			$_controller->init();
		}
	}else{
		require_once("controller/Game.php");
		$_controller = new Game();
		$_controller->init();
	}
?>