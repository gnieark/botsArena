<?php

require_once(__DIR__."/functions.php");
switch ($_POST['act']){

  case "fight":
    $bots=get_Bots_Array('tictactoe');
    //clean $_POST vars
    $postParams=get_Post_Params(count($bots));
    if(!$postParams){
      error(400,"wrong parameters send");
      die;
    }else{
      $bot1=$postParams['bot1'];
      $bot2=$postParams['bot2'];
    }	      
    //init map
    $map=array(
      '0-0' => '','0-1' => '','0-2' => '',
      '1-0' => '','1-1' => '','1-2' => '',
      '2-0' => '','2-1' => '','2-2' => '');
  
    $end=false;
    
    //send init message to bots
    $game_id=get_unique_id();
    for($player = 0; $player < 2; $player++){
                $params[$player]=array(
                    'game-id'        =>  $game_id,
                    'action'         =>  'init',
                    'game'           =>  'tictactoe',
                    'players'        => 2,
                    'board'          => '',
                    'player-index'   => $player 
                );
    }
    get_IA_Response($bots[$bot1]['url'],$params[0]); //don't care about result
    get_IA_Response($bots[$bot2]['url'],$params[1]); //don't care about result
      
            //"game-id":"gameid","action":"init","game":"tictactoe","players":2,"board":null,"you":null,"player-index":0}
        //[0000] body> {"name":"moul-tictactoe","play":null,"error":null}
        
    $playerPlayingNow=1;
    
    while($end==false){
      switch($playerPlayingNow){
	  case  1:
	      $playerURL=$bots[$bot1]['url'];
	      $playerCHAR='X';
	      $playerName=$bots[$bot1]['name'];
	      $playerIndex=0;
	      break;
	  case 2:
	      $playerURL=$bots[$bot2]['url'];
	      $playerCHAR='O';
	      $playerName=$bots[$bot2]['name'];
	      $playerIndex=1;
	      break;
	      
	  default:
	      error(500,"oups");
	      die;
      }
      
      $paramsToSend=array(
        'game-id'    => $game_id,
        'action'     =>  'play-turn',
        'game'       => 'tictactoe',
        'players'    => 2,
        'board'      => $map,
        'you'       =>  $playerCHAR,
        'player-index'  =>$playerIndex
      );
      
      $httpResponse=json_decode(get_IA_Response($playerURL,$paramsToSend));
      $playerResponse=$httpResponse['play'];
      
      //tester la validité de la réponse
      if((isset($map[$playerResponse])) && ($map[$playerResponse]=="")){
	    //reponse conforme
	    echo  "<p>".$playerName." joue en ".$playerResponse." la nouvelle grille est <br/>";       
	    $map[$playerResponse]=$playerCHAR;
	    echo "<table>";
	    for($j=0;$j<3;$j++){
		echo "<tr>";
		for($i=0;$i<3;$i++){
		    echo '<td class="cellj'.$j.' celli'.$i.'">'.$map[$j.'-'.$i].'</td>';
		}
		echo "</tr>";
	    }
	    echo "</table>";
	    //tester si trois caracteres allignés
	    if(
		    (($map['0-0']==$map['0-1'])&&($map['0-1']==$map['0-2'])&&($map['0-2']!==""))
		OR  (($map['1-0']==$map['1-1'])&&($map['1-1']==$map['1-2'])&&($map['1-2']!==""))
		OR  (($map['2-0']==$map['2-1'])&&($map['2-1']==$map['2-2'])&&($map['2-2']!==""))
		OR  (($map['0-0']==$map['1-0'])&&($map['1-0']==$map['2-0'])&&($map['2-0']!==""))
		OR  (($map['0-1']==$map['1-1'])&&($map['1-1']==$map['2-1'])&&($map['2-1']!==""))
		OR  (($map['0-2']==$map['1-2'])&&($map['1-2']==$map['2-2'])&&($map['2-2']!==""))
		OR  (($map['0-0']==$map['1-1'])&&($map['1-1']==$map['2-2'])&&($map['2-2']!==""))
		OR  (($map['0-2']==$map['1-1'])&&($map['1-1']==$map['2-0'])&&($map['2-0']!==""))
	    ){
		echo "<p>".$playerName." ".$playerCHAR." a gagné.</p>";
		save_battle('tictactoe',$bots[$bot1]['name'],$bots[$bot2]['name'],$playerPlayingNow);
		$end=true;
		break;
	    }
	    //tester si toutes les cases ne seraient pas prises
	    $full=true;	      
	    foreach($map as $char){
		if($char==""){
		    $full=false;
		    break;
		}
	    }
	    if($full){
		echo "<p>Match nul</p>";
		save_battle('tictactoe',$bots[$bot1]['name'],$bots[$bot2]['name'],0);
		$end=true;
		break;
	    }
	    
	    //on change de joueur
	    if($playerPlayingNow==1){
		$playerPlayingNow=2;
	    }else{
		$playerPlayingNow=1;
	    }
      }else{
	    echo "<p>".$playerName." a fait une réponse non conforme. Il perd</p>";
	    break;
      
      }
  }
    
      die;
      break;
  default:
      break;
}
