<?php
function  get_arenas_list(){
    include (__DIR__."/arenas_lists.php");
    if(isset($_GET['arena'])){
        foreach ($arenas as $arena){
            if($arena['id'] == $_GET['arena']){
                $arenas['current'] = $arena;
                break;
            }
        }
    }
    return $arenas;
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
   
    $lang = $_GET['lang'];
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