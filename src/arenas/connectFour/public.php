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

require_once(__DIR__."/functions.php");
$bots=get_Bots_Array('connectFou');
?>
<article>
    <h2><?php echo $lang['MAKE_DUEL'];?></h2>
		<p>
			<select name="bot1" id="bot1">
			  <?php
			    foreach($bots as $bot){
			      echo '<option value="'.$bot['id'].'">'.$bot['name'].'</option>';
			    }
			  ?>
			</select>
			&nbsp;VS&nbsp;
			<select name="bot2" id="bot2">
			  <?php
			    foreach($bots as $bot){
			      echo '<option value="'.$bot['id'].'">'.$bot['name'].'</option>';
			    }
			  ?>
			</select>
		</p>
		<p><input type="checkbox" id="fullLogs"/><label for="fullLogs">view the full logs</label></p>
	<p><input id="fightButton" type="button" value="<?php echo $lang['FIGHT']; ?>" onclick="connectFour(document.getElementById('bot1').value,document.getElementById('bot2').value,'<?php echo xd_check_input(2); ?>',0);"></p>
    <div id="fightResult"></div>
</article>