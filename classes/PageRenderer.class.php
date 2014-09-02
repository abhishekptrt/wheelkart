<?php
class PageRenderer {
 
  public $page_template;
  public $header_template;
  public $setting_data;
  public $onload; 
  public $extra_head_html;
  private $module_arrays;
  private $js_includes = array();
  private $js_includes_dont_optimize = array();
  private $css_includes = array();
  private $css_includes_dont_optimize = array();
  public $footer = null;

  function __construct($cb, $page_id, $title, $page_template="homepage.tpl", $header_template="header.tpl.php", $setting_data = NULL) {  
   
    $this->setting_data = $setting_data;
    $this->page_id = $page_id;
    $this->debugging = isset($_GET['debug']);    
    $this->page_template = $page_template;  
    $this->header_template = $header_template;  
    $this->module_arrays = array();
    $this->onload = "";
    $this->page_title = $title;
    $this->html_body_attributes = "";   
    $this->page =  new Template(THEME.DS.$this->page_template);
    $this->setHeader($this->header_template);
	$this->setFooter(THEME.DS."footer.tpl.php");    
    $this->preInitialize($this->setting_data);   
	$this->initNew($cb, $default_mode, $default_block_type, $this->setting_data);
  }
  
  public function setFooter($footer_tpl) {
		$this->footer =  new Template(THEME.DS."footer.tpl.php");
        $this->footer->set('current_theme_path', THEME_URL);
  }

  public function setHeader($header_tpl) {
		$this->header =  new Template(THEME.DS.$header_tpl);
		$this->header->set('current_theme_path', THEME_URL);
  }
  public function setFooterVar($name, $value) {
	  global $sectionID;
	  $sectionID = $value;
  }
  private function preInitialize($setting_data) { 
    $this->setting_data = $setting_data ; 
    $this->modules_array = array();
    foreach (array("middle", "left", "right") as $module_column) {
            $column_modules =  $this->setting_data[$this->page_id]['data'][$module_column]; 
			if (count($column_modules) > 0) {
				foreach ($column_modules as $moduleName) {
					if (!$moduleName) continue;
					  $file = MODULES . DS.$moduleName.DS.$moduleName.".php";
					  $module_exists = false;
					 if (file_exists("$file")) {
						$module_exists = true;
					 }  
 					 if (! $module_exists) {
						echo "<div class='module_error'>Module $moduleName does not exist.</div>";
						continue;
					}
					try {
						require_once($file);
					} catch (Exception $e) {
							echo "<p>Failed to require_once $file.</p>";
							continue;
							throw $e;
					} 
					$obj = new $moduleName; 
					$obj->column    = $module_column;
					$obj->page_id   =  $this->setting_data[$this->page_id]['page_id'] ;
					$obj->renderer     = $this;                 
					$obj->module_name  = $moduleName;					
					$this->modules_array[] = $obj; 
				}
           }		   
     }  
	 
  }
  public function add_header_html($html) {
      if (preg_match("/^\s*$/", $html)) {
          return;
      }
      $this->extra_head_html .= $html."\n";
  }
 
 
  public function add_header_css($file, $optimize = true) {
      if (preg_match("/^\s*$/", $file)) {
          return;
      }
      $path = '';
      $file = trim($file);      
      $this->css_includes[$path][$file] = $file;	 
  }
 
  public function add_header_js($file, $optimize = true) {
    js_includes($file, $optimize);
  }

  public function add_page_js($file) {
    global $js_includes;
    $path_info = pathinfo($file); 
    if(!empty($path_info['dirname']) && !empty($path_info['basename'])) {
      $path = $path_info['dirname'];
      $file = $path_info['basename'];
      $js_includes[$path.'/'][$file] = $file;
    }
  }

 private function get_extra_head_html() {
        global $js_includes;
        $extra_head_html = '';  
        $extra_head_html .= '<script type="text/javascript" language="javascript">';
        $extra_head_html .= 'var base_url = "'.addslashes(BASE_URL).'";';
        $extra_head_html .= 'var CURRENT_THEME_PATH = "'.addslashes(THEME_URL).'";';
        $extra_head_html .= '</script>'."\n";
        if (!empty($js_includes)) {
            foreach ($js_includes as $path => $files) { 
               foreach ($files as $file) {
                        $extra_head_html .= '<script type="text/javascript" ';
                        $extra_head_html .= 'language="javascript" src="';
                        $extra_head_html .= $path.$file.'"></script>'."\n";
                    }
            }
        } 
        if (!empty($this->css_includes)) {
            foreach ($this->css_includes as $path => $files) { 
                foreach ($files as $file) {
                        $extra_head_html .= '<link rel="stylesheet" type="text/css" ';
                        $extra_head_html .= 'href="'.$path.$file.'" />'."\n";
                    }  
            }
        }
        $extra_head_html .= $this->extra_head_html;
		if(1){
		$extra_head_html .='<link rel="canonical" href="'.CANONICAL_BASE_URL.$_SERVER['REQUEST_URI'].'"/>'; 
        }
        return $extra_head_html;
    }
    private function initNew($cb, $default_mode, $default_block_type, $setting_data) {  
		foreach ($this->modules_array as $module) {
			$skipped = $module->skipped ? $module->skipped : null ;
			if ($cb && !$skipped) {
				switch ($cb($module->column, $module->module_name, $module, $this)) {
				case 'skip':
					$skipped = TRUE;
					break;
				 }
			}
			$render_time = NULL;
			if (!$skipped) {
				$start_time = microtime(TRUE);
				$html = $module->render(); 
				if(method_exists($module, 'get_paginationObj')){ 
				  $links = $module->get_paginationObj();
				  $this->rel_prev_link = $links->relPre;
				  $this->rel_next_link = $links->relNext;
				}
				if($module->remove_footer){
				  $this->footer = null;
				}
				if (!$module->do_skip) {
				  $render_time = microtime(TRUE) - $start_time;
				  $this->module_arrays[$module->column][] = $html;
				}
			}
		   
		  }
  }

  
  function render() {   
	global $sectionID;   
    $extra_head_html = $this->get_extra_head_html();
    if($this->rel_prev_link){
      $extra_head_html .= $this->rel_prev_link;
    }
    if($this->rel_next_link){
      $extra_head_html .= $this->rel_next_link;
    }
    html_header($this->page_title, $extra_head_html);
    if ($this->onload) $this->html_body_attributes .= ' onload="'.$this->onload.'"';
    html_body($this->html_body_attributes); 
    foreach ($this->module_arrays as $module_column => $array_modules) { 		
        $this->page->set('array_'.$module_column.'_modules', $array_modules);
    } 
    $this->page->set('header', $this->header);
    $this->page->set('footer', $this->footer);
	$this->page->set('current_theme_path', THEME_URL);
    $res = $this->page->fetch();
  
    return $res;
  }

}

?>
