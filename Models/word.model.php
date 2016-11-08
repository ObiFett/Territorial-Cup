<?php
include_once("./Tools/database.class.php");

class WordModel {

    private $gamedb;

    public function __construct(){
        $this->gamedb = new database();
    }

    public function used($word){
        $stmt = "SELECT * FROM usedWord";
        $stmt .= " WHERE used_g_ID=" . $_SESSION['joined_game'];
        $stmt .= " AND used_d_word='"  . $word . "'";
        return $this->gamedb->query($stmt);
    }

    public function getWords($limit = null){
        $stmt = "SELECT * FROM usedWord";
        $stmt .= " WHERE used_g_ID=" . $_SESSION['joined_game'];
        $stmt .= " ORDER BY used_time DESC";
        if(!is_null($limit))
            $stmt .= " LIMIT " . $limit;
        return $this->gamedb->query($stmt);
    }

    public function insert($word){
        $data = array($_SESSION['joined_game'], $word, date("Y-m-d H:i:s"));
        $cols = array('used_g_ID', 'used_d_word', 'used_time');
        $stmt_parts = $this->gamedb->prepareInsert($cols);
        $stmt = "INSERT INTO usedWord (" . $stmt_parts[0] . ")
                    VALUES (" . $stmt_parts[1] . ")";
        return $this->gamedb->query($stmt, $data);
    }

}

?>