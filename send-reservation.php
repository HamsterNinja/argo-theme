<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once ('include/PHPMailer/src/Exception.php');
require_once ('include/PHPMailer/src/PHPMailer.php');
require('../../../wp-load.php');

if ($_POST){

$first_name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['comment'];
$date = $_POST['date'];
$time = $_POST['time'];
$guests = $_POST['guests'];
$halls = $_POST['halls'];
$table = $_POST['table'];

$bodytext = '<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <title>Email title</title>
  <!--[if mso]>
  <xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
  </xml>
  <![endif]-->
  <style>
    :root {
      color-scheme: light dark;
      supported-color-schemes: light dark;
    }
  </style>
</head>
<body class="body">
  <div role="article" aria-roledescription="email" aria-label="email name" lang="en" style="font-size:1rem">'; 

if ($first_name) {
  $bodytext .= "<p>Имя - ".$first_name."</p>";
}

if ($phone) {
  $bodytext .= "<p>Телефон - ".$phone."</p>";
}

if ($date) {
  $bodytext .= "<p>Дата - ".$date."</p>";
}

if ($time) {
  $bodytext .= "<p>Время - ".$time."</p>";
}

if ($guests) {
  $bodytext .= "<p>Гостей - ".$guests."</p>";
}

if ($halls) {
  $bodytext .= "<p>Зал - ".$halls."</p>";
}

if ($table) {
  $bodytext .= "<p>Стол - ".$table."</p>";
}

if ($message) {
  $bodytext .= "<p>Сообщение - ".$message."</p>";
}

$bodytext .= "</div>
</body>
</html>";

// $admin_email = 'info@argo.cafe';
$admin_email = 'andreroshkin@gmail.com';

$email = new PHPMailer(true);
try {
  
  $email->ClearAttachments(); 
  $email->ClearCustomHeaders(); 
  $email->ClearReplyTos(); 
  
  $email->SingleTo = true;
  $email->ContentType = 'text/html'; 
  $email->IsHTML( true );
  $email->CharSet = 'utf-8';
  $email->ClearAllRecipients();
  $email->From      = $admin_email;
  $email->FromName  = 'Argo';
  $email->Subject   = 'Новое бронирование';
  $email->Body      = $bodytext;
  $email->addAddress($admin_email);  

 
  
  if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $email->AddAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
  }
  if (!$email->send()) { 
    $result = array('status'=>"error", 'message'=>"Mailer Error: ".$email->ErrorInfo);//
    echo json_encode($result);
  } else {
      $result = array('status'=>"success", 'message'=>"Message sent.");
      echo json_encode($result);
  }
}
catch (Exception $e) {
  $result = array('status'=>"error", 'message'=>'Message could not be sent. Mailer Error: '.$email->ErrorInfo);
  echo json_encode($result);
}
}
?> 