<?php

class ScoreLap
{
  private $playersIdsByOrder; //array containing id's of alive players at the beginning of the match, Put only alive bots in!
  private $looserList; //idem but only loosers

  public function addBotOnLap($order,$id){
    $this->playersIdsByOrder[$order] = $id;
  }
  
  public function addLoser($order,$id){
    $this->looserList[$order] = $order;
  }
  public function __construct() {
    $this->playersIdsByOrder = array();
    $this->looserList = array();
  }
 
 public function getLoosersList(){
  return $this->looserList;
 }
 
 //NO!
 // public function make($botsList){
 //   //$botsList must be like array[{botOrder:BotId},{botOrder:BotId}]
 //   $this->looserList = $botsList;
 // }
  
  private function ApplyDraws(){
    //apply draw match to all losers
    if(count($this->looserList) > 1){ //no draw if only 0 or one user at this lap
      foreach($looserList as $order1 => $looser1){
	foreach($looserList as $order2 => $looser2){
	  if($order1 <> $order2){
	    save_battle('tron',
			$this->playersIdsByOrder[$looser1],
			$this->playersIdsByOrder[$looser2],
			0,
			'id');
	  }		      
	}
      }
    } 
  }
  private function ApplyWins(){
  
    //need to make losers List. simply array of orders  
    $loosersOrdersArr = array();
    foreach($this->looserList as $order => $looser){
	$loosersOrdersArr[] = $order;
    }
  
    foreach($this->looserList as $looserOrder => $looserId){
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