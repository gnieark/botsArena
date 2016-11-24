<?php
class DUEL{
  public $rank1;
  public $rank2;
  
  private $factor = 400;
  
  public function __construct($r1,$r2){
    $this->rank1 = $r1;
    $this->rank2 = $r2;
  }
  private function get_k($rank){
    if ($rank < 1000) return 80;
    if ($rank < 2000) return 50;
    if ($rank <= 2400) return 30;
    return 20;
  }
  private function changeScores($score){
       $this->rank1 = $this->rank1 + $this->get_k($this->rank1) * ($score - (1/ (1 + pow(10,(($this->rank2 - $this->rank1) / $this->factor)))));
       $this->rank2 = $this->rank2 + $this->get_k($this->rank2) * (1 - $score - (1/ (1 + pow(10,(($this->rank1 - $this->rank2) / $this->factor)))));
  }
  
  public function oneWinsAgainstTwo(){
    $this->changeScores(1);
  }
  public function twoWinsAgainstOne(){
    $this->changeScores(0);
  }
  public function drawGame(){
    $this->changeScores(0.5);
  }
}

class ELO
{
  public $rank = 1500; //default rank
  
  public function __construct($v=1500) {
    $this->rank = $v;
  }
  
  private function ELO_get_new_ranks($elo1,$elo2,$score){
    /*
    * return an array containing new ELO scores after a battle
    * $score :  0 player 2 won 
    *           0.5 draws
    *           1 player 1 won 
    */
    
    //good luck for understanding it 
    //(see https://blog.antoine-augusti.fr/2012/06/maths-et-code-le-classement-elo/)
    return array(
        $elo1 + ELO_get_k($elo1) * ($score - (1/ (1 + pow(10,(($elo2 - $elo1) / 400))))),
        $elo2 + ELO_get_k($elo2) * (1 - $score - (1/ (1 + pow(10,(($elo1 - $elo2) / 400)))))
    );
  }
  
  
  
  public function looseAgainst(ELO $winer){
  
  }
  public function winAgainst(ELO $looser){
  
  }
  public function drawAgainst(ELO $drawPlayer){
  
  }
  
}
