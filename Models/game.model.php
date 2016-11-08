<?php
include_once("./Tools/database.class.php");

class GameModel {

    private $gamedb;

    public function __construct(){
        $this->gamedb = new database();
    }

    public function getGame($gameID){
        $stmt = "SELECT * FROM game WHERE g_ID=" . $gameID;
        return $this->gamedb->query($stmt);
    }

    public function getFullGame($active, $gameID = null){
        $stmt = "SELECT game.*, A.slot AS playerNum, O.pl_uname AS oppName";
        if(is_null($gameID))
            $stmt .= ", U.used_d_word AS LastWord";
        $stmt .=" FROM game
                 LEFT JOIN inGame A
                    ON A.in_g_ID=game.g_ID
                 LEFT JOIN inGame B
                    ON B.in_g_ID=game.g_ID AND A.in_pl_ID <> B.in_pl_ID
                 LEFT JOIN player O
                    ON B.in_pl_ID=O.pl_ID";
        if(is_null($gameID))
            $stmt .= " LEFT JOIN
                        (SELECT X.used_g_ID, X.used_d_word FROM usedWord X 
                            JOIN (SELECT used_g_ID, MAX(used_time) AS newest
                                FROM usedWord
                                GROUP BY used_g_ID) Y
                            ON X.used_g_ID=Y.used_g_ID AND X.used_time=Y.newest
                        WHERE Y.newest IS NOT NULL) U
                        ON game.g_ID=U.used_g_ID";
        $stmt .= " WHERE A.in_pl_ID=" . $_SESSION["pl_id"];
        if(!is_null($gameID))
            $stmt .= " AND game.g_ID=" . $gameID;
        else if(gettype($active) == "boolean"){
            if($active)
                $stmt .= " AND game.g_active=1";
            else
                $stmt .= " ORDER BY game.g_active DESC, game.g_created DESC";
        }
        return $this->gamedb->query($stmt);
    }

    public function debug(){
        $stmt = "SELECT * from usedWord";
        return $this->gamedb->query($stmt);
    }

    public function getCurrentTurn($gameID){
        $stmt = "SELECT * FROM game
                JOIN inGame
                    ON (game.g_ID=inGame.in_g_ID) 
                WHERE game.g_turn=inGame.slot AND game.g_ID=" . $gameID;
        return $this->gamedb->query($stmt);
    }

    /* DEPRECATED - Used to find only games with more than one player
     * public function getUserGames($pl_id){
            $stmt = "SELECT * FROM inGame
                JOIN (SELECT game.g_ID, game.g_score1, game.g_score2,
                                game.g_active FROM inGame
                    JOIN game
                        ON (inGame.in_g_ID = game.g_ID)
                    WHERE inGame.in_pl_ID=" . $pl_id . ") games
                ON games.g_ID = inGame.in_g_ID
                JOIN player
                    ON (inGame.in_pl_ID = player.pl_ID)
                WHERE inGame.in_pl_ID <> " . $pl_id;
        return $this->gamedb->query($stmt);
    }*/

    public function insert($cols, $data, $pl_id = null){
        $stmt_parts = $this->gamedb->prepareInsert($cols);
        $stmt = "INSERT INTO game (" . $stmt_parts[0] . ")
                    VALUES (" . $stmt_parts[1] . ")";
        $results = $this->gamedb->query($stmt, $data);
        $game_id = $results[0][0];
        $stmt = "INSERT INTO inGame (in_pl_ID, in_g_ID, slot) VALUES " .
                "(" . $_SESSION["pl_id"] . ", " . $game_id . ", 0)";
        $this->gamedb->query($stmt);
        if(!is_null($pl_id)){
            $stmt = "INSERT INTO inGame (in_pl_ID, in_g_ID, slot) VALUES" .
                "(" . $pl_id . ", " . $game_id . ", 1)";
            $this->gamedb->query($stmt);
        }
        //create relation
        return $results;
    }

    public function update($gameID, $game_info){
        $stmt = "UPDATE game SET ";
        $first = true;
        foreach($game_info as $key => $value){
            if($first)
                $stmt .= $key . "='" . $value . "' ";
            else
                $stmt .= ", " . $key . "='" . $value . "' ";
            $first = false;
        }
        $stmt .= "WHERE game.g_ID=" . $gameID;
        return $this->gamedb->query($stmt);
    }

    public function findGame($playerID){
        $stmt = "SELECT TOP 1 in_g_ID FROM inGame G " . 
                " LEFT JOIN game A ON A.g_ID=G.in_g_ID " .  
                " WHERE (SELECT count(in_g_ID) FROM inGame H WHERE " .
                        " G.in_g_ID = H.in_g_ID) < 2 " .
                " AND A.g_active = 1 AND in_pl_ID <> " . $playerID;
        return $this->gamedb->query($stmt);
    }

    public function joinGame($gameID, $playerID){
        $stmt = "INSERT INTO inGame(in_g_ID, in_pl_ID, slot) VALUES " .
            "(" . $gameID . ", " . $playerID . ", 1)";
        return $this->gamedb->query($stmt);
    }

    public function activeGames($playerID){
        $stmt = "SELECT count(g_ID) AS gameCount FROM game
                 JOIN inGame
                    ON in_g_ID = g_ID
                WHERE in_pl_ID=". $playerID . " AND g_active=1";
        return $this->gamedb->query($stmt);
    }

    //DEBUG Function to clear game related tables
    public function resetGames(){
        $stmt = "DELETE FROM games";
        $this->gamedb->query($stmt);
        $stmt2 = "DELETE FROM inGame";
        $this->gamedb->query($stmt2);
        $stmt3 = "DELETE FROM usedWord";
        $this->gamedb->query($stmt3);
    }

}

?>
