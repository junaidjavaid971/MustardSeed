<?php

require 'phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.gmail.com";
//Set TCP port to connect to
$mail->Port = 587;    
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";  
//Provide username and password     
$mail->Username = "junaidjavaid971@gmail.com";                 
$mail->Password = "jipbbzvmwiechlko";                           
                         

$mail->setFrom('junaidjavaid971@gmail.com' , 'Junaid Javed');
$mail->addAddress("muhammad.zohaib@aksa-sds.com", "Muhammad Zohaib");
$mail->addReplyTo('junaidjavaid971@gmail.com');

$mail->isHTML(true);

$mail->Subject = "DhabaShaba Test Email";
$mail->Body = "This is the automated email from DhabaShaba, please confirm if you received the email";

if($mail->send()) {
    echo "Message has been sent successfully";
}else {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>