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
  
  private function apply_looses($loosersArr){    
    //save draws
    if( count($loosersArr) > 1 ){
      $loosersById = array();
      foreach($loosersArr as $bot){
	$loosersById[] = $this->bots[$bot]->id;
      }
      $this->save_draw_bots($loosersById);
    }
    
    //save victories
    if( count($loosersArr) > 0 ){
      //make victorous bots array
      $vbots = array();
      for ($botCount = 0; $botCount < $nbeBots; $botCount++){ 
	if($this->bots[$botCount]->isAlive){
	  $vbots[] = $this->bots[$botCount]->id;
	}
      }
      $this->save_losers_winers($loosersById,$vbots);
    }
    
  }
  private function save_draw_bots($arr){
    /*
    * Recursive function who save all combionaisons of draw matches
    */
    
    if(count($arr) < 2){
      return;
    }else{
      $a = $arr[0];
      array_shift($arr);
      foreach($arr as $bot){
	save_battle('tron',$a,$bot,0,'id');
      }
      $this->save_draw_bots($arr);
    }
  }
  
  private function save_losers_winers($arrLoosers,$arrWiners){
    foreach($arrWiners as $winner){
      foreach($arrLoosers as $loser){
	save_battle('tron',$winer,$loser,1,'id');
      }
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
  public function new_lap(){
    // for all alive bots
    $logs = "";
    $nbeBots = count($this->bots);
    $urls = array();
    $paramToSend = array();
    $board = $this->get_trails();
    $loosers = array();
    $lastsCells = array();
    
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){  
      if  ($this->bots[$botCount]->isAlive){
      
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
    //print_r($responses);
      //grow bots'tails
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){
      if  ($this->bots[$botCount]->isAlive){
      
	$dir = Direction::make($responses[$botCount]['responseArr']['play']);
	
	$lastsCells[$botCount] = $this->bots[$botCount]->grow($dir);
      }
    }
    
    
    //test if loose
    for ($botCount = 0; $botCount < $nbeBots; $botCount++){
      if  ($this->bots[$botCount]->isAlive){
	for( $botCount2 = 0; $botCount2 < $nbeBots; $botCount2++){
      
	  if(($botCount <> $botCount2)
	      && ($this->bots[$botCount2]->trail->contains($lastsCells[$botCount]))
	  ){
	    $loosers[] = $botCount;
	    $this->bots[$botCount]->loose();
	    break;
	 }
	 
      }
      }
    }
    
    return $this->get_trails();
    
    
  }

  
  private function get_multi_IAS_Responses($iasUrls, $postParams){
    //same as the get_IAS_Responses function
    // but more than one bot requested parallely
    
    $cmh = curl_multi_init();
    for ($i = 0; $i < count($iasUrls); $i++){
	$data_string = json_encode($postParams[$i]);
    
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
    //send the requests
    do {
      $returnVal = curl_multi_exec($cmh, $runningHandles);
    } while ($returnVal == CURLM_CALL_MULTI_PERFORM);
    // Loop and continue processing the request
    while ($runningHandles && $returnVal== CURLM_OK) {
	// Wait forever for network
	$numberReady = curl_multi_select($cmh);
	if ($numberReady != -1) {
	  // Pull in any new data, or at least handle timeouts
	  do {
	    $returnVal = curl_multi_exec($cmh, $runningHandles);
	  } while ($returnVal == CURLM_CALL_MULTI_PERFORM);
	}
      }
      
    //Get results
      for ($i = 0; $i < count($iasUrls); $i++){
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
	  'game-id'	=> "".$this->gameId,
	  'action'	=> 'init',
	  'game'		=> 'tron',
	  'board'		=> '',
	  'players'	=> $nbeBots,
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