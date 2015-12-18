<?php
require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('Battleship');

switch ($_POST['act']){
    case "initGame":
        //remove $_SESSION less xd_check
        $xd=$_SESSION['xd_check'];
        session_unset();
        $_SESSION['xd_check']=$xd;
        
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
      
      if(!is_it_possible_to_place_ships_on_grid($postValues['gridWidth'],$postValues['gridHeight'],$postValues['nbShip1'],$postValues['nbShip2'],$postValues['nbShip3'],$postValues['nbShip4'],$postValues['nbShip5'],$postValues['nbShip6'])){
	error (404,"grid is too little for these ships");
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
		$long=abs($yStart - $yEnd) +1;		
	      }else{
		$long=abs($xStart - $xEnd) +1;
	      }
	      $nbBoatsIwant[$long]-=1;
	      $grid[$player]=place_ship_on_map($xStart,$yStart,$xEnd,$yEnd,$grid[$player]);
	      if(!$grid[$player]){
		echo $currentBot['name']." n'a pas placé correctement ses bateaux. Certains se chevauchent. Il perd.";
		if($player==1){
		  save_battle('Battleship',$bot1['name'],$bot2['name'],2);
		}else{
		  save_battle('Battleship',$bot1['name'],$bot2['name'],1);
		}
		die;
	      }
	      //remember each cases of each boats
	      $boatListOfCases=array();
	      if($xStart == $xEnd){
                if($yStart <= $yEnd ){
                    $start=$yStart;
                    $end=$yEnd;
                }else{
                    $start=$yEnd;
                    $end=$yStart;
                }
                for($i = $start; $i <= $end; $i++){
                    $boatListOfCases[]=$xStart.",".$i;
                }
	      }else{
                if($xStart <= $xEnd ){
                    $start=$xStart;
                    $end=$xEnd;
                }else{
                    $start=$xEnd;
                    $end=$xStart;
                }
                for($i = $start; $i <= $end; $i++){
                    $boatListOfCases[]=$i.",".$yStart;
                }
	      
	      }
	      $_SESSION['ships'][$player][]=$boatListOfCases;
	  }
	  foreach($nbBoatsIwant as $nb){
	    if($nb <> 0){
	      echo $currentBot['name']." n'a pas placé le bon nombre de bateaux. Il perd.";
	      if($player==1){
		save_battle('Battleship',$bot1['name'],$bot2['name'],2);
	      }else{
		save_battle('Battleship',$bot1['name'],$bot2['name'],1);
	      }
	      die;
	    }
	  } 
	}
	$_SESSION['ship1']=$postValues['nbShip1'];
	$_SESSION['ship2']=$postValues['nbShip2'];
	$_SESSION['ship3']=$postValues['nbShip3'];
	$_SESSION['ship4']=$postValues['nbShip4'];
	$_SESSION['ship5']=$postValues['nbShip5'];
	$_SESSION['ship6']=$postValues['nbShip6'];
	$_SESSION['strikes'][1]=array();
	$_SESSION['strikes'][2]=array();
	$_SESSION['width']=$postValues['gridWidth'];
	$_SESSION['height']=$postValues['gridHeight'];
	echo json_encode($grid); die;
	
        die;
    
        break;
    case "fight";
    
    /*
     print_r($_SESSION);
    Array ( [xd_check] => VSlWXLQVbYL2sCBwqetQdorR9 [bot1] => Array ( [id] => 10 [name] => stupidIA [url] => http://botsArena.tinad.fr/StupidIABattleship.php [description] => ) [bot2] => Array ( [id] => 10 [name] => stupidIA [url] => http://botsArena.tinad.fr/StupidIABattleship.php [description] => ) [matchId] => 702 [ships] => Array ( [1] => Array ( [0] => Array ( [0] => 3,0 [1] => 3,1 [2] => 3,2 [3] => 3,3 [4] => 3,4 ) [1] => Array ( [0] => 1,5 [1] => 2,5 [2] => 3,5 [3] => 4,5 ) [2] => Array ( [0] => 7,5 [1] => 8,5 [2] => 9,5 ) [3] => Array ( [0] => 0,0 [1] => 1,0 [2] => 2,0 ) [4] => Array ( [0] => 4,4 [1] => 5,4 ) [5] => Array ( [0] => 5,3 [1] => 6,3 [2] => 7,3 [3] => 8,3 [4] => 9,3 ) [6] => Array ( [0] => 2,7 [1] => 3,7 [2] => 4,7 [3] => 5,7 ) [7] => Array ( [0] => 1,9 [1] => 2,9 [2] => 3,9 ) [8] => Array ( [0] => 7,4 [1] => 8,4 [2] => 9,4 ) [9] => Array ( [0] => 0,2 [1] => 0,3 ) [10] => Array ( [0] => 3,9 [1] => 4,9 [2] => 5,9 [3] => 6,9 [4] => 7,9 ) [11] => Array ( [0] => 2,2 [1] => 2,3 [2] => 2,4 [3] => 2,5 ) [12] => Array ( [0] => 0,8 [1] => 1,8 [2] => 2,8 ) [13] => Array ( [0] => 7,7 [1] => 8,7 [2] => 9,7 ) [14] => Array ( [0] => 8,3 [1] => 9,3 ) [15] => Array ( [0] => 4,5 [1] => 5,5 [2] => 6,5 [3] => 7,5 [4] => 8,5 ) [16] => Array ( [0] => 3,2 [1] => 4,2 [2] => 5,2 [3] => 6,2 ) [17] => Array ( [0] => 0,7 [1] => 1,7 [2] => 2,7 ) [18] => Array ( [0] => 6,1 [1] => 7,1 [2] => 8,1 ) [19] => Array ( [0] => 2,3 [1] => 3,3 ) ) [2] => Array ( [0] => Array ( [0] => 0,9 [1] => 1,9 [2] => 2,9 [3] => 3,9 [4] => 4,9 ) [1] => Array ( [0] => 1,4 [1] => 1,5 [2] => 1,6 [3] => 1,7 ) [2] => Array ( [0] => 3,0 [1] => 4,0 [2] => 5,0 ) [3] => Array ( [0] => 0,0 [1] => 1,0 [2] => 2,0 ) [4] => Array ( [0] => 5,2 [1] => 6,2 ) [5] => Array ( [0] => 2,0 [1] => 3,0 [2] => 4,0 [3] => 5,0 [4] => 6,0 ) [6] => Array ( [0] => 2,1 [1] => 3,1 [2] => 4,1 [3] => 5,1 ) [7] => Array ( [0] => 7,3 [1] => 8,3 [2] => 9,3 ) [8] => Array ( [0] => 0,2 [1] => 1,2 [2] => 2,2 ) [9] => Array ( [0] => 5,3 [1] => 5,4 ) [10] => Array ( [0] => 5,3 [1] => 6,3 [2] => 7,3 [3] => 8,3 [4] => 9,3 ) [11] => Array ( [0] => 0,2 [1] => 0,3 [2] => 0,4 [3] => 0,5 ) [12] => Array ( [0] => 7,2 [1] => 8,2 [2] => 9,2 ) [13] => Array ( [0] => 0,1 [1] => 1,1 [2] => 2,1 ) [14] => Array ( [0] => 7,7 [1] => 7,8 ) [15] => Array ( [0] => 4,1 [1] => 4,2 [2] => 4,3 [3] => 4,4 [4] => 4,5 ) [16] => Array ( [0] => 3,9 [1] => 4,9 [2] => 5,9 [3] => 6,9 ) [17] => Array ( [0] => 7,3 [1] => 8,3 [2] => 9,3 ) [18] => Array ( [0] => 6,8 [1] => 7,8 [2] => 8,8 ) [19] => Array ( [0] => 0,5 [1] => 0,6 ) ) ) [shots] => Array ( [1] => Array ( ) [2] => Array ( ) ) [width] => 10 [height] => 10 )
    */
        if(count($_SESSION['strikes'][1]) == count($_SESSION['strikes'][2])){
            //player 1 has to fight
            $currentPlayer=1;
            $currentBot=$_SESSION['bot1'];
            $opponent=2;
            $opponentName=$_SESSION['bot2']['name'];
        }else{
            //it's player2
            $currentPlayer=2;
            $currentBot=$_SESSION['bot2'];
            $opponentName=$_SESSION['bot1']['name'];
            $opponent=2;
        }

      	$botParamsToSend=array(
	      'game'      => 'Battleship',
	      'match_id'  => $_SESSION['matchId']."-".$currentPlayer,
	      'act'       => 'fight',
	      'opponent'  => $opponentName,
	      'width'     => $_SESSION['width'],
	      'height'    => $_SESSION['height'],
	      'ship1'     => $_SESSION['ship1'],
	      'ship2'     => $_SESSION['ship2'],
	      'ship3'     => $_SESSION['ship3'],
	      'ship4'     => $_SESSION['ship4'],
	      'ship5'     => $_SESSION['ship5'],
	      'ship6'     => $_SESSION['ship6'],
	      'your_strikes'	=> json_encode($_SESSION['strikes'][$currentPlayer]),
	      'his_strikes'	=> json_encode($_SESSION['strikes'][$opponent])
      
	  );
	  $anwserPlayer=get_IA_Response($currentBot['url'],$botParamsToSend); 
	  
	  if(!preg_match('/^[0-9]+,[0-9]$/',$anwserPlayer)){
	    echo json_encode(array(
	      'target' => '',
	      'log' => $currentBot['name']." a fait une réponse non conforme, il perd.|".nl2br($anwserPlayer)."|";
	      ));
	     save_battle('Battleship',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],$opponent);
	     die;
	  }
	  list($x,$y)=explode(",",$anwserPlayer);
	  
	  //check if shot is under map's limits
	  if(($x >= $_SESSION['width']) OR ($y >= $_SESSION['height'])){
	    echo json_encode(array(
	      'target' => '',
	      'log' => $currentBot['name']." a fait un tir en dehors des limites de la carte. C'est interdit par les conventions de Genève. Il perd"
	      ));
	     save_battle('Battleship',$bot1['name'],$bot2['name'],$opponent);    
	  }
	 
	 //do this shot hit a boat
	 $result='';
	 foreach($_SESSION['ships'][$opponent] as $ennemyBoat){
	  if(in_array($x.",".$y, $ennemyBoat)){
	    $result='hit';
	    break;
	  }
	 }
	 
	 //save the shot
	 $_SESSION['strikes'][$currentPlayer][]=array(
	  'target' => $x.",".$y,
	  'result' => $result
	  );
	  echo json_encode(array(
	      'opponent'=> $opponent,
	      'target' => $x.",".$y,
	      'log' => $currentBot['name']."tire en ".$x.",".$y." ".$result
	  ));
	 
        die;
        break;
    default:
        break;
}