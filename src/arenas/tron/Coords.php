<?php
class Coords{
  private $min = 0;
  private $max = 999;

  public $x;
  public $y;

  public function __construct(int $x = 0, int $y = 0) {
    if (($x < $this->min) || ($x > $this->max) || ($y < $this->min) || ($y > $this->max)){
      //out of limits
      return false;
    }
  
    $this->x = $x;
    $this->y = $y;
    

  }

  public function __toString(){
    return $this->x.",".$this->y;
    
  }
  
  public function addDirection(Direction $dir){
    return new Coords(
      $this->x + $dir->deltaX,
      $this->y + $dir->deltaY
    );
  }
}