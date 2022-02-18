<?php
function sendEmail($to,$subject,$message){
$messagePreset = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head> 
  <meta charset='UTF-8'> 
  <meta content='width=device-width, initial-scale=1' name='viewport'> 
  <meta name='x-apple-disable-message-reformatting'> 
  <meta http-equiv='X-UA-Compatible' content='IE=edge'> 
  <meta content='telephone=no' name='format-detection'>
</head> 
<body>
    <h1 color='red'>EBT Alert</h1>
    <hr/>
    bodybody
    <br/><br/><br/>
    <hr/>
    <h4>EBT Demo Site has sent you this email, is only a demostrative email, a demo, for info:</h4>
    <a href='https://$_SERVER[SERVER_NAME]/index.php?myaccount'>EBT Alert</a>
</body>
</html>";

    $message = str_replace("bodybody",$message,$messagePreset);

    $from = 'postmaster@ebtalert.cloud';
    // Genera un boundary
    $mail_boundary = "=_NextPart_" . md5(uniqid(time()));
     
    $sender = "postmaster@ebtalert.cloud";
    
     
    $headers = "From: $sender\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
    $headers .= "X-Mailer: PHP " . phpversion();
     
    // Corpi del messaggio nei due formati testo e HTML
    $text_msg = $message;
    $html_msg = $message;
     
    // Costruisci il corpo del messaggio da inviare
    $msg = "This is a multi-part message in MIME format.\n\n";
    $msg .= "--$mail_boundary\n";
    $msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
    $msg .= "Content-Transfer-Encoding: 8bit\n\n";
    $msg .= $message;
     
    $msg .= "\n--$mail_boundary\n";
    $msg .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    $msg .= "Content-Transfer-Encoding: 8bit\n\n";
    $msg .= $message;
     
    // Boundary di terminazione multipart/alternative
    $msg .= "\n--$mail_boundary--\n";
     
    // Imposta il Return-Path (funziona solo su hosting Windows)
    ini_set("sendmail_from", $sender);
     
    // Invia il messaggio, il quinto parametro "-f$sender" imposta il Return-Path su hosting Linux
    mail($to, $subject, $msg, $headers, "-f$sender");

}

?>