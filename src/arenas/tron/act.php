<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark https://blog-du-grouik.tinad.fr et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------

//error_log(json_encode($_SESSION,true)."\n\n");

require_once ("TronGame.php");
require_once ("TronPlayer.php");
require_once ("Direction.php");
require_once ("Trail.php");
require_once ("Coords.php");
require_once ("ScoreLap.php");

switch ($_POST['act']){
  case "initGame":
  
    $rs = mysqli_query($lnMysql,"SELECT id,name,url FROM bots WHERE game='tron';");
    while($r = mysqli_fetch_row($rs)){
      $botsFullArr[$r[0]] = array('id' => $r[0], 'name' => $r[1], 'url' => $r[2]);
    }
  
    
    $botsArrayTemp = json_decode($_POST['bots']);
    //error_log($_POST['bots']);
    $botsInfos = array();
    
    foreach($botsArrayTemp as $id){
      //tester si le bot existe dans la bdd
      if((isset($botsFullArr[$id])) && ($id > 0)){
	$botsInfos[] = $botsFullArr[$id];
      }
    }

    $game = new TronGame($botsInfos);
  
    
    $logs = $game->init_game();
    
    echo json_encode(array(
      'status'	=> $game->get_continue(),
      'logs'	=> $logs,
      'gameId'	=> $game->gameId,
      'botsPosition' => $game->get_lasts_trails()
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

    if($game->gameId <> $_POST['gameId']){
      //sometimes if an ajax callback is applied after init an other game
      echo '{"status":"error"}';
      die; 
    }
    $lap = $game->new_lap();
    if($game->get_continue()){
      $continue = 1;
    }else{
      $continue = 0;
    }
    echo json_encode(array(
      'gameId' 	  	=> $game->gameId,
      'continue' 	=> $continue,
      'lap'		=> $lap
    ));
     $_SESSION['game'] = serialize($game);
    die;

    break;
  default:
    break;

}