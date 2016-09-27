<?php
class AlreadyBeenAddedException extends LogicException { }

class Trail {
  private $trail;

  public function __construct() {
    $this->trail = new SplStack();
  }

  public function last() {
    return $this->trail->top();
  }

  public function emptyTrail(){
     $this->trail = new SplStack();
  }
  
  public function add($value) {
    if(!$this->trail->isEmpty()) {
      if(Trail::kind($this->trail->bottom()) !== Trail::kind($value)) {
        throw new TypeError(
          'items added to a trail must be of the same kind'
        );
      }

      if($this->contains($value)) {
        throw new AlreadyBeenAddedException(
          'value has already been added to the trail'
        );
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
