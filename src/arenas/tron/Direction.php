<?php
class Direction
{
  private static $top = 0;
  private static $bottom = 1;
  private static $left = 2;
  private static $right = 3;
  
  private $value;
  
  public function __construct(){
    $this->value = 0;
  }
  public function __toString(){
    switch ($this->value){
      case Direction::$top:
	return "y+";
	break;
      case Direction::$bottom:
	return "y-";
	break;
      case Direction::$left:
	return "x-";
	break;
      case Direction::$right:
	return "x+";
	break; 
    }
  }
  public function opposite(){
     $opposites = array(
	Direction::$top => Direction::$bottom,
	Direction::$bottom => Direction::$top,
	Direction::$left => Direction::$right,
	Direction::$right => Direction::$left
     );
     
    $opposite = new Direction();
    $opposite->value = $opposites[$this->value];
    return $opposite;
  }
}