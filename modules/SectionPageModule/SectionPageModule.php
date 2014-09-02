<?php
  require_once MODULES.'/Module/Module.php';

  class SectionPageModule extends Module{
    public $outer_template = 'outer_public.tpl';
	public $inner_template = 'inner.tpl.php';
	
   function __construct() {
      parent::__construct();
   }
    function render() { 
		global $pageMappingArr,$sectionObj;		
        $sectionID = $_GET['section_id'];
		$cache_id = json_encode(BASE_URL.$_SERVER['REQUEST_URI']);
		$obj_inner =  new CachedTemplate($cache_id, 36);
		if($obj_inner->is_cached()){ 
		  return $obj_inner->fetch_cache();
	    } 
		$pageID = $pageMappingArr[$sectionID]['page'];
		$blockID = $pageMappingArr[$sectionID]['featured']; 
		$pageMgmtObj = new PageManagement();
		$top_box_data = $pageMgmtObj->getData($pageID, $blockID, 3); 
		$this->top_box_data =  $top_box_data['data'];			

		if($sectionID == VIDEOCAFE){ 

			$contentObj = new Content();
			$args = array();
			$args['limit'] = 12;
			$args['fields'] = array('headline1,c.id,summary,erating,thumbnail,thumbnail_alt,guid,is_aggregator_data,aggregator_url,section_name,section_id,section_parentid');
			$args['is_aggregator_data'] = 0;
			$args['contype_id'] = VIDEO;
			$latest_video = $contentObj->getContentList($args);
			$this->latest_video = $latest_video['data'];
           $this->inner_template = 'videocafe.tpl.php';
	    } 
			
			
			$childSectionArr = $sectionObj->getData(array('parentid' => $sectionID, 'status' => 1, 'sqlclause' => 'priority != 0'), '*', 'priority asc');					
			$contentObj = new Content();
			$i = 0; 
			foreach($childSectionArr['data'] as $key => $val){
 			   $sectionData = array();	
				$params = array('section_id' => $val['id'], 'start'=> 0, 'is_aggregator_data'=> 0, 'limit' => 12); 
				$sectionData = $contentObj->getContentList($params,true);
				$params['result_type']='count';			
				$total_cnt = $contentObj->getContentList($params, true);
				$max_pages =  ceil(($total_cnt - 3) / 6 );
				$start = 4;
				$page = 1;		
				$childSectionArr['data'][$i]['sub_section_data'] = $sectionData;
				$childSectionArr['data'][$i]['total_cnt'] = $total_cnt;
				$childSectionArr['data'][$i]['max_pages'] = $max_pages;
				$childSectionArr['data'][$i]['start'] = $start;
				$childSectionArr['data'][$i]['page'] = $page;
			  $i++;
			}
			$this->childSectionArr = $childSectionArr;
		
        $this->inner_HTML = $this->generate_inner_html($obj_inner);
       $content = parent::render();
      return $content;
    }
   
    
    function generate_inner_html ($obj_inner) {  
	  $inner_template = MODULES.DS.'SectionPageModule'.DS.$this->inner_template;
     // $obj_inner = & new Template(MODULES.DS.'SectionPageModule'.DS.'inner.tpl.php');
      $obj_inner->set('top_box_data', $this->top_box_data);         
	  $obj_inner->set('childSectionArr', $this->childSectionArr);  
	  $obj_inner->set('latest_video', $this->latest_video);  	  
	  $obj_inner->set('sectionName', $this->sectionName);	 
      return $obj_inner->fetch_cache($inner_template);
    }  
  }
?>
