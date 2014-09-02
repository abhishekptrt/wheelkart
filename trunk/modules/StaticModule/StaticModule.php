<?php
 require_once MODULES.DS.'Module'.DS.'Module.php';
  class StaticModule extends Module{
   public $outer_template = 'outer_public.tpl';
    function __construct() {
      parent::__construct();
   }
    function render() {
		if($_GET['type'] == 'tc'){
    		$this->inner_tpl = 'tc.tpl.php';    
		}else if($_GET['type'] == 'tu'){ 
		   $this->inner_tpl = 'tu.tpl.php';    
	    }else if($_GET['type'] == 'sh'){ 
		   $this->inner_tpl = 'sh.tpl.php';    
	    }else if($_GET['type'] == 'sh_tc'){  
		   $this->inner_tpl = 'sh_tc.tpl.php';    
	    }
		else{
		   $this->inner_tpl = 'privacy.tpl.php';    
		}
        $this->inner_HTML = $this->generate_inner_html ();
        $content = parent::render(); 
        return $content;
    }       
    function generate_inner_html () {
      $obj_inner = new Template(MODULES.DS.'StaticModule'.DS.$this->inner_tpl);	      
	  return $obj_inner->fetch();
    }
  }
?>
