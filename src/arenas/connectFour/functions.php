<?php
function get_IA_Response($iaUrl,$postParams){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $iaUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);   
    return htmlentities($output);
}
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