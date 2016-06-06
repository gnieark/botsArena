<?php
function get_Post_Params($botsCount){
      $keysBots=array('bot1','bot2');
      foreach($keysBots as $botKey){
	if(!isset($_POST[$botKey])){
	  return false;
	}
	if(!is_numeric(($_POST[$botKey]))){

	}
	if(($_POST[$botKey] < 0) OR ($_POST[$botKey] > $botsCount)){
	  error(400,"wrong parameters");
	  die;
	}
      }
      return array('bot1' => $_POST['bot1'],'bot2' => $_POST['bot2']);
}




/*
function get_Bots_Array(){
  //Recupérer la liste des Bots
  $bots=array();
  $botsList=explode("\n",file_get_contents(__DIR__."/listOfBots.txt"));
  
  foreach($botsList as $botLigne){
    if(preg_match("/\ (http|https):\/\//", $botLigne)){
      list($name,$url)=explode(" ",$botLigne);
      $bots[]=array("name" => $name, "url" =>$url);
    }
  }
  return $bots;
}
*/
function get_IA_Response($iaUrl,$postParams){
    //send params JSON as body

    $data_string = json_encode($postParams);
   
    $ch = curl_init($iaUrl);                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );
    $output= curl_exec($ch);
    curl_close($ch); 
    echo $output;
    return json_decode($output,TRUE);
}