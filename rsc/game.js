function ajaxTool(urlTarget, sendData){
    var message = "";
    $.ajax({
        url: urlTarget,
        type:"POST",
        data: sendData,
        dataType : 'json',
        async: false,
        success:function(msg){
            message = msg;
        }});
     return message;
}

var waiting = false;

var intervalID = 0;
var wordCode = "";

var selectedTiles = [];
var letterStack = [];

function updateBoard(boardString, finished){
    wordCode = "";
    $("#wordBuilt").val('');

    for (i = 0; i < boardString.length; i+=2){
        $("#tile"+i/2).removeClass();
        $("#tile"+i/2).addClass("boardTile");
        $("#tile"+i/2).text(boardString.charAt(i));
        var colorCode = parseInt(boardString.charAt(i+1));
        var color = "noColor";
        switch(colorCode) {
            case 1:
                color = "red";
                break;
            case 2:
                color = "redLock";
                break;
            case 3:
                color = "blue";
                break;
            case 4:
                color = "blueLock";
                break;
            default:
                break;
        }
        $("#tile"+i/2).addClass(color);
    }
    if(finished){
        $('.boardTile').removeEventListener('click', changeColor);
        $("#errorMessage").text("Game Over!");
    }
}

function addListeners(){
    $('.boardTile').click(function changeColor(){
            appendToWord($(this));
    });
    $('#clearWord').click(clearSelectedTiles);
    $('#submitWord').click(submitWord);

}

function appendToWord(element){
    var pWord;
    if($('#wordBuilt').val.length == 0)
        pWord = "";
    else
        pWord = $('#wordBuilt').val();
    var letter = $(element).text();

    letterStack.push(letter);
    $('#wordBuilt').val(pWord + letter);
	var target_top = $('#wordBuilt').offset().top + parseInt($('#wordBuilt').css('min-height'),10)/3;
	var current_top = $(element).offset().top;
	var add_top = current_top - target_top
	$(element).css("pointer-events","none");
	$(element).animate({bottom:add_top,opacity:0},600,function(){$(element).text(""); $(element).css("visibility","hidden"); $(element).css("bottom","")});

    var tileID = $(element).attr('id');
    selectedTiles.push(tileID);

    tileID = tileID.toString().substr(4, tileID.length-1);
    if(wordCode == "")
        wordCode += tileID;
    else
        wordCode += "|" + tileID;

}

function clearSelectedTiles(){
    var elementID = selectedTiles.pop();
    while(elementID != undefined){
        $('#'+elementID).text(letterStack.pop());
		$('#'+elementID).css("visibility","visible");
		$('#'+elementID).animate({opacity:1});
		$('#'+elementID).css("pointer-events","auto");
		elementID = selectedTiles.pop();
    }
    selectedTiles = [];
    letterStack = [];
    $("#wordBuilt").val("");
    wordCode = "";
}

function submitWord(){
    var boardInfo = {word_code: wordCode};

    $.ajax({
        url:"/game/submit",
        type:"POST",
        data: boardInfo,
        dataType : 'json',
        success:function(msg){
            if(msg['status'] == "error"){
                clearSelectedTiles();
                $("#errorMessage").text(msg["message"]);
            } else {
                $("#errorMessage").text("");
                $('#player1').find('.score').text(msg["score1"]);
                $('#player2').find('.score').text(msg["score2"]);
                clearSelectedTiles();
                var gameOver = false;
                if(msg['game_state'] != "ongoing")
                    gameOver = true;
                updateBoard(msg['board_string'], gameOver);
                swapSelectedPlayer();

                waiting = true;
                intervalID = setInterval(checkForUpdate, 5000);
            }
        }});
}

function checkForUpdate(){
    if(waiting){
        updateAjax();
    } else {
        if(intervalID != 0) {
            clearInterval(intervalID);
            intervalID = 0;
        }

    }
}

function updateAjax(){
    var gameID = $("#gameBoard").attr("gameID");
    var gameInfo = {g_ID: gameID};
    $.ajax({
        url:"/game/updateCheck",
        type:"POST",
        data: gameInfo,
        dataType : 'json',
        success:function(msg){
            if(msg['status'] == "update"){
                clearSelectedTiles();
                $("#errorMessage").text("");
                var gameOver = ((msg['info']['g_active'] == 1) ? false : true);
                updateBoard(msg["info"]['g_tileString'], gameOver);
                $('#player1').find('.score').text(msg["info"]['g_score1']);
                if(msg["info"]["oppName"] != "" && $('#player2').text() == "Waiting"){
                    $('#player2').text(msg["info"]['oppName']);
                    $('#player2').append("<div class='score'>" + msg["info"]['g_score2'] + "</div>");
                } else {
                    $('#player2').find('.score').text(msg["info"]['g_score2']);
                }
                swapSelectedPlayer();
                waiting = false;
            }
        }});
}

function swapSelectedPlayer(){
    var selectedID = $(".selectedPlayer").attr('id');
    if(selectedID == "player1"){
        $("#player1").removeClass("selectedPlayer");
        $("#player2").addClass("selectedPlayer");
    } else {
        $("#player2").removeClass("selectedPlayer");
        $("#player1").addClass("selectedPlayer");
    }
}

function loadBoard(gameID){
    $("#gameData").empty();
    if(gameID !== 0){
        $("#gameData").append(ajaxTool("/game/viewgame", gameID)["html"]);
    } else {
        $("#gameData").append(ajaxTool("/game/random", gameID)["html"]);
    }
}

function loadGame(gameID){  
    loadBoard(gameID);
    if($("#playerNum").val() != $("#turn").val()) {
        waiting = true;
        intervalID = setInterval(checkForUpdate, 10000);
    }
    if(parseInt($("#gameBoard").attr("status")) == 1)
        addListeners();
    else
        $("#errorMessage").text("Game Over!");
}



$(document).ready (function(){
});

