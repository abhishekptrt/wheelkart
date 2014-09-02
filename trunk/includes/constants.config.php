<?php

define('DS', DIRECTORY_SEPARATOR);

define('BASE_URL','http://'.$_SERVER['HTTP_HOST']);
define('CANONICAL_BASE_URL','http://www.indiatimes.com');
define('SITE_MEDIA_URL', 'http://revamp.indiatimes.com/media');




define('THEME_URL', BASE_URL.'/theme');
define('JS', THEME_URL.'/js');
define('IMAGES', THEME_URL.'/images');
define('MODULES', ROOT.DS.'modules');
define('FILES', ROOT.DS.'files');
define('CLASSES', ROOT.DS.'classes');
define('INCLUDES', ROOT.DS.'includes');
define('VENDORS', ROOT.DS.'vendors');
define('THEME', ROOT.DS.'web'.DS.'theme');
define('CURRENT_THEME_FSPATH',ROOT.DS.'web'.DS.'theme');
define('WEB', ROOT.DS.'web');
define('AJAX',ROOT.DS.'web'.DS.'ajax');
define('LOGGER_PATH', ROOT.DS.'web'.DS.'log');



define('TECH_ALERT_EMAIL', 'abhishek.tiwari@indiatimes.co.in');
	

define('SITE_TITLE', 'm.indiatimes.com');
define('SITE_NAME', 'indiatimes_revamp');
define('CONTENT_CACHE_KEY_STR', 'revamp_content');
define('DOMAIN_HOST_IP', $_SERVER['HTTP_HOST']);
define('SITE_ORIGIN_PATH',DOMAIN_HOST_IP);
define('SITE_ORIGIN_PATH1',DOMAIN_HOST_IP); 
define('PAGEURL', "http://".DOMAIN_HOST_IP . $_SERVER['REQUEST_URI']);
define('SITEPATH','http://'.DOMAIN_HOST_IP);
define('DOCUMENT_ROOT', dirname(dirname(__FILE__)));
define('MEDIASERVERPATH', SITEPATH);
define('CSSSITEPATH',MEDIASERVERPATH . '/css');
define('FONTSITEPATH',MEDIASERVERPATH . '/css/fonts');
define('JSSITEPATH', MEDIASERVERPATH . '/js');
define('IMAGESITEPATH',MEDIASERVERPATH . '/images');
define('TPL_DIR_PATH', DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'tpl');
define('TPL_VIDEOCAFE_DIR_PATH', DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'tpl/videocafe');
define('INC_DIR_PATH', DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'inc');
define('VENDORS', DOCUMENT_ROOT.DIRECTORY_SEPARATOR.'vendors');
define('LOGGER_PATH', DOCUMENT_ROOT.DIRECTORY_SEPARATOR.'log');

define('SITE_MEDIA_PATH', DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'web'.DIRECTORY_SEPARATOR.'media');

	


/*<ContentType_Constants>*/
define('NEWS',1);
define('COLUMN', 2);
define('PHOTOGALLERY',3);
define('VIDEO', 4);
define('LISTS', 5);
define('PICTURESTORY', 7);
define('REVIEWS', 6);
define('QUOTES', 8);
define('MATCHREPORT', 9);
define('QUIZ', 10);
define('LIVEUPDATES', 11);
define('SLABS', 12);
define('LONGFORM', 13);

/*</ContentType_Constants>*/

$sctionArr = array(
    1 => 'entertainment',
    2 => 'sports',
    3 => 'lifestyle',
    4 => 'technology',
    19 => 'news',
    115 => 'boyztoyz'
);


/* New section constants */

define('NEWSFEED', 131);
define('SHOWBUZZ', 136);
define('LIFE', 143);
define('TECH', 149);
define('VIDEOCAFE', 153);
define('CULTURE', 156);

$pageMappingArr = array();

$pageMappingArr['home']['page'] =  27;
$pageMappingArr['home']['showcase'] =  147;
$pageMappingArr['home']['lifestyle_network'] = 154;
$pageMappingArr['home']['our_partners'] = 159;

$pageMappingArr[NEWSFEED]['page'] = 28;
$pageMappingArr[NEWSFEED]['featured'] =  148;
$pageMappingArr[NEWSFEED]['spotlight'] =  157;

$pageMappingArr[SHOWBUZZ]['page'] = 29;
$pageMappingArr[SHOWBUZZ]['featured'] =  149;
$pageMappingArr[SHOWBUZZ]['spotlight'] =  158;

$pageMappingArr[LIFE]['page'] = 30;
$pageMappingArr[LIFE]['featured'] =  150;
$pageMappingArr[LIFE]['spotlight'] =  156;

$pageMappingArr[VIDEOCAFE]['page'] = 31;
$pageMappingArr[VIDEOCAFE]['featured'] =  151;
$pageMappingArr[VIDEOCAFE]['today_five'] =  153;

$pageMappingArr[CULTURE]['page'] = 32;
$pageMappingArr[CULTURE]['featured'] =  152;
$pageMappingArr[CULTURE]['spotlight'] =  155;
/*end of new section constants*/

/*<Section_Constants>*/
define('MOVIES_ENTERTAINMENT_SECTION', 1);
define('SPORTS_SECTION', 2);
define('LIFE_STYLE_SECTION', 3);
define('TECH_KNOW_SECTION', 4);
define('HEAVY_METAL_SECTION', 115);
define('MOVIE_REVIEWS_SECTION', 8);
define('TOP_NEWS_SECTION', 19);
define('WILDNWACKY', 39);
define('BOLLYWOOD', 5);
define('HOLLYWOOD', 6);
define('NBT', 154);
define('GADGET_SECTION', 100);
define('BUDGET_BUDGETFORYOU_SECTION', 102);
define('BUDGET_ENTERTAINMENT_SECTION', 103);
define('BUDGET_SHOPPING_SECTION', 105);
define('BUDGET_LIFESTYLE_SECTION', 104);
define('BUDGET_CONSUMENTDURABLES_SECTION', 106);
define('BUDGET_FUNDAS', 111);
define('NBA_SECTION', 122);
define('NBA_SECTION_EVENTS_HIGHLIGHTS', 123);
define('NBA_SECTION_GAME_HIGHLIGHTS', 124);
define('NBA_SECTION_NEWS', 125);

define('FB_APP_ID', '117787264903013');
define('FB_APP_SECRET', 'e06b44dcc2c1a8bf9931fb20a422a9c1');
define('TW_APP_ID', 'kDwI40t817RcGVx4GE0Zg');
define('TW_APP_SECRET', 'z6yqORB17fidjr3o6fSLw6Q41xzm6h4GpfZcSBwgwTU');
define('TPL_DATE_FORMAT', 'd/m/y H:i A');

define('QUOTE_HTML_TEMPLATE', '<blockquote class="gorg"><span class="qUp artlSprt"></span><p style="font-size: 17px;">[[QUOTE]]</p><span class="qdown artlSprt"></span></blockquote>');

$conTypeNameArray = array('1'=>'Article','2'=>'Columns','3'=>'Photogallery','4'=>'Video','5'=>'List','6'=>'Reviews','7'=>'Picture Story','8'=>'Quotes');

$sectionNameArray = array('1'=>'Entertainment','2'=>'Sports','3'=>'LifeStyle','4'=>'Technology','19'=>'News','39'=>'Wild & Wacky','102'=>'Budget For You','111'=>'Budget Fundas','115'=>'Boyz Toyz','122'=>'Nba Jam');

$blocksArr =array('data'=>array(array('id'=>1,'name'=>'Entertainment'),array('id'=>2,'name'=>'Sports'),array('id'=>3,'name'=>'LifeStyle'),array('id'=>4,'name'=>'Technology'),array('id'=>115,'name'=>'Boyz Toyz'),array('id'=>19,'name'=>'News')),'data_count'=>6);



$global['og_thumbnail'] = IMAGESITEPATH . '/fbimage.png';

define('FACEBOOK_PROFILE_LINK', 'https://www.facebook.com/');
define('TWITTER_PROFILE_LINK', 'https://twitter.com/');
$global_city = isset($_SERVER['city']) ? $_SERVER['city'] : 'Delhi';

$sectionredirectArray = array('goth'=>'/lifestyle/art-and-culture/','movie-reviews'=>'/entertainment/bollywood/','shooting'=>'/sports/more-sports/','swimming'=>'/sports/more-sports/','computers'=>'/technology/hardware/','in-the-news'=>'/technology/internet/','upcoming-gadgets'=>'/technology/gadgets/','product-of-the-week'=>'/technology/gadgets/','nba'=>'/sports/basketball/','tennis'=>'/sports/tennis-and-badminton/','racing'=>'/sports/motorsport/','luxury'=>'/lifestyle/work-and-life/','reviews'=>'/lifestyle/work-and-life/','money'=>'/lifestyle/work-and-life/','food'=>'/lifestyle/work-and-life/','books'=>'/lifestyle/art-and-culture/','health'=>'/lifestyle/health-and-fitness/','beauty'=>'/lifestyle/fashion-and-beauty/','fashion'=>'/lifestyle/fashion-and-beauty/','boxing'=>'/sports/more-sports/','olympics'=>'/sports/more-sports/','golf'=>'/sports/more-sports/','hockey'=>'/sports/more-sports/','mma'=>'/sports/more-sports/','gaming'=>'/technology/internet/','apps'=>'/technology/internet/','hardware'=>'/technology/pc-and-laptop/','bikes'=>'/technology/enterprise/','cars'=>'/technology/enterprise/','gadget-of-the-day'=>'/technology/gadgets/','nature'=>'/technology/science/','international'=>'/news/rest-of-the-world/','economy'=>'/news/rest-of-the-world/','world'=>'/news/rest-of-the-world/','science-and-environment'=>'/technology/science/','auto'=>'/technology/enterprise/','national'=> '/news/india/','elections'=>'/news/more-from-india/','others'=>'/sports/more-sports/');




