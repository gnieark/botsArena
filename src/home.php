<article>
<?php echo $lang['SITE_DESCRIPTION'];?>
<h2><?php echo $lang['ADD_YOUR_BOT'];?></h2>
<form method="POST" action="/p/addBot">
    <?php echo xd_check_input(0); ?><input type="hidden" name="act" value="addBot"/>
    <p><label for="botName"><?php echo $lang['BOT_NAME']; ?></label><input id="botName" type="text" name="botName" placeholder="<?php echo $lang['YOUR_ALIAS_FOR_EXEMPLE']; ?>"/></p>
    <p><label for="botGame"><?php echo $lang['BOT_GAME']; ?></label>
        <select id="botGame" name="botGame">
        <?php
        foreach($arenas as $arena){
            echo '<option value="'.$arena['id'].'">'.$arena['id'].'</option>';
        }  
        ?>
        </select></p>
    <p><label for="botURL"><?php echo $lang['BOT_URL']; ?></label><input type="text" name="botURL" id="botURL" placeholder="http://"/></p>
    <p><label><?php echo $lang['BOT_DESCRIPTION']; ?></label><textarea name="botDescription"></textarea></p>
    <p><label for="email"><?php echo $lang['YOUR_EMAIL_FOR_BOT_VALIDATION']; ?></label><input type="text" name="email" id="email"/></p>
    <p><label for="sub"></label><input id="sub" type="submit" value="<?php echo $lang['SAVE_BOT']; ?>"/></p>       
</form>
</article>

