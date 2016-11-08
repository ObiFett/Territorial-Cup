<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width = device-width">
		<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
		<title>Territorial Cup: Duel in the Desert - Lobby</title>
		<link href="http://territorialcup.azurewebsites.net/rsc/LobbyStyles.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo HOST; ?>/rsc/jquery-1.10.2.min.js"></script>
        <script src="<?php echo HOST; ?>/rsc/lobby.js"></script>
    </head>

    <body>
        <div id="main">
            <img class="centered" src="http://territorialcup.azurewebsites.net/rsc/Logo.png" />
			<div id="text" class="bluecentered">Word Duel In The Desert</div>
			<div id="text" style="padding-left:5%">Game History</div>
			<li id="li_2" style="padding-left:5%">
				<div>
					<select name="ActiveGames" class="element select large" id="ActiveGames" SIZE="6" >
						<?php
							if(isset($games) && !empty($games)){
                                //echo '<option value="0" >Select a Game</option>';
								foreach($games as $gameinfo){
                                    $oppName = "Waiting for Opponent";
                                    if(isset($gameinfo["oppName"]) && $gameinfo["oppName"] != "")
                                            $oppName = $gameinfo["oppName"];
                                    echo '<option value="' . $gameinfo["g_ID"] . '" > You: ' .
                                        ($gameinfo["playerNum"] ? $gameinfo["g_score2"] : $gameinfo["g_score1"]) .
                                         " vs " . $oppName . ": " .
                                        ($gameinfo["playerNum"] ? $gameinfo["g_score1"] : $gameinfo["g_score2"]);
                                    if(!$gameinfo['g_active'])
                                        echo " - GAME OVER";
                                    else if($gameinfo["playerNum"] == $gameinfo["g_turn"])
                                        echo " - YOUR TURN!";
                                    else
                                        echo " - OPP TURN";
                                    if(isset($gameinfo['LastWord']) && $gameinfo['LastWord'] != "" && $gameinfo['g_active'])
                                        echo " [Last Word Played:" . $gameinfo['LastWord'] . "]";
                                    echo "</option>";
								}
							} else {
								echo '<option value="0" >No Game History</option>';
							}
						?>
					</select>
				</div> 
			</li>
            <form id="gameInfo" action="/game/viewgame" method="post">
                <input id="inputGID" type="hidden" name="g_ID" value="0" />
            </form>
			<div id="buttonField">
				<a href="game/newgame" class="button buttonright">
					<span class="button-text">+ New Game</span>
				</a>
				<a href="user/options" class="button buttonrightlast">
					<span class="button-text">• • • Options</span>
				</a>			
			</div>
			</div>
    </body>
</html> 