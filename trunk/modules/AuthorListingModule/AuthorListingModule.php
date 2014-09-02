
<?php
  require_once MODULES.'/Module/Module.php';
  class AuthorListingModule extends Module{
    public $outer_template = 'outer_public.tpl';
    public $inner_template = 'inner.tpl.php';
    public $type = null;
	
   function __construct() {
      parent::__construct();
   }
    function render() { 

      
        
        
        $this->inner_HTML = $this->generate_inner_html();
        $content = parent::render();
       return $content;
    }
    function generate_inner_html () {
		
      $obj_inner = & new Template(MODULES.DS.'AuthorListingModule'.DS.'inner.tpl.php');	  
	  $obj_inner->set('author_data', $this->authorArr);  
      $obj_inner->set('articles', $this->articles);  
	  $obj_inner->set('page_links', $this->page_links);  

	  
	  
	  return $obj_inner->fetch();	  
      
    }  
  }
?>
