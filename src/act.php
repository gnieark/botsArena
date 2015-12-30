<?php

//Del unvalidated bots
mysqli_query($lnMysql, "DELETE FROM bots WHERE active='0' AND TIMESTAMPDIFF(DAY, NOW(), date_inscription) > 2");
mysqli_query($lnMysql, "DELETE FROM bot_modifs WHERE TIMESTAMPDIFF(DAY, NOW(), date_modification) > 2");

switch($_POST['act']){
  case "addBot":
    //verifier les variables "botName""botGame""botURL""email""botDescription"
    
    $alerts="";
    
    //botGame -> doit exister
    if(!does_arena_exist($_POST['botGame'],$arenas)){
      error(404,"wrong post parameter");
      die;
    }
    
    //botname -> il ne doit pas y avoir un autre bot du même nom sur le même jeu
    $rs=mysqli_query($lnMysql,
	"SELECT 1 
	 FROM bots 
	 WHERE name='".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botName']))."'
	 AND game='".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."';");
    if(mysqli_num_rows($rs) > 0){
      $alerts.="Un bot existant pour ce jeu porte le même nom.\n";
    }
    
    //BotUrl
    if(!preg_match("/^(http|https):\/\//", $_POST['botURL'])){
      $alerts.="L'URL n'est pas valide.\n";
    }
    
    //email => doit être valide
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $alerts.="L'email n'est pas valide.\n";
    }
    
    if($alerts <>""){
      //do nothing now
    }else{
      //enregistrer le bot et envoyer un email pour la validation
      
      $secret=rand_str(7, '$-_.+!*(),ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
      //last char must be alphanum. Mail client should cut url if isn't.
      $secret.=rand_str(1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
      
      $sql =  "INSERT INTO bots (name,game,url,description,active,date_inscription,validate_secret,author_email) VALUES(
		'".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botName']))."',
		'".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."',
		'".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botURL']))."',
		'".mysqli_real_escape_string($lnMysql,
		    preg_replace('#^(http|https|mailto|ftp)://(([a-z0-9\/\.\?-_=\#@:~])*)#i','<a href="$1://$2">$1://$2</a>'
		    ,nl2br(htmlentities($_POST['botDescription'])))
		    )."',
		'0',
		NOW(),
		'".$secret."',
		'".mysqli_real_escape_string($lnMysql,$_POST['email'])."'";  
		
	$rs=mysqli_query($lnMysql,$sql);
                
        include __DIR__."/config.php";
        require __DIR__.'/PHPMailer/PHPMailerAutoload.php';
        
        $mail = new PHPMailer;
	$mail->isSMTP();
	//$mail->IsHTML(true);
	//$mail->SMTPDebug = 2;
	$mail->Debugoutput = 'html';
	$mail->Host = $smtpParams['host'];
	$mail->Port = $smtpParams['port'];
	$mail->SMTPSecure = $smtpParams['secure'];
	$mail->SMTPAuth = true;
	$mail->Username = $smtpParams['username'];
	$mail->Password = $smtpParams['pass'];
	$mail->setFrom($smtpParams['username'], 'Bots Arena');
	$mail->Subject = 'BotsArena';
	$mail->addAddress($_POST['email']);
	//$mail->msgHTML=$lang['E_MAIL_ADD_BOT_INTRO_HTML'].'<p><a href="'.$siteParam['BASEURL'].'validateBot/'.$secret.'">'.$siteParam['BASEURL'].'validateBot/'.$secret.'</a></p>'.$lang['E_MAIL_ADD_BOT_SIGNATURE_HTML'];
	$mail->Body = $lang['E_MAIL_ADD_BOT_INTRO']."\n".$siteParam['BASEURL'].'p/addBot/'.$secret."\n".$lang['E_MAIL_ADD_BOT_SIGNATURE'];
	if (!$mail->send()) {
	    error(500,"Mailer Error: " . $mail->ErrorInfo);
	} else {
	    //echo "Message sent!";
	}     
    }
    

    break;
    
  case "editBot":
    if(!does_arena_exist($_POST['botGame'],$arenas)){
      error(404,"wrong post parameter");
      die;
    }
    $err="";
    
    //check author e-mail
    $rs=mysqli_query($lnMysql,
    "SELECT 1 FROM bots 
      WHERE author_email='".mysqli_real_escape_string($lnMysql,$_POST['email'])."'
      AND id='".mysqli_real_escape_string($lnMysql,$_POST['botId'])."'"
    );
    if(!$r=mysqli_fetch_row($rs)){
      $err.= "L'adresse e-mail ne correspond pas à celle enregistrée\n";  
    }
    //check name
    $rs=mysqli_query($lnMysql,
      "SELECT 1 FROM bots 
      WHERE name='".mysqli_real_escape_string($lnMysql,htmlentities($_POST['botName']))."'
      AND game='".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."'
      AND id <> '".mysqli_real_escape_string($lnMysql,$_POST['botId'])."'"
    );
      
    if($r=mysqli_fetch_row($rs)){
      $err.="Un bot du même nom existe déjà";
    }
    //BotUrl
    if(!preg_match("/^(http|https):\/\//", $_POST['botURL'])){
      $alerts.="L'URL n'est pas valide.\n";
    }
    if($err <> ""){
    
      //save bot on temp table
      $secret=rand_str(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
      mysqli_query($lnMysql,
       " INSERT INTO bots_modifs( name, game, url, description, date_modification, validate_secret, author_email) VALUES (
	  '".mysqli_real_escape_string($lnMysql,html_entities($_POST['botName']))."',
	  '".mysqli_real_escape_string($lnMysql,$_POST['botGame'])."',
	  '".mysqli_real_escape_string($lnMysql,$_POST['botURL'])."',
	  '".mysqli_real_escape_string($lnMysql,
	    preg_replace('#^(http|https|mailto|ftp)://(([a-z0-9\/\.\?-_=\#@:~])*)#i','<a href="$1://$2">$1://$2</a>'
	    ,nl2br(htmlentities($_POST['botDescription'])))
	   )."',
	   
	  NOW(),
	  '".$secret."',
	  '".mysqli_real_escape_string($lnMysql,$_POST['email'])."'"
	);
	
	//send e-mail
	
	include __DIR__."/config.php";
        require __DIR__.'/PHPMailer/PHPMailerAutoload.php';
        
        $mail = new PHPMailer;
	$mail->isSMTP();
	//$mail->IsHTML(true);
	$mail->SMTPDebug = 2;
	$mail->Debugoutput = 'html';
	$mail->Host = $smtpParams['host'];
	$mail->Port = $smtpParams['port'];
	$mail->SMTPSecure = $smtpParams['secure'];
	$mail->SMTPAuth = true;
	$mail->Username = $smtpParams['username'];
	$mail->Password = $smtpParams['pass'];
	$mail->setFrom($smtpParams['username'], 'Bots Arena');
	$mail->Subject = 'BotsArena';
	$mail->addAddress($_POST['email']);
	$mail->Body = $lang['E_MAIL_EDIT_BOT']."\n".$siteParam['BASEURL'].'p/editBot/'.$secret."\n".$lang['E_MAIL_ADD_BOT_SIGNATURE'];
	if (!$mail->send()) {
	    error(500,"Mailer Error: " . $mail->ErrorInfo);
	} else {
	    //echo "Message sent!";
	}     
	  
	  
    
    }else{
      echo $err;
    }

    
    break;
    
   default:
    error(500,"erf");
    break;

}