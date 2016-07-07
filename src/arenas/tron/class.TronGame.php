<?php
class TronGame{
  private $bots;
  private $gameId;
  public function getBotsPositions(){
    $nbeBots = count($this->bots);
    $arr = array();
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){
      $arr[$botCount] = array(
	"name"	=> $this->bots[$botCount]->getName(),
	"tail"	=> $this->bots[$botCount]->getTail()
      
      );
    }
    return $arr;
  }
  public function getGameId(){
    return $this->gameId;
  }
  public function get_continue(){
    //count bots alive. if less than 1, game is ended
    $count = 0;
    foreach($this->bots as $bot){
      if( $bot->getStatus() == true){
	$count++;
      }
    }
    if($count > 1){
      return true;
    }else{
      return false;
    }
  }
  public function init_game(){
    //send init messages to bots
    $logs = "";
    $nbeBots = count($this->bots);
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){
      $messageArr = array(
	'game-id'	=> "".$this->gameId,
	'action'	=> 'init',
	'game'		=> 'tron',
	'board'		=> '',
	'players'	=> $nbeBots,
	'player-index'	=> $botCount
      );
      
      $resp = get_IA_Response($this->bots[$botCount]->getURL(),$messageArr);
      
      if($_POST['fullLogs'] == "true"){
	$logs.='Arena send to '.$bots[$botCount]->getName().'<em>'.htmlentities($resp['messageSend']).'</em><br/>
	HTTP status: <em>'.htmlentities($resp['httpStatus']).'</em><br/>
	Bot anwser: <em>'.htmlentities($resp['response']).'</em><br/>';
      }else{
	$logs.="Init message send to ".$this->bots[$botCount]->getName()."<br/>";
      }  
    }
    
    return $logs;
  }
  private function getBusyCells(){
    $arr=array();
    foreach($this->bots as $bot){
      $arr = array_merge($arr,$bot->getTail);
    }
    return $arr;
  }
  public function __construct($botsIds){
    
    $this->gameId = get_unique_id();
    $this->bots = array();
    $positions = array();
    $botCount = 0;
    $err = "";
    foreach($botsIds as $botId){
      //find a random start position
      do{
	  $x = rand(1,999);
	  $y = rand(1,999);
      }while(in_array($x.",".$y,$positions));
      
      $positions[] = $x.",".$y;
      $this->bots[$botCount] =  new TronPlayer($botId,$x,$y,'y+');
      
      if  ($this->bots[$botCount]->getStatus() === false){
      
       $err = "Something went wrong for ".$this->bots[$botCount]->getName()."<br/>";
      }else{
	$botCount++;
      }
    }
    return $err;
  }
}
?>