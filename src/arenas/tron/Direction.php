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

  public $deltaX;
  public $deltaY;
  
  public function __construct(){
    $this->value = 0;
  }
  
  private function setValue($value){
    $this->value = $value;
    switch ($value){
      case Direction::$bottom:
	$this->deltaY = -1;
	$this->deltaX = 0;
	break;
      case Direction::$top:
	$this->deltaY = 1;
	$this->deltaX = 0;
	break;  
      case Direction::$left:
	$this->deltaY = 0;
	$this->deltaX = -1;
	break;
      case Direction::$right:
	$this->deltaY = 0;
	$this->deltaX = 1;
	break;
    }
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
	$dir->setValue(Direction::$right);
	break;
      case "x-":
	$dir->setValue(Direction::$left);
	break;
      case "y+":
	$dir->setValue(Direction::$top);
	break;
      case "y-":
	$dir->setValue(Direction::$bottom);
	break;
      default:
	//error_log("expected 'x+', 'x-', 'y+' or 'y-'". (string)$str."received.");
	return false;
	//throw new InvalidDirectionException("expected 'x+', 'x-', 'y+' or 'y-'". (string)$str."received.");
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
    $opposite->setValue($opposites[$this->value]);
    return $opposite;
  }
}