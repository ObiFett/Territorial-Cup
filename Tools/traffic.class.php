<?php
class Traffic
{
    public function __construct(){
    }
    
    public function check_ajax(){

        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&      
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if(!IS_AJAX)
            return false;
        else {
            $pos = strpos($_SERVER['HTTP_REFERER'],getenv('HTTP_HOST'));
            if($pos===false)
                return false;
            else
                return true;
        }
        
    }
}
?>
