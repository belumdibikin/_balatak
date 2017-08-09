<?php
class View {

    protected $data = array();

    public function renderView($viewname) {
        ob_start();
        if(file_exists("view/" . $viewname . ".php")){
            require "view/" . $viewname . ".php";
        }else{
            require "view/error404.php";
        }
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    function assign($key, $val) {
        $this->data[$key] = $val;
    }

    function assignArrayData($arrayData) {
        $this->data = array_merge($this->data, $arrayData);
    }
}