<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------

require_once(__DIR__."/functions.php");


switch ($_POST['act']){
  case "initGame":
  
    //check if bots exists
    $botsArrayTemp = json_decode($_POST['bots']);
    
    $bots = array();
    $positions = array();
    $botCount = 0;
    foreach($botsArrayTemp as $botId){
      do{
	  $x = rand(1,999);
	  $y = rand(1,999);
      }while(in_array($x.",".$y,$positions));
      
      $positions[] = $x.",".$y;
      $bots[$botCount] =  new TronPlayer($botId,$x,$y,'y+');
      
      if  ($bots[$botCount]->getStatus() === false){
       unset($bots[$botCount]);
      }else{
	$botCount++;
      }
      
    }
    $_SESSION['players'] = $botCount;
    if ($botCount < 2){
	error (500,"missing bots");
    }

    $logs="";
    
    //send init message
    $gameId = get_unique_id();
    $responses = array();

    for ($botCount = 0; $botCount < count($bots); $botCount ++){
      $messageArr = array(
	'game-id'	=> "".$gameId,
	'action'	=> 'init',
	'game'		=> 'tron',
	'board'		=> '',
	'players'	=> $_SESSION['players'],
	'player-index'	=> $botCount
      );
      
      $resp = get_IA_Response($bots[$botCount]->getURL(),$messageArr);
      if($_POST['fullLogs'] == "true"){
	$logs.='Arena send to '.$bots[$botCount]->getName().'<em>'.htmlentities($resp['messageSend']).'</em><br/>
	HTTP status: <em>'.htmlentities($resp['httpStatus']).'</em><br/>
	Bot anwser: <em>'.htmlentities($resp['response']).'</em><br/>';
      }else{
	$logs.="Init message send to ".$bots[$botCount]->getName()."<br/>";
      }
      
    }
    
    //save bots on session var
    $_SESSION['bots'] = serialize($bots);
    $_SESSION['gameId'] = $gameId;
    
    echo json_encode(array(
      'status'	=> 'OK',
      'logs'	=> $logs,
      'gameId'	=> $gameId
    ));
    
    die;
    break;
  case "play":
    $logs = "";
    //check for correct game ID
    if($_POST['gameId'] <> $_SESSION['gameId']){
      echo '{"status":"error"}';
      die;
    }
    $bots = unserialize($_SESSION['bots']);
    $board= array(); 
    //make the board array
    for ($botCount = 0; $botCount < count($bots); $botCount ++){
      $board[$botCount] = $bots[$botCount]->getTail();
    }
    
    $busyCells = array(); //les cases prises
    $responses = array();
    $loosingBots = array();
    $targets = array();
    for ($botCount = 0; $botCount < count($bots); $botCount ++){
    
      if($bots[$botCount]->getStatus()){
	$messageArr = array(
	  'game-id'	=>  '',
	  'action'	=> 'play-turn',
	  'game'		=> 'tron',
	  'board'		=> $board,
	  'player-index'	=> $botCount, // To do: verifier que ça restera le même à chaque tour
	  'players'	=> $_SESSION['players']	
	);
	
	$busyCells = array_merge($busyCells, $bots[$botCount]->getTail()); 
	$responses[$botCount] = get_IA_Response($bots[$botCount]->getURL(),$messageArr);
	if(in_array($responses[$botCount]['responseArr'], $busyCells)){
	  //this bot plays on a non empty cell, it looses
	  $bots[$botCount]->loose();
	  $logs.= $bots[$botCount]->getName()." Played on a non empty cell, he loses.<br/>";
	  $loosingBots[] = $bots[$botCount]->getName();
	}else{
	  $targets[] = $responses[$botCount]['responseArr'];
	}
	
      }
    }

    for ($botCount = 0; $botCount < count($bots); $botCount ++){
      if($bots[$botCount]->getStatus()){
	 
      }
     }
    
    
    
    //save pat game betwin loosing bots
     save_draw_bots($loosingBots);
    //find losing bots
    
  
    
    
  
    print_r($targets);
  
  
    die;
    break;
  default:
    break;

}