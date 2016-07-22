<?php
class TronPlayer{
  public $url;
  public $id;
  public $name;
  public $trail;
  private $direction;
  
  public $isAlive = true;
    
  public function grow(Direction $dir){
    $this->trail->add($this->trail->last()->addDirection($dir));
    return $this->trail->last();
  }
  
  public function loose(){
   $this->isAlive = false;
   $this->trail->emptyTrail();
    return false;
  }
  public function make($botId, Coords $initialsCoords,$name,$url){
    $this->id = $botId;
    $this->trail = new Trail;
    $this->trail->add($initialsCoords);
    $this->name = $name;
    $this->url = $url;
    $this->state = true;
  }
  public function __construct(){
    $this->state = false; 
  }
 
}