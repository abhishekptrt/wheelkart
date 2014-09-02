<?php
  require_once '../include.php';
  global $settings_new;
  $container_file = "container.tpl.php";
   //print_r($_GET); die;
    
	$url_author_name = $author_name		= $_GET['param'];
	$objDb = Database::Instance();
	$authorArr = $objDb->getDataFromTable(array('name'=>str_replace("-"," ",$author_name)), 'author', 'id,biodata,twitter,websiteurl,thumbnail,facebook,name,author_big_thumbnail');
	$authorArr = $authorArr['data'][0];
    $fields = array('c.headline1','c.carousal_headline', 'c.summary', 'c.thumbnail', 'c.thumbnail_alt','c.source_id' ,'c.source_url','c.source_alias','c.publishdate','c.guid','c.contype_id','csr.section_name','csr.section_id','is_aggregator_data','aggregator_url', 'c.is_exclusive', 'c.by_line_author_id','c.by_line_author_id_text','c.by_line_columnist','c.list_count');
	$args['fields'] = $fields;
	$author_name 		= str_replace('-and-', ' & ',  $author_name);
	$author_name 		= str_replace( '-', ' ', $author_name);
	$args['author_name']= $author_name; 	
	$args['groupby'] =' csr.content_id ';
	$page=isset($_GET['pg'])?$_GET['pg']:1;
	$args['limit']	= 12;

	$args['start'] = ($page-1)*$args['limit'];
	$args['is_aggregator_data'] = 0 ;
	$contentObj = new Content;
	$totalrec='';
	$articles = $contentObj->getContentList($args); 
	$args['result_type']='count';		
    $total_article_cnt_val = $contentObj->getContentList($args,true);
	$args['author_name']= $author_name; 
	$objPaginate = new Pagination('', '12', 4, strtolower(BASE_URL.'/author/'.$url_author_name.'/'),"pg-", $page);
    $objPaginate->paginate($total_article_cnt_val);        
    $page_links =  $objPaginate->renderPrev(). $objPaginate->renderNav('', '').  $objPaginate->renderNext(); 
	$articles  = $articles['data'];
	$titlePage = ucfirst($author_name).' Indiatimes.com Editor ';
	$metatitle=ucfirst($author_name).' - Indiatimes.com';
	if(!empty($authorArr['biodata']))
	{						
		$metadescription = str_stop($authorArr['biodata'],155);
	}



  function setup_module($column, $module, $obj) {
  global $authorArr, $articles, $page_links;
  switch ($module) {
	 case 'AuthorListingModule':
     $obj->authorArr = $authorArr;
     $obj->articles = $articles;
	 $obj->page_links = $page_links;
   }  
  }
 
  $page = new PageRenderer("setup_module", PAGE_AUTHOR_PAGE, $titlePage, $container_file,'header.tpl.php', $settings_new); 
  echo $page->render();
?>
