function addScrollListListener(){
    $('#ActiveGames').change(function() {
        $("input#inputGID").val($(this).val());
        var gameID = $(this).val();
        var gameInfo = {game_ID: gameID};
        $.ajax({
            url:"/game/setGame",
            type:"POST",
            data: gameInfo,
            dataType : 'json',
            success:function(msg){
                if(msg == "good request"){
                    location.href = "/game/viewGame";
                }
            }});
    });
}

$(document).ready (function(){
    addScrollListListener();
});

