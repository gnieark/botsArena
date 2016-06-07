<?php
/*
* stupid IA for battle ship
* choose by random a free column
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST'); 
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

$in=file_get_contents('php://input');
$params=json_decode($in, TRUE);
$grid=$params['board'];
$colAvailable=array();

for($i=0;$i<7;$i++){
  if($grid[5][$i] == ""){
    $colAvailable[]=$i;
  }
}
shuffle($colAvailable);
echo "{'play':'".$colAvailable[0]."'}";
die;
