<?php
class TronPlayer{
  public $url;
  public $id;
  public $name;
  public $trail;
  public $isAlive = true;
  public $playerIndex = -1; //if unset is -1
  public $looseCause = "";
  public $nextDir;
    
  public function grow(Direction $dir){
   $dest = $this->trail->last()->addDirection($dir);
   if($dest === false){
	$this->loose();
	return false;
    }
    
    $this->trail->add($this->trail->last()->addDirection($dir));
    return $this->trail->last();
  }
  
  public function applyNextDir(){
    $this-> grow($this->nextDir);
  }
  
  public function loose(){
   $this->isAlive = false;
   $this->trail->emptyTrail();
   //error_log($this->name." a perdu");
    return false;
  }
  public function make($botId, Coords $initialsCoords,$name,$url,$playerIndex = -1){
    $this->id = $botId;
    $this->trail = new Trail;
    $this->trail->add($initialsCoords);
    $this->name = $name;
    $this->url = $url;
    $this->state = true;
    $this->playerIndex = $playerIndex;
  }
  public function __construct(){
    $this->state = false; 
  }
}