<?php
require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('Battleship');

switch ($_POST['act']){
    case "initGame":
      //verifier parametres POST       
	$postParamsWanted=array(
	      // key,min,max
	  array('gridWidth',1,100),
	  array('gridHeight',1,100),
	  array('nbShip1',0,10),
	  array('nbShip2',0,10),
	  array('nbShip3',0,10),
	  array('nbShip4',0,10),
	  array('nbShip5',0,10),
	  array('nbShip6',0,10)
	);
  
      foreach($postParamsWanted as $p){
	if(!isset($_POST[$p[0]])){
	  error (500,'missing parameter 1');
	  die;
	}else{
	  $value=$_POST[$p[0]];
	}
	if (
		  (!is_numeric($value))
	      OR  ($value < $p[1])
	      OR  ($value > $p[2])
	    )
	{
	      error(500,'wrong parameters '.$p[0]);
	      die;
	}
	$postValues[$p[0]]=$value;
		
      }
      //check if bots exists
      $bot1Exists = false;
      $bot2Exists = false;
      foreach($bots as $bot){
	if($bot['id'] == $_POST['bot1']){
          
	  $bot1 = $bot;
	  $bot1Exists =true;
	}
	if($bot['id'] == $_POST['bot2']){
	  $bot2 = $bot;
	  $bot2Exists =true;
	}
	if ($bot1Exists && $bot2Exists){
	  break;
	} 
      }
      if ((!$bot1Exists) OR (!$bot2Exists)){
	error (500,"missing parameter 2");
      }
      
      //vars checked, lets init the initGame 
	
	$_SESSION['matchId']=get_unique_id();
	
	
	
	// get_IA_Response($iaUrl,$postParams)
	//array à envoyer au bot 1
	
	$bot1ParamsToSend=array(
            'game'      => 'Battleship',
            'match_id'  => $_SESSION['matchId']."-1",
            'act'       => 'init',
            'opponent'  => $bot2['name'],
            'width'     => $postValues['gridWidth'],
            'height'    => $postValues['gridHeight'],
            'ship1'     => $postValues['nbShip1'],
            'ship2'     => $postValues['nbShip2'],
            'ship3'     => $postValues['nbShip3'],
            'ship4'     => $postValues['nbShip4'],
            'ship5'     => $postValues['nbShip5'],
            'ship6'     => $postValues['nbShip6']
            
	);
	
	if(!$boatsPlayer1 = json_decode(get_IA_Response($bot1['url'],$bot1ParamsToSend))){
	  echo $bot1['name']." a fait une réponse non conforme, il perd.";
	  save_battle('Battleship',$bot1['name'],$bot2['name'],2);
	}
        echo $anwserPlayer1; die;
    
        break;
    default:
        break;
}