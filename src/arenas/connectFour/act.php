<?php

require_once(__DIR__."/functions.php");
switch ($_POST['act']){

  case "newfight":
    //initialiser la map
    $_SESSION['map']=array(
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
      array("","","","","","",""),
    );
    
    
    echo json_encode($_SESSION['map']);
  case "fight":
  
      die;
    break;    
  default:
    break;
}