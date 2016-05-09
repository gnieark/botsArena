<?php
require_once(__DIR__."/functions.php");

$bots=get_Bots_Array('connectFour');
$postParams=get_Post_Params(count($bots));
if(!$postParams){
  $bot1="";
  $bot2="";
}else{
  $bot1=$postParams['bot1'];
  $bot2=$postParams['bot2'];
}

?>
    <article>
    <h2>Connect Four</div>
    <div id="fightResult"></div>
</article>
