<?php 
  require_once '../include.php';
  global $settings_new;  												  
                                       
  $container_file = "container.tpl.php";

  $titlePage = (isset($_GET['page']) && $_GET['page']>1) ? ' | Page '.$_GET['page'] : ''; 
    
	$id = intval( $_GET[ 'id' ] ); 
	$page = intval( (isset( $_GET[ 'page' ] ) && is_numeric( $_GET[ 'page' ] )) ? $_GET[ 'page' ] : 1  );
	$contentObj = new Content();
	$article = $contentObj->getDetailPageData( $id, NULL, array('pg'=>$page));			
    $guids =  $contentObj->get_prev_next_ids($article['data'][0]['section_parentid'],$article['data'][0]['id']);
     
	
  $sectionID =$article['data'][0]['section_parentid'];
  if(($article['data'][0]['expirydate'] !='' && ($article['data'][0]['expirydate'] <=  @date("Y-m-d H:i:s"))) || $article['data'][0]['status']!=1 ){
    $redirect_to = BASE_URL;
    header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' .$redirect_to);
		exit;
  }
  $article =  $article['data'][0];
  if($guids['prev_guid']){ 
  	$article['prev_article_guid'] = $guids['prev_guid'];
  }else{
  	$article['prev_article_guid'] ='';
  }
  if($guids['next_guid']){
  	$article['next_article_guid'] = $guids['next_guid'];
  } else{
  	$article['next_article_guid']='';
  }  
    $_GET['section_id'] = $article['section_parentid'];    
	$metatitle = str_replace('indiatimes.com', 'Indiatimes Mobile', $article[ 'page_title' ].''.$titlePage);
    $metadescription = $article[ 'page_description' ];
	if ( !empty($article['guid_previous']) ) {
	  $rel_prev_link = '<link rel="prev" href="'.$article['guid_previous'].'">';
    }
    if ( !empty($article['guid_next']) ) {
	  $rel_next_link = '<link rel="next" href="'.$article['guid_next'].'">';
    }
  
  function setup_module($column, $module, $obj) {
  global $article, $page; 
  switch ($module) {
	  case 'ArticlePageModule':
		  $obj->article = $article;
	      $obj->page = $page;
        break;    
   }  
  }
  $_GET['sectionID'] = $article['section_parentid'];
  $page = new PageRenderer("setup_module", PAGE_ARTICLE_PAGE, $metatitle, $container_file,'header.tpl.php', $settings_new);
  if($page->footer){
    $page->footer->set('sectionID', $article['section_parentid']);
  }
  $page->add_header_js('flashblock-detector.js');  
  $page->add_header_js('js.js'); 
  $page->add_header_js('socialShare.js'); 
  echo $page->render();
?>
