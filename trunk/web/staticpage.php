<?php 
 require_once '../include.php'; 
 global $settings_new;

 if(isset($_GET['type']) && $_GET['type'] == 'tc'){
	 $metatitle = 'Terms and Conditions | Indiatimes Mobile';
	 $metadescription = 'Go through this page to know about Indiatimes.com terms and conditions for the users. TIL may change or update this information from time to time entirely at its own discretion';
 } else {
	$metatitle = 'Privacy Policy | Indiatimes Mobile';
	$metakeyword = '';
	$metadescription = 'Know how we use the user’s information, cookies, information sharing, accessing and updating personal information under privacy policy of Indiatimes Mobile';
 }
function setup_module($column, $module, $obj) {
    switch ($module) {
   }  
}
$page = new PageRenderer("setup_module", PAGE_STATIC_PAGE, $metatitle, 'container.tpl.php','header.tpl.php', $settings_new); 
echo $page->render();
