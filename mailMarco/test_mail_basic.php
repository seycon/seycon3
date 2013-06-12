<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<?php

require_once('class.phpmailer.php');

$mail  = new PHPMailer(); 

$body  = 
"<body style='margin: 10px;'>
<div style='width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;'>
<div align='center'><img src='images/logotipo.png' style='height: 60px; width: 55px'></div><br>
<br>
<p>
Esta es un mensaje enviado desde el servidor de seyconsoft 
</p>
</div>
</body>";
//$body             = preg_replace('/[\]/','',$body);

$mail->SetFrom('marquito.futuro@gmail.com', 'marco');

$mail->AddReplyTo("marquito.futuro@gmail.com","marco");

$address = "zadorodrigo@hotmail.es";
$mail->AddAddress($address, "Marco");
//$mail->AddAddress("eguez@consultoraguez.com", "Jorge");
$mail->AddAddress("marquito.futuro@gmail.com", "Marco");

$mail->Subject  = "Envio de Correo desde Seycon";

$mail->AltBody  = "Marco Rodrigo"; 

$mail->MsgHTML($body);

$mail->AddAttachment("images/phpmailer.gif");      
$mail->AddAttachment("images/phpmailer_mini.gif"); 

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
