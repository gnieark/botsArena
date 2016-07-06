<?php


function save_draw_bots($arr){
  /*
  * Recursive function who save all combionaisons of draw matches
  */
  
  if(count($arr) < 2){
    return;
  }else{
    $a = $arr[0];
    array_shift($arr);
    foreach($arr as $bot){
      save_battle('tron',$a,$bot,0);
    }
    save_draw_bots($arr);
  }
}
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

class TronPlayer{
  private $url;
  private $name;
  private $tail = array();
  private $direction;
  private $state;
  public function getTail(){
    return $this->tail;
  }
  public function getStatus(){
    return $this->state;
  }
  public function getURL(){
    return $this->url;
  }
  public function getName(){
    return $this->name;
  }
  private function set_direction($newDir){
    //can't be the opposite of the previous direction
    if(
	   (($newDir == "x+") && ($this->direction == "x-"))
	|| (($newDir == "x-") && ($this->direction == "x+"))
	|| (($newDir == "y+") && ($this->direction == "y-"))
	|| (($newDir == "y-") && ($this->direction == "y+"))
     ){
	return false;
    }
    $this->direction = $newDir;
    return true;
  }
  public function getTargetCell($dir){
  
    if($dir == ""){
      $dir = $this->direction;
    }
    if(!$this->set_direction($dir)){
      return false;
    }
    $headCoords = end($this->tail);
    
    switch ($dir){
      case "y+":
	return array($headCoords[0],$headCoords[1]++);
	break;
      case "y-":
	return array($headCoords[0],$headCoords[1]--);
	break;
      case "x+":
	return array($headCoords[0]++,$headCoords[1]);
	break;
      case "x-":
	return array($headCoords[0]--,$headCoords[1]);
	break;
      default:
	return false;
    }
  
  
  }
  
  public function grow($dir=""){
    $this->tail[] = $this->getTargetCell($dir);
  }
  public function loose(){
    $this->state = false;
    $this->tail = array();
    return false;
  }
  public function __construct($id,$initialX,$initialY,$initialDirection){
    $lnBdd = conn_bdd();
    $rs = mysqli_query($lnBdd,
      "SELECT name,url FROM bots WHERE game='tron' AND id='".mysqli_real_escape_string($lnBdd, $id)."';"
    );
    if(($r=mysqli_fetch_row($rs)) && in_array($initialDirection,array('x-','x+','y-','y+'))){
      $this->name = $r[0];
      $this->url = $r[1];
      $this->tail = array(array($initialX,$initialY));
      $this->direction = $initialDirection;
      $this->state= true;
    }else{
      $this->state = false;
    }
  }
}