<h2>Ajouter votre bot</h2>
<form method="POST" action="/">
    <?php echo xd_check_input(0); ?><input type="hidden" name="act" value="addBot"/>
    <p><label for="botName">Nom de votre Bot: </label><input id="botName" type="text" name="botName" placeholder="votre pseudo par exemple"/></p>
    <p><label for="botGame">Jeu du bot: </label>
        <select id="botGame" name="botGame">
        <?php
        foreach($arenas as $arena){
            echo '<option value="'.$arena['id'].'">'.$arena['id'].'</option>';
        }  
        ?>
        </select></p>
    <p><label for="botURL">URL du bot:</label><input type="text" name="botURL" id="botURL" placeholder="http://"/></p>
    <p><label>Description:</label><textarea name="botDescription"></textarea></p>
    <p><label for="email">Votre e-mail (sera utilisé pour valider l'inscription du bot)</label><input type="text" name="email" id="email"/></p>
    <p><input type="submit" value="Enregistrer mon bot"/></p>       
</form>

