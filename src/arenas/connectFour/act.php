<?php

require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('connectFou');
$new=false;
switch ($_POST['act']){
    
    case "newFight":
        $new=true;
        //remove $_SESSION less xd_check
        $xd=$_SESSION['xd_check'];
        session_unset();
        $_SESSION['xd_check']=$xd;
        
        
        //init map
        $_SESSION['map']=array(
            array("","","","","","",""),
            array("","","","","","",""),
            array("","","","","","",""),
            array("","","","","","",""),
            array("","","","","","",""),
            array("","","","","","",""),
            );
            
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
            
            //get a match id
            $_SESSION['matchId']=get_unique_id();
            $_SESSION['game']="connectFou";
            
            //send init message   
            
            for($player = 0; $player < 2; $player++){
                $params[$player]=array(
                    'game-id'        =>  $_SESSION['matchId'],
                    'action'         =>  'init',
                    'game'           =>  'connectfour',
                    'players'        => 2,
                    'board'          => '',
                    'player-index'   => $player 
                    );
            }
            /*
             *     'messageSend' 	=> $data_string,
             *     'httpStatus'  	=> $curl_getinfo($ch)['http_code'],
             *     'response'	=> $output,
             *     'responseArr'	=> $arr 
             */
            get_IA_Response($_SESSION['bot1']['url'],$params[0]); //don't care about result
            get_IA_Response($_SESSION['bot2']['url'],$params[1]); //don't care about result
            
            
            case "fight":
                
                if($_SESSION['game'] <> "connectFou"){
                    error(500,"game non found"); 
                    die;
                }
                if((!$new) && ($_POST['gameId'] <> $_SESSION['matchId'])){
                    error (512, "not correct gameId");
                }
                
                //What player has to play?
                if(!isset($_SESSION['currentPlayer'])){
                    $_SESSION['currentPlayer']=1;
                    $you="X";
                    $currentBotName=$_SESSION['bot1']['name'];
                    $opponentName=$_SESSION['bot2']['name'];
                    $botUrl=$_SESSION['bot1']['url'];
                }else{
                    if($_SESSION['currentPlayer']==1){
                        $_SESSION['currentPlayer']=2;
                        $you="O";
                        $opponentName=$_SESSION['bot1']['name'];
                        $currentBotName=$_SESSION['bot2']['name'];
                        $botUrl=$_SESSION['bot2']['url'];
                    }else{
                        $_SESSION['currentPlayer']=1;
                        $opponentName=$_SESSION['bot2']['name'];
                        $currentBotName=$_SESSION['bot1']['name'];
                        $botUrl=$_SESSION['bot1']['url'];
                        $you="X";
                    }
                }
                /*
                 * 
                 * 
                 *   game-id string identifiant la partie.
                 *   action string identifiant la phase, init tout de suite, sera play-turn dans le châpitre suivant.
                 *   game string identifiant le jeu. Ici, ce sera forcément tictactoe. ça peut servir si vous donnez une seule URL pour plusieurs bots.
                 *   players Int indiquant le nombre de joueurs dans la partie, toujours 2 au morpion.
                 *   board Vide à cette étape, voir chapitre suivant.
                 *   player-index int, L'ordre de votre bot dans les tours de jeu. Le premier joueur a la valeur 0, le deuxième 1.
                 */
                //make post datas to send
                $postDatas=array(
                    'game-id'  	=> "".$_SESSION['matchId'],
                    'action'	=> 'play-turn',
                    'game'       	=> 'connectfour',
                    'players'	=> 2,
                    'opponent'  	=> $opponentName,
                    'you'	     	=> $you,
                    'board'	=> $_SESSION['map'],
                    'player-index'	=> $_SESSION['currentPlayer'] - 1
                );
                    
                //send query
                $tempPlayer = get_IA_Response($botUrl,$postDatas);
                if(isset($tempPlayer['responseArr']['play'])){
                    $anwserPlayer = $tempPlayer['responseArr']['play'];
                }else{
                    $anwserPlayer = -1;
                }

                if($_POST['fullLogs'] == "true"){
                 $fullLogs='Arena send to '.$currentBotName.'<em>'.htmlentities($tempPlayer['messageSend']).'</em><br/>
                 HTTP status: <em>'.htmlentities($tempPlayer['httpStatus']).'<em><br/>
                 Bot anwser: <em>'.htmlentities($tempPlayer['response']).'<em><br/>';
                    
                }else{
                    $fullLogs='';
                }
                //vérifier la validité de la réponse
                if((isset($_SESSION['map'][5][$anwserPlayer])) && ($_SESSION['map'][5][$anwserPlayer] == "")){
                    //reponse conforme
                    
                    for($y = 0; $_SESSION['map'][$y][$anwserPlayer] <> ""; $y++){
                    }
                    
                    $_SESSION['map'][$y][$anwserPlayer]=$you;
                    $strikeX=$anwserPlayer;
                    $strikeY=$y;
                    
                    //does he win?
                    $wins=false;
                    
                    //diagonale  \
                    $count=1;
                    $x=$strikeX;
                    $y=$strikeY;
                    $cellsWin=array();
                    $cellsWin[]=array($x,$y);
                    while(($x > 0) && ($y < 5) && ($_SESSION['map'][$y + 1][$x - 1] == $you)){
                        $x--;
                        $y++;
                        $count++;
                        $cellsWin[]=array($x,$y);
                    }
                    
                    $x=$strikeX;
                    $y=$strikeY;
                    while(($x < 6) && ($y > 0) && ($_SESSION['map'][$y - 1][$x + 1] == $you)){
                        $x++;
                        $y--;
                        $count++;
                        $cellsWin[]=array($x,$y);
                    }
                    
                    if($count>3){
                        $wins=true;
                    }
                    
                    //diagonale /
                    if(!$wins){
                        
                        $count=1;
                        $x=$strikeX;
                        $y=$strikeY;	
                        $cellsWin =array();
                        $cellsWin[]=array($x ,$y);
                        while(($x < 6) && ($y < 5) && ($_SESSION['map'][$y + 1][$x + 1 ] == $you)){
                            $x++;
                            $y++;
                            $count++;
                            $cellsWin[]=array($x,$y);
                        }
                        $x=$strikeX;
                        $y=$strikeY;
                        while(($x > 0) && ($y > 0) && ($_SESSION['map'][$y - 1][$x - 1 ] == $you)){
                            $x--;
                            $y--;
                            $count++;
                            $cellsWin[]=array($x,$y);
                        }
                        if($count>3){
                            $wins=true;
                        }
                    }
                    
                    
                    //horizontale
                    if(!$wins){
                        $count=1;
                        $x=$strikeX;
                        $y=$strikeY;
                        $cellsWin =array();
                        $cellsWin[]=array($x ,$y);
                        while(($x < 6) && ($_SESSION['map'][$y][$x + 1 ] == $you)){
                            $x++;
                            $count++;
                            $cellsWin[]=array($x,$y);
                            
                        }
                        $x=$strikeX;
                        while(($x >0) && ($_SESSION['map'][$y][$x - 1 ] == $you)){
                            $count++;
                            $x--;
                            $cellsWin[]=array($x,$y);
                        }
                        if($count>3){
                            $wins=true;
                        }
                    }
                    
                    //verticale
                    if(!$wins){
                        $count=1;
                        $x=$strikeX;
                        $y=$strikeY;
                        
                        $cellsWin =array();
                        $cellsWin[]=array($x ,$y);
                        
                        while(($y < 5) && ($_SESSION['map'][$y + 1 ][$x] == $you)){
                            $y++;
                            $count++;
                            $cellsWin[]=array($x,$y);
                        }
                        
                        $y=$strikeY;
                        while(($y >0) && ($_SESSION['map'][$y - 1][$x] == $you)){
                            $count++;
                            $y--;
                            $cellsWin[]=array($x,$y);
                        }
                        if($count>3){
                            $wins=true;
                        }
                    }
                    
                    
                    if($wins){
                        $anwserToJS=array(
                            'continue'	=> 0,
                            'strikeX' 	=> $strikeX,
                            'strikeY'	=> $strikeY,
                            'strikeSymbol'=> $you,
                            'log'		=> $fullLogs.$you." ".$currentBotName." joue colonne ". $anwserPlayer." et a gagné",
                            'cellsWin'	=> json_encode($cellsWin),
                            'gameId'      => $_SESSION['matchId']
                            );
                            if($_SESSION['currentPlayer']==1){
                                save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],1);
                            }else{
                                save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],2);
                            }
                                
                        }else{
                            
                            //Was it the last cell?
                            
                            $full=true;
                            foreach ($_SESSION['map'][5] as $cell) {
                                if ($cell == ""){
                                    $full=false;
                                    break;
                                }
                            }
                            
                            if($full){
                                
                                save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],0);
                                $anwserToJS=array(
                                    'continue'	=> 0,
                                    'strikeX' 	=> $strikeX,
                                    'strikeY'	=> $strikeY,
                                    'strikeSymbol'=> $you,
                                    'log'	=> $fullLogs.$you." ".$currentBotName." joue colonne ". $anwserPlayer." match nul",
                                    'gameId'      => $_SESSION['matchId']
                                    );
                                    
                            }else{
                                
                                $anwserToJS=array(
                                    'continue'	=> 1,
                                    'strikeX' 	=> $strikeX,
                                    'strikeY'	=> $strikeY,
                                    'strikeSymbol'=> $you,
                                    'log'	=> $fullLogs.$you." ".$currentBotName." joue colonne ". $anwserPlayer,
                                    'gameId'      => $_SESSION['matchId']
                                    );
                            }
                        }
                        
                    }else{
                        //reponse non conforme
                        
                        $anwserToJS=array(
                            'continue' =>0,
                            'strikeX' 	=> -1,
                            'strikeY'	=> -1,
                            'log'	=> $fullLogs.$you." ".$currentBotName." made a non conform anwser.",
                            'gameId'      => $_SESSION['matchId']
                            );
                            if($_SESSION['currentPlayer']==1){
                                save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],2);
                            }else{
                                save_battle('connectFou',$_SESSION['bot1']['name'],$_SESSION['bot2']['name'],1);
                            }
                    }
                    
                    echo json_encode($anwserToJS);
                    
                    
                    
                    die;
                    break;    
                    default:
                        break;
}