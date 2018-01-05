<?php
//Written by: FengRong
//Debugged by Rong Zhang
//Assisted by Jingxuan Chen
function sendemail($user,$email,$stockname,$stopprice,$price){

require_once 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'stockpredictiong10@gmail.com';                 // SMTP username
$mail->Password = 'segroup10';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('stockPrediction123@gmail.com', 'Stock Forecaster');
$mail->addAddress($email, $user);     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = "Your notification from Stock Forecaster!";
$mail->Body    = "Your stock ".$stockname." price is going <b>lower</b> than ".$stopprice.".<br />The current price of ".$stockname." is ".$price.".<br /><br />Group 10<br />Stock Forecaster" ;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
        
}
?>