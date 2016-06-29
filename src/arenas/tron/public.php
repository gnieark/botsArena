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
$bots=get_Bots_Array('tron');
$botsArr=array();
foreach($bots as $bot){
  $botsArr[]=array('id' => $bot['id'], 'name' => $bot['name']);
}
?>
<article id="mainArticle">
    <h2><?php echo $lang['MAKE_DUEL'];?></h2>
    <aside id="configurePlayers">

    </aside>
   <script>
   var botsAvailable = <?php echo json_encode($botsArr); ?>;
   show_bot_panel(0);
   </script>
   
   
		<p><input type="checkbox" id="fullLogs"/><label for="fullLogs">view the full logs</label></p>
	<p><input id="fightButton" disabled="disabled" type="button" value="<?php echo $lang['FIGHT']; ?>" onclick="tron(document.getElementById('bot1').value,document.getElementById('bot2').value,'<?php echo xd_check_input(2); ?>');"></p>
	 <div id="fightResult"></div>

</article>