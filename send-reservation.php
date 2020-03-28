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

$bodytext = ""; 

if ($first_name) {
  $bodytext .= "<p>Имя - ".$first_name."</p>";
}

if ($phone) {
  $bodytext .= "<p>Телефон - ".$phone."</p>";
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