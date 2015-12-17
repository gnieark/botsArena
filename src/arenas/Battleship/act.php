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
      
      if(!is_it_possible_to_place_ships_on_grid($postValues['gridWidth'],$postValues['gridHeight'],$postValues['nbShip1'],$posValues['nbship2'],$postValues['nbship3'],$postValues['nbship4'],$postValues['nbship5'],$postvalues['nbship6'])){
	error (404,"grid is too little for these sips");
      }
      
      //vars checked, lets init the initGame 
	
	$_SESSION['matchId']=get_unique_id();
	
	
	for($player = 1; $player <= 2; $player++){
	
	  if($player==1){
	    $opponentName=$bot2['name'];
	    $currentBot=$bot1;
	  }else{
	    $opponentName=$bot1['name'];
	    $currentBot=$bot2;
	  }
	  
	  $botParamsToSend=array(
	      'game'      => 'Battleship',
	      'match_id'  => $_SESSION['matchId']."-1",
	      'act'       => 'init',
	      'opponent'  => $opponentName,
	      'width'     => $postValues['gridWidth'],
	      'height'    => $postValues['gridHeight'],
	      'ship1'     => $postValues['nbShip1'],
	      'ship2'     => $postValues['nbShip2'],
	      'ship3'     => $postValues['nbShip3'],
	      'ship4'     => $postValues['nbShip4'],
	      'ship5'     => $postValues['nbShip5'],
	      'ship6'     => $postValues['nbShip6']
	      
	  );
	  $anwserPlayer=get_IA_Response($currentBot['url'],$botParamsToSend);
	  $boatsPlayer = json_decode( html_entity_decode($anwserPlayer));
	  if(!$boatsPlayer){
	    echo $currentBot['name']." a fait une réponse non conforme, il perd.".$anwserPlayer;
	    if($player==1){
	      save_battle('Battleship',$bot1['name'],$bot2['name'],2);
	    }else{
	      save_battle('Battleship',$bot1['name'],$bot2['name'],1);
	    }
	    die;
	  }
	  
	  //init grid
	  for($y = 0; $y < $postValues['gridHeight']; $y++){
	    for($x = 0; $x < $postValues['gridWidth']; $x++){
		    $grid[$player][$y][$x]=0;
	    }
	  }
	  
	  //vérifier si'il y a le bon nombre de bateaux et les placer
	  $nbBoatsIwant=array(0,$postValues['nbShip1'],$postValues['nbShip2'],$postValues['nbShip3'],
				$postValues['nbShip4'],$postValues['nbShip5'],$postValues['nbShip6']);
  
	  foreach($boatsPlayer as $boat){
	      list($startCoord,$endCoord) = explode("-",$boat);
	      list($xStart,$yStart)=explode(",",$startCoord);
	      list($xEnd,$yEnd)=explode(",",$endCoord);
	      if($xStart == $xEnd){
		$long=abs($yStart - $yEnd +1);		
	      }else{
		$long=abs($xStart - $xEnd +1);
	      }
	      $nbBoatsIwant[$long]-=1;
	      $grid[$player]=place_ship_on_map($xStart,$yStart,$xEnd,$yEnd,$grid[$player]);
	      if(!$grid[$player]){
		echo $currentBot['name']." n'a pas placé correctement ses bateaux. Certains se chevauchent. Il perd<pre>".$anwserPlayer." ".$xStart.$yStart.$xEnd.$yEnd."</pre>";
		if($player==1){
		  save_battle('Battleship',$bot1['name'],$bot2['name'],2);
		}else{
		  save_battle('Battleship',$bot1['name'],$bot2['name'],1);
		}
		die;
	      }
	  }
	  foreach($nbBoatsIwant as $nb){
	    if($nb <> 0){
	      echo $currentBot['name']." n'a pas placé correctement ses bateaux. Il perd. sa réponse: <pre>".$anwserPlayer."</pre>";
	      if($player==1){
		save_battle('Battleship',$bot1['name'],$bot2['name'],2);
	      }else{
		save_battle('Battleship',$bot1['name'],$bot2['name'],1);
	      }
	      die;
	    }
	  } 
	}
	
	$_SESSION['grids']=$grid;
	echo json_encode($grid); die;
	
        die;
    
        break;
    default:
        break;
}