<?php
class TronGame
{
  private $bots; //array of bots
  public $gameId;
  private $status; //false => Game ended or not initialised

  private function getAliveBots(){
    $aliveBots = array();
    foreach($this->bots as $bot){
      if($bot->isAlive){
	$aliveBots[] = $bot;
      }
    }
    return $aliveBots;
  }  
  
  private function getBotByPlayerIndex($index){
    foreach($this->bots as $bot){
      if($bot->playerIndex == $index){
	return $bot;
      }
    }
    return false;
  }
  private function initScoring(){
    /*
     *Add all alive bots on a ScoreLap object and return it
     */
     $scoring = new ScoreLap();
      foreach($this->getAliveBots()as $bot){
	$scoring->addBotOnLap($bot);
      }
      return $scoring;
  }
  public function get_continue(){
    //count bots alive. if less than 1, game is ended
    if(count($this->getAliveBots()) > 1){
      return true;
    }else{
      return false;
    }
  }
  
  public function get_trails(){
     //return all trails for draw svg
    $trailsArr = array();
    foreach($this->bots as $bot){
      $trailsArr[] = $bot->trail->getTrailAsArray();
    }
    //error_log("*********".json_encode($trailsArr,true)."********");
    return $trailsArr;
    
  }
  private function get_map_as_an_unique_trail(){
    $trail = new Trail;
    foreach($this->bots as $bot){
      $trail->mergeWith($bot->trail);
    }
    return $trail;
  
  }
  public function get_lasts_trails(){
    //return only the lasts coords for each tail
    $trailsArr = array();
    foreach($this->bots as $bot){
      $trailsArr[] = $bot->trail->last();
    }
    return $trailsArr;
  }
  public function new_lap(){
  
    if($this->get_continue() === false){
      return false;
    }
  
    $scoreObj = $this->initScoring();
    $aliveBots = $this->getAliveBots();
    
    //fixed Query parameters
    $nbeBots = count($this->bots); 
    $board = $this->get_trails(); //same for each bot
    $initialMapAsATrail = $this->get_map_as_an_unique_trail();
    
    //Open curl multi
    $cmh = curl_multi_init();
    $ch = array();
    

    
    foreach($aliveBots as $bot){
      $i = $bot->playerIndex; //because $i is shorter
      
      $bodyRequestArr[$i] = array(
	  'game-id'		=>  "".$this->gameId,
	  'action'		=> 'play-turn',
	  'game'		=> 'tron',
	  'board'		=> $board,
	  'player-index'	=> $i,
	  'players'		=> $nbeBots
	);
	$data_string = json_encode($bodyRequestArr[$i]);
	$ch[$i] = curl_init($bot->url);                                                                      
	curl_setopt($ch[$i], CURLOPT_CUSTOMREQUEST, "POST"); 
	curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch[$i], CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch[$i], CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_multi_add_handle($cmh,$ch[$i]);	
    }

     //send the requests
    do {
      $returnVal = curl_multi_exec($cmh, $runningHandles);
    }while($runningHandles > 0);
  
    //get results
    foreach($ch as $playerIndex=>$cr){
      $currentBot = $this->getBotByPlayerIndex($playerIndex);
    
       // Check for errors
      $curlError = curl_error($cr);
      $response = curl_multi_getcontent($cr);
      
      if($curlError !== "") {
      
	//erreur curl, he loses
	$scoreObj-> addLoser($currentBot);
	$currentBot->loose();
	error_log("no curl response".$playerIndex); //debug
	
      }elseif(! $arr = json_decode($response,TRUE)){
      
	//la reponse n'est pas un json, il a perdu
	$scoreObj-> addLoser($currentBot);
	$currentBot->loose();
	error_log("la reponse est pas JSON".$playerIndex); //debug
	
      }elseif(Direction::make($arr['play']) === false){
      
	//tester ici la réponse
	 //he loose il utilise probablement une de ses propres cases
	  $scoreObj-> addLoser($currentBot);
	  $currentBot->loose();
	  error_log("La reponse ne contient pas une direction".$playerIndex); //debug
	  
      }elseif($initialMapAsATrail->contains($currentBot->trail->last()->addDirection(Direction::make($arr['play'])))){ //ounch
      
	  //le bot tente d'aller sur une case qui était prise au début du round
	     $scoreObj-> addLoser($currentBot);
	     $currentBot->loose();
	     error_log("Il joue sur une case deja prise".$playerIndex); //debug
      }else{
	    //mettre de coté la direction du bot
	    $currentBot->nextDir = Direction::make($arr['play']);
      }
      //close curl
      curl_multi_remove_handle($cmh, $cr);
      curl_close($cr); 
    
    }
    
    
    $lastLoosers = array();
    $aliveBots = $this->getAliveBots(); 
    //pour tous les bots encore vivants, on teste si deux d'entre eux ne cibleraient pas la même case
    foreach ($aliveBots as $bot1){
      foreach ($aliveBots as $bot2){
	if($bot1->playerIndex == $bot2->playerIndex) continue;
	if($bot1->trail->last()->addDirection($bot1->nextDir) == $bot2->trail->last()->addDirection($bot2->nextDir)){
	  //he loose
	  $scoreObj-> addLoser($bot1);
	  $lastLoosers[] = $bot1;
	  //$bot1->loose(); 
	  break;
	}
      }
    }
    foreach($lastLoosers as $bot){
      $bot->loose();
    }
    
    //ok, faire grossir les bots qui restent
    foreach($this->getAliveBots() as $bot){
      $bot-> applyNextDir();
    }
    
    //apply scores:
    $scoreObj-> ApplyScores();
    
    return array(
      'last_points'	=> $this->get_lasts_trails(),
      'loosers'		=> $scoreObj->getLoosersList()
     );
  }

  
  
 
   
  public function init_game(){
      //send init messages to bots
      $logs = "";
      $fullLogs = "";
      $nbeBots = count($this->bots);
      for ($botCount = 0; $botCount < $nbeBots; $botCount++){
	$messageArr = array(
	  'game-id'		=> "".$this->gameId,
	  'action'		=> 'init',
	  'game'		=> 'tron',
	  'board'		=> '',
	  'players'		=> $nbeBots,
	  'player-index'	=> $botCount
	);      
	
	$resp = get_IA_Response($this->bots[$botCount]->url,$messageArr);
	$fullLogs .= 'Arena send to '.$this->bots[$botCount]->name.'<em>'.htmlentities($resp['messageSend']).'</em><br/>
	  HTTP status: <em>'.htmlentities($resp['httpStatus']).'</em><br/>
	  Bot anwser: <em>'.htmlentities($resp['response']).'</em><br/>';
	$logs.="Init message send to ".$this->bots[$botCount]->name."<br/>";    
      }
      return array($logs,$fullLogs);
    }
    
  public function __construct($botsInfos){    
    $this->gameId = get_unique_id();
    $this->bots = array();
    $positions = array();
    $botCount = 0;
    $err = "";
    
    //print_r($botsInfos);
    
    foreach($botsInfos as $bot){
      //find a random start position
      do{
	  $x = rand(1,99);
	  $y = rand(1,99);
      }while(in_array($x.",".$y,$positions));
      
      $positions[] = $x.",".$y;
      $startCoord = new Coords($x,$y);
  
      $this->bots[$botCount] =  new TronPlayer();
      $this->bots[$botCount]->make($bot['id'],$startCoord,$bot['name'],$bot['url'],$botCount);
      
      if  ($this->bots[$botCount]->isAlive === false){
	$err .= "Something went wrong for ".$this->bots[$botCount]->getName()."<br/>";
      }else{
	$botCount++;
      }
    }
    return $err;
  }
}