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
//démmarrer une session php
@session_start();

require_once("../src/functions.php");



$arenas=get_arenas_list();
$lang=get_language_array();
//form submitting
if (isset($_POST['xd_check']))
{
	//vérifier le numero de formulaire
	if (($_SESSION['xd_check']!=$_POST['xd_check']) AND ($_POST['xd_check'] !="")){
		erreur ('Something wrong has appen');
		die;
	}
	//call the good act.php
	if((isset($arenas['current'])) && (file_exists("src/arenas/act.php"))){
	  require_once("src/arenas/act.php");
	}else{
	  require_once("src/default/act.php");
	}

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
	
	<title></title>
</head>
<body>
    
  <header>
  	<nav id="languages"><a href="/fr">fr</a>&nbsp;<a href="/en">en</a></nav>
    <h1><?php 
    		if(isset($arenas['current'])){ 
    				echo $arenas['current']['title'];
    		}else{
    			echo $lang['SITE_NAME'];
    		} ?></h1>
  </header>
  <section>
  </section>
  <footer>
  </footer>
</body>
</html>
