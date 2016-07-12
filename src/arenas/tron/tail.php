<?php 
class Tail{
  
  public $tail;
  
    
  public function __toString(){
    $str = "";
    foreach(Tail::$tail as $coord){
      $str .= "[".$coord."],"; 
    }
    return $str;
  }
  
  public function __make(Coords $InitialCoords){
    $this->tail = array($InitialCoords);
  }
  
  public function empty_tail(){
    
  }
  
  public function grow(Direction $dir){
    $last = $this->getLastTailCoord();
    $this->tail[] = $last->addDirection($dir);
  }
  
  public function getLastTailCoord(){
    return end(Tail::$tail);
  }
}