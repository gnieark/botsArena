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
require_once ("class.TronGame.php");
require_once ("class.TronPlayer.php");

switch ($_POST['act']){
  case "initGame":
  
    $botsArrayTemp = json_decode($_POST['bots']);
    $botsIds = array();
     //dont take non selected bots (value=0)
    foreach($botsArrayTemp as $bot){
      if($bot > 0){
	$botsIds[] = $bot;
      }
    }
    $game = new TronGame($botsIds);
    $logs = $game->init_game();
    
    echo json_encode(array(
      'status'	=> $game->get_continue(),
      'logs'	=> $logs,
      'gameId'	=> $game->getGameId(),
      'botsPosition' => $game->getBotsPositions()
    ));
    
    $_SESSION['game'] = serialize($game);
  
    
    die;
    break;
  case "play":
    $logs = "";
    if(!isset($_SESSION['game'])){
      echo '{"status":"error"}';
      die; 
    }
    
    $game = unserialize($_SESSION['game']);

    if($game->getGameId() <> $_POST['gameId']){
      //sometimes if an ajax callback is applied after init an other game
      echo '{"status":"error"}';
      die; 
    }
    $game->new_lap();
    
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
	print_r($responses[$botCount]);
	if(in_array($bots[$botCount]->getTargetCell($responses[$botCount]['responseArr']['play']), $busyCells)){
	  //this bot plays on a non empty cell, it looses
	  $bots[$botCount]->loose();
	  $logs.= $bots[$botCount]->getName()." Played on a non empty cell, he loses.<br/>";
	  $loosingBots[] = $bots[$botCount]->getName();
	}else{
	  $targets[] = $bots[$botCount]->getTargetCell($responses[$botCount]['responseArr']['play']);
	}
	
      }
    }
    //test if some bots plays at the same place
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