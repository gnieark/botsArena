<?php

switch($_POST['act']){
  case "addBot":
    //verifier les variables "botName""botGame""botURL""email""botDescription"
    
    $alerts="";
    
    //botGame -> doit exister
    $arenaExists=false;
    foreach($arenas as $arena){
      if($_POST['botGame'] == $arena['id']){
	$arenaExists=true;
	break;
      }
    }
    if(!$arenaExists){
      error(404,"wrong post parameter");
    }
    
    //botname -> il ne doit pas y avoir un autre bot du même nom sur le même jeu
    $rs=mysqli_query($lnMysql,
	"SELECT 1 
	 FROM bots 
	 WHERE name='".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botName']))."'
	 AND game='".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."';");
    if(mysqli_num_rows($rs) > 0){
      $alerts.="Un bot existant pour ce je porte le même nom\n";
    }
    
    //BotUrl (doit retourner un code 200)
    if(!preg_match("/^(http|https):\/\//", $_POST['botURL'])){
      $alerts.="L'URL n'est pas valide\n";
    }
    
    //email => doit être valide
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $alerts.="L'email n'est pas valide\n";
    }
    
    if($alerts <>""){
      echo $alerts;
    }else{
      //enregistrer le bot et envoyer un email pour la validation
      
      $secret=rand_str(8, '$-_.+!*\'(),ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'); 
      
      $rs=mysqli_query($lnMysql,
        "INSERT INTO bots (name,game,url,description,active,date_inscription,validate_secret) VALUES
        (   '".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botName']))."',
            '".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."',
            '".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botURL']))."',
            '".mysqli_real_escape_string($lnMysql,
                preg_replace('#^(http|https|mailto|ftp)://(([a-z0-9\/\.\?-_=\#@:~])*)#i','<a href="$1://$2">$1://$2</a>'
                  ,nl2br(htmlentities($_POST['botDescription'])))
            )."',
            'NOW(),
            '".$secret."'"
        );
        
        include_once (__DIR__."/config.php");
        require_once (__DIR__."/class.phpmailer.php");
        
        
        
        $mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 2;
	$mail->Debugoutput = 'html';
	$mail->Host = $smtpParams['host'];
	$mail->Port = $smtpParams['port'];
	$mail->SMTPSecure = $smtpParams['secure'];
	$mail->SMTPAuth = true;
	$mail->Username = $smtpParams['username'];
	$mail->Password = $smtpParams['pass'];
	$mail->setFrom($smtpParams['username'], 'First Last');
	$mail->Subject = 'BotsArena';
	$mail->msgHTML=$lang['E_MAIL_ADD_BOT_INTRO_HTML'].'<p><a href="'.BASEURL.'validateBot/'.$secret.'">'.BASEURL.'validateBot/'.$secret.'</a></p>'.$lang['E_MAIL_ADD_BOT_SIGNATURE_HTML'];
	$mail->AltBody = $lang['E_MAIL_ADD_BOT_INTRO']."\n".BASEURL.'validateBot/'.$secret."\n".$lang['E_MAIL_ADD_BOT_SIGNATURE'];
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "Message sent!";
	}
        
        
        
    }
    
  
    echo "TODO";
    break;
   default:
    error(500,"erf");
    break;

}