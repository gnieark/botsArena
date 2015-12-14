<?php
function place_ship_on_map($x1,$y1,$x2,$y2,$map){

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
        if(!preg_match('^[0-9]+-(1|2)$',$match_id)){
	  echo "parametre incorrect"; die;
        }
        
        //construire une grille
        for($i=0; $i < $width; $i++){
	  for($j=0; $j < $height; $j++){
	    $map[$i][$j]=0;
	  }
        }  
        
        $shipsCoords=array();
        
        //pour toutes les tailles de bateau
        for($shipWidth = 6; $shipWidth >= 0; $shipWidth--){
	  //nombre de bateau à placer de cette taille
	  $shipCount=$('ship'.$shipWidth); // #trollface
	  for( $sh = 0; $sh < $shipCount; $sh++){
             $directions=array();
             while( count($directions) == 0){
             
                do{
                    $xtest=rand(0,$width -1);
                    $ytest=rand(0,$height -1);
                }while($map[$ytest][$xtest] == 1);
                
                //Y a t'il la place pour le bateau vers le haut?
                if($ytest < $shipWidth){
                $top=false; 
                }else{
                    $top=true;
                    for($i = $ytest; $i > $ytest - $shipWidth; $i--){
                        if($map[$i][$xtest] == 1){
                            $top=false;
                            break;
                        }
                    }
                }
                
                //vers le bas
                if($ytest + $shipWidth > $height){
                    $bottom=false;
                }else{
                    $bottom=true;
                    for($y=$ytest; $i < $ytest + $shipWidth; $i++){
                        if($map[$i][$xtest] == 1){
                            $bottom=false;
                            break;
                        }
                    }
                }
                
                //droite
                if($xtest + $shipWidth > $width){
                    $rigth=false;
                }else{
                    $right=true;
                    for($i=$xtest; $i < $xtest + $shipWidth, $i++){
                        if($map[$ytest][$y] == 1){
                            $right= false;
                            break;
                        }
                    }
                }
                
                //gauche
                if($xtest < $shipWidth){
                    $left=false;
                }else{
                    $left=true;
                    for($i = $xtest; $i > $xtest - $shipWidth; $i--){
                        if($map[$ytest][$y] == 1){
                            $left= false;
                            break;
                        }                    
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
                    $shipsCoords[]=$xtest.",".$ytest."-".$ytest.",".$ytest - $shipWidth;
                
                    break;
                case 'bottom':
                    break;
                case 'left':
                    break;
                case 'right':
                    break;
            
            }

	  }
        }
        
        
        break;
    default: 
        break;
    
}
