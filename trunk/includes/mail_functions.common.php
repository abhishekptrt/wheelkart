<?php

define('SITE_MAILER_TEAM', 'indiatimes.com');
define('RETURNPATH', 'noreply@indiatimes.com');



/*
  $toEmail = To Email address
  $url = Url For Verification
  $linkText = Text to appear in anchor tag
 */

function mailSSOVerificationCode($toEmail, $vc) {
  $strMessage = 'Hi ';
  if ($toEmail != '') {
    $strMessage .= $toEmail;
  }
  
  $url = SITEPATH.'/activation.php?code='.$vc.'&unm='.$toEmail;

  $strMessage .= '! <br/><br/>Thanks for signing up with Indiatimes.com. In order to complete your registration, please verify your email address by clicking on the link below: ';  
  $strMessage .= '<br/><a href="' . $url . '"';  
  $strMessage .= ' target="_blank" >' . $url . '</a>';
  $strMessage .= '<br/>(If clicking on the above link doesn\'t work, try copy and pasting it into your browser)';
  $strMessage .= '<br/><br/>To ensure that you receive future emails from us, please add <a href="mailto:mailerservice@indiatimes.com">mailerservice@indiatimes.com</a> to your address book or as an approved sender in your email settings.';
  $strMessage .= '<br/><br/>Regards,<br/><br/>The Indiatimes Team';
  $strMessage .= '<br/><br/>----------------------------------';
  $strMessage .= '<br/><br/>** Disclaimer **';
  $strMessage .= '<br/>This is a public forum provided by Indiatimes.com for its users to share their views with friends/public at large. Indiatimes.com is not responsible for the content of this email. Anything written in this email does not necessarily reflect the views or opinions of Indiatimes.com. Please note that neither the email address nor the name of the sender has been verified.';
  
  $subject = 'Verification mail from '.SITE_TITLE;
  
  sendHTMLMail($toEmail, $subject, $strMessage);
}

// eof getTemplateForVerificationMail

/*
  $toEmail = To Email address
 */

function welcomeMailAfterSSOVerification($toEmail) {
  $strMessage = 'Welcome ';
  if ($toEmail != '') {
    $strMessage .= $toEmail.', ';
  }

  $strMessage .= '<br/><br/>You are now a member of the Indiatimes Network.';
  $strMessage .= '<br/><br/>You can use this email address to sign in across the Indiatimes network and';
  $strMessage .= '<br/><br/>- Connect to news that matters to you';
  $strMessage .= '<br/>- Save articles to read at leisure';
  $strMessage .= '<br/>- Ask questions, get answers. Tune into QnA';
  $strMessage .= '<br/>- and much more...';
  $strMessage .= '<br/><br/>Regards,';
  $strMessage .= '<br/><br/>The Indiatimes Team';
  $strMessage .= '<br/><br/>----------------------------------';
  $strMessage .= '<br/><br/>** Disclaimer **';
  $strMessage .= '<br/>This is a public forum provided by Indiatimes.com for its users to share their views with friends/public at large. Indiatimes.com is not responsible for the content of this email. Anything written in this email does not necessarily reflect the views or opinions of Indiatimes.com. Please note that neither the email address nor the name of the sender has been verified.';
  
  $subject = 'Welcome mail from Indiatimes';

  sendHTMLMail($toEmail, $subject, $strMessage);
}

// eof getTemplateForWelcomeMail

/*
  $toEmail = To Email address
  $paassword = Newly generated password
 */

function mailPassword($toEmail, $password) {
  $strMessage = 'Hi ';
  if ($toEmail != '') {
    $strMessage .= $toEmail.', ';
  }

  $strMessage .= '<br/><br/>You recently requested for your Indiatimes.com password to be sent to this registered email address. Your password is given below:';
  $strMessage .= '<br/><br/>Password : '.$password;  
  $strMessage .= '<br/><br/>If you have received this message in error or did not make this password request, please disregard this email. ';
  $strMessage .= '<br/><br/>Regards,';
  $strMessage .= '<br/>The Indiatimes Team';
  $strMessage .= '<br/><br/>---------------------------------- ';
  $strMessage .= '<br/><br/>** Disclaimer **';
  $strMessage .= '<br/>This is a public forum provided by Indiatimes.com for its users to share their views with friends/public at large. Indiatimes.com is not responsible for the content of this email. Anything written in this email does not necessarily reflect the views or opinions of Indiatimes.com. Please note that neither the email address nor the name of the sender has been verified.';
  
  $subject = 'Mail from Indiatimes.com';

  sendHTMLMail($toEmail, $subject, $strMessage);
}

function mailResetPassword($toEmail, $token, $first_name='User')
{    
    $forgotpassword_url = SITEPATH.'/resetpassword.php?authtoken='.$token.'&unm='.$toEmail;
    $first_name = ($first_name!='') ? $first_name:'User';

    $message  = '';
    $message .= '<p>Dear '.$first_name.',</p>';
    $message .= '<p>As requested, this e-mail provides your User ID and a link for resetting your account password for Indiatimes.<br />Please follow these instructions to register a new password.</p>';

    $message .= '<p>1) Note your Email address - '.$toEmail.'</p>';
    $message .= '<p>2) Click the link below and follow the instructions provided to reset your account password.<br /><br />';
    $message .= '<a target="_blank" href="'.$forgotpassword_url.'">'.$forgotpassword_url. '</a></p>';
    $message .= '<p>(If the link is not clickable, you can copy and paste the link into your web browser address window.)<br /><br />Link: '.$forgotpassword_url.'</p>';    

    $message .= '<br/><br/>Regards,';
    $message .= '<br/>The Indiatimes Team';

    $mailSubject = "Indiatimes - Reset Password Request";
        
    sendHTMLMail($toEmail, $mailSubject, $message);    
}

// eof getTemplateForForgotPassword

// eof getTemplateForForgotPassword

/*
  $toEmail = To Email address
  $url = Url For Verification
  $linkText = Text to appear in anchor tag
 */

function mailShutterbugVerificationCode($name='User',$toEmail, $vc) {
  $strMessage = 'Hi ';
  if ($toEmail != '') {
    $strMessage .= ucwords($name).'!';
  }
  
  $url = SITEPATH.'/shutterbug_activation.php?code='.$vc.'&unm='.$toEmail;

  $strMessage .= ' <br/><br/>You are only one step away before you can begin to participate in ShutterBug. You are required to click on the verification link below. ';  
  $strMessage .= '<br/><a href="' . $url . '"';  
  $strMessage .= ' target="_blank" >' . $url . '</a>';
  $strMessage .= '<br/>(If clicking on the above link doesn\'t work, try copy and pasting it into your browser)';
  $strMessage .= '<br/><br/><b>Please remember that all future communication with respect to your participation in ShutterBug would be communicated to you via email, to this address.</b>';
  $strMessage .= '<br/><br/>For any query, reach out to us at help.shutterbug@gmail.com';
  $strMessage .= '<br/><br/>Regards,<br/><br/>Team ShutterBug';
   
  $subject = 'Verification for Indiatimes ShutterBug ';
  $fromEmail = 'ShutterBug <noreply@indiatimes.com>';
  sendHTMLMail($toEmail, $subject, $strMessage, $fromEmail, $fromEmail);
}

function welcomeMailAfterShutterbugVerification($name='User', $toEmail, $pwd = null) {
  $strMessage = 'Welcome ';
  if ($toEmail != '') {
    $strMessage .= ucwords($name).'! ';
  }
    $obj = new ShutterBug();
    $arr = $obj->getCurrentRunningThemeDetail();
 
  $strMessage .= '<br/><br/>You have been successfully registered for Indiatimes ShutterBug.';
  if($pwd){
	$strMessage .= '<br/><br/>Password :'.$pwd;
  }
  $strMessage .= '<br/><br/> You may now begin to send us entries for the Theme of the Week: <b>'.$arr['name'].' </b>. <Theme description>';
  $strMessage .= '<br/><br/>There are two ways you can do that:';
  $strMessage .= '<br/>1. <b>Attach your entries and mail it to us on <b>indiatimes.shutterbug@gmail.com</b>. Send us one image per email and caption/title goes into subject line.</b>';
  $strMessage .= '<br/>2. <b>Click your image using Instagram. Remember to add </b><b>#'.SHUTTERBUG_TAG.'</b>.';
  $strMessage .= "<br/><br/>We strongly advise you to go through our detailed Guidelines.";
  $strMessage .= '<br/><br/>Welcome aboard. Have fun clicking!';
  $strMessage .= '<br/><br/>Team ShutterBug';
  $strMessage .= '<br/><br/>For any queries, drop us a mail at help.shutterbug@gmail.com';
    
  $subject = 'Welcome to Indiatimes ShutterBug';
  $fromEmail = 'ShutterBug <noreply@indiatimes.com>';
  sendHTMLMail($toEmail, $subject, $strMessage, $fromEmail);
}

?>