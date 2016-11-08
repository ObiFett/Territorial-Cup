<div>
    <div id="scrollerContainer">
    <?php
    if(isset($games) && !empty($games)){
        foreach($games as $gameinfo){
            $oppName = "Waiting for Opponent";
            if(isset($gameinfo["oppName"]) && $gameinfo["oppName"] != ""){
                $oppName = $gameinfo["oppName"];
            }?>
            <li id="<?php echo $gameinfo["g_ID"];?>">
                <div class="GameScores">
                    <div class="UserScore"> You: 
                        <?php echo ($gameinfo["playerNum"] ? $gameinfo["g_score2"] : $gameinfo["g_score1"])?>
                    </div>
                    <div class="VStext"> VS </div>
                    <div class="OppScore"><?php echo $oppName . ': ' .
                        ($gameinfo["playerNum"] ? $gameinfo["g_score1"] : $gameinfo["g_score2"])?>
                    </div>

                    <div class="turnPlayer">
                        <?php
                        if(!$gameinfo['g_active'])
                                echo "GAME OVER";
                        else if($gameinfo["playerNum"] == $gameinfo["g_turn"])
                                echo "YOUR TURN!";
                        else
                                echo $oppName . "'s TURN";?>
                    </div>
                    <div class="lastWord">
                        <?php if(isset($gameinfo['LastWord']) && $gameinfo['LastWord'] != "" && $gameinfo['g_active'])
                            echo " Last Word Played: " . $gameinfo['LastWord'] ?>
                    </div> 
                </div>
            </li> 
        <?php
        }
    } 
    else {?>
        <li>No Game History</li>
    <?php } ?>
    </div>
</div>
