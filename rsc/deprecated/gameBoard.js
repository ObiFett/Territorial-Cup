
var activePlayer;
var waiting = false;

var intervalID = 0;

var originalScore1 = 0;
var originalScore2 = 0;

var tileStack = [];
var selectedTiles = [];
var modifiedTiles = [];
var newColors = [];
var oldColors = [];
var letters = [];
var wordCode = "";
var letterPlacement = {};

function updateBoard(boardString, finished){
    selectedTiles = [];
    newColors = [];
    oldColors = [];
    letters = [];
    modifiedTiles = [];

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
            tileUpdate($(this), activePlayer);
    });
    $('#clearWord').click(clearSelectedTiles);
    $('#submitWord').click(submitWord);

}

function tileUpdate(element, color){
    if($.inArray($(element).attr('id'), selectedTiles) == -1){
        var classes = $(element).attr('class').split(' ');
        var oldColor = classes[classes.length-1];
        $(element).addClass("selectedTile");
        selectedTiles.push($(element).attr('id'));
        if(oldColor == "noColor"){
            $(element).removeClass("noColor");
            $(element).addClass(color);
            oldColorUpdate(element, "noColor", color);
            checkForLocks(element, true);
        } else {
            $(element).removeClass(oldColor);
            if(oldColor == color){
                $(element).addClass(color);
                oldColorUpdate(element, oldColor, color);
            } else if(oldColor == "blue"){
                $(element).addClass("red");
                oldColorUpdate(element, oldColor, "red");
                checkForLocks(element, true);
            } else if (oldColor == "red") {
                $(element).addClass("blue");
                oldColorUpdate(element, oldColor, "blue");
                checkForLocks(element, true);
            } else {
                $(element).addClass(oldColor);
                oldColorUpdate(element, oldColor, oldColor);
            }
        }
        appendToWord(element);
    }
}

function appendToWord(element){
    var pWord;
    if($('#wordBuilt').val.length == 0)
        pWord = "";
    else
        pWord = $('#wordBuilt').val();
    $('#wordBuilt').val(pWord + $(element).text());
    var tileID = $(element).attr('id');
    tileID = tileID.toString().substr(4, tileID.length-1);
    wordCode += tileID + "|";
    console.log(wordCode.toString());
    letterPlacement[tileID] = $(element).text();
    $(element).text("");
}

function checkForLocks(element, recurse){
    var elementID = String($(element).attr('id'));
    var position = parseInt(elementID.substring(4, elementID.length));
    var locked = true;
    
    if(position > 3){
        var top = position-4;
        var tTile = String('#tile'+top);
        if($(tTile).hasClass(activePlayer) || $(tTile).hasClass(activePlayer+'Lock')){
            if(recurse)
                checkForLocks($(tTile),false);
        } else{
            locked = false;
        }
    }
    if(position < 20) {
        var bottom = position+4;
        var bTile = String('#tile'+bottom);
        if($(bTile).hasClass(activePlayer) || $(bTile).hasClass(activePlayer+'Lock')){
            if(recurse)
                checkForLocks($(bTile),false);
        } else{
            locked = false;
        }
    }
    if(position%4 != 0) {
        var left = position-1;
        var lTile = String('#tile'+left);
        if($(lTile).hasClass(activePlayer) || $(lTile).hasClass(activePlayer+'Lock')){
            if(recurse)
                checkForLocks($(lTile),false);
        } else{
            locked = false;
        }
    }
    if(position%4 != 3) {
        var right = position+1
        var rTile = String('#tile'+right);
        if($(rTile).hasClass(activePlayer) || $(rTile).hasClass(activePlayer+'Lock')){
            if(recurse)
                checkForLocks($(rTile),false);
        } else{
            locked = false;
        }
    }

    if(locked && ($(element).hasClass(activePlayer))){
        applyLock(element, activePlayer+'Lock');
    }
}

function applyLock(element, newcolor){
    $(element).removeClass(activePlayer);
    $(element).addClass(newcolor);
    oldColorUpdate(element, activePlayer, newcolor);
    /*var arrayIndex = $.inArray($(element).attr('id'), modifiedTiles);
    var classes = $(element).attr('class').split(' ');
    var oldColor = classes[classes.length-1];
    if(arrayIndex == -1){
        modifiedTiles.push($(element).attr('id'));
        oldColors.push(oldColor);
        newColors.push(newcolor);
        letters.push($(element).text());
    } else {
        newColors[arrayIndex] = newcolor;
    }
    updateScore(oldColor, newcolor);*/
}

function oldColorUpdate(element, ocolor, ncolor){
    var arrayIndex = $.inArray($(element).attr('id'), modifiedTiles);
    if(arrayIndex == -1){
        modifiedTiles.push($(element).attr('id'));
        oldColors.push(ocolor);
        newColors.push(ncolor);
        letters.push($(element).text());
    } else {
        newColors[arrayIndex] = ncolor;
    }
    updateScore(ocolor, ncolor);
}

function updateScore(oldC, newC) {
    var scoreUpdate1 = parseInt($('#player1').find('.score').text());
    var scoreUpdate2 = parseInt($('#player2').find('.score').text());
    if(oldC == "noColor"){
        if(newC == "red")
            scoreUpdate1++;
        else
            scoreUpdate2++;
    } else if (oldC == "blue"){
        if(newC == "red") {
            scoreUpdate1++;
            scoreUpdate2--;
        }
    } else if (oldC == "red"){
        if(newC == "blue") {
            scoreUpdate1--;
            scoreUpdate2++;
        }
    }
    $('#player1').find('.score').text(scoreUpdate1);
    $('#player2').find('.score').text(scoreUpdate2);
}

function clearSelectedTiles(){
    var elementID = modifiedTiles.pop();
    while(elementID != undefined){
        $('#'+elementID).removeClass(newColors.pop());
        $('#'+elementID).addClass(oldColors.pop());
        $('#'+elementID).removeClass("selectedTile");
        $('#'+elementID).text(letters.pop());
        elementID = modifiedTiles.pop();
    }
    selectedTiles = [];
    $("#wordBuilt").val("");
    wordCode = "";
    $('#player1').find('.score').text(originalScore1);
    $('#player2').find('.score').text(originalScore2);
}

function submitWord(){
    var colorCodes = {noColor: "n",
                        red: "r",
                        redLock: "s",
                        blue: "b",
                        blueLock: "c"};
    //loop through boardtiles and create string
    var boardString = "";
    $(".boardTile").each(function(){
        if($(this).text() == ""){
            var tileID = $(this).attr('id');
            tileID = tileID.toString().substr(4, tileID.length-1);
            boardString += letterPlacement[tileID];
        } else
            boardString += $(this).text();
        var classes = $(this).attr('class').split(' ');
        boardString += colorCodes[classes[classes.length-1]];
    });
    //submit with ajax
    var score1 = parseInt($('#player1').find('.score').text());
    var score2 = parseInt($('#player2').find('.score').text());
    var gameID = $("#gameBoard").attr("gameID");
    var boardInfo = {word_code: wordCode,
                        board_string: boardString,
                        g_ID: gameID,
                        newScore1: score1,
                        newScore2: score2};
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
                originalScore1 = score1;
                originalScore2 = score2;
                clearSelectedTiles();
                var gameOver = false;
                if(msg['game_state'] != "ongoing")
                    gameOver = true;
                updateBoard(msg['board_string'], gameOver);
                swapSelectedPlayer();
                
                waiting = true;
                intervalID = setInterval(checkForUpdate, 10000);
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
    var playerNum = 0;
    if(activePlayer == "blue")
        playerNum = 1;
    var gameInfo = {    g_ID: gameID,
                        player_num: playerNum};
    $.ajax({
        url:"/game/updateCheck",
        type:"POST",
        data: gameInfo,
        dataType : 'json',
        success:function(msg){
            if(msg['status'] == "update"){
                clearSelectedTiles();
                $("#errorMessage").text("");
                var gameOver = (msg['info']['g_active'] ? false : true);
                updateBoard(msg["info"]['g_tileString'], gameOver);
                $('#player1').find('.score').text(msg["info"]['g_score1']);
                if(msg["info"]["oppName"] != ""){
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
/*function playerDebug(){
    $('.playerName').click(function changeActivePlayer(){
        $('.selectedPlayer').removeClass("selectedPlayer");
        $(this).addClass("selectedPlayer");
        var oldPlayer = activePlayer;
        if($(this).attr('id') == "player1")
            activePlayer = "red";
        else
            activePlayer = "blue";
        if(oldPlayer != activePlayer)
            clearSelectedTiles();
    });
}*/

$(document).ready (function(){
    activePlayer = $("#playerNum").val();
    if($("#playerNum").val() != $("#turn").val()) {
        waiting = true;
        intervalID = setInterval(checkForUpdate, 10000);
    }
    originalScore1 = parseInt($('#player1').find('.score').text());
    originalScore2 = parseInt($('#player2').find('.score').text());
    if(parseInt($("#gameBoard").attr("status")) == 1)
        addListeners();
    else
        $("#errorMessage").text("Game Over!");
    //playerDebug();
});