<?php //print '<pre>'; print_r($_POST); die;
 require_once "../../include.php"; 
if(!empty($_POST)){ 
	$objEmail = new Share();
	$param = array(); 
	$param['fromEmail'] = strip_tags($_POST['fromEmail']);
	$param['toEmail'] = strip_tags($_POST['toEmail']);
	$arrTpl = array();
	$arrTpl['toName'] = '';
	$arrTpl['fromName'] =  strip_tags($_POST['fromName']);
	$arrTpl['message'] =  strip_tags($_POST['message']);
	$arrTpl['id'] =  isset($_POST['article_id']) ? $_POST['article_id'] : "";
	$arrTpl['fromEmail'] = strip_tags($_POST['fromEmail']);
    $arrRes = $objEmail->getTemplateForEmail($arrTpl);
	$param['subject'] = $arrRes['subject'];
	$param['message'] = $arrRes['message'];
	$param['id'] = $arrTpl['id'];

	$objEmail->viaEmail($param);
	echo $msg = 'Your email with the article link has been sent.';
} 

?>