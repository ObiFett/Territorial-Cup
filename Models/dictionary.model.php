<?php
include_once("./Tools/database.class.php");

class DictionaryModel {

    private $gamedb;

    public function __construct(){
        $this->gamedb = new database();
    }

    public function validCheck($word){
        $query = "SELECT * FROM dictionary where d_word='" . $word . "'";
        $results_array = $this->gamedb->query($query);
        if(empty($results_array))
            return false;
        else
            return true;
    }
}
?>
