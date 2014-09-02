<?php
  require_once MODULES.'/Module/Module.php';
  class IndexPageModule extends Module{
    public $outer_template = 'outer_public.tpl';
	public $inner_template = 'middle.tpl';
    function __construct() {
	  parent::__construct();
    }
    function render() {
		global $pageMappingArr;
		$cache_id = 'MiddleModule~'.$this->inner_template;
		$obj_inner =  new CachedTemplate($cache_id, 36);
		if($obj_inner->is_cached()){ 
		  return $obj_inner->fetch_cache();
	   }  
		$contentObj = new Content();
		$args = array();
		$args['limit'] = 12;
		$args['fields'] = array('headline1,c.id,summary,erating,thumbnail,thumbnail_alt,guid,is_aggregator_data,aggregator_url,section_name,section_id,section_parentid,carousal_headline');
		$args['is_aggregator_data'] = 0;
		$latest_content = $contentObj->getContentList($args);
		$this->latest_content = $latest_content['data'];

        $args['contype_id_in'] = PHOTOGALLERY.','.PICTURESTORY;
		$latest_galleries = $contentObj->getContentList($args);
		$this->latest_galleries = $latest_galleries['data'];       
		$pageID =  $pageMappingArr[VIDEOCAFE]['page'];
		$blockID = $pageMappingArr[VIDEOCAFE]['today_five'];
		$pageMgmtObj = new PageManagement();
		$video_contents = $pageMgmtObj->getData($pageID, $blockID, 12); 
		$this->video_contents = $video_contents['data'];
		unset( $args['contype_id_in'] );
        $args['type'] = 'visits';
		$populer_contents = $contentObj->getContentList($args);
		$this->populer_contents = $populer_contents['data'];
        

		$pageMgmtObj = new PageManagement();
		$top_box_data = $pageMgmtObj->getData($pageMappingArr['home']['page'], $pageMappingArr['home']['showcase'], 3);
        $this->top_box_data = $top_box_data['data'];



     

       $this->inner_HTML = $this->generate_inner_html ($obj_inner);
       $content = parent::render();
       return $content;
    }
   
    
    function generate_inner_html ($obj_inner) { 	
 	  $inner_template = MODULES.DS.'IndexPageModule'.DS.'middle.tpl.php';
	   //$obj_inner = new Template($inner_template);
	  
       $obj_inner->set('top_box_data', $this->top_box_data); 
	   $obj_inner->set('latest_contents', $this->latest_content); 
	   $obj_inner->set('latest_galleries', $this->latest_galleries);
	   $obj_inner->set('video_contents', $this->video_contents); 
	   $obj_inner->set('populer_contents', $this->populer_contents);
	   
       return $obj_inner->fetch_cache($inner_template);
    }
  
  }

?>
