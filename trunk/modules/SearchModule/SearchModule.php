<?php
  require_once MODULES.'/Module/Module.php';
  class SearchModule extends Module{
    public $outer_template = 'outer_public.tpl';
	  public $inner_template = 'inner.tpl.php';
	  public $objPaginate    = null;
    public $page_links     = null;
    
   function __construct() {
      parent::__construct();
   }
    function render() { 
    if($this->searchData->numFound > 0){
    $objPaginate = new Pagination('', '12', 4, strtolower(BASE_URL.'/search/'.$this->query.'/'),"pg-", $this->page);  
    $objPaginate->paginate($this->searchData->numFound);        
    $this->page_links =  $objPaginate->renderPrev(). $objPaginate->renderNav('', ''). $objPaginate->renderNext();
    $this->objPaginate = $objPaginate;
    }
    $this->inner_HTML = $this->generate_inner_html();
    $content = parent::render();
     return $content;
    }
   
    function get_paginationObj(){ 
      return $this->objPaginate;
    }
    
    function generate_inner_html () {		
     $obj_inner = & new Template(MODULES.DS.'SearchModule'.DS.'inner.tpl.php');	  
     $obj_inner->set_object('contents', $this->searchData);   
     $obj_inner->set_object('objPaginate', $this->objPaginate); 
	 $obj_inner->set('page_links', $this->page_links);
     $obj_inner->set('query', $this->query);        
	   return $obj_inner->fetch();	  
      
    }  
  }
?>
