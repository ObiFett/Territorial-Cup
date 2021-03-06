<!DOCTYPE html>
<html>
    <head>
        <META charset="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width = device-width">
		<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
        <link href="<?php echo HOST; ?>/rsc/Lobbystyles.css" rel="stylesheet" type="text/css" />
        <title>Territorial Cup: Duel in the Desert - Game</title>
        <script src="<?php echo HOST; ?>/rsc/jquery-1.10.2.min.js"></script>
        <script src="<? echo HOST; ?>/rsc/game.js"></script>
    </head>

    <body>
        <div id="main">
            <img class="left" src="http://territorialcup.azurewebsites.net/rsc/ASU_Fork.jpg" />
			<img class="right" src="http://territorialcup.azurewebsites.net/rsc/Wildcat.gif" />

            <div id="formData">
                <div id="scores">
                    <?php
                    if($GameInfo['playerNum'] == 0){
                        echo    '<div id="player1" class="playerName ' . ($GameInfo['g_turn'] ? "" : "selectedPlayer") . '">'.
                                        $_SESSION['username'] .
                                    '<div class ="score">' . $GameInfo['g_score1'] . '</div>
                                </div>
                                <div id="player2" class="playerName ' . ($GameInfo['g_turn'] ? "selectedPlayer" : "") . '">'.
                                        (isset($GameInfo['oppName']) ? $GameInfo['oppName'] : "Waiting") .
                                    '<div class ="score">' . $GameInfo['g_score2'] . '</div>
                                </div>';
                    } else {
                        echo    '<div id="player1" class="playerName ' . ($GameInfo['g_turn'] ? "" : "selectedPlayer") . '">'.
                                        (isset($GameInfo['oppName']) ? $GameInfo['oppName'] : "Waiting") .
                                    '<div class ="score">' . $GameInfo['g_score1'] . '</div>
                                </div>
                                <div id="player2" class="playerName ' . ($GameInfo['g_turn'] ? "selectedPlayer" : "") . '">'.
                                        $_SESSION['username'] .
                                    '<div class ="score">' . $GameInfo['g_score2'] . '</div>
                                </div>';

                    }?>
                </div>
                <div id="errorMessage"></div>
                <div id="wordInfo">
                    <div id="wordField">
                        <form id="gameForm" action="./game/submitTurn" method="post">
                            <input id="wordBuilt" readonly="true" name="word" />
                            <input id="playerNum" type="hidden" name="number" value="<?php echo (($GameInfo['playerNum'] == 0) ? "red" : "blue"); ?>"/>
                            <input id="turn" type="hidden" name="cTurn" value="<?php echo (($GameInfo['g_turn'] == 0) ? "red" : "blue"); ?>"/>
                        </form>
                    </div>
                </div>
                <div id="gameBoard" gameID="<?php echo $gameID; ?>" status="<?php echo $GameInfo['g_active']; ?>">
                <?php
                $new_div = false;
                $end_tiles = array(78, 86, 90);
				$j = 0;
                for ($i = 0; $i < strlen($board_tiles); $i+=2){
                    if(!$new_div && $j == 0) {
                        echo '<div class="sectionContainer" style="width: 90%; ">';
                        $new_div = true;
                    }
					if(!$new_div && $j == 1) {
                        echo '<div class="sectionContainer" style="width: 90%; ">';
                        $new_div = true;
                        echo '<div class="sectionContainer" style="width: 20%; float:left; height:27px;"></div>';
                    }
					if(!$new_div && $j == 2) {
                        echo '<div class="sectionContainer" style="width: 90%; ">';
                        $new_div = true;
						echo '<div class="sectionContainer" style="width: 60%; float:left; height:27px;"> </div>';
                    }
                    echo '<div ';
                    echo 'id="tile' . $i/2 . '" ';
                    echo 'class="boardTile ';
                    $colorCode = intval($board_tiles[$i+1]);
                    $color = "noColor";
                    switch($colorCode) {
                        case 1:
                            $color = "red";
                            break;
                        case 2:
                            $color = "redLock";
                            break;
                        case 3:
                            $color = "blue";
                            break;
                        case 4:
                            $color = "blueLock";
                            break;
                        default:
                            break;
                    }
                    echo $color . '">';
                    echo $board_tiles[$i] . '</div>' . "\r\n";
                    if(in_array($i, $end_tiles) ){
                        echo "</div>";
                        $new_div = false;
						$j++;
                    }
                }?>       
                </div>

                <div id="buttonField">
					<a href="/" class="button">
						<span class="button-text">Back</span>
					</a>
                    <a href="javascript:;" id="clearWord" class="button" style="background:#CCCCCC">
                        <span class="button-text">Clear</span>
                    </a>
                    <a href="javascript:;" id="submitWord" class="button" style="background:#CCCCCC">
                        <span class="button-text">Submit</span>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
