<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width = device-width">
		<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
        <title>Territorial Cup: Duel in the Desert - New Game Lobby</title>
        <link href="http://territorialcup.azurewebsites.net/rsc/lobbystyles.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo HOST; ?>/rsc/jquery-1.10.2.min.js"></script>
        <script src="<?php echo HOST; ?>/rsc/lobby.js"></script>
</head>

<body>
<div id="main">
    <img class="centered" src="http://territorialcup.azurewebsites.net/rsc/Logo.png" />
	<div id="text" style="padding-top: 10%; padding-left:5%">Active Games:</div>
	<li id="li_2" style="padding-left:5%">
        <div>
            <select name="ActiveGames" class="element select large" id="ActiveGames" SIZE="3">
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
                        echo '<option value="0" >No Active Games</option>';
                    }
                ?>
            </select>
        </div>
    </li>
	<form id="gameInfo" action="/game/viewgame" method="post">
        <input id="inputGID" type="hidden" name="g_ID" value="0" />
    </form>
	<div id="buttonField">
		<a href="<?php echo (($active_games > 2) ? '' : 'invite'); ?>" 
                class="button buttonright <?php if($active_games > 2){echo 'inactive';}; ?>" name="InviteUser">
			<span class="button-text">+ Invite User</span>
		</a>
		<a href="<?php echo (($active_games > 2) ? '' : 'random'); ?>" 
                class="button buttonright <?php if($active_games > 2){echo 'inactive';}; ?>" name="RandomUser">
			<span class="button-text">+ Random User</span>
		</a>
		<a href="/" class="button buttonrightlast">
			<span class="button-text">- Back</span>
		</a>
	</div>
</div>
</body>
</html>
