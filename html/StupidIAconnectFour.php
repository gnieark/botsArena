<?php
/*
* stupid IA for battle ship
* choose by random a free column
*/

$grid=json_decode($_POST['grid']);
print_r($grid);
$colAvailable=array();

for($i=0;$i<7;$i++){
  if($colAvailable[4][$i] == ""){
    $colAvailable[]=$i;
  }
}
shuffle($colAvailable);
echo $i;
die;
