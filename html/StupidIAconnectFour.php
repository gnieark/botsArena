<?php
/*
* stupid IA for battle ship
* choose by random a free column
*/

$grid=json_decode($_POST['grid']);

$colAvailable=array();

for($i=0;$i<7;$i++){
  if($grid[5][$i] == ""){
    $colAvailable[]=$i;
  }
}
shuffle($colAvailable);
echo $i;
die;
