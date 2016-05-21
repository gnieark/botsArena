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
	 $strikeX=$anwserPlayer;
	 $strikeY=$y;
	
	//does he win?
	$wins=false;
	
	//diagonale  \
	$count=1;
	$x=$strikeX;
	$y=$strikeY;
	$cellsWin=array();
	$cellsWin[]=array($x,$y);
        while(($x > 0) && ($y < 5) && ($_SESSION['map'][$y + 1][$x - 1] == $you)){
            $x--;
            $y++;
            $count++;
            $cellsWin[]=array($x,$y);
        }
        
        $x=$strikeX;
	$y=$strikeY;
	while(($x < 6) && ($y > 0) && ($_SESSION['map'][$y - 1][$x + 1] == $you)){
            $x++;
            $y--;
            $count++;
            $cellsWin[]=array($x,$y);
	}
	
	if($count>3){
            $wins=true;
	}
	
	//diagonale /
        if(!$wins){
	    
            $count=1;
            $x=$strikeX;
            $y=$strikeY;	
            $cellsWin =array();
	    $cellsWin[]=array($x ,$y);
            while(($x < 6) && ($y < 5) && ($_SESSION['map'][$y + 1][$x + 1 ] == $you)){
                $x++;
                $y++;
                $count++;
                $cellsWin[]=array($x,$y);
            }
            $x=$strikeX;
            $y=$strikeY;
            while(($x > 0) && ($y > 0) && ($_SESSION['map'][$y - 1][$x - 1 ] == $you)){
                $x--;
                $y--;
                $count++;
                $cellsWin[]=array($x,$y);
            }
            if($count>3){
                $wins=true;
            }
	}
	
	
	//horizontale
	if(!$wins){
            $count=1;
            $x=$strikeX;
            $y=$strikeY;
            $cellsWin =array();
	    $cellsWin[]=array($x ,$y);
            while(($x < 6) && ($_SESSION['map'][$y][$x + 1 ] == $you)){
                $x++;
                $count++;
                $cellsWin[]=array($x,$y);
                
            }
            $x=$strikeX;
            while(($x >0) && ($_SESSION['map'][$y][$x - 1 ] == $you)){
                $count++;
                $x--;
                $cellsWin[]=array($x,$y);
            }
            if($count>3){
                $wins=true;
            }
	}
	
        //verticale
	if(!$wins){
            $count=1;
            $x=$strikeX;
            $y=$strikeY;
            
            $cellsWin =array();
	    $cellsWin[]=array($x ,$y);
            
            while(($y < 5) && ($_SESSION['map'][$y + 1 ][$x] == $you)){
                $y++;
                $count++;
                 $cellsWin[]=array($x,$y);
            }
            
            $y=$strikeY;
            while(($y >0) && ($_SESSION['map'][$y - 1][$x] == $you)){
                $count++;
                $y--;
                $cellsWin[]=array($x,$y);
            }
            if($count>3){
                $wins=true;
            }
	}
	

	if($wins){
	  $anwserToJS=array(
	  'continue'	=> 0,
	  'strikeX' 	=> $strikeX,
	  'strikeY'	=> $strikeY,
	  'strikeSymbol'=> $you,
	  'log'		=> $you." ".$currentBotName." joue colonne ". $anwserPlayer." et a gagné",
	  'cellsWin'	=> json_encode($cellsWin)
	  );
	  if($_SESSION['currentPlayer']==1){
            save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],1);
          }else{
            save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],2);
          }
	  
	}else{
	  $anwserToJS=array(
	  'continue'	=> 1,
	  'strikeX' 	=> $strikeX,
	  'strikeY'	=> $strikeY,
	  'strikeSymbol'=> $you,
	  'log'	=> $you." ".$currentBotName." joue colonne ". $anwserPlayer
	  );

	}
      
      }else{
	//reponse non conforme
	$anwserToJS=array(
	  'continue' =>0,
	  'strikeX' 	=> -1,
	  'strikeY'	=> -1,
	  'log'	=> $you." ".$currentBotName." a fait une réponse non conforme, il perd"
	);
        if($_SESSION['currentPlayer']==1){
            save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],2);
          }else{
            save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],1);
          }
      }
      
      echo json_encode($anwserToJS);
      
  
  
      die;
    break;    
  default:
    break;
}