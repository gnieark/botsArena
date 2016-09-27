<?php
use PHPUnit\Framework\TestCase;
require_once '../Coords.php';
require_once '../Direction.php';

class CoordsTest extends TestCase {

 
  public function testCirculaire(){
    $startCoord = new Coords(15,3);
    $endCoord = $startCoord->addDirection(Direction::make('x+'))
                           ->addDirection(Direction::make('y-'))
                           ->addDirection(Direction::make('x-'))
                           ->addDirection(Direction::make('y+'));
                           
  
  
    $this->assertTrue($endCoord == $startCoord);
  }
  public function testIsDifferent(){
    $startCoord = new Coords(15,3);
    $endCoord = $startCoord->addDirection(Direction::make('x+'));
    fwrite(STDERR, $startCoord ."\n");
    fwrite(STDERR, $endCoord ."\n");
    $this->assertFalse($endCoord == $startCoord);
  }
}