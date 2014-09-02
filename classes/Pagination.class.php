<?php
/*
 * To maintain pagination
 */
class Pagination {
	public $php_self;
	public $rows_per_page = 20; //Number of records to display per page
	public $total_rows = 0; //Total number of rows returned by the query
	public $links_per_page = 5; //Number of links to display per page
	public $append = ""; //Paremeters to append to pagination links
	public $sql = "";
	public $debug = false;
	public $page = 1;
	public $max_pages = 0;
	public $offset = 0;
	public $db = null;
        public $relNext='';
        public $relPre='';

                /**
	 * Constructor
	 *
	 * @param string $sql SQL query to paginate. Example : SELECT * FROM users
	 * @param integer $rows_per_page Number of records to display per page. Defaults to 10
	 * @param integer $links_per_page Number of links to display per page. Defaults to 5
	 * @param string $append Parameters to be appended to pagination links 
	 */
	
	function __construct($sql_val, $rows_per_page = 20, $links_per_page = 5, $url = "", $append = "",$pg='1') {
		$this->db = Database::Instance();
		$this->sql = $sql_val;
		$this->rows_per_page = (int)$rows_per_page;
		if (intval($links_per_page ) > 0) {
			$this->links_per_page = (int)$links_per_page;
		} else {
			$this->links_per_page = 5;
		}
		$this->append = ($append == '') ? '' : $append ;
		$this->php_self =  $url;
		
		//if (isset($_GET['pg'] )) {
			 $this->page = intval($pg );
		//}
	}
	
	/**
	 * Executes the SQL query and initializes internal variables
	 *
	 * @access public
	 * @return resource
	 */
	function paginate($total_record) {
		//if($total_record == ''){
			//$this->db->query($this->sql);
			//$this->total_rows = $this->db->getRowCount();
		//}else{ 
		
			$this->total_rows = $total_record; 
		//}
		//Return FALSE if no rows found
		if ($this->total_rows == 0) {
			if ($this->debug)
				echo "Query returned zero rows.";
			return FALSE;
		}
		
		//Max number of pages
		$this->max_pages = ceil($this->total_rows / $this->rows_per_page );
		if ($this->links_per_page > $this->max_pages) {
			$this->links_per_page = $this->max_pages;
		}
		
		//Check the page value just in case someone is trying to input an aribitrary value
		if ($this->page > $this->max_pages || $this->page <= 0) {
			$this->page = 1;
		}
		
		//Calculate Offset
		$this->offset = $this->rows_per_page * ($this->page - 1);
		return true;
	}
	
	/**
	 * Display the link to the first page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'First'
	 * @return string
	 */
	function renderFirst($tag = 'First') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page == 1) {
			return '<a href="javascript: void(0);" >'.$tag.'</a>';
		} else {
			return '<a href="' . $this->php_self . $this->append . '1' .'">' . $tag . '</a>';
		}
	}
	
	/**
	 * Display the link to the last page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
	 * @return string
	 */
	function renderLast($tag = 'Last') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page == $this->max_pages) {
			return '<a href="javascript: void(0);">'.$tag.'</a>';
		} else {
			return '<a href="' . $this->php_self . $this->append . $this->max_pages.'" class="prevnext">' . $tag . '</a>';
		}
	}
	
	/**
	 * Display the next link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '>>'
	 * @return string
	 */
	function renderNext($tag = 'Next',$suffix='') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page < $this->max_pages) { 
                     $this->relNext ='<link rel="next" href="'.$this->php_self .  $this->append . ($this->page + 1).$suffix.'">';
			return '<a href="' . $this->php_self .  $this->append . ($this->page + 1).$suffix.'" class="next" title="Next">' . $tag . '<span class="sprite_img right-arrw"></span></a>';
		} else {
			return '<a href="javascript: void(0);" class="next disable" title="Next">' . $tag . '<span class="sprite_img right-arrw"></span></a>';
		}
	}
	
	/**
	 * Display the previous link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '<<'
	 * @return string
	 */
	function renderPrev($tag = 'Previous',$suffix='') {
		if ($this->total_rows == 0)
			return FALSE;
		if($this->page == 2)
		{
                        $urlWithoutLastSlash = rtrim($this->php_self, "/");
                        $this->relPre = $urlWithoutLastSlash .$suffix;
                                     $this->relPre = '<link rel="prev"  href="'.$this->relPre.'">';
			return '<a href="' . $this->php_self .$suffix.  '" class="previous" title="Previous">' . $tag . '</a>';   
               
			return '<a href="' . $this->php_self .$suffix.  '" class="previous" title="Previous"><span class="sprite_image left-arrw"></span>' . $tag . '</a>';
		}
		else if ($this->page > 1) {
			$this->relPre = '<link rel="prev" href="'.$this->php_self .  $this->append .($this->page - 1) .$suffix.'">';

			return '<a href="' . $this->php_self .  $this->append .($this->page - 1) .$suffix. '" class="previous" title="Previous"><span class="sprite_img left-arrw"></span>' . $tag . '</a>';
		} else {
			return '<a href="javascript:void(0);" class="previous disable" rel="prev"><span class="sprite_img left-arrw"></span> ' . $tag . '</a>';
		}
	}
	
	/**
	 * Display the page links
	 *
	 * @access public
	 * @return string
	 */
	function renderNav($prefix = '', $suffix = '') {
		if ($this->total_rows == 0)
			return FALSE;
		
		$batch = ceil($this->page / $this->links_per_page );
		$end = $batch * $this->links_per_page;
		if ($end > $this->max_pages) {
			$end = $this->max_pages;
		}
		$start = $end - $this->links_per_page + 1;
		$links = '';
		
		for($i = $start; $i <= $end; $i ++) {
			if ($i == $this->page) {
				$links .= '<a href="javascript: void(0);" class="selected">'.$i.'</a>';
			} 
			else if($i == 1)
			{
                            $urlWithoutLastSlash = rtrim($this->php_self, "/");
				$links .= $prefix . '<a href="' . $urlWithoutLastSlash . $suffix. '" >' . $i . '</a>' ;
			}
			else {
				$links .= $prefix . '<a href="' . $this->php_self .  $this->append . $i. $suffix. '" >' . $i . '</a>' ;
			}
		}
		
		return $links;
	}
	
	/**
	 * Display full pagination navigation
	 *
	 * @access public
	 * @return string
	 */
	function renderFullNav() {
		return $this->renderFirst() . $this->renderPrev() . $this->renderNav() . $this->renderNext() . $this->renderLast();
	}
	
	/**
	 * Set debug mode
	 *
	 * @access public
	 * @param bool $debug Set to TRUE to enable debug messages
	 * @return void
	 */
	function setDebug($debug) {
		$this->debug = $debug;
	}
}
?>