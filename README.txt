# branch changes 
$files = glob(THEME."/js/*.js");		
		$js = "";
		foreach($files as $file) {
			 $js .= JSMin::minify(file_get_contents($file)); 
		} 
	$fp = fopen(ROOT."/web/cache/combined.js", 'w');
	fwrite($fp, $js);
	fclose($fp);



Servers: 

223.165.27.163 /192.168.27.163
223.165.27.164 /192.168.27.164
223.165.27.165 /192.168.27.165



Installation:

a)  We are Using Log4php pear pakage for log.
    need to create log folder under web folder with write permission(755)

b) We are using template based caching 
     need to create cache folder under web folder with write permission (755)
c) create env.config.php 

Example :
define('ROOT', realpath(dirname(__FILE__)));
$dbdetails = array(		
	'indiatimes'	 => array(
		"host" => '192.168.27.174',
		 "database" => 'indiatimes',
		 "user"	   => 'ITstagingDB',
		 "password" => 'InDIAtimesStagING%^$123'),					
	'indiatimes_cms' => array(
		'host'      => '192.168.27.174',
		'database'  => 'indiatimes',
		'user'      => 'ITstagingDB',
		'password'  => 'InDIAtimesStagING%^$123'),

	'indiatimes_comments' => array(
		'host'		=> '10.157.222.71',
		'database'  => 'indiatimes_comments',
		'user'		=> 'root',
		'password'  => 'redhat'),
);
$memcache_servers[] = array('192.168.27.173', 11311);








##################
Project Details:
##################


##############
Folders deatils :

1) html: this folder  contains latest html images, css committed by UI developer .
2) class: Classes which interacts with data source
3) includes : contains  files
4) modules: contains modules file which is being used on pages .
5) web : Document root of the Application.
































Flow :

1)  Page is being created with help of PageRenderer class.
    it renders module belongs to that page.
   

    PageRenderer->render() calls module->render() belongs to the same page.

2) To create new page do following .
 Add a constant like define ('PAGE_SUB_SECTION_PAGE',4); in include.php
  
Add corresponding section 

 PAGE_SUB_SECTION_PAGE=> array('page_id'=>PAGE_SUB_SECTION_PAGE,
                        'page_name'=>'sub_section.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'SubSectionModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 					

in $settings_new array.



left, middle and right sections o the page put module name here : SubSectionModule
Define module .



