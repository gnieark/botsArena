<?php
function is_it_possible_to_place_ships_on_grid($gridWidth,$gridHeight,$nbShipsSize1,$nbShipsSize2,$nbShipsSize3,$nbShipsSize4,$nbShipsSize5,$nbShipsSize6){
  //return false or true
  //not a perfect solution
  $shipsArea=$nbShipsSize1 + (2 * $nbShipsSize2) + (3 * $nbShipsSize3) + (4 * $nbShipsSize4) + (5 * $nbShipsSize5) + (6 * $nbShipsSize6);
  if( $shipsArea > intval($gridHeight * $gridWidth / 2)){
    return false;
  }
  //longest ship
  for($i=6; $i > 0; $i--){
    $var='nbShipsSize'.$i;
    if($$var > 0){
        $longestShip=$$var;
        break;
    }
  }
  if( (!isset($longestShip))
       OR(($longestShip > $gridWidth) && ($longestShip > $gridHeight))
  ){
    return false;
  }
  return true;
}
function get_Post_Params($ccbotsCount){
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

function generate_numeric_select($start,$end,$selected,$name,$id){
    $out="<select";
    if($name !== ""){
        $out.=' name="'.$name.'"';
    }
    if($id !== ""){
        $out.=' id="'.$id.'"';
    }
    $out.=">";
    
    if($selected == -1){
        for($i=$start; $i <= $end; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        }
    }else{
        for($i=$start; $i < $selected; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        } 
        $out.='<option value="'.$selected.'" selected="selected">'.$selected.'</option>';
        for($i=$selected + 1; $i <= $end; $i++ ){
            $out.='<option value="'.$i.'">'.$i.'</option>';
        }
    }
    return $out."</select>";
    
}

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


function place_ship_on_map($x1,$y1,$x2,$y2,$map){
  if ((($x1 <> $x2) && ($y1 <> $y2))
    OR (!isset($map[$y1][$x1]))
    OR (!isset($map[$y2][$x2]))){
      return false;
  }
  if($x1 == $x2){
    //horizontal ship
    if($y1 <= $y2 ){
      $start=$y1;
      $end=$y2;
    }else{
      $start=$y2;
      $end=$y1;
    }
    for($i = $start; $i <= $end; $i++){
      if($map[$i][$x1]==0){
	$map[$i][$x1]=1;
      }else{
	return false;
      }	
    }
    return $map; 
  }
  if($y1 == $y2){
    //vertical ship
    if( $x1 <= $x2){
      $start=$x1;
      $end=$x2;
    }else{
      $start=$x2;
      $end=$x1;
    }
    for( $i = $start; $i <= $end; $i++){
      if( $map[$y1][$i] == 0){
	$map[$y1][$i]=1;
      }else{
	return false;
      }
    }
    return $map;
  }
}