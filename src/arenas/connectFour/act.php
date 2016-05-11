<?php

require_once(__DIR__."/functions.php");
switch ($_POST['act']){

  case "newFight":
    //initialiser la map
    $_SESSION['map']=array(
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
    );
    
    
    echo "plop".json_encode($_SESSION['map']);
  case "fight":
  
      die;
    break;    
  default:
    break;
}