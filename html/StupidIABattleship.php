<?php
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
        
        //pour toutes les tailles de bateau
        for($shipWidth = 6; $shipWidth >= 0; $shipWidth--){
	  //nombre de bateau à placer de cette taille
	  $shipCount=$('ship'.$shipWidth); // #trollface
	  for( $sh = 0; $sh < $shipCount; $sh++){
	    $xtest=rand($width);
	    
	  
	  
	  }
        }
        
        
        break;
    default: 
        break;
    
}
