<?php

require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('connectFou');

switch ($_POST['act']){

  case "newFight":
    //remove $_SESSION less xd_check
    $xd=$_SESSION['xd_check'];
    session_unset();
    $_SESSION['xd_check']=$xd;
  
  
    //init map
    $_SESSION['map']=array(
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
    );
      
    //check if bots exists
    $bot1Exists = false;
    $bot2Exists = false;
    foreach($bots as $bot){
      if($bot['id'] == $_POST['bot1']){
	  $bot1 = $bot;
	  $_SESSION['bot1']=$bot;
	  $bot1Exists =true;
	}
	if($bot['id'] == $_POST['bot2']){
	  $bot2 = $bot;
	  $_SESSION['bot2']=$bot;
	  $bot2Exists =true;
	}
	if ($bot1Exists && $bot2Exists){
	  break;
	} 
      }
      if ((!$bot1Exists) OR (!$bot2Exists)){
	error (500,"missing parameter 2");
      }
      
      //get a match id
      $_SESSION['matchId']=get_unique_id();
      $_SESSION['game']="connectFou";
         
    //echo "plop".json_encode($_SESSION['map']);
  case "fight":
  
      if($_SESSION['game'] <> "connectFou"){
	error(500,"game non found");    
      }
  
      //What player has to play?
      if(!isset($_SESSION['currentPlayer'])){
	$_SESSION['currentPlayer']=1;
	$you="X";
	$currentBotName=$_SESSION['bot1']['name'];
	$opponentName=$_SESSION['bot2']['name'];
	$botUrl=$_SESSION['bot1']['url'];
      }else{
	if($_SESSION['currentPlayer']==1){
	  $_SESSION['currentPlayer']=2;
	  $you="O";
	  $opponentName=$_SESSION['bot1']['name'];
	  $currentBotName=$_SESSION['bot2']['name'];
	  $botUrl=$_SESSION['bot2']['url'];
	}else{
	  $_SESSION['currentPlayer']=1;
	  $opponentName=$_SESSION['bot2']['name'];
	  $currentBotName=$_SESSION['bot1']['name'];
	  $botUrl=$_SESSION['bot1']['url'];
	  $you="X";
	}
      }
  
      //make post datas to send
      $postDatas=array(
	'game'       => 'connectFour',
	 'match_id'  => $_SESSION['matchId']."-".$_SESSION['currentPlayer'],
	 'opponent'  => $opponentName,
	 'you'	     => $you,
	 'grid'		=> json_encode( $_SESSION['map'])
	    
      );
      //send query
      $anwserPlayer=get_IA_Response($botUrl,$postDatas);
      
      //vérifier la validité de la réponse

      
      if((isset($_SESSION['map'][5][$anwserPlayer])) && ($_SESSION['map'][5][$anwserPlayer] == "")){
	//reponse conforme
	
	for($y = 0; $_SESSION['map'][$y][$anwserPlayer] <> ""; $y++){
	}
	 $_SESSION['map'][$y][$anwserPlayer]=$you;
	
	//does he win?
	for($i=0;$i < 7;$i++){
	  for($j=0;$j < 6;$j++){
	    if($_SESSION['map'][$j][$i]== $you){
	    
	      $wins=false;
	      //tester si 4 pions allignés vers la droite
	      if($i<4){
		$wins=true;
		for($x = $i+1; $x < $i+4; $x++){
		  if($_SESSION['map'][$j][$x] <> $you){
		    $wins=false;
		    break;
		  }
		}
	      }
	      
	      //tester si 4 pions allignés diagonale vers la droite
	      if((!$wins) && ($i < 4) && ($j < 3)){
		$wins=true;
		for($x = $i+1, $y = $j+1; $x < $i+4 ; $x++, $y++){
		    if($_SESSION['map'][$j][$x] <> $you){
		    $wins=false;
		    break;
		  }
		}
	      }
	      //tester si 4 pions allignés diagonale vers la gauche
	      if((!$wins) && ($i > 3) && ($j < 3)){
		$wins=true;
		  for($x = $i-1, $y = $j+1; $x > $i - 4 ; $x++, $y++){
		    if($_SESSION['map'][$j][$x] <> $you){
		    $wins=false;
		    break;
		  }
		}
	      
	      }
	    }
	  }
	}
	
	if($wins){
	  $anwserToJS=array(
	  'continue'	=> 0,
	  'grid' 	=> $_SESSION['map'],
	  'log'	=> $you." ".$currentBotName." a gagné" 
	  );
	}else{
	  $anwserToJS=array(
	  'continue'	=> 1,
	  'grid' 	=> $_SESSION['map'],
	  'log'	=> $you." ".$currentBotName." joue colonne ". $anwserPlayer
	  );
	
	
	}
      
      }else{
	//reponse non conforme
	$anwserToJS=array(
	  'continue' =>0,
	  'grid' => $_SESSION['map'],
	  'log'	=> $you." ".$currentBotName." a fait une réponse non conforme, il perd"
	);
      }
      
      echo json_encode($anwserToJS);
      
  
  
      die;
    break;    
  default:
    break;
}