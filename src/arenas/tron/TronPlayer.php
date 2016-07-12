<?php
class TronPlayer{
  private $url;
  public $id;
  public $name;
  public $tail;
  private $direction;
  
  public $isAlive = true;
    
  public function grow($dir=""){
    $targetCell = $this->getTargetCell($dir);
    $this->tail[] = $targetCell;
    return $targetCell;
  }
  
  public function loose(){
   
   $this->isAlive = false;
   // $this->tail = array();
    return false;
  }
  public function __make($botId, Coords $initialsCoords,$name,$url){
    $this->id = $botId;
    $this->tail = Tail::make($initialsCoords);
    $this->name = $name;
    $this->url = $url; 
  }
  public function __construct(){
    $this->state = true; 
  }
  /*
  public function __construct($id,$initialX,$initialY,$initialDirection){
    $lnBdd = conn_bdd();
    $rs = mysqli_query($lnBdd,
      "SELECT name,url FROM bots WHERE game='tron' AND id='".mysqli_real_escape_string($lnBdd, $id)."';"
    );
    if(($r=mysqli_fetch_row($rs)) && in_array($initialDirection,array('x-','x+','y-','y+'))){
      $this->id = $id;
      $this->name = $r[0];
      $this->url = $r[1];
      $this->tail = array(array($initialX,$initialY));
      $this->direction = $initialDirection;
      $this->state= true;
    }else{
      $this->state = false;
    }
  }
  */
}
?>