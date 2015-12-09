<article>

<?php
//<?php echo xd_check_input(0);<input type="hidden" name="act" value="addBot"/>

if((isset($_POST['xd_check'])) && ($_POST['act'] == "addBot")){
    //validation d'ou pas du formulaire
    if($alerts==""){
        //it worked
        echo "<h2>Relevez vos e-mails</h2><p>Un lien pour valider le bot vient de vous être envoyé par e-mail. (Vérifiez dans vos spams si vous ne trouvez pas :-$ )</p>";
    }else{
        echo "<h2>Petite ereur dans votre formulaire</h2><p>".$alerts."</p>";
        // TO do put form again
        
    
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