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
    
    $_SESSION['bots'] = array();
    $positions = array();
    $botCount = 0;
    foreach($botsArrayTemp as $botId){
      do{
	  $x = rand(1,999);
	  $y = rand(1,999);
      }while(in_array($x.",".$y,$positions));
      
      $positions[] = $x.",".$y;
      $_SESSION['bots'][$botCount] =  new TronPlayer($bot['id'],500,10,'y+');
      
      if  ($_SESSION['bots'][$botCount]->getStatus() === false){
       unset($_SESSION['bots'][$botCount]);
      }else{
	$botCount++;
      }
      
    }

    if ($botCount < 2){
	error (500,"missing bots");
    }
    
  
  
  
    break;
  default:
    break;

}