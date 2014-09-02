<?php
  require_once MODULES.'/Module/Module.php';
  class ListingPageModule extends Module{
    public $outer_template = 'outer_public.tpl';
    public $inner_template = 'inner.tpl.php';
    public $type = null;
	
   function __construct() {
      parent::__construct();
   }
    function render() {
		global $section_data;
		$contentObj = new Content();
		$params = array( );
		$page = isset($_GET['pg'])?$_GET['pg']:1;
		$params['limit']	= 10;
  	    $params['start'] = ($page-1)*10; 
		if(isset($_GET['type']) && $_GET['type'] == 'gallery'){				
				$params['contype_id_in'] =  PHOTOGALLERY .','. PICTURESTORY;				
				$params['is_aggregator_data']=0;				
				//$params['debug']	= 2;
			 	$this->sub_sectiondata = $contentObj->getContentList($params,true);
				$params['result_type']='count';						
				$total_article_cnt_val = $contentObj->getContentList($params,true);
				$objPaginate = new Pagination('', '12', 4, strtolower(BASE_URL.'/photogallery/'),"pg-", $page);
				$this->section_data = array('name'=>'PHOTO GALLERY');		 
	    } else if(isset($_GET['type']) && $_GET['type'] == 'quiz') {
            $params['contype_id']	= QUIZ; 
			$params['is_aggregator_data']=0;				
			//$params['debug']	= 2;
			$this->sub_sectiondata = $contentObj->getContentList($params,true);
			$params['result_type']='count';						
			$total_article_cnt_val = $contentObj->getContentList($params,true);
			$objPaginate = new Pagination('', '12', 4, strtolower(BASE_URL.'/quiz/'),"pg-", $page);
			$this->section_data = array('name'=>'Quizzes');		 
			
		}

        $objPaginate->paginate($total_article_cnt_val);        
        $this->page_links =  $objPaginate->renderPrev(). $objPaginate->renderNav('', ''). $objPaginate->renderNext();
        
        
        $this->objPaginate = $objPaginate;
        $this->inner_HTML = $this->generate_inner_html();
        $content = parent::render();
       return $content;
    }
   
    function get_paginationObj(){ 
      return $this->objPaginate;
    }
    
    function generate_inner_html () {
		
      $obj_inner = & new Template(MODULES.DS.'ListingPageModule'.DS.'inner.tpl.php');	        
	  $obj_inner->set('sub_sectiondata', $this->sub_sectiondata);         
	  $obj_inner->set_object('objPaginate', $this->objPaginate); 
	  $obj_inner->set('page_links', $this->page_links);
	  return $obj_inner->fetch();	  
      
    }  
  }
?>
