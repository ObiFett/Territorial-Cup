<?php

class database {

   private $serverName;
   private $userName;
   private $userPassword;
   private $dbName;
   private $conn;
   private $connectionInfo;

   public function __construct(){
       $this->serverName = "tcp:kfds4bk5z8.database.windows.net,1433";
       $this->userName = "territorialcup@kfds4bk5z8";
       $this->userPassword = "ASUCapstone20!#";
       $this->dbName = "territoAkC7GA4EG";

       $this->connectionInfo = array(
           "Database"=>$this->dbName,
           "UID"=>$this->userName,
           "PWD"=>$this->userPassword,
           "MultipleActiveResultSets"=>true);
   }

   private function openConn(){
       sqlsrv_configure('WarningsReturnAsErrors', 0);
       $this->conn = sqlsrv_connect($this->serverName, $this->connectionInfo);
       if(!$this->conn){
           FatalError("Failed to connect...");
       }
   }

   public function query($stmt, $values = null){
       $query_type = explode(" ", $stmt);
       if($query_type[0] != "SELECT"){
           $stmt .= "; SELECT SCOPE_IDENTITY() AS IDENTITY_COLUMN_NAME";
       }

       $this->openConn();
       if(is_null($values))
           $results = sqlsrv_query($this->conn, $stmt);
       else
           $results = sqlsrv_query($this->conn, $stmt, $values);

       $results_array = array();

       if($query_type[0] != "SELECT"){
           sqlsrv_next_result($results);
           $results_array[0] = sqlsrv_fetch_array($results);
       } else {
           $i = 0;
           while($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)){
               $results_array[$i] = $row;
               $i++;
           }
       }
       $this->closeConn();

       return $results_array;
   }

   public function closeConn(){
       sqlsrv_close($this->conn);
   }

   public function prepareInsert($cols){
       $return_array = array();
       $stmt = "";
       $var_vals = "";
       $first = true;
        foreach ($cols as $col_name){
            if($first) {
                $stmt .= $col_name;
                $var_vals = "?";
                $first = false;
            } else {
                $stmt .= ", " . $col_name;
                $var_vals .= ",?";
            }
        }
        $return_array[0] = $stmt;
        $return_array[1] = $var_vals;

        return $return_array;
   }

}

?>
