<article>

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

//<?php echo xd_check_input(0);<input type="hidden" name="act" value="addBot"/>

if((isset($_POST['xd_check'])) && ($_POST['act'] == "addBot")){
    //validation d'ou pas du formulaire
    if($alerts==""){
        //it worked
        echo "<h2>Relevez vos e-mails</h2><p>Un lien pour valider le bot vient de vous être envoyé par e-mail. (Vérifiez dans vos spams si vous ne trouvez pas :-$ )</p>";
    }else{
        echo "<h2>Petite ereur dans votre formulaire</h2><p>".nl2br($alerts)."</p>";
        // TO do put form again
        
        echo'<form method="POST" action="/p/addBot">
        '.xd_check_input(0).'<input type="hidden" name="act" value="addBot"/>
        <p><label for="botName">Nom de votre Bot: </label><input id="botName" type="text" name="botName" placeholder="votre pseudo par exemple" value="'.htmlentities($_POST['botName']).'"/></p>
        <p><label for="botGame">Jeu du bot: </label>
            <select id="botGame" name="botGame">';
            
            foreach($arenas as $arena){
                if($arena['id'] == $_POST['botGame']){
                    echo '<option value="'.$arena['id'].'" selected="selected">'.$arena['id'].'</option>';
                }else{
                    echo '<option value="'.$arena['id'].'">'.$arena['id'].'</option>';
                }
            }  
            echo '
            </select></p>
        <p><label for="botURL">URL du bot:</label><input type="text" name="botURL" id="botURL" placeholder="http://" value="'.htmlentities($_POST['botURL']).'"/></p>
        <p><label>Description:</label><textarea name="botDescription">'.htmlentities($_POST['botDescription']).'</textarea></p>
        <p><label for="email">Votre e-mail (sera utilisé pour valider l\'inscription du bot)</label><input type="text" name="email" id="email" value="'.htmlentities($_POST['email']).'"/></p>
        <p><label for="sub"></label><input id="sub" type="submit" value="Enregistrer mon bot"/></p>       
    </form>';

    }

}elseif(isset($_GET['params'])){

    //checker si un bot avec ce secret est à valider
    $rs=mysqli_query($lnMysql,"SELECT id,active,game FROM bots WHERE validate_secret='".mysqli_real_escape_string($lnMysql,$_GET['params'])."'");
    if($r=mysqli_fetch_row($rs)){
        if($r[1]=='1'){
            echo "<p>Ce bot a déjà été activé</p>";
        }else{
            mysqli_query($lnMysql, "UPDATE bots SET active='1' WHERE id='".$r[0]."'");
            echo "<p>Merci! Votre Bot vient d'être activé, RDV sur son arène pour le faire combattre.</p>";
        }
    }else{
        //problem
        echo "<p>Paramètre incorrect, désolé.</p>";
    }
}else{
    //problem
    echo "<p>Paramètre incorrect, désolé.</p>";
}
?>
</article>