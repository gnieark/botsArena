<?php
class Coords{
  private static $min = 0;
  private static $max = 999;

  public $x;
  public $y;

  public function __construct(int $x = 0, int $y = 0) {
    if (($x < Coords::$min) || ($x > Coords::$max) || ($y < Coords::$min) || ($y > Coords::$max)){
      //out of limits
      error_log("a bot out of limits");
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