<?php
 require_once "../../include.php"; 
if ('newsLetterHeadLine' == $_POST['action']) {
   $db = Database::Instance('indiatimes_cms');
    $keyValueArray['news_letter_headline']=$_POST['newsLetterHeadLine'];
    $whereClauseKeyValArray['id']=$_POST['stroy_id'];
    echo $affected_count = $db->updateDataIntoTable($keyValueArray, $whereClauseKeyValArray, 'content', $debug=1);
    
    
} else {

    $email = strip_tags($_POST['email']);
    $section_id = strip_tags($_POST['section_id']);
    $arrInsert = array();

    $arrInsert['insertdate'] = date('Y-m-d H:i:s');
    $arrInsert['ip'] = getClientIP();
    $arrInsert['email'] = $email;
    $arrInsert['section_id'] = $section_id;

    $objComment = new Newsletter();

    $cnt = $objComment->checkEmailSubmit($email);
    if ($cnt == 0) { 
        $ret = $objComment->insertTable($arrInsert);
        if (is_integer($ret) && $ret > 0) {
            $arrReturn['result'] = 'success';
        } else {
            $arrReturn['result'] = 'fail';
        }
    } else {
        $arrReturn['result'] = 'before';
    }
    echo json_encode($arrReturn);
}