<?php
use PHPUnit\Framework\TestCase;

require_once '../Direction.php';
require_once '../Coords.php';
require_once '../TronPlayer.php';

class TronPlayerTest extends TestCase {
  public function validPlayer(Direction $direction) {
    return new TronPlayer(
      'http://127.0.0.1',
      'test',
      new Coords(0, 0),
      $direction
    );
  }

  public function testTronPlayerCreation() {
    $this->assertInstanceOf(
      TronPlayer::class,
      $this->validPlayer(Direction::make('x+'))
    );
  }

  public function directions() {
    return array(
      array(Direction::make('x+')),
      array(Direction::make('x-')),
      array(Direction::make('y+')),
      array(Direction::make('y-')),
    );
  }

  /**
    * @dataProvider directions
    * @expectedException OppositeForbiddenException
    */
  public function testOppositeForbidden(Direction $direction) {
    $player = $this->validPlayer($direction);
    $player->changeDirection($direction->opposite());
  }

  public function testAlreadyLost() {
    $right = Direction::make('x+');
    $down = Direction::make('y-');
    $left = Direction::make('x-');
    $up = Direction::make('y+');

    $player = $this->validPlayer($right);
    $player->nextMove($right);
    $player->nextMove($down);
    $player->nextMove($left);

    try {
      $player->nextMove($up);
      throw new Exception('TronPlayer did not throw AlreadyPlayedException');
    } catch(AlreadyPlayedException $e) { }

    try {
      $player->nextMove($up);
      throw new Exception('TronPlayer did not throw AlreadyLostException');
    } catch(AlreadyLostException $e) { }
  }
}
