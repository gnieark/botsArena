<h2>Add Bot</h2>
<?php
if(isset($_GET['params'])){
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
