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
    
    $siteTitle=$currentArenaArr['title'];
    $siteDescription=$currentArenaArr['metaDescription'];
    $mainSectionScript="../src/arenas/".$currentArena."/public.php";
    $asideSectionContent='<h2>infos:</h2><p>'.$lang['DEV-YOUR-OWN-BOT'].'<br/> <a href="/'.$currentArena.'/doc">'.$lang['DOC_SPECS_LINKS'].'</a></p>
    <h2>Scores</h2>';
    
    $podium=ELO_get_podium($currentArena);
    $count=0;
    $asideSectionContent.='<ul class="podium">';
    foreach($podium as $sc){
        $count++;
        
        switch($count){
            case 1:
                $img='<img src="/imgs/Gold_Medal.svg" alt="Gold_Medal.svg"/>';
                break;
            case 2: 
                $img='<img src="/imgs/Silver_Medal.svg" alt="Silver_Medal.svg"/>';
                break;
            case 3:
                $img='<img src="/imgs/Bronze_Medal.svg" alt="Bronze_Medal.svg"/>';
                break;
            default:
                $img='<img src="/imgs/Emoji_u1f4a9.svg" alt="caca"/>';
                break;
            
        
        }
        
        $asideSectionContent.='<li>'.$img.'&nbsp;<a href="/p/aboutBot/'.urlencode(htmlentities(($sc['name']))).'">'.htmlentities($sc['name']).'</a> ELO rank: '.$sc['ELO'].'</li>'; 
    }
    $asideSectionContent.='</ul><p><a href="#" onclick="document.getElementById(\'detailMatches\').setAttribute(\'class\',\'\');">Détail des matchs &gt;&gt;</a></p><article id="detailMatches" class="hidden">';
    
    foreach($hist as $sc){
        $asideSectionContent.='<h3><a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot1'])).'">'.$sc['bot1'].'</a> VS <a href="/p/aboutBot/'.urlencode(htmlentities($sc['bot2'])).'">'.$sc['bot2'].'</a></h3>
            <ul>
            <li>'.$sc['bot1']." ".$lang['VICTORIES'].":".$sc['player1Wins'].'</li>
            <li>'.$sc['bot2']." ".$lang['VICTORIES'].":".$sc['player2Wins'].'</li>
            <li>'.$lang['DRAW'].":".$sc['draws'].'</li>
            </ul>';
    }
    $asideSectionContent.='</article>';
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
    $siteTitle="Specifications ".$currentArenaArr['title'];
    $siteDescription="documentation, faites votre propre bot pour ".$currentArenaArr['metaDescription'];
    $mainSectionScript="../src/arenas/".$currentArenaArr['id']."/doc-".$lang['lang'].".html";
    $asideSectionContent=''; //to do
    $cssAdditionalScript="";
    if(isset($currentArenaArr['cssFile'])){
      $cssAdditionalScript.='<style type="text/css"><!--'."\n".file_get_contents("../src/arenas/".$currentArena."/".$currentArenaArr['cssFile'])."\n--></style>";
    }
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
        $siteTitle="About";
        $siteDescription="bots arena about page";
        $mainSectionScript="../src/about.html";
        $asideSectionContent=''; //to do or not to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;
    case "addBot":
        $siteTitle="Valider l'ajout d'une IA";
        $siteDescription="bots arena about page";
        $permitIndex=false;
        $mainSectionScript="../src/addBot.php";
        $asideSectionContent=''; //to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;
    case "aboutBot":
        if(!isset($_GET['params'])){
            error(404,"Page does not exists");
            die;
        }
        $rs=mysqli_query($lnMysql,
            "SELECT id,game,url,description,date_inscription 
            FROM bots 
            WHERE name='".mysqli_real_escape_string($lnMysql,$_GET['params'])."' 
            AND active='1'"); 
        if(!$r=mysqli_fetch_row($rs)){
            error(404,"Page doesn't exist");
            die;
        }
        $theBot=array(
            'id'                => $r[0],
            'game'              => $r[1],
            'url'               => $r[2],
            'description'       => $r[3],
            'date_inscription'  => $r[4]
        );
        
        $siteTitle=htmlentities($_GET['params']);
        $siteDescription=htmlentities($_GET['params'])." bot details";
        $mainSectionScript="../src/aboutBot.php";
        $hist=get_battles_history($r[1]);
        $asideSectionContent='<h2>Scores</h2>'; 
        foreach($hist as $sc){
            $asideSectionContent.='<h3>'.$sc['bot1'].' VS '.$sc['bot2'].'</h3>
            <ul>
            <li>'.$sc['bot1']." ".$lang['VICTORIES'].":".$sc['player1Wins'].'</li>
            <li>'.$sc['bot2']." ".$lang['VICTORIES'].":".$sc['player2Wins'].'</li>
            <li>'.$lang['DRAW'].":".$sc['draws'].'</li>
            </ul>';
        }
        $cssAdditionalScript="";
        $jsAdditionalScript=""; 
 
        break;
    case "editBot":
        if(!isset($_GET['params'])){
            error(404,"Page does not exists");
            die;
        }
        $rs=mysqli_query($lnMysql,
            "SELECT id,name,game,url,description,unclean_description,date_inscription 
            FROM bots 
            WHERE id='".mysqli_real_escape_string($lnMysql,$_GET['params'])."' 
            AND active='1'"); 
        if(!$r=mysqli_fetch_row($rs)){
            error(404,"Page doesn't exist");
            die;
        }
        $theBot=array(
            'id'                => $r[0],
            'name'		=> $r[1],
            'game'              => $r[2],
            'url'               => $r[3],
            'description'       => $r[4],
            'unclean_description'=> $r[5],
            'date_inscription'  => $r[6]
        );
        $siteTitle="Modifier un bot";
        $siteDescription="bots arena ";
        $permitIndex=false;
        $mainSectionScript="../src/editBot.php";
        $asideSectionContent=''; //to do
        $cssAdditionalScript="";
        $jsAdditionalScript="";
      break;
      
    case "validateEditBot":
	//check if secret is ok
	if(!isset($_GET['params'])){
            error(404,"Page does not exists");
            die;
        }
        
        $rs=mysqli_query($lnMysql," SELECT 1 FROM bots_modifs WHERE validate_secret='".mysqli_real_escape_string($lnMysql,$_GET['params'])."';");
        if(!$r=mysqli_fetch_row($rs)){
            error(404,"Page doesn't exist");
            die;
        }
        
        $siteTitle="Your bot is changed";
        $siteDescription="bots arena ";
        $permitIndex=false;
        $mainSectionScript="../src/validateEditBot.php";
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
        $asideSectionContent='<h2>Principe:</h2><p class="center"><img src="/principe.gif" alt=""/></p>';
        $cssAdditionalScript="";
        $jsAdditionalScript="";
}


if(!isset($currentArena)){
    $currentArena="";
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
<html lang="<?php echo $lang['lang']; ?>">
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
      <?php
	if(isset($_GET['doc'])){
	    echo '<nav id="languages"><a href="/'.$currentArena.'/doc-fr">fr</a>&nbsp;<a href="/'.$currentArena.'/doc-en">en</a></nav>';
	 }else{
	    echo '<nav id="languages"><a href="/'. $currentArena.'-fr">fr</a>&nbsp;<a href="/'.$currentArena.'-en">en</a></nav>';
	 }
      
      echo '<nav id="menus"><a href="/"';
      if(($currentArena == "") && (!isset($_GET['doc']))){ 
	echo ' class="selected"';
      }
      echo '>'.$lang['HOME'].'</a>';

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
    if($asideSectionContent <> ""){
        echo "<aside>".$asideSectionContent."</aside>";
    }
     include $mainSectionScript;
	?>
  </section>
  <footer>
    <a href="/p/About"><?php echo $lang['ABOUT']; ?></a><a href="https://github.com/gnieark/botsArena">Bots'Arena source code</a><a href="/p/legals"><?php echo $lang['LEGALS']; ?></a>
  </footer>
</body>
</html>