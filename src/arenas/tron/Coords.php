<?php
class Coords{
  public $x;
  public $y;
  public function __construct(int $x = 0, int $y = 0) {
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