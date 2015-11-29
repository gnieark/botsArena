<?php
require_once(__DIR__."/functions.php");

$bots=get_Bots_Array();
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
		<p>
			<select name="bot1" id="bot1">
			  <?php
			    for($i=0;$i<count($bots);$i++){
			      if($i==$bot1)
				$selected='selected="selected"';
			      else
				$selected='';
			      
			      echo '<option value="'.$i.'" '.$selected.'>'.$bots[$i]['name'].'</option>';
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
			      echo '<option value="'.$i.'" '.$selected.'>'.$bots[$i]['name'].'</option>';
			    }
			  ?>
			</select>
		</p>
	<p><input type="button" value="Fight!" onclick="tictactoe(document.getElementById('bot1').value,document.getElementById('bot2').value,'<?php echo xd_check_input(2); ?>');"></p>
    </article>
    <article id="fightResult"></article>