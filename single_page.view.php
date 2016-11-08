<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width = device-width">
		<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
		<title>Territorial Cup: Duel in the Desert - Lobby</title>
		<link href="http://territorialcup.azurewebsites.net/rsc/LobbyStyles.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo HOST; ?>/rsc/jquery-1.11.0.js"></script>
        <script src="<?php echo HOST; ?>/rsc/game.js"></script>
        <script src="<?php echo HOST; ?>/rsc/site.js"></script>
    </head>
    
<!-- START MAIN DIV -->
    <body>
        <div id="main">
            <div id="headerDiv" class="header">
                <img class="centered" src="http://territorialcup.azurewebsites.net/rsc/Logo.png" />
                <div class="text bluecentered">Word Duel In The Desert</div>
            </div>
<!-- 
********************
        LOBBY
********************
-->
        <div id="lobby" class="page <?php echo (($login) ? 'center"' : ' right"'); ?>>
            <div class="text" style="padding-left:5%">Game History</div>
            <ul id="li_1" style="padding-left:5%"> 
            </ul>
            <input id="inputGID" type="hidden" name="g_ID" value="0" />
            <div class="buttonField">
                <a id="newGameButton" href="javascript:;" class="button buttonright">
                    <span class="button-text">+ New Game</span>
                </a>
                <a id="optionsButton" href="javascript:;" class="button buttonrightlast">
                    <span class="button-text">• • • Options</span>
                </a>			
            </div>	
        </div>

<!-- 
******************
    Game Board Menu
******************
-->		

		<div id="gameboardmenu" class="menutransition hide">
		 <div id="headerDiv" class="header">
                    <img class="centered" src="http://territorialcup.azurewebsites.net/rsc/Logo.png" />
                    <div class="text bluecentered">Word Duel In The Desert</div>
                </div>
                <div class="buttonField gameMenu">
                    <a href="javascript:;" id="mainMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                        <span class="button-text">Main Menu</span>
                    </a>
                    <a href="javascript:;" id="newGameMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                        <span class="button-text">New Game</span>
                    </a>
                    <a href="javascript:;" id="resignMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                        <span class="button-text">Resign</span>
                    </a>
			</div>
			
                    <ul id="li_3" style="padding-left:5%"> 
                    </ul>
                    <input id="inputGID" type="hidden" name="g_ID" value="0" />
		</div>
		
<!-- 
********************
        GAME
********************
-->
        <div id="game" class="page right">
		
		<div id="menuOverlay" class="menu-overlay" href="javascript:;">
                <a href="javascript:;" id="hideGameOverlay" class="closeModal">
                
                </a>
                
                <div class="formData">
                   
                </div>
        </div>
		<!--
		temp removing game menu and transitioning to separate page
		<div id="gameboardmenu" class="gamemenu">
			<a href="javascript:;" id="mainMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                     <span class="button-text">Main Menu</span>
            </a>
			<a href="javascript:;" id="newGameMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                     <span class="button-text">New Game</span>
            </a>
			<a href="javascript:;" id="resignMenuButton" class="button buttonmenu" style="background:#CCCCCC">
                     <span class="button-text">Resign</span>
            </a>
			
			<ul id="li_3" style="padding-left:5%"> 
            </ul>
            <input id="inputGID" type="hidden" name="g_ID" value="0" />
			
		</div>
		-->
            <img class="left" src="http://territorialcup.azurewebsites.net/rsc/ASU_Fork.jpg" />
            <img class="right" src="http://territorialcup.azurewebsites.net/rsc/Wildcat.gif" />
            <div id="gameFormData">
                <a href="javascript:;" id="bordermenu" class="border-menu" style="background:#CCCCCC">
                        <span class="button-menu-text">☰</span>
                    </a>
				
				<div id="gameData">
                </div>

                <div class="buttonField gameButton">
                    <a href="javascript:;" class="button backButton">
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
<!-- 
******************
    INVITE OVERLAY
******************
-->
		
        <div id="inviteOverlay">
                <a href="javascript:;" id="closeInvite" class="closeModal">
                X
                </a>
                <div class="modalText">
					Challenge a friend to a battle!
				</div>
				<img class="left" src="http://territorialcup.azurewebsites.net/rsc/ASU_Fork.jpg" />
				<img class="right" src="http://territorialcup.azurewebsites.net/rsc/Wildcat.gif" />
                <div class="formData">
                    <input type="text" class="rounded modal" name="email" placeholder="Opponent's Email">
                    <a href="javascript:;" class="button center" name="SendInvite">
                        <span class="button-text">Send Invite</span>
                    </a>
                </div>
        </div>
		
<!-- 
******************
    OVERLAY FADER (REUSABLE)
******************
-->	

		<div id="fader">
		</div>

<!-- 
******************
    NEWGAME
******************
-->
        <div id="newgame" class="page right">
            <div class="text" style=" padding-left:5%">Active Games:</div>
            <li id="li_2" style="padding-left:5%">    
            </li>
            <input id="inputGID" type="hidden" name="g_ID" value="0" />
            <div class="buttonField">
                    <a href="javascript:;" id="inviteButton" class="button buttonright  <?php if($active_games > 2){echo 'inactive';}; ?>" name="InviteUser">
                            <span class="button-text">+ Invite User</span>
                    </a>
                    <a href="javascript:;" id="randomButton" class="button buttonright  <?php if($active_games > 2){echo 'inactive';}; ?>" name="RandomUser">
                            <span class="button-text">+ Random User</span>
                    </a>
                    <a href="javascript:;" class="button buttonrightlast">
                            <span class="button-text backButton">- Back</span>
                    </a>
            </div>
        </div>

<!-- 
******************
    CREATE ACCOUNT
******************
-->
        <div id="create" class="page right">
            <div class="text" style="padding-top: 10%;padding-left:5%">Register:
		<div class="spinner" id="RegisterSpinner" style="display: none"></div>
		<div class="formData" id="registerFormData">
                        <input id="regUsername" type="text" class="rounded" name="username" placeholder="Username">
                        <input id="regEmail" type="email" class="rounded" name="email" placeholder="E-mail Address">
                        <input id="regConfirmEmail" type="email" class="rounded" name="emailconfirm" placeholder="Confirm E-mail Address">
                        <input id="regPW" type="password" class="rounded" name="password" placeholder="Password">
                        <input id="regConfirmPW" type="password" class="rounded" name="passwordconfirm" placeholder="Confirm Password">
                        <br>
                        Choose Your Side:
                        <select id="regUniPicker" name="university" style="display:block">
                        <option value="ArizonaStateUniversity">Arizona State University</option>
                        <option value="NorthernArizonaUniversity">Northern Arizona University</option>
                        <option value="UniversityOfArizona">University of Arizona</option>
                        <option value="GrandCanyonUniversity">Grand Canyon University</option>
                        </select>
			<button id="registerButton" type="submit" class="rounded" name="register"> Register </button>
		<a href="javascript:;" class="button buttonrightlast">
                            <span class="button-text backButton">- Back</span>
                    </a>
		</div>
            </div>
            <div id="registerErrorText"></div>
        </div>

<!-- 
******************
    LOGIN
******************
-->
    <div id="login" class="page <?php echo (($login) ? 'left"' : ' center"'); ?>>
        <div class="text" style="padding-top: 10%; padding-left:5%">Log in:
            <div class="spinner" id="LoginSpinner" style="display: none"></div>
            <div id="loginFormData" style="display: block">
                <div id="loginErrorText"></div>
                <input id="loginInputUsername" type="text" class="rounded" name="user" placeholder="Username">
                <input id="loginInputPassword" type="password" class="rounded" name="pword" placeholder="Password">
                <button id="loginButton" type="submit" class="rounded" name="login"> Log in </button>
            </div>
	</div>
	<div class="text" style="padding-top: 5%; padding-left:5%">
            <a id="forgotButton" href="javascript:;">Forgot Username/Password?</a>
	</div>
	<div class="text" style="padding-top: 5%; padding-left:5%">
		Don't have an account? <a id="createButton" href="javascript:;">Sign up here.</a>
	</div>
    </div>
<!-- 
******************
    OPTIONS
******************
-->
    <div id="options" class="page right">
        <div class="text" style="padding-top: 10%; padding-left:5%">Options:</div>
	
	<div class="buttonField">
            <a id="statButton" href="javascript:;" class="button buttonright">
                    <span class="button-text">+ Statistics</span>
            </a>
            <a id="settingsButton" href="javascript:;" class="button buttonright">
                    <span class="button-text">+ Account Settings</span>
            </a>
            <a href="javascript:;" class="button buttonrightlast backButton">
                    <span class="button-text">- Back</span>
            </a>
	</div>
    </div>
<!-- 
******************
    RECOVERY
******************
-->
    <div id="recovery" class="page right">
        <div class="text" style="padding-top: 10%; padding-left:5%">Forgot your account info?
            <input type="email" class="rounded" name="email" placeholder="E-mail Address">
            <button id="recoveryButton" type="submit" class="rounded" name="recover"> Send Recovery E-mail </button>
        </div>
        <a href="javascript:;" class="button backButton">
            <span class="button-text">- Back</span>
        </a>
    </div>

<!-- 
******************
    SETTINGS
******************
-->
    <div id="settings" class="page right">
        <div class="text" style="padding-top: 10%;padding-left:5%">Account Settings:
		<div class="text" style="padding-top: 5%; padding-left:5%">Change Email Address:
                    <input id="emailField" type="email" class="rounded" name="email" placeholder="E-mail Address">
                    <input id="confirmEmailField" type="email" class="rounded" name="emailconfirm" placeholder="Confirm E-mail Address">
                    <button id="changeEmailButton" type="submit" class="rounded" name="submit"> Change E-mail </button>
		</div>

		<div class="text" style="padding-top: 5%; padding-left:5%">Change Password:
                    <input id="currentPWField" type="password" class="rounded" name="currentpw" placeholder="Current Password">
                    <input id="newPWField" type="password" class="rounded" name="newpw" placeholder="New Password">
                    <input id="confirmPWField" type="password" class="rounded" name="confirmpw" placeholder="Confirm New Password">
                    <button id="changePWButton" type="submit" class="rounded" name="submit"> Change Password </button>	
		</div>
	</div>
	
	<div class="buttonField">
            <a href="javascript:;" class="button buttonright backButton">
                <span class="button-text">- Back</span>
            </a>
	</div>
    </div>

<!-- END MAIN DIV -->
        </div>
    </body>
</html> 
