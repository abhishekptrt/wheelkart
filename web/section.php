<?php
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
	$sectionObj = new Section();
	$sectionID = $_GET['section_id'];
	$sectionDataArr = $sectionObj->getData(array('id' => $sectionID), '*', '', '', 1);

	
	$metatitle = str_replace('indiatimes.com', 'Indiatimes Mobile', strtolower($sectionDataArr['data'][0]['metatitle'])); 
	$metakeyword = str_replace('indiatimes.com', 'Indiatimes Mobile', strtolower($sectionDataArr['data'][0]['metakeyword']));
	$metadescription = str_replace('indiatimes.com', 'Indiatimes Mobile', strtolower($sectionDataArr['data'][0]['metadescription']));
	$sectionName =$sectionDataArr['data'][0]['name'];	
  function setup_module($column, $module, $obj) {
	  global $form_data, $edit, $paging;
	  switch ($module) {
	   }  
  } 
  $page = new PageRenderer("setup_module", PAGE_SECTION_PAGE, $metatitle, $container_file,'header_black.tpl.php', $settings_new);
  $page->footer->set('sectionID', $sectionID);    
  $page->add_header_js('jquery-2.1.0.min.js');  
  $page->header->set('section_name', $sectionName);  
  echo $page->render();
?>
