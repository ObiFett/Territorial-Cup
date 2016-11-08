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
	ToggleNewGameButton();
});

/*function ToggleNewGameButton(){
	 $this->model = new GameModel();
     //check to see if they have any open games
     $active_games = $this->model->activeGames($_SESSION['pl_id']);
		
	if ($active_games[0]['gameCount'] == 2)
	{
		//disable New Game button
		document.getElementByName("RandomUser").disabled = true; 
		document.getElementByName("RandomUser").style.background = LightGrey;
		document.getElementByName("InviteUser").style.background = LightGrey;
		
		//grey out New Game button
	}
	else
	{
		//enable New Game button
		document.getElementByName("RandomUser").disabled = false; 
		
		document.getElementByName("InviteUser").disabled = false;

		//return default active colors		
	}
	
}*/

