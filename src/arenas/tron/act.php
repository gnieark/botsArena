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
    $bots=get_Bots_Array('tron');
    $botsArray = json_decode($_POST['bots']);

    $bot1Exists = false;
    $bot2Exists = false;
    foreach($bots as $bot){
	if($bot['id'] == $_POST['bot1']){
	    $_SESSION['bots'][]=new TronPlayer($bot['id'],500,10,'y+');
	    $bot1Exists =true;
	}
	if($bot['id'] == $_POST['bot2']){
	    $_SESSION['bots'][]=new TronPlayer($bot['id'],500,989,'y-');
	    $bot2Exists =true;
	}
	if ($bot1Exists && $bot2Exists){
	    break;
	} 
    }
    if ((!$bot1Exists) OR (!$bot2Exists)){
	error (500,"missing parameter 2");
    }
    
  
  
  
    break;
  default:
    break;

}