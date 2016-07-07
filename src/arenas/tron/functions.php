<?php
include ("class.TronGame.php");
include ("class.TronPlayer.php");
function save_draw_bots($arr){
  /*
  * Recursive function who save all combionaisons of draw matches
  */
  
  if(count($arr) < 2){
    return;
  }else{
    $a = $arr[0];
    array_shift($arr);
    foreach($arr as $bot){
      save_battle('tron',$a,$bot,0);
    }
    save_draw_bots($arr);
  }
}


