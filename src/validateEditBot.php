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

$rs=mysqli_query($lnMysql,"SELECT 1 FROM bots_modifs WHERE validate_secret='".mysqli_real_escape_string($lnMysql,$_GET['params'])."';");
if(!$r=mysqli_fetch_row($rs)){
    error(404,"Page doesn't exist");
    die;
}

mysqli_query($lnMysql,
"UPDATE bots, bots_modifs
  SET bots.name		=	bots_modifs.name
  , bots.game		=	bots_modifs.game
  , bots.url		=	bots_modifs.url
  , bots.description	=	bots_modifs.description
  , bots.unclean_description =	bots_modifs.unclean_description
 WHERE
  bots.id=bots_modifs.real_id
  AND bots_modifs.validate_secret='".mysqli_real_escape_string($lnMysql,$_GET['params'])."';");
  
?>
 <h2>Thanks!</h2>
 <p> Votre bot est validé, merci d'avoir donné à manger à Bots'Arena</p>