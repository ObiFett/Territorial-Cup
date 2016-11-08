/*
 * NAVIGATION FUNCTIONS
 */

var lobbyActive = false;
var gameActive = false;
var backList = new Array();
var current = "";



function getCurrent(){
	current = $(".page.center").attr('id');
	if(current==="lobby")
	{
		lobbyUpdate();
	}
	if(current=="newgame")
	{
		newgameUpdate();
	}
	if(current =="game")
	{
		gamemenuUpdate();
	}
}

function navigate(functionName, newView){
    if(newView)
        checkRefresh(newView);
    if(functionName === back){
        back();    
    } else {
        eval(functionName)(newView);
    }
}

function forward(newView){
    if(newView === 'game'){
        $("#headerDiv").hide();
    } else {
        $("#headerDiv").show();
    }
    backList.push(current);
    slide(current, newView);
    current = newView;
}

function slide(current, newView) {
    var from;
    var to;
    //If newView is left, transition right
    if($("#" + newView).hasClass("right")){
        from = "right";
        to = "left";
		checkRefresh(newView);
		$("#"+ current).removeClass("center").addClass("transition " + to);
		$("#"+ newView).removeClass(from).addClass("transition center"); 
    } 
	else if($("#" + newView).hasClass("left")){
        from = "left";
        to = "right";
		checkRefresh(newView);
		$("#"+ current).removeClass("center").addClass("transition " + to);
		$("#"+ newView).removeClass(from).addClass("transition center");
    }
}

function back(){
    if(backList.length > 0) {
        //$("#" + current).hide();
        var previous = backList.pop();
        //$("#" + previous).show();
        slide(current, previous);
        current = previous;  
    }
    if(current !== 'game'){
        $("#headerDiv").show();
    } 
}

function newHome(newView){
    slide(current, newView);
    backList = new Array();
    backList.push(newView);
	current = newView; 
}

function checkRefresh(newView){
    var updateFunction = newView + 'Update';
    try {
        eval(updateFunction)();
    } catch (e){}
}

function backButtonListener(){
    $(".backButton").click(function(){navigate(back);});
}

function optionsListener(){
    $("#optionsButton").click(function(){navigate("forward", "options");});
}

function newGameListener(){
    $("#newGameButton").click(function(){navigate("forward", "newgame");});
}

function forgotListener(){
    $("#forgotButton").click(function(){navigate("forward", "recovery");});
}

function createListener(){
    $("#createButton").click(function(){navigate("forward", "create");});
}

function settingsListener(){
    $("#settingsButton").click(function(){navigate("forward", "settings");});
}

function gameboardmenuListener(){
	$("#bordermenu").click(function(){
            //show menu
            //var e = document.getElementById("#gameboardmenu");
            $("#li_3").empty();
            $("#li_3").append(ajaxTool("/game/lobby", ""));
            addScrollListListener();
            if($("#gameboardmenu").css("visibility") == "hidden")
             {
					/*
                    $("#gameboardmenu").css("visibility", "visible");
                    $("#gameboardmenu").css("width", "100%");
                    $("#gameboardmenu").css("left", "70%");
                    $("#gameboardmenu").css("opacity", "1");
					*/
					$("#gameboardmenu").removeClass("hide").addClass("show");
					
					$("#game").css("left", "70%");
                    $("#menuOverlay").css("visibility", "visible");
                    //e.style.visibility = 'visible';
                    //e.style.width = '100%;';
            }
            else
             {	/*
                 $("#gameboardmenu").css("visibility", "hidden");
                 $("#gameboardmenu").css("width", "0%");
                 $("#gameboardmenu").css("opacity", "0");
				 $("#gameboardmenu").css("left", "0");
				 */
				 $("#gameboardmenu").removeClass("show").addClass("hide");
				 
                 $("#game").css("left", "0");
                 $("#menuOverlay").css("visibility", "hidden");
                 //e.style.visibility = 'hidden';
                 //e.style.width = '0%';
             }

                     /*$("#menuOverlay").css("visibility", "visible");*/
             /*$("#fader").css("visibility", "visible");*/


             $('#menuOverlay').click(function() {
				/*
				 $("#gameboardmenu").css("visibility", "hidden");
                 $("#gameboardmenu").css("width", "0%");
                 $("#gameboardmenu").css("left", "0");
                 $("#gameboardmenu").css("opacity", "0");
				 */
				 $("#gameboardmenu").removeClass("show").addClass("hide");
			 
                 $("#menuOverlay").css("visibility", "hidden");
				 $("#game").css("left", "0");
                 //$("#fader").css("visibility", "hidden");
             });
    
		/*
		toggle between show and hide menu
		add z layer over game board add on click event for layer to hide
		the menu
		*/
	});
}

function MainMenuListener(){
    $("#mainMenuButton").click(function(){
	/*
	$("#gameboardmenu").css("visibility", "hidden");
	$("#gameboardmenu").css("width", "0%");
	$("#gameboardmenu").css("opacity", "0");
	$("#gameboardmenu").css("left", "0");
	*/
	$("#gameboardmenu").removeClass("show").addClass("hide");
	
	$("#game").css("left", "0");
	$("#menuOverlay").css("visibility", "hidden");
	navigate("forward", "lobby");});
}

function NewGameMenuListener(){
    $("#newGameMenuButton").click(function(){
	/*
	$("#gameboardmenu").css("visibility", "hidden");
	$("#gameboardmenu").css("width", "0%");
	$("#gameboardmenu").css("opacity", "0");
	$("#gameboardmenu").css("left", "0");
	*/
	$("#gameboardmenu").removeClass("show").addClass("hide");
	
	$("#game").css("left", "0");
	$("#menuOverlay").css("visibility", "hidden");
	
	navigate("forward", "newgame");});
}

function ResignMenuListener(){
    $("#resignMenuButton").click(function(){
	
	//logic to end game
	/*
        
	$("#gameboardmenu").css("visibility", "hidden");
	$("#gameboardmenu").css("width", "0%");
	$("#gameboardmenu").css("opacity", "0");
	$("#gameboardmenu").css("left", "0");
	*/
        ajaxTool("/game/resign", "");
        $("#errorMessage").html("Game Over!");
	$("#gameboardmenu").removeClass("show").addClass("hide");
	
	$("#game").css("left", "0");
	$("#menuOverlay").css("visibility", "hidden");
	
	});
}

/*
 * AJAX FUNCTIONS
 */
 
function enterListener(){
    $("#loginInputPassword").keyup(function(event){
        if(event.keyCode == 13){
            var submitData = {
            user: $("#loginInputUsername").val(),
            pword: $("#loginInputPassword").val()
        };
        $("#LoginSpinner").show();
        $("#loginFormData").hide();
        var message = ajaxTool("/user/login", submitData);
        setTimeout(
            function(){
                $("#LoginSpinner").hide();
                $("#loginFormData").show();
                if(message['status'] === "success") {
                    navigate("newHome", "lobby");
                } else {
                    $("#loginInputPassword").val('');
                    $("#loginErrorText").text(message['login_error']);
                }
            }, 1000);
        }
    });
}

function loginListener(){
    $("#loginButton").click(function(){
        var submitData = {
            user: $("#loginInputUsername").val(),
            pword: $("#loginInputPassword").val()
        };
        $("#LoginSpinner").show();
        $("#loginFormData").hide();
        var message = ajaxTool("/user/login", submitData);
        setTimeout(
            function(){
                $("#LoginSpinner").hide();
                $("#loginFormData").show();
                if(message['status'] === "success") {
                    navigate("newHome", "lobby");
                } else {
                    $("#loginInputPassword").val('');
                    $("#loginErrorText").text(message['login_error']);
                }
            }, 1000);     
    });
}


function changePWListener(){
    $("#changePWButton").click(function(){
        //TASK NEEDED
        //AJAX to send information to backend
        var submitData = {
            current: $("#currentPWField").val(),
            newPW: $("#newPWField").val(),
            confirmPW: $("#confirmPWField").val()
        };
        var message = ajaxTool("/user/changePassword", submitData);
    });
}

function changeEmailListener(){
    $("#changeEmailButton").click(function(){
        //TASK NEEDED
        //AJAX to send information to backend
        var submitData = {
            email: $("#emailField").val(),
            confirmEmail: $("#confirmEmailField").val()
        };
        var message = ajaxTool("/user/changeEmail", submitData);
    });
}

function randomListener(){
    $("#randomButton").click(function(){
        var message = ajaxTool("/game/checkRandom", null);
        if(message['status'] === "valid") {
            loadGame(0);
            navigate("forward", "game");
        }
    });
}

function registerListener(){
    $("#registerButton").click(function(){
        //TASK NEEDED
        //Logic to handle sending form data to backend
        
        var submitData = {
            username: $("#regUsername").val(),
            email: $("#regEmail").val(),
            password: $("#regPW").val(),
            emailconfirm: $("#regConfirmEmail").val(),
            passwordconfirm: $("#regConfirmPW").val()
        };
        
        var message = ajaxTool("/user/create", submitData);
		
	$("#RegisterSpinner").show();
        $("#registerFormData").hide();
        	
        setTimeout(
            function(){
                $("#RegisterSpinner").hide();
                $("#registerFormData").show();
                if(message['status'] === "success") {
                    navigate("forward", "lobby");
                } else {
                    $("#registerErrorText").text(message['status']);
                }
            }, 1000); 
    });
}

function statListener(){
    //to be implemented
    //$("#statButton").click(function(){navigate("forward", "statistics");});
}

function recoveryListener(){
    $("#recoveryButton").click(function(){
        //TASK NEEDED
        //logic to send recovery request
        
        var submitData = {
            email: $("#recover").val()
        };
        
        var message = ajaxTool("/user/emailPass", submitData);
        
        back();
    });
}

function addScrollListListener(){
    $('#scrollerContainer li').click(function() {
        var gameID = $(this).attr("id");
        var gameInfo = {game_ID: gameID};
        $.ajax({
            url:"/game/setGame",
            type:"POST",
            data: gameInfo,
            dataType : 'json',
            success:function(msg){
                if(msg === "good request"){
                    loadGame(gameID);
                    navigate("forward", "game");
                }
                if($("#gameboardmenu").css("visibility") !== "hidden"){
                    /*
                    $("#gameboardmenu").css("visibility", "hidden");
                    $("#gameboardmenu").css("width", "0%");
                    $("#gameboardmenu").css("opacity", "0");
                    $("#gameboardmenu").css("left", "0");
                    */
                    $("#gameboardmenu").removeClass("show").addClass("hide");
					
                    $("#game").css("left", "0");
                    $("#menuOverlay").css("visibility", "hidden");
                }
            }});
    });
}

function lobbyUpdate() {
    lobbyRefresh();
}

function lobbyRefresh(){
    $("#li_1").empty();
    $("#li_1").append(ajaxTool("/game/lobby", ""));
    addScrollListListener();
}

function newgameUpdate(){
    newgameRefresh();
}

function newgameRefresh(){
    $("#li_2").empty();
    $("#li_2").append(ajaxTool("/game/newgame", ""));
    addScrollListListener();
}

function gamemenuUpdate(){
    gamemenuRefresh();
}

function gamemenuRefresh(){
    $("#li_3").empty();
    /*which call?*/
    $("#li_3").append(ajaxTool("/game/newgame", ""));
    addScrollListListener();
}

function inviteListener(){
    $('#closeInvite').click(function() {
        $("#inviteOverlay").css("visibility", "hidden");
        $("#inviteOverlay").css("width", "0%");
        $("#inviteOverlay").css("height", "0%");
        $("#fader").css("visibility", "hidden");
    });
    
    $('#inviteButton').click(function() {
        $("#inviteOverlay").css("visibility", "visible");
        $("#inviteOverlay").css("width", "75%");
        $("#inviteOverlay").css("height", "40%");
        $("#fader").css("visibility", "visible");
    });
}



	
$(document).ready (function(){
    getCurrent();
    
	  enterListener();
	
    loginListener();
    newGameListener();
    optionsListener();
    changePWListener();
    changeEmailListener();
    backButtonListener();
    inviteListener();
    randomListener();
    registerListener();
    forgotListener();
    createListener();
    registerListener();
    settingsListener();
    statListener();
    recoveryListener();
    gameboardmenuListener();
    MainMenuListener();
    NewGameMenuListener();
    ResignMenuListener();
});



