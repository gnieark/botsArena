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
