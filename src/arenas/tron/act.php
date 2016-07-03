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
    $players = $botCount;
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
	'players'	=> $players,
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
  
    //check for correct game ID
    if($_POST['gameId'] !== $_SESSION['gameId']){
      echo '{"status":"error"}';
      die;
    }
    $bots = unserialize($_SESSION['bots']);
    
    for ($botCount = 0; $botCount < count($bots); $botCount ++){
      $sendMessage = array(
	'game-id'	=>  ''
      );
    }
    print_r($_SESSION);
  
  
    die;
    break;
  default:
    break;

}