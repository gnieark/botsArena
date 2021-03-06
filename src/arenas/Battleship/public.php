<!--<article>
<h2>Cette arène est en cours de maintenance, elle sera de nouveau prête dans les prochains jours</h2>
<p><img style="width: 100%;" src="/imgs/Arenes-Nimes.jpg" alt="travaux sur les arènes de Nimes"/></p>

</article>-->
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

$bots=get_Bots_Array('Battleship');
$postParams=get_Post_Params(count($bots));
if(!$postParams){
  $bot1="";
  $bot2="";
}else{
  $bot1=$postParams['bot1'];
  $bot2=$postParams['bot2'];
}

?>
    <article>
    <h2><?php echo $lang['MAKE_DUEL'];?></h2>
        <p><label for="width">Largeur de la grille:</label><?php echo generate_numeric_select(1,100,10,'width','width'); ?></p>
        <p><label for="height">Hauteur de la grille:</label><?php echo generate_numeric_select(1,100,10,'height','height'); ?></p>
        <p><label for="ship1">Nombre de navires de 1 case:</label><?php echo generate_numeric_select(0,10,0,'ship1','ship1'); ?></p>
        <p><label for="ship2">Nombre de navires de 2 cases:</label><?php echo generate_numeric_select(0,10,1,'ship2','ship2'); ?></p>
        <p><label for="ship3">Nombre de navires de 3 cases:</label><?php echo generate_numeric_select(0,10,2,'ship3','ship3'); ?></p>
        <p><label for="ship4">Nombre de navires de 4 cases:</label><?php echo generate_numeric_select(0,10,1,'ship4','ship4'); ?></p>
        <p><label for="ship5">Nombre de navires de 5 cases:</label><?php echo generate_numeric_select(0,10,1,'ship5','ship5'); ?></p>
        <p><label for="ship6">Nombre de navires de 6 cases:</label><?php echo generate_numeric_select(0,10,0,'ship6','ship6'); ?></p>
        <p><label>&nbsp;</label><em>
                <select name="bot1" id="bot1">
                    <?php
                    for($i=0;$i<count($bots);$i++){
                        if($i==$bot1)
                            $selected='selected="selected"';
                        else
                            $selected='';
                        
                        echo '<option value="'.$bots[$i]['id']."-".htmlentities($bots[$i]['name']).'" '.$selected.'>'.htmlentities($bots[$i]['name']).'</option>';
                    }
                    ?>
                </select>
                &nbsp;VS&nbsp;
                <select name="bot2" id="bot2">
                    <?php
                    for($i=0;$i<count($bots);$i++){
                        if($i==$bot2)
                            $selected='selected="selected"';
                        else
                            $selected='';
                            
                        echo '<option value="'.$bots[$i]['id']."-".htmlentities($bots[$i]['name']).'" '.$selected.'>'.htmlentities($bots[$i]['name']).'</option>';
                    }
                    ?>
                </select></em>
        </p>
        <p><input type="checkbox" id="fullLogs"/><label for="fullLogs">view the full logs</label></p>
	<p><label>&nbsp;</label><input type="button" value="<?php echo $lang['FIGHT']; ?>" onclick="battleship(document.getElementById('bot1').value,document.getElementById('bot2').value,getElementById('width').value,getElementById('height').value,getElementById('ship1').value,getElementById('ship2').value,getElementById('ship3').value,getElementById('ship4').value,getElementById('ship5').value,getElementById('ship6').value,'<?php echo xd_check_input(2); ?>',document.getElementById('fullLogs').checked);"></p>
    <div id="fightResult"></div>
</article>