<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark https://blog-du-grouik.tinad.fr et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------

require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('Battleship');

switch ($_POST['act']){
    case "initGame":
        //remove $_SESSION less xd_check
        $xd=$_SESSION['xd_check'];
        session_unset();
        $_SESSION['xd_check']=$xd;
        
        //verifier parametres POST       
        $postParamsWanted=array(
            // key,min,max
            array('gridWidth',1,100),
            array('gridHeight',1,100),
            array('nbShip1',0,10),
            array('nbShip2',0,10),
            array('nbShip3',0,10),
            array('nbShip4',0,10),
            array('nbShip5',0,10),
            array('nbShip6',0,10)
        );
            
        foreach($postParamsWanted as $p){
            if(!isset($_POST[$p[0]])){
                error (500,'missing parameter 1');
                die;
            }else{
                $value=$_POST[$p[0]];
            }
            if (
                (!is_numeric($value))
                OR  ($value < $p[1])
                OR  ($value > $p[2])
                )
                {
                    error(500,'wrong parameters '.$p[0]);
                    die;
                }
                $postValues[$p[0]]=$value;
        }
        if(($_POST['fullLogs'] <> 'true') && ($_POST['fullLogs'] <> 'false')){
            error(500, 'wrong POST parameters');
            die;
        }    
        if($_POST['fullLogs'] == 'true'){
                $fullLogs = true;
        }else{
            $fullLogs = false;
        }
        
        //check if bots exists
        $bot1Exists = false;
        $bot2Exists = false;
        foreach($bots as $bot){
            if($bot['id'] == $_POST['bot1']){
                
                $bot1 = $bot;
                $_SESSION['bot1']=$bot;
                $bot1Exists =true;
            }
            if($bot['id'] == $_POST['bot2']){
                $bot2 = $bot;
                $_SESSION['bot2']=$bot;
                $bot2Exists =true;
            }
            if ($bot1Exists && $bot2Exists){
                break;
            } 
        }
        if ((!$bot1Exists) OR (!$bot2Exists)){
            error (500,"missing parameter 2");
        }
        
        
        if(!is_it_possible_to_place_ships_on_grid($postValues['gridWidth'],$postValues['gridHeight'],
            $postValues['nbShip1'],$postValues['nbShip2'],$postValues['nbShip3'],
            $postValues['nbShip4'],$postValues['nbShip5'],$postValues['nbShip6']))
        {
            error (404,"grid is too little for these ships");
        }
            
        //******vars checked, lets init the initGame *******
            
        $_SESSION['matchId']=get_unique_id();
        
        
        for($player = 1; $player <= 2; $player++){
            
            if($player==1){
                $opponentName=$bot2['name'];
                $currentBot=$bot1;
            }else{
                $opponentName=$bot1['name'];
                $currentBot=$bot2;
            }
            
            $botParamsToSend=array(
                'game-id'   => $_SESSION['matchId'],
                'game'      => 'battleship',
                'action'    => 'init',
                'players'    => 2,
                'player-index'   => $player -1,
                'board'  => array(
                    'opponent'  => $opponentName,
                    'width'     => $postValues['gridWidth'],
                    'height'    => $postValues['gridHeight'],
                    'ship1'     => $postValues['nbShip1'],
                    'ship2'     => $postValues['nbShip2'],
                    'ship3'     => $postValues['nbShip3'],
                    'ship4'     => $postValues['nbShip4'],
                    'ship5'     => $postValues['nbShip5'],
                    'ship6'     => $postValues['nbShip6']
                    )
            );
            
            $anwserPlayerJson=get_IA_Response($currentBot['url'],$botParamsToSend);
            if($fullLogs){
                $fullLogs='Arena send to '.$currentBot['name'].'<em>'.htmlentities($anwserPlayerJson['messageSend']).'</em><br/>
                HTTP status: <em>'.htmlentities($anwserPlayerJson['httpStatus']).'</em><br/>
                Bot anwser: <em>'.htmlentities($anwserPlayerJson['response']).'</em><br/>';
            }else{
                $fullLogs="";
            }
                
                
                
            
            
            if(!isset($anwserPlayerJson['responseArr']['boats'])){
            
                echo $fullLogs.$currentBot['name']." a fait une réponse non conforme, il perd 1.";
                if($player==1){
                    save_battle('Battleship',$bot1['name'],$bot2['name'],2);
                }else{
                    save_battle('Battleship',$bot1['name'],$bot2['name'],1);
                }
                die;
            }
            
            $boatsPlayer = $anwserPlayerJson['responseArr']['boats'];
            
            //init grid
            for($y = 0; $y < $postValues['gridHeight']; $y++){
                for($x = 0; $x < $postValues['gridWidth']; $x++){
                    $grid[$player][$y][$x]=0;
                }
            }
            
            //vérifier si'il y a le bon nombre de bateaux et les placer
            $nbBoatsIwant=array(0,$postValues['nbShip1'],$postValues['nbShip2'],$postValues['nbShip3'],
            $postValues['nbShip4'],$postValues['nbShip5'],$postValues['nbShip6']);
            
            foreach($boatsPlayer as $boat){
                list($startCoord,$endCoord) = explode("-",$boat);
                list($xStart,$yStart)=explode(",",$startCoord);
                list($xEnd,$yEnd)=explode(",",$endCoord);
                if($xStart == $xEnd){
                    $long=abs($yStart - $yEnd) +1;		
                }else{
                    $long=abs($xStart - $xEnd) +1;
                }
                $nbBoatsIwant[$long]-=1;
                $grid[$player]=place_ship_on_map($xStart,$yStart,$xEnd,$yEnd,$grid[$player]);
                if(!$grid[$player]){
                    echo $currentBot['name']." n'a pas placé correctement ses bateaux. Certains se chevauchent. Il perd.";
                    if($player==1){
                        save_battle('Battleship',$bot1['name'],$bot2['name'],2);
                    }else{
                        save_battle('Battleship',$bot1['name'],$bot2['name'],1);
                    }
                    die;
                }
                //remember each cases of each boats
                $boatListOfCases=array();
                if($xStart == $xEnd){
                    if($yStart <= $yEnd ){
                        $start=$yStart;
                        $end=$yEnd;
                    }else{
                        $start=$yEnd;
                        $end=$yStart;
                    }
                    for($i = $start; $i <= $end; $i++){
                        $boatListOfCases[]=$xStart.",".$i;
                    }
                }else{
                    if($xStart <= $xEnd ){
                        $start=$xStart;
                        $end=$xEnd;
                    }else{
                        $start=$xEnd;
                        $end=$xStart;
                    }
                    for($i = $start; $i <= $end; $i++){
                        $boatListOfCases[]=$i.",".$yStart;
                    }
                    
                }
                $_SESSION['ships'][$player][]=$boatListOfCases;
            }
            foreach($nbBoatsIwant as $nb){
                if($nb <> 0){
                    echo $fullLogs.$currentBot['name']." n'a pas placé le bon nombre de bateaux. Il perd.";
                    if($player==1){
                        save_battle('Battleship',$bot1['name'],$bot2['name'],2);
                    }else{
                        save_battle('Battleship',$bot1['name'],$bot2['name'],1);
                    }
                    die;
                }
            } 
        }
        $_SESSION['ship1']=$postValues['nbShip1'];
        $_SESSION['ship2']=$postValues['nbShip2'];
        $_SESSION['ship3']=$postValues['nbShip3'];
        $_SESSION['ship4']=$postValues['nbShip4'];
        $_SESSION['ship5']=$postValues['nbShip5'];
        $_SESSION['ship6']=$postValues['nbShip6'];
        $_SESSION['strikes'][1]=array();
        $_SESSION['strikes'][2]=array();
        $_SESSION['width']=$postValues['gridWidth'];
        $_SESSION['height']=$postValues['gridHeight'];
        echo json_encode($grid); die;
                
            die;
            
            break;
            case "fight":
                if($_POST['fullLogs'] == 'true'){
                        $fullLogs = true;
                }else{
                    $fullLogs = false;
                }
                if(count($_SESSION['strikes'][1]) == count($_SESSION['strikes'][2])){
                    //player 1 has to fight
                    $currentPlayer=1;
                    $currentBot=$_SESSION['bot1'];
                    $opponent=2;
                    $opponentName=$_SESSION['bot2']['name'];
                }else{
                    //it's player2
                    $currentPlayer=2;
                    $currentBot=$_SESSION['bot2'];
                    $opponentName=$_SESSION['bot1']['name'];
                    $opponent=1;
                }
                
                $botParamsToSend=array(
                    'game'              => 'Battleship',
                    'game-id'           => $_SESSION['matchId']."-".$currentPlayer,
                    'action'            => 'play-turn',
                    'player-index'      => $currentPlayer - 1,
                    'board'  => array(
                    'opponent'          => $opponentName,
                        'width'             => $_SESSION['width'],
                        'height'            => $_SESSION['height'],
                        'ship1'             => $_SESSION['ship1'],
                        'ship2'             => $_SESSION['ship2'],
                        'ship3'             => $_SESSION['ship3'],
                        'ship4'             => $_SESSION['ship4'],
                        'ship5'             => $_SESSION['ship5'],
                        'ship6'             => $_SESSION['ship6'],
                        'your_strikes'	=> $_SESSION['strikes'][$currentPlayer],
                        'his_strikes'	=> $_SESSION['strikes'][$opponent]
                        )
                    );
                    $anwserPlayerJson=get_IA_Response($currentBot['url'],$botParamsToSend); 
                    if($fullLogs){
                        $fullLogs='Arena send to '.$currentBot['name'].'<em>'.htmlentities($anwserPlayerJson['messageSend']).'</em><br/>
                        HTTP status: <em>'.htmlentities($anwserPlayerJson['httpStatus']).'</em><br/>
                        Bot anwser: <em>'.htmlentities($anwserPlayerJson['response']).'</em><br/>';
                    }else{
                        $fullLogs="";
                    }
               
                    
                    if(!preg_match('/^[0-9]+,[0-9]+$/',$anwserPlayerJson['responseArr']['play'])){
                        echo json_encode(array(
                            'target' => '',
                            'log' => $fullLogs.$currentBot['name']." a fait une réponse non conforme, il perd."));
                            save_battle('Battleship',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],$opponent);
                            die;
                    }
                    list($x,$y)=explode(",",$anwserPlayerJson['responseArr']['play']);
                    
                    //check if shot is under map's limits
                    if(($x >= $_SESSION['width']) OR ($y >= $_SESSION['height'])){
                        echo json_encode(array(
                            'target' => '',
                            'log' => $currentBot['name']." a fait un tir en dehors des limites de la carte. ".$x.",".$y." C'est interdit par les conventions de Geneve. Il perd"
                            ));
                            save_battle('Battleship',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],$opponent); 
                            die;
                    }
                    
                    //put previous strikes in a one dimmension array ;
                    $previousStrikes=array();
                    foreach( $_SESSION['strikes'][$currentPlayer] as $strikes){
                        $previousStrikes[]=$strikes['target'];
                    }
                    
                    //do this strike hit a boat?
                    $continue=1;
                    $result='';
                    
                    for( $shipIndex = 0; $shipIndex < count( $_SESSION['ships'][$opponent]); $shipIndex ++){
                        $ennemyBoat = $_SESSION['ships'][$opponent][$shipIndex];
                        
                        if(in_array($x.",".$y, $ennemyBoat)){
                            $result='hit';
                            //sunk?
                            $sunk=true;
                            foreach($ennemyBoat as  $boatCase){
                                if((!in_array($boatCase,$previousStrikes)) && ($boatCase <> $x.",".$y)) {
                                    $sunk=false;
                                    break;
                                }
                            }
                            if($sunk){
                                $result="hit and sunk";
                                //remove the ship
                                unset($_SESSION['ships'][$opponent][$shipIndex]);
                                $_SESSION['ships'][$opponent] = array_values($_SESSION['ships'][$opponent]);
                                //var_dump($_SESSION['ships'][$opponent]);
                                //win the game?
                                if(count($_SESSION['ships'][$opponent]) == 0){
                                    $result="hit sunk and win";
                                    $continue=0;
                                    save_battle('Battleship',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],$currentPlayer);
                                }
                            }
                            
                            break;
                        }
                    }
                    
                    //remember this shot
                    $_SESSION['strikes'][$currentPlayer][]=array(
                        'target' => $x.",".$y,
                        'result' => $result
                        );
                        
                        //send message for the arena's ajax web page
                        echo json_encode(array(
                            'opponent'=> $opponent,
                            'target' => $x.",".$y,
                            'log' => $currentBot['name']." tire en ".$x.",".$y." ".$result,
                            'continue' => $continue
                            ));
                            
                            die;
                            break;
                            default:
                                break;
}