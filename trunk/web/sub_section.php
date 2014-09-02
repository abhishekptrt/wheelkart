<?php
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
 
  function setup_module($column, $module, $obj) {
  global $form_data, $edit, $paging;
  switch ($module) {
   }  
  }  
  $page = new PageRenderer("setup_module", PAGE_SUB_SECTION_PAGE, $metatitle, $container_file,'header.tpl.php', $settings_new); 
  $page->footer->set('sectionID', $sectionID);  
  echo $page->render();
?>
