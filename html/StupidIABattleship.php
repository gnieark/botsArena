<?php
function place_ship_on_map($x1,$y1,$x2,$y2,$map){
  if (($x1 <> $x2) && ($y1 <> $y2)){
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
      $map[$i][$x1]=1;
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
      $map[$y1][$i]=1;
    }
    return $map;
  }

}
switch($_POST['act']){
    case "init":
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
             $directions=array();
             while( count($directions) == 0){
                do{
                    $xtest=rand(0,$width -1);
                    $ytest=rand(0,$height -1);
                }while($map[$ytest][$xtest] == 1);
                
                //Y a t'il la place pour le bateau vers le haut?

                //haut
                $top=true;
                for($i = $ytest; $i >= $ytest - $shipWidth + 1; $i--){
                    if ((!isset($map[$i][$xtest]))
                    OR ($map[$i][$xtest] == 1)){
                        $top=false;
                        break;
                    }
                }
                
                //vers le bas
                $bottom=true;
                for($i = $ytest; $i < $ytest + $shipWidth -1; $i++){
                    if ((!isset($map[$i][$xtest]))
                    OR ($map[$i][$xtest] == 1)){
                        $bottom=false;
                        break;
                    }
                }
                
                //droite
                $right=true;
                for($i = $xtest; $i < $xtest + $shipWidth -1; $i++){
                    if((!isset($map[$ytest][$i]))
                    OR($map[$ytest][$i] == 1)){
                        $right= false;
                        break;
                    }
                }
                
                //gauche
                $left=true;
                for($i = $xtest; $i >= $xtest - $shipWidth + 1; $i--){
                    if((!isset($map[$ytest][$i]))
                    OR($map[$ytest][$i] == 1)){
                        $left= false;
                        break;
                    }                    
                }
                
                
                
                $directions=array();
                if($top){
                    $directions[]='top';
                }
                if($bottom){
                    $directions[]='bottom';
                }
                if($left){
                    $directions[]='left';
                }
                if($right){
                    $directions[]='right';
                }
            }
            
            shuffle($directions);
            switch($directions[0]){
                case 'top':
                    $shipsCoords[]=$xtest.",".$ytest."-".$xtest.",".($ytest - $shipWidth + 1);
		    $map= place_ship_on_map($xtest,$ytest,$xtest,$ytest - $shipWidth + 1,$map);
                    break;
                case 'bottom':
		    $shipsCoords[]=$xtest.",".$ytest."-".$xtest.",".($ytest + $shipWidth - 1);
		    $map= place_ship_on_map($xtest,$ytest,$xtest,$ytest + $shipWidth -1 ,$map);
                    break;
                case 'left':
		    $shipsCoords[]=$xtest.",".$ytest."-".($xtest - $shipWidth + 1).",".$ytest;
		    $map= place_ship_on_map($xtest,$ytest,$xtest - $shipWidth + 1 ,$ytest,$map);
                    break;
                case 'right':
		    $shipsCoords[]=$xtest.",".$ytest."-".($xtest + $shipWidth - 1 ).",".$ytest;
		    $map= place_ship_on_map($xtest,$ytest,$xtest + $shipWidth -1 ,$ytest,$map);
                    break;
            
            }

	  }
        }
        
        echo json_encode($shipsCoords);
        break;
    default: 
        break;
    
}
