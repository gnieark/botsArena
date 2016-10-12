<?php
class TronPlayer{
  public $url;
  public $id;
  public $name;
  public $trail;
  private $direction;
  public $isAlive = true;
    
  public function grow(Direction $dir){
   $dest = $this->trail->last()->addDirection($dir);
   if($dest == false){
	$this->loose();
	return false;
    }
    
    $this->trail->add($this->trail->last()->addDirection($dir));
    return $this->trail->last();
  }
  public function loose(){
   $this->isAlive = false;
   $this->trail->emptyTrail();
   //error_log($this->name." a perdu");
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