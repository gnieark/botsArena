<?php
require_once(__DIR__."/functions.php");

$bots=get_Bots_Array('connectFou');

?>
<article>
    <h2><?php echo $lang['MAKE_DUEL'];?></h2>
		<p>
			<select name="bot1" id="bot1">
			  <?php
			    for($i=0;$i<count($bots);$i++){
			      echo '<option value="'.$i.'">'.$bots[$i]['name'].'</option>';
			    }
			  ?>
			</select>
			&nbsp;VS&nbsp;
			<select name="bot2" id="bot2">
			  <?php
			    for($i=0;$i<count($bots);$i++){
			      echo '<option value="'.$i.'">'.$bots[$i]['name'].'</option>';
			    }
			  ?>
			</select>
		</p>
	<p><input type="button" value="<?php echo $lang['FIGHT']; ?>" onclick="connectFour(document.getElementById('bot1').value,document.getElementById('bot2').value,'<?php echo xd_check_input(2); ?>');"></p>
    <div id="fightResult"></div>
</article>
