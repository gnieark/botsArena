<?php

class TronPlayer{
  private $url;
  private $name;
  private $tail = array();
  private $direction;
  private $state;

  private function set_direction($newDir){
    //can't be the opposite of the previous direction
    if(
	   (($newDir == "x+") && ($this->direction == "x-"))
	|| (($newDir == "x-") && ($this->direction == "x+"))
	|| (($newDir == "y+") && ($this->direction == "y-"))
	|| (($newDir == "y-") && ($this->direction == "y+"))
     ){
	return false;
    }
    $this->direction = $newdir;
    return true;
  }
  
  public function grow($dir=""){
    if($dir == ""){
      $dir = $this->direction;
    }
    if(!$this->set_direction()){
      return false;
    }
    $headCoords = end($this->tail);
    
    
    switch ($dir){
      case "y+":
	$targetCoords = array($headCoords[0],$headCoords[1]++);
	break;
      case "y-":
	$targetCoords = array($headCoords[0],$headCoords[1]--);
	break;
      case "x+":
	$targetCoords = array($headCoords[0]++,$headCoords[1]);
	break;
      case "x-":
      $targetCoords = array($headCoords[0]--,$headCoords[1]);
	break;
      default:
	return false;
      
    }
    $this->tail[] = $targetCoords;

  }
  
  
  public function __construct($id,$initialX,$initialY,$initialDirection){
    $lnBdd = conn_bdd();
    $rs = mysqli_query($lnBdd,
      "SELECT name,url FROM bots WHERE game='tron' AND id='".mysqli_real_escape_string($lnBdd, $id)."';"
    );
    if(($r=mysqli_fetch_row($rs)) && in_array($initialDirection,array('x-','x+','y-','y+'))){
      $this->name = $r[0];
      $this->url = $r[1];
      $this->tail = array(array($initialX,$initialY));
      $this->direction = $initialDirection;
      $this->state= true;
    }else{
      $this->state = false;
    }
  
  
  
  }
}