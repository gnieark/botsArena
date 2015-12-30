<?php

if(isset($_POST['xd_check'])){
 $botName=$_POST['botName'];
 $botGame=$_POST['botGame'];
 $botURL=$_POST['botURL'];
 $botDescription=$_POST['botDescription'];
 $email=$_POST['email'];
 
 
 
 
 
 
}else{
 $botName=$theBot['name'];
 $botGame=$theBot['game'];
 $botURL=$theBot['url'];
 $botDescription=$theBot['description'];
 $email="";
}

?>
<h2>EditBot</h2>
<form method="POST" action="/p/editBot/<?php echo $theBot['id']; ?>">
    <?php echo xd_check_input(0); ?><input type="hidden" name="act" value="editBot"/><input type="hidden" name="botId" value="<?php echo $theBot['id']; ?>"/>
    <p><label for="botName"><?php echo $lang['BOT_NAME']; ?></label><input id="botName" type="text" name="botName" value="<?php echo htmlentities($botName); ?>" placeholder="<?php echo $lang['YOUR_ALIAS_FOR_EXEMPLE'];?>"/></p>
    <p><label for="botGame"><?php echo $lang['BOT_GAME']; ?></label>
        <select id="botGame" name="botGame">
        <?php
        foreach($arenas as $arena){
	  if($arena['id']  == $botGame){
	    $selected='selected="selected"';
	  }else{
	    $selected='';
	  }
          echo '<option value="'.$arena['id'].'" '.$selected.'>'.$arena['id'].'</option>';
        }
        ?>
        </select></p>
    <p><label for="botURL"><?php echo $lang['BOT_URL']; ?></label><input type="text" name="botURL" id="botURL" value="<?php echo htmlentities($botURL);?>" placeholder="http://"/></p>
    <p><label><?php echo $lang['BOT_DESCRIPTION']; ?></label><textarea name="botDescription"><?php echo htmlentities($botDescription);?></textarea></p>
    <p><label for="email"><?php echo $lang['YOUR_EMAIL_FOR_BOT_EDIT']; ?></label><input type="text" name="email" value="<?php echo htmlentities($email);?>" id="email"/></p>
    <p><label for="sub"></label><input id="sub" type="submit" value="<?php echo $lang['SAVE_BOT']; ?>"/></p>       
</form>