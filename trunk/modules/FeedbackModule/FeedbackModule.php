<?php
 require_once MODULES.DS.'Module'.DS.'Module.php';
  class FeedbackModule extends Module{
   public $outer_template = 'outer_public.tpl';
   function __construct() {
      parent::__construct();
   }
    function render() {
        global $section_class;		
    		$this->inner_tpl = 'inner.tpl.php';    
        $this->inner_HTML = $this->generate_inner_html ();
        $content = parent::render(); 
        return $content;
    }       
    function generate_inner_html () {
      $obj_inner = new Template(MODULES.DS.'FeedbackModule'.DS.$this->inner_tpl);	      
	  return $obj_inner->fetch();
    }
  
  }
?>
