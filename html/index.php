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

@session_start();

require_once("../src/functions.php");
$lnMysql=conn_bdd();
$arenas=get_arenas_list();
$lang=get_language_array();

//check type of page
//$_GET['arena'] -> an arena
//$_GET['doc'] -> arena documentation
//$_GET['page'] -> a simple page like about page, legals etc...
//Nothing -> home page

$permitIndex=true; //will be set to false for pages that google or other bot must not index

if(isset($_GET['arena'])){
  //Arena

  //check if arena exists
   $currentArena = false;
   foreach($arenas as $arena){
        if($arena['id'] == $_GET['arena']){
            $currentArena = $_GET['arena'];
            $currentArenaArr=$arena;
            break;
        }
    }
    if(!$currentArena){
        error(404,"Wrong parameter");
        die;
    }
    $hist=get_battles_history($currentArena);
    
    
    $siteTitle=$currentArenaArr['title']." - bots Arena";
    $siteDescription=$currentArenaArr['metaDescription'];
    $mainSectionScript="../src/arenas/".$currentArena."/public.php";
    $asideSectionContent='<h2>infos:</h2><p>'.$lang['DEV-YOUR-OWN-BOT'].'<br/> <a href="/'.$currentArena.'/doc">'.$lang['DOC_SPECS_LINKS'].'</a></p>
    <h2>Scores</h2>';
    foreach($hist as $sc){
        $asideSectionContent.='<h3>'.$sc['bot1'].' VS '.$sc['bot2'].'</h3>
            <ul>
            <li>'.$sc['bot1']." ".$lang['VICTORIES'].":".$sc['player1Wins'].'</li>
            <li>'.$sc['bot2']." ".$lang['VICTORIES'].":".$sc['player2Wins'].'</li>
            <li>'.$lang['DRAW'].":".$sc['draws'].'</li>
            </ul>';
    }
    
    $cssAdditionalScript="";
    if(isset($currentArenaArr['cssFile'])){
      $cssAdditionalScript.='<style type="text/css"><!--'."\n".file_get_contents("../src/arenas/".$currentArena."/".$currentArenaArr['cssFile'])."\n--></style>";
    }
      //arena specific script js (if needed)
    $jsAdditionalScript="";
    if(isset($currentArenaArr['jsFile'])){
       $jsAdditionalScript.='<script type="text/javascript"><!--'."\n".file_get_contents("../src/arenas/".$currentArena."/".$currentArenaArr['jsFile'])."\n--></script>";
    }
    

}elseif(isset($_GET['doc'])){
  //arena's documentation page

    //check if arena exists
   $currentArena = false;
   foreach($arenas as $arena){
        if($arena['id'] == $_GET['doc']){
            $currentArena = $_GET['doc'];
            $currentArenaArr=$arena;
            break;
        }
    }
    if(!$currentArena){
        error(404,"Wrong parameter");
        die;
    }
    $siteTitle="Specifications ".$currentArenaArr['title']." - bots Arena";
    $siteDescription="documentation, faites votre propre bot pour ".$currentArenaArr['metaDescription'];
    $mainSectionScript="../src/arenas/".$currentArenaArr['id']."/doc-".$lang['lang'].".html";
    $asideSectionContent=''; //to do
    $cssAdditionalScript="";
    $jsAdditionalScript="";
    
}elseif(isset($_GET['page'])){
  //simple page
  switch($_GET['page']){
    case "legals":
        $siteTitle="Mentions légales - bots Arena";
        $siteDescription="OSEF";
        $mainSectionScript="../src/legals.html";
        $asideSectionContent=''; //to do or not to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;
    case "About":
        $siteTitle="About - bots Arena";
        $siteDescription="bots arena about page";
        $mainSectionScript="../src/about.html";
        $asideSectionContent=''; //to do or not to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;
    case "addBot":
        $siteTitle="Valider l'ajout d'une IA - bots Arena";
        $siteDescription="bots arena about page";
        $permitIndex=false;
        $mainSectionScript="../src/addBot.php";
        $asideSectionContent=''; //to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;

    default:
      error(404,"Not found");
      break;
  }

}else{
  //home page
        $siteTitle="Bots Arena";
        $siteDescription="bots arena main page. Program your own artificiel intelligence and let it play here";
        $mainSectionScript="../src/home.php";
        $asideSectionContent=''; //to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
}



//form submitting
if (isset($_POST['xd_check'])){
    //vérifier le numero de formulaire
    if (($_SESSION['xd_check']!=$_POST['xd_check']) AND ($_POST['xd_check'] !="")){
        error (400, 'Something wrong has appen');
        die;
    }
    //call the good act.php
    if(($currentArena <> "") && (file_exists("../src/arenas/".$currentArena."/act.php"))){
        require_once("../src/arenas/".$currentArena."/act.php");
    }else{
        require_once("../src/act.php");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="ROBOTS" content="<?php if($permitIndex){ echo "INDEX, FOLLOW";}else{echo "noindex, nofollow";}?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Gnieark" />
    <meta name="description" content="<?php echo $siteDescription; ?>"/>
    <title><?php echo $siteTitle; ?></title>
      <style type="text/css">
        @import url(/style.css);
      </style>
      <?php  echo $cssAdditionalScript."\n".$jsAdditionalScript; ?>
</head>
<body>
  <header>
    <h1><?php echo $siteTitle; ?></h1>

      <nav id="languages"><a href="<?php echo $currentArena; ?>-fr">fr</a>&nbsp;<a href="<?php echo $currentArena; ?>-en">en</a></nav>
      <nav id="menus"><a href="/"<?php if(($currentArena == "") && (!isset($_GET['doc']))) echo ' class="selected"'; ?>><?php echo $lang['HOME']; ?></a>
      <?php
        if(!isset($currentArena)){
            $currentArena="";
        }
        foreach($arenas as $arena){
            if( $arena['id'] == $currentArena){
                $class="selected";
            }else{
                $class="";
            }
            echo '<a href="'.$arena['url'].'" class="'.$class.'">'.$arena['title'].'</a>';
        }
      ?></nav>
  </header>
  <section>
    <?php 
    include $mainSectionScript; 
    if($asideSectionContent <> ""){
        echo "<aside>".$asideSectionContent."</aside>";
    }
    ?>
  </section>
  <footer>
  </footer>
</body>
</html>
