<?php
include_once("./Tools/database.class.php");

class UserModel {

    private $gamedb;

    public function __construct(){
        $this->gamedb = new database();
    }

    public function get($username, $password){
        $stmt = "SELECT * FROM player WHERE ";
        $stmt .= "pl_uname='" . $username . "' AND ";
        $stmt .= "pl_pword='" . $password . "'";
        return $this->gamedb->query($stmt);
    }
    
    public function checkavail($username, $email){
        $stmt = "SELECT * FROM player WHERE ";
        $stmt .= "pl_uname='" . $username . "' OR ";
        $stmt .= "pl_email='" . $email . "'";
        return $this->gamedb->query($stmt);
    }

    public function insert($cols, $data){
        $stmt_parts = $this->gamedb->prepareInsert($cols);
        $stmt = "INSERT INTO player (" . $stmt_parts[0] . ")
                    VALUES (" . $stmt_parts[1] . ")";
        return $this->gamedb->query($stmt, $data);
    }

    public function update($username, $update_info){
        $stmt = "UPDATE player SET ";
        $first = true;
        foreach($update_info as $key => $value){
            if($first)
                $stmt .= $key . "='" . $value . "' ";
            else
                $stmt .= ", " . $key . "='" . $value . "' ";
            $first = false;
        }
        $stmt .= "WHERE player.pl_uname=" . $username;
        return $this->gamedb->query($stmt);
    }
}

?>
