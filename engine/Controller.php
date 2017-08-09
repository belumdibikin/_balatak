<?php
class Controller {
	
	public function __construct(){
	}	

	public function init(){
	}	

	function loadView($viewname = "home", $variables = array()){

		$_view = new View();
		$_view->assignArrayData($variables);
		return $_view->renderView("$viewname");
	}

	function loadModel($modelname = ""){
		$_model = NULL;
		if(file_exists("model/" . $modelname . ".php")){
            include_once("model/" . $modelname . ".php");
            $_model = new $modelname;
        }else{
            include_once("view/errorModel.php");
        }
        return $_model;
	}
}

