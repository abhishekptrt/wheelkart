<?php
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
  function setup_module($column, $module, $obj) {
  global $form_data, $edit, $paging;
  switch ($module) {
   }  
}
      
$metadescription =  "Indiatimes mobile provides you the latest news from India on entertainment, lifestyle, technology, movies, cricket, bollywood and more." ;
$page = new PageRenderer("setup_module", PAGE_GENERAL_PAGE, 'Current News India: Latest News on Entertainment, Lifestyle, Technology, Political, World, Sports - Indiatimes Mobile', $container_file,'header_black.tpl.php', $settings_new);   
$page->add_header_js('ad.js'); 
echo $page->render();
?>
