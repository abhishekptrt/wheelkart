<?php 
  
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
  $startval = 0;
  $MAX_FORMS_PER_PAGE = 10;
  $int_limit = 10;
  $query = (isset($_GET['q']) && !empty($_GET['q'])) ? $_GET['q'] : '';
  $query = rtrim($query, '/');
  $get_search_string = strip_tags($query);
   
  $page = strip_tags(intval(isset($_GET['pg']) && !empty($_GET['pg']))) ? $_GET['pg'] : 0;
  $get_limit = strip_tags(isset($_GET['limit'])&& intval($_GET['limit']))  ? $_GET['limit']: 0;
  if($page!=0){
	  $startval = ($page - 1) * $MAX_FORMS_PER_PAGE;
  }
  
  $filters = array();
  $filters['q'] = $query;
  $searchData = Content::getSearchContents($filters, $startval, $MAX_FORMS_PER_PAGE);
  //p($searchData);
  function setup_module($column, $module, $obj) {
    global $searchData, $page, $query;
    switch ($module) {
     case 'SearchModule':  
		  $obj->searchData = $searchData;
      $obj->page = $page;
      $obj->query = $query;
      break;
   }  
  }
$title = null;
if($query){
  $metatitle  = 'Search Results for '.$query.' | Indiatimes Mobile';
  $metadescription = 'Click below the results obtained for the search term {search-term} at  Indiatimes Mobile';
}

$page = new PageRenderer("setup_module", PAGE_SEARCH_PAGE, $metatitle, $container_file,'header.tpl.php', $settings_new); 

echo $page->render();
