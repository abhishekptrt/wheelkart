<?php  
 require_once "../../include.php"; 
if(!empty($_POST['message']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
     $toMail = 'indiatimes.wap@indiatimes.co.in';
     $subject = 'Indiatimes:feedback mail';
     $body =  htmlspecialchars($_POST['message']);   
     $fromEmail = $_POST['email'];
     sendHTMLMail($toMail, $subject, $body, $fromEmail);
     $data = array( 'Success'=>"Feedback is sent successfully");  
   }else {
     $data = array( 'Error'=>"There is some problem.");
   }

?>
