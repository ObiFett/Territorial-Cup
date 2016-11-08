<?php
class View
{

    public $vars;

    public function __construct(){
        $vars = array();
    }
    
    public function render($templateFile){

        extract($this->vars);
        ob_start();
        require($templateFile);
        echo ob_get_clean();
    }
    
    public function renderAjax($templateFile) {
        extract($this->vars);
        ob_start();
        require($templateFile);
        return ob_get_clean();
    }
}
?>
