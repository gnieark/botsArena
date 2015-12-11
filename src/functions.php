<?php
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
    switch($code){
        case 404:
            header("HTTP/1.0 404 Not Found");
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8" /><title>Page Not found</title></head><body><p>'.$message.'</p></body></html>';
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
    return $linkMysql; //does PHP can do that?

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
function save_battle($game,$bot1,$bot2,$resultat){
    //resultat: 0 match nul, 1 bot1 gagne 2 bot 2 gagne

    global $lnMysql;
    
    $game=substr($game,0,8); //limit 8 char for limitting  mysql index size

    
    //chercher les id de bot 1 et bot2
    $rs=mysqli_query($lnMysql,"SELECT name,id FROM bots 
                                WHERE name='".mysqli_real_escape_string($lnMysql,$bot1)."'
                                 OR name='".mysqli_real_escape_string($lnMysql,$bot2)."'");
    while($r=mysqli_fetch_row($rs)){
        $bots[$r[0]]=$r[1];
    }
    
    if((!isset($bots[$bot1])) OR (!isset($bots[$bot2]))){
        error (500,"database corrupt");
        die;
    }
   
    switch($resultat){
        case 0:
            $field="nulCount";
            break;
        case 1:
            $field="player1_winsCount";
            break;
        case 2:
            $field="player2_winsCount";
            break;
        default:
             error (500,"something impossible has happened");
             break;
    }
    
    mysqli_query($lnMysql,
        "INSERT INTO arena_history(game,player1_id,player2_id,".$field.") VALUES
        ('".mysqli_real_escape_string($lnMysql,$game)."',
        '".$bots[$bot1]."',
        '".$bots[$bot2]."',
        '1')
        ON DUPLICATE KEY UPDATE ".$field." = ".$field." + 1;");
}
function get_unique_id(){
  $fp = fopen(__DIR__'countBattles.txt', 'c+');
  flock($fp, LOCK_EX);

  $count = (int)fread($fp, filesize('count.txt'));
  ftruncate($fp, 0);
  fseek($fp, 0);
  fwrite($fp, $count + 1);

  flock($fp, LOCK_UN);
  fclose($fp);
  return $count;
}
