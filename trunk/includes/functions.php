<?php
function url_exists($url) {
    // Version 4.x supported
    $handle   = curl_init($url);
    if (false === $handle)
    {
        return false;
    }
    curl_setopt($handle, CURLOPT_HEADER, false);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
    curl_setopt($handle, CURLOPT_NOBODY, true);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
    $connectable = curl_exec($handle);
    curl_close($handle);   
    return $connectable;
}

function get_ids($data = array()){
	if(!empty($data)){
	$id_array = array_map(
            create_function('$person', 'return $person["id"];'), $data
        ); 
		return $ids_to_skip = implode(',', $id_array);
	} else{
		return false;
	}
}
function getsectionurl($sectionid='', $section_name='', $section = false)
{
  $surl = '';
  if ( $sectionid != '' )
  { 
    $sectionid = trim($sectionid);
	$sectionname = $section_name;
	$sectionname = getUrlName($sectionname);
	
    if ( $section && ($sectionid == MOVIES_ENTERTAINMENT_SECTION || $sectionid == SPORTS_SECTION || $sectionid == LIFE_STYLE_SECTION || $sectionid == TECH_KNOW_SECTION || $sectionid==TOP_NEWS_SECTION || $sectionid==BUDGET_BUDGETFORYOU_SECTION || $sectionid==BUDGET_FUNDAS || $sectionid==HEAVY_METAL_SECTION) )
    {
      if ( $sectionid == MOVIES_ENTERTAINMENT_SECTION )
      {
        $surl = MOVIES_ENTERTAINMENT_LANDING_URL;
      }
      else if ( $sectionid == SPORTS_SECTION )
      {
        $surl = SPORTS_LANDING_URL;
      }
      else if ( $sectionid == LIFE_STYLE_SECTION )
      {
        $surl = LIFE_STYLE_LANDING_URL;
      }
      else if ( $sectionid == TECH_KNOW_SECTION )
      {
        $surl = TECH_KNOW_LANDING_URL;
      }
	  else if ( $sectionid == TOP_NEWS_SECTION )
      {
        $surl = TOP_NEWS_LANDING_URL;
      }
	  else if ( $sectionid == BUDGET_BUDGETFORYOU_SECTION )
      {
        $surl = BUDGET_BUDGETFORYOU_SECTION_LANDING_URL;
      }
	   else if ( $sectionid == BUDGET_FUNDAS )
      {
        $surl = BUDGET_BUDGET_FUNDAS_LANDING_URL;
      }
	   else if ( $sectionid == HEAVY_METAL_SECTION )
      {
        $surl = HEAVY_METAL_LANDING_URL;
      }
    }
    else
    {
	  if ( $sectionid == MOVIES_ENTERTAINMENT_SECTION || $sectionid == SPORTS_SECTION || $sectionid == LIFE_STYLE_SECTION || $sectionid == TECH_KNOW_SECTION ||  $sectionid == HEAVY_METAL_SECTION)
		{
			$surl = BASE_URL . '/' . $sectionname .'/all';
		}
		else if($sectionid == BUDGET_FUNDAS )
		{
			 $surl = BUDGET_BUDGET_FUNDAS_LANDING_URL;
		}
		else{
			$object_cache = Cache::cacheInstance();
			$cache_key = 'section_name';
			$sections = $object_cache->get($cache_key);
			
			if(isset($sections['data']))
			{
				foreach ($sections['data'] as $i => $v) 
				{
					$array[$i] = $v['id'];
					$key = array_search($sectionid, $array);
				}
				if(isset($key))
				{
					if($sections['data'][$key]['parentname']=='budget for you' || $sections['data'][$key]['parentname']=='budget fundas')
					{
						//$surl = BASE_URL . '/'.'budget-2013/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                                $surl = BASE_URL . '/'.'budget-2013/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
					}
					else
					{
						//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                                $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
					}
				}
				
				else
				{
					//fetch data from db
					$objT = new Section();
					$objT->refreshSectionCache();
					$sections = $object_cache->get($cache_key);
					foreach ($sections['data'] as $i => $v) 
					{
						$array[$i] = $v['id'];
						$key = array_search($sectionid, $array);
					}
					if(isset($key))
					{
						if($sections['data'][$key]['parentname']=='budget for you' || $sections['data'][$key]['parentname']=='budget fundas')
						{
							//$surl = BASE_URL . '/'.'budget-2013/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                                        $surl = BASE_URL . '/'.'budget-2013/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
						}
						else
						{
							//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                                        $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
						}
					}
					
				}
			}
			else
			{
				$objT = new Section();
					$objT->refreshSectionCache();
					$sections = $object_cache->get($cache_key);
					if(!empty($sections['data'])){
						foreach ($sections['data'] as $i => $v) 
						{
							$array[$i] = $v['id'];
							$key = array_search($sectionid, $array);
						}
					}
					if(isset($key))
					//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                        $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;    
			}
		}
    }
  }
  else if($section_name !='')
	{
	  $sectionname = getUrlName($section_name);
	  $object_cache = Cache::cacheInstance();
	  $cache_key = 'section_name';
	  $sections = $object_cache->get($cache_key);
			
			if(isset($sections['data']))
			{
				foreach ($sections['data'] as $i => $v) 
				{
					$array[$i] = $v['name'];
					$key = array_search($sectionname, $array);
				}
				if(isset($key))
				//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                    $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
				else
				{
					//fetch data from db
					$objT = new Section();
					$objT->refreshSectionCache();
					$sections = $object_cache->get($cache_key);
					foreach ($sections['data'] as $i => $v) 
					{
						$array[$i] = $v['name'];
						$key = array_search($sectionname, $array);
					}
					if(isset($key))
					//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                            $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
					
				}
			}
			else
			{
				$objT = new Section();
					$objT->refreshSectionCache();
					$sections = $object_cache->get($cache_key);
					foreach ($sections['data'] as $i => $v) 
					{
						$array[$i] = $v['name'];
						$key = array_search($sectionname, $array);
					}
					if(isset($key))
					//$surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname .'/';
                                            $surl = BASE_URL . '/' .getUrlName($sections['data'][$key]['parentname']).'/'. $sectionname;
			}
	}
  return $surl;
}
function getUrlName($name){
	$name = trim(strtolower($name));
	$name = str_replace(array(' & ',' '), array('-and-','-'), $name);
	return $name;
}

function sendHTMLMail($to, $subject, $body, $from = '', $bcc = FALSE, $folder = 'true', $host = 'nmailer.indiatimes.com') {
	if(APPLICATION_ENV == 'development') {
		return true;
	}
	if (!$to) {
		return;
	}
	if (empty ($from)) {
		$from = 'Administrator<noreply@indiatimes.co.in>';
	}

	include_once "Mail.php";
	$recipients = $to;
	if ($bcc) {
		$bcc = 'abhishek.tiwari@indiatimes.co.in';
	}
	$headers = array('From' => $from,
	'To' => $to,
	'Bcc' => $bcc,
	'Cc' => $cc,
	'Subject' => $subject,
	'Date' => date('r'),
	'Reply-To' => RETURNPATH,
	'Return-Path' => RETURNPATH,
	'MIME-Version' => '1.0',
	'Content-Type' => 'text/html; charset=UTF-8',
	'Content-Transfer-Encoding' => '8bit',
	'X-Mailer' => 'PHP/' . phpversion()
	);
	$smtp = Mail::factory('smtp', array('host' => $host,
		  'port' => $port,
		  'auth' => FALSE));
	$mail = $smtp->send($recipients, $headers, $body);
	if (PEAR::isError($mail)) {
		return $mail->getMessage();
	} else {
		return true;
	}
}

//eof sendHTMLMail


function getClientIP()
{
	if ( isset($_SERVER["HTTP_TRUE_CLIENT_IP"]) )
		$IP = $_SERVER["HTTP_TRUE_CLIENT_IP"];
	elseif ( isset($_SERVER["HTTP_NS_REMOTE_ADDR"]) )
	$IP = $_SERVER["HTTP_NS_REMOTE_ADDR"];
	else
		$IP = $_SERVER["REMOTE_ADDR"];
	return $IP;
}

function v($val = NULL) {
  echo "<pre>";
  var_dump($val);
  echo "</pre>";
  exit;
}
function p($val=NULL) { 
  echo '<pre>';
  print_r($val);
  echo '</pre>';
  exit;
}


function calculate_time($time) {
  $time_ago = time()-$time;
  $days = $time_ago/86400;
  $hrs = ($days-(int)$days)*24; 
  $min = ($hrs-(int)$hrs)*60;
  $sec = ($min-(int)$min)*60;
 
  if ((int)$days != 0) {
    if((int)$days == 1) {
     $days_name = " day";
    }
    else {
     $days_name = " days";
    }
    $time_ago = (int)$days.$days_name;
  } else if ((int)$hrs != 0) {
    if((int)$hrs == 1) {
     $hrs_name = " hr";
    }
    else {
     $hrs_name = " hrs";
    }
    $time_ago = (int)$hrs. $hrs_name;
  } else if ((int)$min != 0) {
     if((int)$min == 1) {
     $min_name = " min";
    }
    else {
     $min_name = " mins";
    } 
    $time_ago = (int)$min. $min_name;
  } else if  ((int)$sec != 0) {
    $time_ago = (int)$sec. " sec";
  }
    return $time_ago." ago";
  }

function calculate_time_left($time) {
  $time_left = $time - time();
  if ($time_left < 0){
   return "Closed question";
  } else {
    $days_left = $time_left/86400;
    $hrs_left = ($days_left-(int)$days_left)*24; 
    $min_left = ($hrs_left-(int)$hrs_left)*60;
    $sec_left = ($min_left-(int)$min_left)*60;
    if ((int)$days_left != 0) {
      $time_left = (int)$days_left." day";
    } else if ((int)$hrs_left != 0) {
      $time_left = (int)$hrs_left." hr";
    } else if ((int)$min_left != 0) {
      $time_left = (int)$min_left." min";
    } else if  ((int)$sec_left != 0) {
      $time_left = (int)$sec_left." sec";
    }
  return $time_left." left";
  }
}
function stripslashes_deep($value) {
  $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
  return $value;
}
function killslashes() {
  if (defined("SLASHES_KILLED")) return;

  // no magic quotes, thanks!
  set_magic_quotes_runtime(0);
  if (get_magic_quotes_gpc()) {
     $_POST = stripslashes_deep($_POST);
     $_GET = stripslashes_deep($_GET);
     $_REQUEST = stripslashes_deep($_REQUEST);
     $_COOKIE = stripslashes_deep($_COOKIE);
  }

  define("SLASHES_KILLED", TRUE);
}

function parse_file_size_string($sz) {
	if (preg_match("/^(\d+)([gmk])$/i", $sz, $m)) {
		$num = (int)$m[1];
		$mult = $m[2];

		switch (strtolower($mult)) {
			case 'g': $num *= 1024; // fallthrough
			case 'm': $num *= 1024; // fallthrough
			case 'k': $num *= 1024;
			break;
		}
		return $num;
	}
	// failed to parse as something like '2M' - just force it to return as an integer
	return (int)$sz;
}


function format_file_size($sz) {
	$sz = floatval($sz);
	if ($sz > 500*1024*1024) {
		return sprintf("%.dGB", $sz/1073741824.0);
	}
	if ($sz > 500*1024) {
		return sprintf("%.dMB", $sz/1048576.0);
	}
	if ($sz > 1023) {
		return sprintf("%.dKB", $sz/1024.0);
	}
	return sprintf("%d bytes", $sz);
}


/**
  * @author   Zoran Hron
  * @name     getConstantsByPrefix
  * @brief    This function returns an array of defined constants with given prefix
  * @return   array of defined constants with given prefix or an empty array
  *
  * @example  getConstantsByPrefix("PAGE_");
  */
function getConstantsByPrefix($prefix) {
    $result    = array();
    $constants = get_defined_constants();

    foreach($constants as $key=>$value) {
      if(substr($key,0,strlen($prefix))==$prefix) {
        $result[$key] = $value;
      }
    }
    return $result;
}

function wrap_text($text, $chunks_len, $max_len, $brek_str = " ") {
  if(strlen($text) > $chunks_len) {
    $newtext = wordwrap($text, $chunks_len, "|", true);
    $nb_breaks = substr_count($newtext, "|");
    $newtext_len = strlen($newtext) - $nb_breaks;
    if($newtext_len <= $max_len) {
      $newtext = str_replace("|", $brek_str, $newtext);
      return $newtext;
    } else {
      $str_arr = str_split($newtext);
      for($cnt = $max_len; $cnt > 0; $cnt--) {
        if($str_arr[$cnt] == "|") break;
      }
      $newtext = substr($newtext, 0, $cnt);
      $newtext = str_replace("|", $brek_str, $newtext);
      return $newtext;
    }
  }
  return $text;
}


function abbreviate_text($text, $max_len, $abbr_pos = null, $abbr_str = '..') {
  if(strlen($text) <= $max_len) {
    return $text;
  }
  $max_len++;
  $cut_len = (!is_null($abbr_pos)) ? $abbr_pos : ($max_len - strlen($abbr_str));
  $first_part  = substr($text, 0, $cut_len) . $abbr_str;
  $fp_len = strlen($first_part);
  $second_part = substr($text, $fp_len - $max_len, $max_len - $fp_len);
  return $first_part . $second_part;

}

/**
  * @author   Zoran Hron
  * @name     type_cast
  * @brief    This function convert Object or Array to given object type
  * @return   Object of requested type
  *
  * @example  type_cast($net_info, 'Network');
  */
function type_cast($object_or_array, $new_classname) {
  if(is_array($object_or_array)) {
    $object_or_array = (object)$object_or_array;
  }
  if(class_exists($new_classname)) {
   $old_object = serialize($object_or_array);
   $new_object = 'O:' . strlen($new_classname) . ':"' . $new_classname . '":' . substr($old_object, $old_object[2] + 7);
   return unserialize($new_object);
  }
  else {
    throw new Exception("[helper_functions.php]::type_cast(): Can't typecast, class with name '$new_classname' is undefined.");
  }
}

/** 
  * @name     curPageURL
  * @return   Current Page URL 
  *
  */
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function cleanUrl($string) {
        $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^ A-Za-z0-9\-]/', '', $string); //  allow space Removes special chars.
        $str = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        trim($str);
        return $str;
}
function is_ajax_request(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       return true;
     } else{
       return false;
	 }
}

function ago($datetime)
{
  if ( $datetime != '' )
  {
	$tmStamp = strtotime($datetime);
    $currentTimeStamp = time();
    $timeDiff = $tmStamp - $currentTimeStamp;
    $totalDays = 0;
    if ( $timeDiff >= 31536000 )
    {
      $no = floor($timeDiff / 31536000);
      $ret = $no . ' year';
    }
    else if ( $timeDiff >= 2592000 )
    {
      $no = floor($timeDiff / 2592000);
      $ret = $no . ' month';
    }
    else if ( $timeDiff >= 86400 )
    {
      $no = floor($timeDiff / 86400);
      $ret = $no . ' day';
    }
    else if ( $timeDiff / 3600 > 1 )
    {
      $no = floor($timeDiff / 3600);
      $ret = $no . ' hour';
    }
    else
    {
      $no = floor($timeDiff / 60);
      $ret = $no . ' min';
    }

    if ( $no > 1 )
    {
      $ret .= 's';
    }
    if($no <= 0) {
		$ret = ' Just now';
	}else{
	    $ret = $ret. ' to go ';
	}
	return $ret;
  }
  else
  {
    return '';
  }
}
function getCulrProcess($cUrl)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $cUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
  $content = curl_exec($ch);
  curl_close($ch);
  return $content;
}
function getsign($date){
     list($year,$month,$day)=explode("-",$date);
     if(($month==1 && $day>20)||($month==2 && $day<20)){
          return "aquarius";
     }else if(($month==2 && $day>18 )||($month==3 && $day<21)){
          return "pisces";
     }else if(($month==3 && $day>20)||($month==4 && $day<21)){
          return "aries";
     }else if(($month==4 && $day>20)||($month==5 && $day<22)){
          return "taurus";
     }else if(($month==5 && $day>21)||($month==6 && $day<22)){
          return "gemini";
     }else if(($month==6 && $day>21)||($month==7 && $day<24)){
          return "cancer";
     }else if(($month==7 && $day>23)||($month==8 && $day<24)){
          return "leo";
     }else if(($month==8 && $day>23)||($month==9 && $day<24)){
          return "virgo";
     }else if(($month==9 && $day>23)||($month==10 && $day<24)){
          return "libra";
     }else if(($month==10 && $day>23)||($month==11 && $day<23)){
          return "scorpio";
     }else if(($month==11 && $day>22)||($month==12 && $day<23)){
          return "sagittarius";
     }else if(($month==12 && $day>22)||($month==1 && $day<21)){
          return "capricorn";
     }
}
function videocafeurl($sectionData)
{
	$url = '';
	if(isset($sectionData['id']) && isset($sectionData['name']))
	{
		$url = VIDEOCAFE_LANDING_URL.strtolower(str_replace(" ","-",$sectionData['name']))."/".$sectionData['id'];
	}
	return $url;
}
//---- array search in 2 multidimention array -----
function lookupKey($families, $firstName) {
        foreach ($families as $surname => $names) {
                if (in_array($firstName, $names)) {
                        return $surname;
                }
        }
        return null;
}
function showImage($article){
	
  $html = null;
  if (!empty($article['screenshot'])) {
    $size = explode('x', $article['screenshot_wh']);
	$html = '<img alt="'.htmlspecialchars($article['screenshot_alt']).'" title="'. htmlspecialchars($article['screenshot_alt']).'" src="'.getResizedThumb($article['screenshot'], '350x134').'" width="350" height="134" />';
  } 
 return $html;
}
/*function to have dynamic color for sections*/
function getsectioncolor($section_parent_id)
{	
	$color = '';
	switch($section_parent_id)
	{
		case NEWSFEED:
		case SPORTS_SECTION:
		case TOP_NEWS_SECTION:
			$color = 'blue';
		break;
		case SHOWBUZZ:
		case MOVIES_ENTERTAINMENT_SECTION:
			$color = 'red';
		break;
		case LIFE:
		case LIFE_STYLE_SECTION:
		case TECH_KNOW_SECTION:
		case HEAVY_METAL_SECTION:
			$color = 'green';
		break;
		case VIDEOCAFE:
			$color = 'orange';
		break;
		case 'black':
			$color = 'black';
		break;
		case CULTURE:
		default:
			$color = 'sky';
		break;
		
	}
	return $color;

}
function getTextBetweenTags($string, $start, $end) {
	$string = " ".$string;
	$ini = strpos($string, $start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}
function getsectionlink($sectionid='', $section_name=''){
	$link = '';
	if ( $sectionid != '' )
	  { 
		$objT = new Section();
		$sectionArr = $objT->getData(array("id"=>$sectionid),"guid");
		$link = str_replace(array(' & ',' '), array('-and-','-'), $sectionArr['data'][0]['guid']);
	  }
	else if($section_name!='')
	 {
		$objT = new Section();
		$sectionArr = $objT->getData(array("name"=>$section_name),"guid");
		$link = str_replace(array(' & ',' '), array('-and-','-'), $sectionArr['data'][0]['guid']);
	 }
	
	return $link;
}
function authorlinkdata($author_id='', $author_name='')
{
  $strlink = '';
  if ( $author_name != '' )
  {
	  $author_name = getUrlName($author_name);
      $strlink = SITEPATH . '/author/' . $author_name;
  }
  return $strlink;
}

function getURL($matches) { 
			   $media = explode('/media/', $matches['2']); 
			   if(false === strpos($matches['2'], SITE_MEDIA_URL) ){
				 return '<img src ="'.$matches['2'].'" clasas="lazy" />';
			   }else{
				   return '<img src ="'.getResizedThumb('/'.$media[1],'350x350').'" clasas="lazy" />';
			   }
}
function formatNumber($num){
    if($num > 1000){
       return (floor($num / 100) / 10) . 'k'; 
    }  else {
        return $num;
    }
}

function cache_js($files = array(), $expire = 3600){
   $mtime = @filemtime(ROOT."/web/cache/combined.js");
   if(file_exists(ROOT."/web/cache/combined.js") && ($mtime + $expire) < time() ) {
      @unlink(ROOT."/web/cache/combined.js");
   } else {
		foreach($files as $file) {
		 $js[] = JSMin::minify(file_get_contents(THEME.'/js/'.$file)); 
		 //$js[] = file_get_contents(THEME.'/js/'.$file);
		}
		$fp = fopen(ROOT."/web/cache/combined.js", 'w');
		fwrite($fp, implode("\n", $js));
		fclose($fp); 
   }
   $html .= '<script defer type="text/javascript"  ';
   $html .= 'language="javascript" ';
   $html .= 'src="'.BASE_URL.'/cache/combined.js"></script>';
   return $html;
}

?>
