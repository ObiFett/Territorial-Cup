<?php
include("./Models/game.model.php");
include("./Models/dictionary.model.php");
include("./Models/word.model.php");
include("GameFunctions.php");

class GameController {

    private $params;
    private $model;
    private $page;

    public function __construct($p){
        $this->page = new View();
        $this->params = $p;
        if(!isset($_SESSION['username']) && $p[2] != ""){
            $_SESSION['prev_URI'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . HOST . "/user/login");
            exit();
        } else {
            if(method_exists($this, $p[2]))
                $this->$p[2]();
            else
                $this->lobby();
        }
    }

	public function invite() {
		$this->page->render("./Game/invite.view.php");
	}
	
    public function random(){
        $this->model = new GameModel();
        //check to see if they have any open games, if so redirect
        $active_games = $this->model->activeGames($_SESSION['pl_id']);
        if($active_games[0]['gameCount'] > 2){
            header('Location: ' . HOST);
            exit();
        }
        //check for open games that they are not in
        $open_game = $this->model->findGame($_SESSION['pl_id']);
        if(!empty($open_game))
        {
            //if open game, assign to and load game
            $this->model->joinGame($open_game[0]['in_g_ID'],$_SESSION['pl_id']);
            $_SESSION['joined_game'] = $open_game[0]['in_g_ID'];
            header('Location: ' . HOST . "/game/viewGame");
            exit();
        }
        else
        {
            //if no open game, create a game
            $letter_bag = $this->letterBag();
            $board_string = "";
            for($i = 0; $i < 46; $i++){
                $randNum = rand(0, count($letter_bag)-1);
                $board_string .= $letter_bag[$randNum] . "0";
                unset($letter_bag[$randNum]);
                $letter_bag = array_values($letter_bag);
            }
            $cols = array(  'g_tilestring',
                            'g_active',
                            'g_created',
                            'g_turn',
                            'g_score1',
                            'g_score2');
            $data = array(  $board_string,
                            1, date("Y-m-d H:i:s"), 0, 0, 0);
            $results_array = $this->model->insert($cols, $data);
            $_SESSION['joined_game'] = $results_array[0][0];
            $this->viewGame();
        }
    }

    public function viewGame(){
        if(isset($_SESSION['joined_game'])){
            $gameid = $_SESSION['joined_game'];
            $this->model = new GameModel();
            $game_info_array = $this->model->getFullGame("single", $gameid);
            $this->page->vars['GameInfo'] = $game_info_array[0];
            $this->page->vars['gameID'] = $game_info_array[0]['g_ID'];
            $this->page->vars['board_tiles'] = $game_info_array[0]['g_tileString'];

            $this->model = new WordModel();
            $this->page->vars['word_list'] = $this->model->getWords();

            $this->page->render("./Game/game.view.php");
        } else {
            header('Location: ' . HOST . "");
            exit();
        }
    }

    public function newgame(){
        $this->model = new GameModel();
        $this->page->vars['games'] = $this->model->getFullGame(true);
        
        $active_games = $this->model->activeGames($_SESSION['pl_id']);
        $this->page->vars['active_games'] = $active_games[0]['gameCount'];

		$this->page->render("./Game/newgame.view.php");
    }

    public function lobby(){
        if(isset($_SESSION['username'])){
            $this->model = new GameModel();
            $this->page->vars['games'] = $this->model->getFullGame(false);
            //print_r($this->page->vars['games']);
        }
        $this->page->render("./index.view.php");
    }

    /* DEBUG FUNCTION
     * clears game, inGame, and usedWord tables
     * must enable resetGames() in game.model as well
    public function resetDB(){
        $this->model = new GameModel();
        $this->model->resetGames();
        $this->page->render("./index.view.php");
    }*/

    /***AJAX***/

    public function submit(){
        $error = false;
        $return_info = array("status"=> "success");
        //$return_info["board_string"] = $_POST["board_string"];
        $return_info['game_state'] = "ongoing";

        //get game information
        $this->model = new GameModel();
        $game_info = $this->model->getFullGame(false, $_SESSION["joined_game"]);

        //check if its their game
        if(empty($game_info)){
            $return_info["status"] = "error";
            $return_info["message"] = "Don't hack other games";
            $error = true;
        }

        //check the game is active
        if(!$game_info[0]['g_active'] && !$error){
            $return_info["status"] = "error";
            $return_info["message"] = "Game Over!";
            $error = true;
        }

        //check if its their turn
        if(($game_info[0]['playerNum'] != $game_info[0]['g_turn']) && !$error){
            $return_info["status"] = "error";
            $return_info["message"] = "Wait your turn!";
            $error = true;
        }

        //DEPRECATED: No longer passes string from client to server
        //Ensure no tampering on client side
        /*if(!$error){
            $board_string = $_POST['board_string'];
            $game_info_array = $this->model->getGame($_POST["g_ID"]);
        }*/

        $word = "";
        if(isset($_POST['word_code']) && !$error){
            $word_code = $_POST['word_code'];
            if($word_code == ""){
                $return_info["status"] = "error";
                $return_info["message"] = "Please enter a word.";
                $error = true;
            } else {
                $letter_place = explode("|", $_POST['word_code']);
                $word = "";
                foreach($letter_place as $num){
                    if($num != "")
                        $word .= $game_info[0]["g_tileString"][intval($num)*2];
                }
                //check if word in dictionary
                $this->model = new DictionaryModel();
                $valid_word = $this->model->validCheck($word);
                if(!$valid_word && !$error){
                    $return_info["status"] = "error";
                    $return_info["message"] = "Not a Word!";
                    $error = true;
                }
                if(!$error){
                    $this->model = new WordModel();
                    $used_word_check = $this->model->used($word);
                    if(!empty($used_word_check)){
                        $return_info["status"] = "error";
                        $return_info["message"] = "Word already used";
                        $error = true;
                    }
                }
            }
        }

        //if a valid turn update the board
        if(!$error){
            //TODO: Insert Word into Word Table
            $this->model->insert($word);

            //update the boardstring
            $return_info['board_string'] = $this->transformBoard($game_info[0], $_POST['word_code']);

            //check for endgame condition and new scores
            $game_over = true;
            $length = strlen($return_info['board_string']);
            $return_info["score1"] = 0;
            $return_info["score2"] = 0;
            for($i = 1; $i < $length ; $i += 2){
                $tileOwner = intval($return_info['board_string'][$i]);
                if (!$tileOwner)
                    $game_over = false;
                else if($tileOwner == 1 || $tileOwner == 2)
                    $return_info["score1"] += 1;
                else
                    $return_info["score2"] += 1;
            }
            //update game state
            $this->updateGameState($return_info, $game_info[0], $game_over);
            if($game_over){
                $return_info["game_state"] = "finished";
            }
        }
        echo json_encode($return_info);
    }

    public function updateCheck(){
        $results['status'] = "error";

        $this->model = new GameModel();
        $full_game_info = $this->model->getFullGame(false, $_POST['g_ID']);
        if($full_game_info[0]['g_turn'] == $full_game_info[0]['playerNum']){
            $results['status'] = "update";
            $results["info"] = $full_game_info[0];
        } else {
            $results['status'] = "No Update";
        }
        echo  json_encode($results);
    }

    public function setGame(){
        $result = "not a valid game";
        if(isset($_POST['game_ID'])){
            //check if its their game
            $this->model = new GameModel();
            $full_game_info = $this->model->getFullGame(false, $_POST['game_ID']);
            if(!empty($full_game_info)){
                $_SESSION['joined_game'] = $_POST['game_ID'];
                $result = "good request";
            }
        }
        echo json_encode($result);
    }



    /***UTILITY***/
    private function transformBoard($game_info, $word_code){
        $letter_place = explode("|", $word_code);
        $board_string = $game_info['g_tileString'];
        $turn = $game_info['g_turn'];

        foreach($letter_place as $num){
            $pos = (intval($num)*2)+1;
            //if not locked, change color to active player
            $oldColorCode = intval($board_string[$pos]);
            if($turn == 0){
                if($oldColorCode == 3 || $oldColorCode == 0)
                    $board_string = $this->changeColor($board_string, $pos, 1);
            } else {
                if($oldColorCode == 1 || $oldColorCode == 0)
                    $board_string = $this->changeColor($board_string, $pos, 3);
            }
            //if not already locked, check if now locked
            if(!in_array($oldColorCode, array(2, 4)))
                $board_string = $this->checkLocks($board_string, $pos, $turn);
        }
        return $board_string;
    }

    private function changeColor($board_string, $pos, $new_code){
        $board_string[intval($pos)] = $new_code;
        return $board_string;
    }

    private function checkLocks($board_string, $pos, $turn, $rec = true){
        $turn_colors = array(1, 2);
        if($turn)
            $turn_colors = array(3, 4);

        $tile = intval($pos/2);

        $locked = true;

        $surr_tiles = array();
        $surr_tiles['bottom'] = -1;
        $surr_tiles['top'] = -1;
        $surr_tiles['left'] = -1;
        $surr_tiles['right'] = -1;

        //look at tile above
        if($tile > 4){
            $surr_tiles['top'] = $pos-5*2;
            if($tile > 39){
                if($tile > 43)
                    $surr_tiles['top'] = $pos-2*2;
                else
                    $surr_tiles['top'] = $pos-4*2;
            }
        }
        
        //look at tile below
        $exceptions = array(35, 40, 41);
        if($tile < 44 && !in_array($tile, $exceptions)) {
            $surr_tiles['bottom'] = $pos+5*2;
            if($tile > 34){
                if($tile < 40)
                    $surr_tiles['bottom'] = $pos+4*2;
                else
                    $surr_tiles['bottom'] = $pos+2*2;
            }
        }

        //look at tile to the left
        if(($tile%5 != 0 && $tile != 44) || ($tile == 45)) {
            $surr_tiles['left'] = $pos-1*2;
        }

        //look at tile to the right
        if(($tile%5 != 4 && $tile != 43 && $tile != 45) || ($tile == 44)) {
            $surr_tiles['right'] = $pos+1*2;
        }

        foreach ($surr_tiles as $value){
            if($value != -1 && in_array($board_string[$value], $turn_colors)){
                if($rec)
                    $board_string = $this->checkLocks($board_string, $value, $turn, false);
            } else if($value != -1){
                $locked = false;
            }
        }

        if($locked){
            $board_string = $this->changeColor($board_string, $pos, $turn_colors[1]);
        }
        
        return $board_string;
    }

    private function updateGameState($return_info, $game_info, $over){
        $turn = ($game_info['g_turn'] ? 0 : 1);

        $this->model = new GameModel();
        $gameInfo = array(
            "g_tilestring" => $return_info['board_string'],
            "g_turn" => $turn);
        $gameInfo["g_score1"] = $return_info["score1"];
        $gameInfo["g_score2"] = $return_info["score2"];
        if($over)
            $gameInfo["g_active"] = 0;
        $this->model->update($_SESSION['joined_game'], $gameInfo);
    }

    private function letterBag(){
        $letterBag = array();
        $letterCounts = array(
            "e" => 12, "a" => 9, "i" => 9, "o" => 8,
            "n" => 6, "r" => 6, "t" => 6, "l" => 4,
            "s" => 4, "u" => 4, "d" => 4, "g" => 3,
            "b" => 2, "c" => 2, "m" => 2, "p" => 2,
            "f" => 2, "h" => 2, "v" => 2, "w" => 2,
            "y" => 2, "k" => 1, "j" => 1, "x" => 1,
            "q" => 1, "z" => 1);
        foreach ($letterCounts as $key => $value){
            for($i = $value; $i > 0; $i--){
                array_push($letterBag, $key);
            }
        }
        return $letterBag;
    }
}
?>
