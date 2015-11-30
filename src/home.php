<article>
<?php
echo $lang['SITE_DESCRIPTION'];?>
</article>
<article id="addBot">
    <h2>Ajouter votre bot</h2>
    <form method="POST" action="/">
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
        <p><label>Description:</label><textarea></textarea></p>
        <p><label for="email">Votre e-mail (sera utilisé pour valider l'inscription du bot)</label><input type="text" name="email" id="email"/></p>
        <p><input type="submit" value="Enregistrer mon bot"/></p>       
    </form>
</article>