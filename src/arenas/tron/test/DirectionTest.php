<?php
use PHPUnit\Framework\TestCase;
require_once '../Direction.php';

class Directiontest extends TestCase {
  public function invalidStrings() {
    return array(
      array('jhgjhg'),
      array('X+'),
      array(4)
    );
   }
    /**
      * @dataProvider invalidStrings
      * @expectedException invalidDirectionException
      */
  public function testRejectInvalidString($invalidString){
    Direction::make($invalidString);
  }
  public function validStrings(){
    return array(
      array('x+'),
      array('y+'),
      array('x-'),
      array('y-'),
    );
  }
  
  /**
    * @dataProvider validStrings
    */
  public function testAcceptValidString($validString){
    $this->assertInstanceOf(Direction::class,Direction::make($validString));
  }
  
  /**
    * @dataProvider validStrings
    */
  public function testToString($validString){
    $this->assertTrue(Direction::make($validString) == $validString);
  }
  
  /**
    * @dataProvider validStrings
    */
  public function testOpposite($validString){
    $dir = Direction::make($validString);
    $op = $dir->opposite();
    
    $this->assertInstanceOf(Direction::class,$op);
    $this->assertFalse($dir == $op);
  }
  
  /**
    * @dataProvider validStrings
    */  
  public function testOppositeOpposite($validString){
    $dir = Direction::make($validString);
    $opop = $dir->opposite()->opposite();
    $this->assertTrue($dir == $opop);
  }
  

}