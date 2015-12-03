<?php

switch($_POST['act']){
  case "addBot":
    //verifier les variables "botName""botGame""botURL""email""botDescription"
    
    $alerts="";
    
    //botGame -> doit exister
    if(!in_array($_POST['$arenas'],$arenas)){
      erreur(404,"wrong post parameter");
    }
    
    //botname -> il ne doit pas y avoir un autre bot du même nom sur le même jeu
    $rs=mysqli_query($lnMysql,
	"SELECT 1 
	 FROM bots 
	 WHERE name='".mysqli_real_escape_string($lnMysql,$_POST['botname'])."'
	 AND game='".mysqli_real_escape_string($lnMysql,$_POST['game'])."';");
    if(mysqli_num_rows($rs) > 0){
      $alerts.="Un bot existant pour ce je porte le même nom\n";
    }
    
    //BotUrl (doit retourner un code 200)
    if(!preg_match("/^(http|https):\/\//", $_POST['botURL'])){
      $alerts.="L'URL n'est pas valide\n";
    }
    
    //email => doit être valide
    //only oner @
    if(
	(substr_count('@',$_POST['email']) <> 1) 
     || (substr_count('.@',$_POST['email']) > 0)
     || (substr_count('@.',$_POST['email']) > 0)
     || (substr_count('..',$_POST['email']) > 0)
     || (substr_count('.',$_POST['email']) == 1)
     ){
      $alerts.="L'email n'est pas valide\n";
    }
    
    //BotDescription=> a voir
    
    if($alerts <>""){
    
    }else{
      //enregistrer le bot et envoyer un email pour la validation
      
    
    
    }
    
  
    echo "TODO";
    break;
   default:
    error(500,"erf";)
    break;

}