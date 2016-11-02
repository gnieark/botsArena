<?php

class ScoreLap
{

  private $participants; //array containing bots objects
  private $loosers; //those who losed

  
  public function addBotOnLap(TronPlayer $bot){
    $this->participants[] = $bot;
  }
  
  public function addLoser(TronPlayer $bot){
    $this->loosers[] = $bot;
  }
  public function __construct() {
    $this->participants= array();
    $this->loosers = array();
  }
 
 public function getLoosersList(){
  return $this->$loosers;
 }

 private function ApplyDraws(){
  
    //apply draw match to all losers
    if(count($this->looserList) < 2) return;
    
    foreach($loosers as $$looser1){
      foreach($loosers as $looser2){
	if($looser1->playerIndex == $looser2->playerIndex) continue;
	save_battle('tron', $looser1->id, $looser2->id, 0, 'id' );
      }
    }
  }
  
  
 private function ApplyWins(){
  
    //need to make losers List. simply array of orders  
    $loosersIndexArr = array();
    foreach($this->looserList as $looser){
	$loosersIndexArr[] = $looser->playerIndex;
    }
    foreach($this->loosers as $looser){
      foreach($this->participants as $participant){
	if(in_array($participant->playerIndex,$loosersIndexArr)) continue;
	save_battle('tron', $looser->id, $participant->id, 2, 'id');  
      }
    }
  }
  public function ApplyScores(){
    $this-> ApplyDraws();
    $this-> ApplyWins();
  }
}