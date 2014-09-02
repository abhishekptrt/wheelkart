<?php 
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
 
  function setup_module($column, $module, $obj) {
  global $form_data, $edit, $paging;
  switch ($module) {
   }  
  }
$page = new PageRenderer("setup_module", PAGE_FEEDBACK_PAGE, 'INDIATIMES Feedback ', $container_file,'header.tpl.php', $settings_new); 
echo $page->render();
