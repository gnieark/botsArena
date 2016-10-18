<?php
class TronGame
{
  private $bots; //array of bots
  public $gameId;
  private $status; //false => Game ended or not initialised
  
  public function get_continue(){
    //count bots alive. if less than 1, game is ended
    $count = 0;
    foreach($this->bots as $bot){
      if( $bot->isAlive == true){
	$count++;
      }
    }
    if($count > 1){
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
    return $trailsArr;
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
    // for all alive bots
    $logs = "";
    $nbeBots = count($this->bots);
    $urls = array();
    $paramToSend = array();
    $board = $this->get_trails();
    //$board = $this->get_lasts_trails();
    $lastsCells = array();
    
    $scoring = new ScoreLap();
    
    
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){  
      if  ($this->bots[$botCount]->isAlive){
	
	$scoring->addBotOnLap($botCount,$this->bots[$botCount]->id);
	$urls[$botCount] = $this->bots[$botCount]->url;
	
	$paramsToSend[$botCount] = array(
	  'game-id'		=>  "".$this->gameId,
	  'action'		=> 'play-turn',
	  'game'		=> 'tron',
	  'board'		=> $board,
	  'player-index'	=> $botCount, // To do: verifier que ça restera le même à chaque tour
	  'players'		=> $nbeBots
	);
      }
    }
    
    $responses = $this->get_multi_IAS_Responses($urls,$paramsToSend);

    //grow bots'tails
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){
      if  ($this->bots[$botCount]->isAlive){
      
	if(!$dir = Direction::make($responses[$botCount]['responseArr']['play'])){
	  //he loses , non conform response
	    $scoring-> addLoser($botCount,$this->bots[$botCount]->id);
	    $this->bots[$botCount]->loose();
	}else{
	
	  $lastsCells[$botCount] = $this->bots[$botCount]->grow($dir);
	  
	  if($lastsCells[$botCount] === false){
	    //$loosers[] = $botCount;
	    $scoring-> addLoser($botCount,$this->bots[$botCount]->id);
	    $this->bots[$botCount]->loose();
	  }
	  
	}
      }
    }
    //test if loose
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){ 
      if  ($this->bots[$botCount]->isAlive){

	//tester si collusion avec les cases actuelles
	for( $botCount2 = 0; $botCount2 < $nbeBots; $botCount2++){   
	  if(($botCount <> $botCount2)
	      && ($this->bots[$botCount2]->trail->contains($lastsCells[$botCount]))
	  ){
	  
	    $scoring-> addLoser($botCount,$this->bots[$botCount]->id);
	    $this->bots[$botCount]->loose();
	    break;
	 } 
	}
      }
    }
    
    //$this->apply_looses($loosersList);
    return array(
      'last_points'	=> $this->get_lasts_trails(),
      'loosers'		=> $scoring->getLoosersList()
     );
  }
  private function get_multi_IAS_Responses($iasUrls, $postParams){
    //same as the get_IAS_Responses function
    // but more than one bot requested parallely
    
    $cmh = curl_multi_init();
    for ($i = 0; $i < count($iasUrls); $i++){
	  if(isset($postParams[$i])){ //dont use already deads bots
	      $data_string = json_encode($postParams[$i]);
	      
	      //error_log($data_string);
	      
	      $ch[$i] = curl_init($iasUrls[$i]);                                                                      
	      curl_setopt($ch[$i], CURLOPT_CUSTOMREQUEST, "POST"); 
	      curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, false);
	      curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, false);
	      curl_setopt($ch[$i], CURLOPT_POSTFIELDS, $data_string);                                                                  
	      curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, true);                                                                      
	      curl_setopt($ch[$i], CURLOPT_HTTPHEADER, array(                                                                          
		  'Content-Type: application/json',                                                                                
		  'Content-Length: ' . strlen($data_string))                                                                       
	      );
	      curl_multi_add_handle($cmh,$ch[$i]);
	   }
    }
    
    //send the requests
    do {
      $returnVal = curl_multi_exec($cmh, $runningHandles);
    }while($runningHandles > 0);
    
     
    //Get results
     
    for ($i = 0; $i < count($iasUrls); $i++){
    
      if(isset($postParams[$i])){
	  // Check for errors
	  $curlError = curl_error($ch[$i]);
	  if($curlError == "") {
	    $response = curl_multi_getcontent($ch[$i]);
	    if(! $arr = json_decode($response,TRUE)){
		$arr=array();
	      }
	    $res[$i] = array(
	      'messageSend' 	=> json_encode($postParams[$i]),
	      'response'		=> $response,
	      'httpStatus'	=> curl_getinfo($ch[$i])['http_code'],
	      'responseArr'	=> $arr   
	    ); 
	    
	  }else{
	    $res[$i] = false;
	  }
	  //close
	  curl_multi_remove_handle($cmh, $ch[$i]);
	  curl_close($ch[$i]);
	
	}
	
      }
      // Clean up the curl_multi handle
      curl_multi_close($cmh);
      return $res;
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
	  $x = rand(1,999);
	  $y = rand(1,999);
      }while(in_array($x.",".$y,$positions));
      
      $positions[] = $x.",".$y;
      $startCoord = new Coords($x,$y);
  
      $this->bots[$botCount] =  new TronPlayer();
      $this->bots[$botCount]->make($bot['id'],$startCoord,$bot['name'],$bot['url']);
      
      if  ($this->bots[$botCount]->isAlive === false){
	$err .= "Something went wrong for ".$this->bots[$botCount]->getName()."<br/>";
      }else{
	$botCount++;
      }
    }
    return $err;
  }
}