<?php
class Template {
    var $vars; 
    function Template($file = null) {
        $this->file = $file;
    }
    function set($name, $value) {
        $this->vars[$name] = is_object($value) ? $value->fetch() : $value;
    }
    function set_object($name, $value) {
        $this->vars[$name] = $value;
    }

    function fetch($file = null) {
        if(!$file) $file = $this->file;
        @extract($this->vars); 
        ob_start();            
        include($file);        
        $contents = ob_get_contents(); 	   
        ob_end_clean();                       
        return $contents;              
    }
}
class CachedTemplate extends Template {
    var $cache_id;
    var $expire;
    var $cached;
    function CachedTemplate($cache_id = null, $expire = 3600) {        
        $this->Template();
        $this->cache_id = $cache_id ? (ROOT."/web/cache/" . md5($cache_id)) : $cache_id;
        $this->expire   = $expire;
		    if(isset($_GET['cf'])&& $_GET['cf']== 1){
			    @unlink($this->cache_id);
		    }
    }
    function is_cached() {
		if($this->cached) return true;      

        if(!$this->cache_id) return false;

        if(!file_exists($this->cache_id)) return false;

        if(!($mtime = filemtime($this->cache_id))) return false;
        
        if(($mtime + $this->expire) < time()) {
            @unlink($this->cache_id);
            return false;
        }
        else {           
            $this->cached = true;
            return true;
        }
    }
    function fetch_cache($file=NULL) {

        if($this->is_cached()) {
            $fp = @fopen($this->cache_id, 'r');
            $contents = fread($fp, filesize($this->cache_id));
            fclose($fp);
            return $contents;
        }
        else {
            $contents = $this->fetch($file);
            global $debug_disable_template_caching;
            if (!$debug_disable_template_caching) {
                // Write the cache
                if($fp = fopen($this->cache_id, 'w')) {
                    fwrite($fp, $contents);
                    fclose($fp);
                } else {
                    die('Unable to write cache $this->cache_id.');
                }
            }

            return $contents;
        }
    }

    public static function invalidate_cache($file) {    
      $file_path = ROOT."/web/cache/" . md5($file);
      if (file_exists($file_path)) {
	        unlink($file_path);
      }
      
    }
}

?>
