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
        
        
        
        break;
    default: 
        break;
    
}
