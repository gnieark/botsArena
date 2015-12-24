<?php
function is_it_possible_to_place_ships_on_grid($gridWidth,$gridHeight,$nbShipsSize1,$nbShipsSize2,$nbShipsSize3,$nbShipsSize4,$nbShipsSize5,$nbShipsSize6){
  //return false or true
  //not a perfect solution
  $shipsArea=$nbShipsSize1 + 2 * $nbShipsSize2 + 3 * $nbShipsSize3 + 4 * $nbShipsSize4 + 5 * $nbShipsSize5 + 6 * $nbShipsSize6;
  if( $shipsArea > $gridHeight * $gridWidth / 2){
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

switch($_POST['act']){
    case "init":
      file_put_contents(__DIR__."/log.txt",print_r($_POST,true));
	$wantedVars=array(
            'match_id'  => false, //false-> string ; true -> integer
            'opponent'  => false,
            'width'     => true,
            'height'    => true,
            'ship1'     => true,
            'ship2'     => true,
            'ship3'     => true,
            'ship4'     => true,
            'ship5'     => true,
            'ship6'     => true
	);
        foreach($wantedVars as $key => $shouldBeInteger){
            if(($shouldBeInteger) && (!is_numeric($_POST[$key]))){
                echo "var is not numeric"; die;
            }
            $$key=$_POST[$key];
        }
        if(!preg_match('/^[0-9]+-(1|2)$/',$match_id)){
	  echo "parametre incorrect"; die;
        }
        
        if(!is_it_possible_to_place_ships_on_grid($width,$height,$ship1,$ship2,$ship3,$ship4,$ship5,$ship6)){
	  echo "I don't want play this game";
	  die;
        }
        
        a:
        $map=array();
        //construire une grille
        for($i=0; $i < $width; $i++){
	  for($j=0; $j < $height; $j++){
	    $map[$j][$i]=0;
	  }
        }
        
        $shipsCoords=array();
        
        //pour toutes les tailles de bateau
        for($shipWidth = 6; $shipWidth > 0; $shipWidth--){
	  //nombre de bateau à placer de cette taille
	  $dynVar='ship'.$shipWidth;
	  $shipCount=$$dynVar; // #trollface
	  for( $sh = 0; $sh < $shipCount; $sh++){ //loop for all boats witch size is $shipWidth
	    //find free cases
	    $freeCases=array();
	    for($y=0; $y < $height; $y++){
	      for($x=0; $x < $width; $x++){
		if($map[$y][$x] == 0){
		  $directions=array();
		  //test top
		  $top=true;
		  for($i = $y; $i > $y - $shipWidth; $i--){
		    if((!isset($map[$i][$x])) OR ($map[$i][$x]==1)){
		      $top=false;
		      $break;
		    }
		  }
		  if($top){
		    $directions[]='top';
		  }
		  //test Bottom
		  $bottom=true;
		  for($i = $y; $i < $y + $shipWidth; $i++){
		    if(((!isset($map[$i][$x])) OR $map[$i][$x]==1)){
		      $bottom=false;
		      $break;
		    }
		  }
		  if($bottom){
		    $directions[]='bottom';
		  }
		  //test left
		  $left=true;
		  for($i = $x; $i > $x - $shipWidth; $i--){
		    if((!isset($map[$y][$i])) OR ($map[$y][$i]==1)){
		      $left=false;
		      $break;
		    }
		  }
		  if($left){
		    $directions[]='left';
		  }
		  //test right
		  $right=true;
		  for($i = $x; $i < $x + $shipWidth; $i++){
		    if((!isset($map[$y][$i])) OR ($map[$y][$i]==1)){
		      $right=false;
		      $break;
		    }
		  }
		  if($right){
		    $directions[]='right';
		  }
		  
		  if(count($directions)>0){
		   $freeCases[]=array($x,$y,$directions);
		  }
		
		}
	      }
	    }
	    
	    if(count($freeCases) == 0){
	      //can't place the ship
	      goto a; //#facepalm
	    }
	    shuffle($freeCases); //choose start case for this ship
	    shuffle($freeCases[0][2]); //choose random direction
	    $x=$freeCases[0][0];
	    $y=$freeCases[0][1];
	    switch($freeCases[0][2][0]){
                case 'top':
                    $shipsCoords[]=$x.",".$y."-".$x.",".($y - $shipWidth + 1);
		    $map= place_ship_on_map($x,$y,$x,$y - $shipWidth + 1,$map);
                    break;
                case 'bottom':
		    $shipsCoords[]=$x.",".$y."-".$x.",".($y + $shipWidth - 1);
		    $map= place_ship_on_map($x,$y,$x,$y + $shipWidth -1 ,$map);
                    break;
                case 'left':
		    $shipsCoords[]=$x.",".$y."-".($x - $shipWidth + 1).",".$y;
		    $map= place_ship_on_map($x,$y,$x - $shipWidth + 1 ,$y,$map);
                    break;
                case 'right':
		    $shipsCoords[]=$x.",".$y."-".($x + $shipWidth - 1 ).",".$y;
		    $map= place_ship_on_map($x,$y,$x + $shipWidth -1 ,$y,$map);
                    break;
            
            }
	  }
        }

        echo json_encode($shipsCoords);
        file_put_contents(__DIR__."/log.txt",json_encode($shipsCoords),FILE_APPEND);
        break;
    case "fight":
      //for debog arena
      file_put_contents(__DIR__."/log.txt",print_r($_POST,true),FILE_APPEND);

	echo rand(0,$_POST['width'] -1).",".rand(0,$_POST['height'] -1);
      
      
      die;
      break;
    default: 
        break;
    
}
