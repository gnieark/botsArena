<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------
include(__DIR__."/DUEL.php"); 

function  get_arenas_list(){
    include (__DIR__."/arenas_lists.php");
    return $arenas;
}
function get_Bots_Array($arena,$activeOnly=true){
  global $lnMysql;
  //$bots[]=array("name" => $name, "url" =>$url);
  
  if($activeOnly){
    $addClause=" AND active='1'";
  }else{
    $addClause="";
  }
  
  $rs=mysqli_query($lnMysql,
    "SELECT id,name,url,description FROM bots WHERE game='".mysqli_real_escape_string($lnMysql,$arena)."'".$addClause);
   $bots=array();
   while($r=mysqli_fetch_row($rs)){
    $bots[]=array(
      'id' => $r[0],
      'name'=> $r[1],
      'url' => $r[2],
      'description' => $r[3]
    );
   }
   return $bots;
}
function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){

    $chars_length = (strlen($chars) - 1);
    $string = $chars{rand(0, $chars_length)};

    for ($i = 1; $i < $length; $i = strlen($string)){
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    return $string;
}

function xd_check_input($id=1){
        /*
        *On génére un hash aléatoire qui sera 
        *ajouté aux formulaires, afin d'ajouter 
        *une vérification supplémentaire
        *lors du traitement de ce dernier
        */
  /*
  *     le parametre $id permet de selectionner le type de retour
  *     0=> un input type hidden sans id
  *     1=> un input type hidden avec id
  *     2=> juste la valeur
  */
        if(!isset($_SESSION['xd_check'])){
                //le générer
                $_SESSION['xd_check']=rand_str(25);
        }
        switch($id){
            case 0:
              return "<input type=\"hidden\" name=\"xd_check\" value=\"".$_SESSION['xd_check']."\"/>";
              break;
            case 1:
              return "<input type=\"hidden\" name=\"xd_check\" id=\"xd_check\" value=\"".$_SESSION['xd_check']."\"/>";
              break;
            case 2:
              return $_SESSION['xd_check'];
              break;
            default:
              return "<input type=\"hidden\" name=\"xd_check\" id=\"xd_check\" value=\"".$_SESSION['xd_check']."\"/>";
              break;
        }
}

function get_language_array(){
  /*
  * Choisir la langue de l'utilisateur
  * en priorisant parametre GET, cookie, info du navigateur
  * Retourner l'array contenant les bonnes traductions
  */

  $langsAvailable=array('fr','en');
  $language="";
  if( isset($_GET['lang']) ){ 
  
    $language = $_GET['lang'];
    setcookie( 'lang', $language, time() + 60*60*24*30 );
  
  }elseif( isset($_COOKIE['lang']) ){
    $language=$_COOKIE['lang'];
  }else{ 
  
    if(in_array(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2),$langsAvailable)){
      $language=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    
  }
  
  if(!in_array($language,$langsAvailable)){
    $language="en";
  }
  
  include (__DIR__."/../lang/".$language.".php");
  return $lang;
}
function error($code,$message){
    error_log($code." ".$message);
    switch($code){
        case 404:
            header("HTTP/1.0 404 Not Found");
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8" /><title>Page Not found</title></head><body><p>'.$message.'</p>
            <pre>
 _  _    ___  _  _   
| || |  / _ \| || |  
| || |_| | | | || |_ 
|__   _| | | |__   _|
   | | | |_| |  | |  
   |_|  \___/   |_|  
</pre><p><a href="/">Go to home page</a></p></body></html>';
            die;
         case 400:
	    header ("HTTP/1.0 400 Bad Request");
	    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8" /><title>Bad request</title></head><body><p>'.$message.'</p></body></html>';
	    die;
	  case 500:
	    header ("HTTP/1.0 500 Internal Server Error");
	    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8" /><title>Internal Server Error</title></head><body><p>'.$message.'</p></body></html>';
	    die;
        default:

            die;
            break;
    }
    
}
function conn_bdd(){
    require (__DIR__."/config.php");  

    if (!$linkMysql=mysqli_connect($mysqlParams['host'], $mysqlParams['user'], $mysqlParams['pass'])) {                                                                                                         
        error(500,'database connexion failed');                                                                                                                                                  
        die;                                                                                                                                                                                           
    }                                                                                                                                                                                                      
    mysqli_select_db($linkMysql,$mysqlParams['database']);
    mysqli_set_charset($linkMysql, 'utf8');  
    return $linkMysql;

}
function get_battles_history($game){
    global $lnMysql;
    $game=substr($game,0,8); //limit 8 char for limitting  mysql index size
   
    $rs=mysqli_query($lnMysql,
        " SELECT   
            player1.name,
            player2.name,
            arena_history.player1_winsCount,
            arena_history.player2_winsCount,
            arena_history.nulCount
           FROM
            bots as player1,
            bots as player2,
            arena_history
           WHERE
            player1.id=arena_history.player1_id
            AND player2.id=arena_history.player2_id
            AND arena_history.game='".mysqli_real_escape_string($lnMysql,$game)."';"    
    );
    
    $results=array();
    while($r=mysqli_fetch_row($rs)){
        $results[]= array(
            'bot1'  => $r[0],
            'bot2'  => $r[1],
            'player1Wins'   => $r[2],
            'player2Wins'   => $r[3],
            'draws'          => $r[4]
        );
    
    }
    return $results;
}
function ELO_get_podium($arena){
    global $lnMysql;
    $podium=array();
    $rs=mysqli_query($lnMysql,"SELECT id,name,description,ELO FROM bots WHERE game='".substr($arena,0,10)."' AND active='1' ORDER BY ELO DESC, name");
    while($r = mysqli_fetch_row($rs)){
        $podium[]=array(
            'id'            => $r[0],
            'name'          => $r[1],
            'description'   => $r[2],
            'ELO'           => $r[3]
        );
    }
    return $podium;
}


function save_battle($game,$bot1,$bot2,$result,$nameOrIds = 'name'){
    /*
     * Calculate new ELO ranks and save them on conn_bdd
     */
  
    global $lnMysql;
    $game=substr($game,0,8); //limit 8 char for limitting  mysql index size
  
    if($nameOrIds == "name"){
      for( $i=1; $i<3; $i++){
	$str = "bot".$i;
	$botName = $$str;
      	$rs=mysqli_query($lnMysql,"SELECT id,ELO FROM bots  WHERE name='".mysqli_real_escape_string($lnMysql,$botName)."'");
      	$r = mysqli_fetch_row($rs);
      	$bot[$i] = array(
	  'id'	=> $r[0],
	  'ELO'	=> $r[1]
      	);
      }
    }else{
      //the same, but query by id
      for( $i=1; $i<3; $i++){
	$str = "bot".$i;
	$botName = $$str;
      	$rs=mysqli_query($lnMysql,"SELECT id,ELO FROM bots  WHERE id='".mysqli_real_escape_string($lnMysql,$botName)."'");
      	$r = mysqli_fetch_row($rs);
      	$bot[$i] = array(
	  'id'	=> $r[0],
	  'ELO'	=> $r[1]
      	);
      }  
    }
    
    //apply $result
    $duel = new DUEL( $bot[1]["ELO"] , $bot[2]["ELO"]);
    
    switch ($result){
        case 0:
            $duel->drawGame();
            $field="nulCount";
            break;
        case 1:
	    $duel->oneWinsAgainstTwo();
	    $field="player1_winsCount";
            break;
        case 2:
           $duel->twoWinsAgainstOne();
           $field="player2_winsCount";
           break;
        default:
             error (500,"Oups");
             break;
    }
    
    //update ELO rank on database
    mysqli_multi_query($lnMysql,"
      UPDATE bots SET ELO = '".$duel->rank1."' WHERE id='".$bot[1]["id"]."';
      UPDATE bots SET ELO = '".$duel->rank2."' WHERE id='".$bot[2]["id"]."';
      INSERT INTO arena_history(game,player1_id,player2_id,".$field.") VALUES
        ('".mysqli_real_escape_string($lnMysql,$game)."',
        '".$bot[1]["id"]."',
        '".$bot[2]["id"]."',
        '1')
        ON DUPLICATE KEY UPDATE ".$field." = ".$field." + 1;   
      ");        
      
}
function get_unique_id(){
    //increment the number 

  $fp = fopen(__DIR__.'/../countBattles.txt', 'c+');
  flock($fp, LOCK_EX);
  $count = (int)fread($fp, filesize(__DIR__.'/../countBattles.txt'));
  ftruncate($fp, 0);
  fseek($fp, 0);
  fwrite($fp, $count + 1);
  flock($fp, LOCK_UN);
  fclose($fp);
  return $count;
}
function get_default_aside_content($currentArena){
  global $lang, $currentArenaArr;
    
    //bla bla
    $asideSectionContent = '<h2>infos:</h2><p>'.$lang['DEV-YOUR-OWN-BOT'].'<br/> <a href="/'.$currentArena.'/doc">'.$lang['DOC_SPECS_LINKS'].'</a></p>';
    
    //lien pour le ludus
    if(isset($currentArenaArr['ludusUrl'])){
     $asideSectionContent.= '<p><a href="'.$currentArenaArr['ludusUrl'].'">'.$lang['GO-TO-LUDUS'].'</a>&nbsp;'.$lang['LUDUS-DETAIL'].'</p>';
    }
    
    //scores
    $asideSectionContent.='<h2>Scores</h2>';
    $podium=ELO_get_podium($currentArena);
    $count=0;
    $asideSectionContent.= '<ul class="podium">';
    foreach($podium as $sc){
        $count++;
        
        switch($count){
            case 1:
                $img = '<img src="/imgs/Gold_Medal.svg" alt="Gold_Medal.svg"/>';
                break;
            case 2: 
                $img = '<img src="/imgs/Silver_Medal.svg" alt="Silver_Medal.svg"/>';
                break;
            case 3:
                $img = '<img src="/imgs/Bronze_Medal.svg" alt="Bronze_Medal.svg"/>';
                break;
            default:
                $img = '<img src="/imgs/Emoji_u1f4a9.svg" alt="caca"/>';
                break;
        }
        
        $asideSectionContent.='<li>'.$img.'&nbsp;<a href="/p/aboutBot/'.urlencode(htmlentities(($sc['name']))).'">'.htmlentities($sc['name']).'</a> ELO rank: '.$sc['ELO'].'</li>'; 
    }
    $asideSectionContent.='</ul><p><a href="/'.$currentArena.'/scores">Détail des matchs &gt;&gt;</a></p>';
   return $asideSectionContent;
}
function does_arena_exist($string,$arenasArr){
    foreach($arenasArr as $arena){
      if($string == $arena['id']){
	return true;
      }
    }
    return false;
}

function get_IA_Response($iaUrl,$postParams){
    // send params JSON as body
    // return query detail on an array

    $data_string = json_encode($postParams);
   
    $ch = curl_init($iaUrl);                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );

    $output= curl_exec($ch);
    $httpCode = curl_getinfo($ch)['http_code'];    
    curl_close($ch); 
    if(! $arr = json_decode($output,TRUE)){
      $arr=array();
    }
    
    return array(
      'messageSend' 	=> $data_string,
      'httpStatus'  	=> $httpCode,
      'response'	=> $output,
      'responseArr'	=> $arr    
    );
}
