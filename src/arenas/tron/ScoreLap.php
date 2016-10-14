<?php

class ScoreLap
{
  private $playersIdsByOrder; //array containing id's of alive players at the beginning of the match, Put only alive bots in!
  private $looserList; //only containing theirs orders

  public function addBotOnLap($order,$id){
    $this->playersIdsByOrder[$order] = $id;
  }
  
  public function addLoser($order){
    $this->looserList[] = $order;
  }
  public function __construct() {
    $this->playersIdsByOrder = array();
    $this->looserList = array();
  }
  public function make($botsList){
    //$botsList must be like array[{botOrder:BotId},{botOrder:BotId}]
    $this->looserList = $botsList;
  }
  
  private function ApplyDraws(){
    //apply draw match to all losers
    if(count($this->looserList) > 1){ //no draw if only 0 or one user at this lap
      foreach($looserList as $looser1){
	foreach($looserList as $looser2){
	  save_battle('tron',
		      $this->playersIdsByOrder[$looser1],
		      $this->playersIdsByOrder[$looser2],
		      0,
		      'id');
	}
      }
    } 
  }
  private function ApplyWins(){
    foreach($this->looserList as $looser){
      foreach($playersIdsByOrder as $order=>$player){
	if(!in_array($order,$this->looserList)){
	  	  save_battle('tron',
		      $this->playersIdsByOrder[$looser],
		      $player,
		      2,
		      'id');  
	}
      }
    }
  
  }
  public function ApplyScores(){
    $this-> ApplyDraws();
    $this-> ApplyWins();
  
  }
}