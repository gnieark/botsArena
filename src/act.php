<?php

switch($_POST['act']){
  case "addBot":
    //verifier les variables "botName""botGame""botURL""email""botDescription"
    
    //botname -> il ne doit pas y avoir una autre bot avec les 8 même premiers caracteres
    
    //botGame -> doi exister
    
    //BotUrl (doit retourner un code 200)
    
    //email => doit être valide
    
    //BotDescription=> a voir
  
    echo "TODO";
    break;
   default:
    error(500,"erf";)
    break;

}