<?php


function save_draw_bots($arr){
  /*
  * Recursive function who save all combionaisons of draw matches
  */
  
  if(count($arr) < 2){
    return;
  }else{
    $a = $arr[0];
    array_shift($arr)
    foreach($arr as $bot){
      save_battle('tron',$a,$bot,0);
    }
    save_draw_bots($arr);
  }
}
class TronPlayer{
  private $url;
  private $name;
  private $tail = array();
  private $direction;
  private $state;
  public function getTail(){
    return $this->tail;
  }
  public function getStatus(){
    return $this->state;
  }
  public function getURL(){
    return $this->url;
  }
  public function getName(){
    return $this->name;
  }
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
  
  public function loose(){
    $this->state = false;
    $this->tail = array();
    return false;
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