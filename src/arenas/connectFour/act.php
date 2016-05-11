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
      }else{
	if($_SESSION['currentPlayer']==1){
	  $_SESSION['currentPlayer']=2;
	  $you="O";
	}else{
	  $_SESSION['currentPlayer']=1;
	  $you="X";
	}
      }
  
      //make post datas to send
      $postDatas=array(
	'game'       => 'conectFour',
	 'match_id'  => $_SESSION['matchId']. $_SESSION['currentPlayer'],
	 //'opponent'  => $opponentName,
	 'you'	     => $you,
	 'grid'		=> json_encode( $_SESSION['map'])
	    
      );
      //debug
      print_r($postDatas);
      print_r($_SESSION['bot1']);
      
  
  
      die;
    break;    
  default:
    break;
}