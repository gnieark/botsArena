<?php
class AlreadyBeenAddedException extends LogicException { }

class Trail {
  private $trail;

  public function __construct() {
    $this->trail = new SplStack();
  }

  public function last() {
    if($this->trail->isEmpty()){
      return false;
    }
    return $this->trail->top();
  }

  public function emptyTrail(){
     $this->trail = new SplStack();
  }
  public function getTrail(){
    return $this->trail;
  }
  public function mergeWith($trailToMerge){
    if($trailToMerge->getTrail()->isEmpty()) return;
    
    foreach($trailToMerge->getTrail() as $value) {
      $this->trail->push($value);
    }
    
  }
  
  public function add($value) {
    if(!$this->trail->isEmpty()) {
      if(Trail::kind($this->trail->bottom()) !== Trail::kind($value)) {
	return false;
      }

      if($this->contains($value)) {
        //throw new AlreadyBeenAddedException(
        //  'value has already been added to the trail'.$value.'|'.$this->__toString()
        //);
        return false;
      }
    }

    $this->trail->push($value);
  }
  public function __toString(){
    return json_encode($this->getTrailAsArray()); 
  }

  public function getTrailAsArray(){
    $arr = array();
    foreach($this->trail as $coord) {
     $arr[] = array($coord->x,$coord->y);
    }
    return $arr;
  }
  public function contains($searchedValue) {
    foreach($this->trail as $value) {
      if($value == $searchedValue) return true;
    }

    return false;
  }

  public static function kind($var) {
    $type = gettype($var);
    if($type == 'object') $type .= ' ' . get_class($var);
    return $type;
  }
}
