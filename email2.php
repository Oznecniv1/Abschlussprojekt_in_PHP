<?php
// Hier findet die automatische E-Mail versendung statt. 
// du musst nur ein paar SQL abfragen setzen und 
// Normale Includes
include	("include/functions.php");
include ("include/config.php");

//Muss drinne sein
include ("include/phpmailer/class.phpmailer.php");

// Die Funktionen für die E-Mail versendung
$mail             = new PHPMailer();
$mail->From       = "hier die mail vom absender eintragen";
$mail->FromName   = "hier der name des absenders rein";
$mail->Subject    = "Betreff der Mail";
$mail->Body       = "Willkommen auf der seite und viel spass beim nachkochen";
$mail->AltBody    = "Nur Text";
$mail->AddAddress("");
// $mail->AddCC("nocheinempfaenger@empf.de");
// $mail->AddBCC("derbccempfaenger@empf.de");
$mail->AddReplyTo("");
$mail->AddAttachment("mail_word.doc");
$mail->AddAttachment("mail_excel.xls");
?>