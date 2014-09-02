<?php
 require_once MODULES.DS.'Module'.DS.'Module.php';
  class ArticlePageModule extends Module{
   public $outer_template = 'outer_public.tpl';
   public $objPaginate = null;
   function __construct() {
      parent::__construct();
   }
    function render() {   
     global $section_class,$contentObj,$pageMappingArr;		
		$this->section_class = $section_class;
		$related_galleries = array();			
		$rhs_related_stories = array();
		$filters['keywords'] = $this->article['keywords'];
		$filters['contype_id'] = $this->article['contype_id']; 
		$filters['section_id'] = $this->article['section_parentid'];
		$filters['id'] = $this->article['id'];
		$filters['limit'] = 12;
		try {
			$this->contents = $contentObj->getRelatedArticles($filters);				
		}  catch( Exception $e ){
			$this->contents = array();				
		}      
        $pageMgmtObj = new PageManagement();
        $page_id =  $pageMappingArr[$article['section_parentid']]['page'];
        $block_id = $pageMappingArr[$article['section_parentid']]['spotlight'];
        $pageArr = $pageMgmtObj->getData($page_id, $block_id, 1);
        $this->spotlight_contents = $pageArr['data'];
        
        
		
		
     if( $this->article['contype_id'] == QUIZ ){
        $this->quizs = $contentObj->getQuiz($this->article['id']);
		$this->answers = $contentObj->getAnswers($this->article['id']);
        $this->inner_tpl = 'quiz.tpl.php'; 
     } else if( $this->article['contype_id'] == VIDEO ){ 
		$pageID =  $pageMappingArr[VIDEOCAFE]['page'];
		$blockID = $pageMappingArr[VIDEOCAFE]['today_five'];
		$pageMgmtObj = new PageManagement();
		$video_contents = $pageMgmtObj->getData($pageID, $blockID, 5); 
		$this->video_contents = $video_contents['data'];

        $this->inner_tpl = 'video.tpl.php';
     } else if( $this->article[ 'contype_id' ] == PHOTOGALLERY || $this->article[ 'contype_id' ] == PICTURESTORY ){		   
		
        $this->renderer->add_header_js('gallery.js');  
		$this->renderer->add_header_js('hammer.min.js');

        $this->inner_tpl = 'photo.tpl.php';
     } else if($this->article[ 'contype_id' ] == LISTS){		
        $this->article['description'] = str_replace("__PAGEBREAK__", " ", $this->article['_description_full']);
			  $pointer = $pg - 1;
			  for ($i = 0; $i < $pointer; $i++) {
				$previouspgcnt = $previouspgcnt + count(explode('__LISTSEPARATOR__', $temp_description[$i]));
				if ($i != 0) {
				  $previouspgcnt--;
				}
			  }
			  $this->article['current_count'] = $pointer;
			  $this->article['list_content'] = explode('__LISTSEPARATOR__', $this->article['description']);
			  $this->article['current_list'] = array();
			  $temp_start = ($this->article['list_sort_order'] == 'asc') ? 1 : count($this->article['list_content']);
			  foreach ($this->article['list_content'] as $key => $val) {
				if (strlen(trim($val)) > 0) {
				  $pattern = '/__START__(.*)__END__/';
				  preg_match($pattern, $val, $matches);
				  $this->article['current_list'][$key]['title'] = $matches[1];
				  $this->article['current_list'][$key]['count'] = $temp_start;
				  $this->article['current_list'][$key]['content'] = preg_replace($pattern, '', $val);
				  ($this->article['list_sort_order'] == 'asc') ? $temp_start ++ : $temp_start -- ;
				}
			  }
         $this->inner_tpl = 'list.tpl.php';        
      } else if(SLABS == $this->article[ 'contype_id' ] ){ 			
			$this->article['content_slab_list'] = explode('__SLABSEPARATOR__', $this->article['description']);			
			$contentObj = new Content();
			$args['contype_id'] = SLABS;	
			$args['fields'] = array('guid');
			$args['start'] = 0;
			$args['limit'] = 40; 
			$args['id_not_in'] = $this->article['id'];
			$related_slabs = $contentObj->getContentList($args, $setmem = false);
		    $this->related_slabs = json_encode($related_slabs['data']);			
	       $this->inner_tpl = 'slab.tpl.php';
		   $this->remove_footer = 1;
		  
	  } else {
    	   $this->inner_tpl = 'inner.tpl.php';
      }	    
        $this->inner_HTML = $this->generate_inner_html ();
        $content = parent::render(); 
        return $content;
    }   
    function get_paginationObj(){
      return $this->objPaginate;
    }
    
    function generate_inner_html () {
      $obj_inner = new Template(MODULES.DS.'ArticlePageModule'.DS.$this->inner_tpl);	 
      $obj_inner->set('article', $this->article);   	 
  	  $obj_inner->set('quizs', $this->quizs);   
	  $obj_inner->set('answers', $this->answers);   
	  $obj_inner->set('pg', $this->page); 
	  $obj_inner->set_object('contents', $this->contents);
      $obj_inner->set('video_contents', $this->video_contents);
	  $obj_inner->set('related_slabs', $this->related_slabs);
	  $obj_inner->set('spotlight_contents', $this->spotlight_contents);
	  
	  return $obj_inner->fetch();
    }
  
  }
?>
