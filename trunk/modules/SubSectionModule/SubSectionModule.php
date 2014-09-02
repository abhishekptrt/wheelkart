<?php
  require_once MODULES.'/Module/Module.php';
  class SubSectionModule extends Module{
    public $outer_template = 'outer_public.tpl';
    public $inner_template = 'inner.tpl.php';
    public $type = null;
	
   function __construct() {
      parent::__construct();
   }
    function render() { 		
		$contentObj = new Content();
        if (strpos($_GET['param'],'contype_') !== false) {
			$_GET['param'] = rtrim($_GET['param'], "/");
		}else{
			$_GET['param'] = str_replace('/', '', $_GET['param']);
		}

		$paramVal	= explode('/',$_GET['param']);			
		list($callType, $value, $parent_type, $parent_value ) = explode('_', $paramVal[0]);
		list($contype, $contype_value  ) = explode('_', $paramVal[1]);
		$params = array( );
		$page = isset($_GET['pg'])?$_GET['pg']:1;
		$params['limit']	= 12;
  	    $params['start'] = ($page-1)*12; 
		switch ($contype_value){
							case 'article':
								$params['contype_id']	= NEWS;
							break;
							case 'photogallery':
								$params['contype_id'] =  PHOTOGALLERY .','. PICTURESTORY;
							break;
							case 'video':
								$params['contype_id']	= VIDEO; 
							break;							
							case 'quiz': 
								$params['contype_id']	= QUIZ; 
							break;
		}	
        if ( $callType == 'section'){
			 $sectionObj 	= new Section();
			 $value_guid				=$parent_value."/". $value."/" ;
			 $value	        =   str_replace( array('-and-','-'), array( ' & ',' '), $value);
			

			 $section_data 	= $sectionObj->getData(array( 'lower(guid)' => $value_guid ), 'id, parentid ,name,metatitle,metakeyword,metadescription,section_big_thumbnail ','','',1);
			 $section_data = $section_data['data'];
			 if($section_data[0]['parentid'] == 0){
				$params['section_parent_id']	=	$section_data[0]['id'];
               $this->renderer->setFooterVar('sectionID', $section_data[0]['id']); ;  

			 }else {
                $this->renderer->setFooterVar('sectionID', $section_data[0]['parentid']); ;  
				$params['section_id']	= $section_data[0]['id'];
			 }
		  
            $params['is_aggregator_data']	= 0;     		
			$this->sub_sectiondata = $contentObj->getContentList($params,true);
			$params['result_type']='count';		
			$total_article_cnt_val = $contentObj->getContentList($params,true);
			$value 		= 	str_replace( ' ','-', $value);
			$paging_path = BASE_URL.'/'.$parent_value.'/'.$value.'/';
			if( $contype_value ){
				 $paging_path .='contype_'.$contype_value.'/';
				 $this->contype_value = $contype_value;
			}
			$this->section_data = $section_data[0];
			$this->value = $value;
			$this->nav_all = preg_replace("/contype_.*?\//","",$paging_path);			

			$objPaginate = new Pagination('', '12', 4, strtolower($paging_path),"pg-", $page);		
			$objPaginate->paginate($total_article_cnt_val);        
			$this->page_links =  $objPaginate->renderPrev(). $objPaginate->renderNav('', ''). $objPaginate->renderNext();
	
        } 


		
        
        
        $this->objPaginate = $objPaginate;
        $this->inner_HTML = $this->generate_inner_html();
        $content = parent::render();
       return $content;
    }
   
    function get_paginationObj(){ 
      return $this->objPaginate;
    }
    
    function generate_inner_html () {
		
      $obj_inner = & new Template(MODULES.DS.'SubSectionModule'.DS.'inner.tpl.php');	  
      $obj_inner->set('section_data', $this->section_data);         
	  $obj_inner->set('sub_sectiondata', $this->sub_sectiondata);         
	  $obj_inner->set_object('objPaginate', $this->objPaginate); 
	  $obj_inner->set('page_links', $this->page_links);
	  $obj_inner->set('nav_all', $this->nav_all);
	  $obj_inner->set('contype_value', $this->contype_value);
	  $obj_inner->set('value', $this->value);
	  
	  
	  return $obj_inner->fetch();	  
      
    }  
  }
?>
