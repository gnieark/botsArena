<?php
/*
* stupid IA for battle ship
* choose by random a free column
*/
$in=file_get_contents('php://input');
$params=json_decode($in, TRUE);
$grid=$params['grid'];
$colAvailable=array();

for($i=0;$i<7;$i++){
  if($grid[5][$i] == ""){
    $colAvailable[]=$i;
  }
}
shuffle($colAvailable);
echo $colAvailable[0];
die;
