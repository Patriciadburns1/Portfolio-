<?php
require_once('email_config.php');
require('phpmailer/PHPMailer/PHPMailerAutoload.php');
$message=[]; 
$output =[
    'success'=> null,
    'messages' => []
]; 
$message['name'] = filter_var($_POST['name'],FILTER_SANITIZE_STRING); 

if(empty($message['name'])){
    $output['success']=false; 
    $output['message'][] = 'missing name key'; 
}

$message['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRING); 
if(empty($message['email'])){
    $ouput['success']= false;
    $ouput['message'][]='missing message key'; 
}

$message['subject'] = filter_var($_POST['subject'], FILTER_SANITIZE_STRING); 

if($output['success']!== null){
    http_response_code(400); 
    echo json_encode($output);
    exit(); 
}

$mail = new PHPMailer;

$mail -> From = $message ['email']; 
$mail -> FromName = $message ['name']; 
$mail -> addAddress (EMAIL_TO_ADDRESS, EMAIL_USERNAME); // add a recipient 



$mail->SMTPDebug = 3;           // Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username
$mail->Password = EMAIL_PASS;   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = 'example@gmail.com';  // sender's email address (shows in "From" field)
$mail->FromName = 'Example Name';   // sender's name (shows in "From" field)
$mail->addAddress('recipient1@example.com', 'First Recipient');  // Add a recipient
//$mail->addAddress('ellen@example.com');                        // Name is optional
$mail->addReplyTo('example@gmail.com');                          // Add a reply-to address
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(false);  // Set email format to HTML

$message['subject']  = $message ['name']. 'has sent you a message on your portfolio' // if no subject 

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';






if(!$mail->send()) {
    $output['success']= false ; 
    $output['message'][] = $mail->ErrorInfo;
} else {
    $output['success']= true ; 
}
 echo json_encode($output); 



?>

