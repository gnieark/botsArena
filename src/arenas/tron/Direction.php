<?php
class InvalidDirectionException extends UnexpectedValueException{

}
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
  public static function make($str){
    $dir = new Direction();
    switch((string)$str){
      case "x+":
	$dir->value = Direction::$right;
	break;
      case "x-":
	$dir->value = Direction::$left;
	break;
      case "y+":
	$dir->value = Direction::$top;
	break;
      case "y-":
	$dir->value = Direction::$bottom;
	break;
      default:
	throw new InvalidDirectionException("expected 'x+', 'x-', 'y+' or 'y-'". (string)$str."received.");
	break;
	
    }
    return $dir;
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