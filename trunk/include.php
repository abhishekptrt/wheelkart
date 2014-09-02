<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once 'env.config.php';
$path_prefix = ROOT;
require_once ROOT.'/includes/constants.config.php';
require_once INCLUDES.'/html_generate.php';
require_once INCLUDES.'/functions.php';
require_once INCLUDES.'/imagefunctions.php'; 
require_once INCLUDES.'/page.php';
require_once INCLUDES.'/mail_functions.common.php';

function indiatime_wap_autoload($className){
	if ( file_exists(ROOT . '/classes/' . $className . '.class.php') ){
		include_once(ROOT . '/classes/' . $className . '.class.php');
	}
}
spl_autoload_register('indiatime_wap_autoload');
//include(VENDORS.'/log4php/Logger.php');




?>
