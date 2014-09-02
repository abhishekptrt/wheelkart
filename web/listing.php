<?php
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
 
 $titlePage = (isset($_GET['pg']) && $_GET['pg']>1) ? ' | Page '.$_GET['pg'] : '';
  if(!empty($_GET['type'])){
      $type = $_GET['type'];
      $metatitle = $meta_info[$type]['title'].$titlePage ;
      $metadescription = $meta_info[$type]['description'];
  }  

  function setup_module($column, $module, $obj) {
  global $form_data, $edit, $paging;
  switch ($module) {
   }  
  }  
  $page = new PageRenderer("setup_module", PAGE_LISTING_PAGE, $metatitle, $container_file,'header.tpl.php', $settings_new); 
 
  echo $page->render();
?>
