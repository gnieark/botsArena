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

$arenas=get_arenas_list();
$lang=get_language_array();

if(isset($_GET['arena'])){
    //check if arena is list
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
}else{
    $currentArena = "";
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
	  require_once("../src/arenas/".$currentArena."/act.php");
	}

}
//title
if($currentArena == ""){
  $siteTitle = $lang['SITE_NAME'];
		
}else{
  $siteTitle=$currentArenaArr['title'];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="ROBOTS" content="INDEX, FOLLOW" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Gnieark" />
    <title><?php echo $siteTitle; ?></title>
      <style type="text/css">
	@import url(/style.css);
      </style>
     <?php
      //arena specific script js (if needed)
	if(isset($currentArenaArr['jsFile'])){
	  echo '<script type="text/javascript"><!--'."\n";
	  echo file_get_contents("../src/arenas/".$currentArena."/".$currentArenaArr['jsFile']);
	  echo "\n--></script>";
	}
     ?>
</head>
<body>   
  <header>
	<h1><?php echo $siteTitle; ?></h1>
  	<nav id="languages"><a href="-fr">fr</a>&nbsp;<a href="-en">en</a></nav>
  	<nav id="menus"><a href="/"<?php if($currentArena == "") echo ' class="selected"'; ?>><?php echo $lang['HOME']; ?></a>
  	<?php
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
      switch($currentArena){
	case "":
	  echo "<h2>Accueil</h2>";
	  break;
	default:
	  include ("../src/arenas/".$currentArena."/public.php");
	  break;
      }
    ?>
  </section>
  <footer>
  </footer>
</body>
</html>