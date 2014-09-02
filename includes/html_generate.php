<?php


$meta_info['gallery']['title'] ="Latest Photos from News, Entertainment, Technology, Sports – Indiatimes Mobile";
$meta_info['gallery']['description'] ="Indiatimes Mobile photogallery provides you photos of latest news, entertainment, sports, technology, lifestyle. View Latest photos now.";





function getPagingGuid($guid, $pid, $previewFlag='')
{ 
  if ( $guid != '' && $pid != '' && $pid !== 0 && $previewFlag == '' )
  {
    $pageStr = str_replace('.html', '', $guid);
    $pageguid = $pageStr . '-' . $pid . '.html';
  }
  else if ( $guid != '' && $pid != '' && $previewFlag == 'p' )
  {
    $pageguid = $guid . $pid;
  }
  else
  {
    $pageguid = $guid;
  }
  return $pageguid;
}
function getResizedThumb($thumb, $size){ 
 list($w,$h) = explode('x',$size);	
 return uihelper_resize_img($thumb, $w, $h); 

}
function str_stop($string, $max_length){
  if ( strlen($string) > $max_length )
  {
    $string = substr($string, 0, $max_length);
    $pos = strrpos($string, " ");
    if ( $pos === false )
    {
      return substr($string, 0, $max_length) . "...";
    }
    return substr($string, 0, $pos) . "...";
  }
  else
  {
    return $string;
  }
}	
function js_includes($file, $optimize = true) {
    global $js_includes;    
    if (!isset($js_includes)) {
        $js_includes = array();
    }
   // $path = THEME_URL.DS.'js'.DS;
    $path = THEME_URL.'/js/';
    $file = trim($file);   
    $js_includes[$path][$file] = $file;   
    return '';
}


function html_header($title='', $optional_arguements='', $style_css='') {
  global $metatitle, $metadescription, $rel_prev_link, $rel_next_link;
  echo  "<!DOCTYPE html>\n";
  echo  "  <head>\n";
  echo  "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n"; 
  echo  "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />\n";      
  if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
	echo  "<meta name=\"apple-itunes-app\" content=\"app-id= 717209912\">";
  }

  echo "<meta http-equiv=\"Cache-control\" content=\"no-store\">";
  echo "<meta charset=\"utf-8\">";
  echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">";
 

  echo  "<meta name=\"HandheldFriendly\" content=\"True\" />\n";  
  echo  '<meta name="google-site-verification" content="ZgFICIedNvVZl5pV9EfAUeenwta9vBY0Za_GgmV4zuw" />'."\n";   
  echo  '<meta name="google-play-app" content="app-id=com.til.indiatimes">';
  echo  "    <title>\n";
  echo  "      $title \n";
  echo  "    </title>\n";
  if(!empty($metatitle)){
    echo  "<meta name=\"title\" content=\"$metatitle\" />\n";
  }  
  if(!empty($metadescription)){
    echo  "<meta name=\"description\" content=\"$metadescription\" />\n";
  }  
  if(!empty($metakeyword)){
  echo  "<meta name=\"keywords\" content=\"$metakeyword\" />\n";
  }  
  if($rel_prev_link){
    echo $rel_prev_link;    
  }
  if($rel_next_link){
    echo $rel_next_link;
  } 
   echo  '<link rel="shortcut icon" href="'.IMAGES.'/favicon.ico" type="image/x-icon">';
   echo	 '<link rel="icon" href="'.IMAGES.'/favicon.ico" type="image/x-icon">'."\n";
   echo    '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'/css/style.css?v='.filemtime(THEME.DS.'css'.DS.'style.css').'" />'."\n";
    

   echo    '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'/css/flexslider.css?v='.filemtime(THEME.DS.'css'.DS.'flexslider.css').'" />'."\n";

   echo    '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'/css/fonts/fonts.css" />'."\n";
     
  /// echo    '<link rel="stylesheet" type="text/css" href="'.THEME_URL.'/css/jquery.smartbanner.css" />'."\n";

   echo    '<script type="text/javascript" language="javascript" src="'.THEME_URL.'/js/jquery-2.1.0.min.js"></script>'."\n";

  echo    '<script type="text/javascript" language="javascript" src="'.THEME_URL.'/js/jquery.lazy.min.js"></script>'."\n";
   
  echo $optional_arguements; 
  echo "  </head>\n";
}

function html_body($optional_parameters='') {  
  echo "<body $optional_parameters>\n";
  echo "\n";
}

function html_footer() {
  echo "</html>\n";
  ob_end_flush();
}


function default_exception() {
  global $debug_for_user;
  if ($debug_for_user == TRUE) {
    set_exception_handler('exception_handler');
  }
}
function getimagehtml($image, $width, $height, $attributes="", $image_url="") {

 if (!$image_url) {
  if (!file_exists($image) || !is_file($image)) {
    return;
  }
 }
 $output = NULL;
 $title = NULL;
 if (preg_match('/^http/', $image)) {
 	return '<img src="'.$image.'"  alt="" />';
 }

 $img = getimagesize($image);  // returns actual image attributes
 if ($img[1]) {
   $w = $img[0]; // actual image width
   $h = $img[1]; // actual image height
   $aar = $w / $h; // actual image aspect ratio
   $dar = $width / $height; // desired image aspect ratio

   $output .= '<img src="' . ($image_url ? $image_url : BASE_URL."/".$image) . '" alt="' . $title . '" ';
     if ($w <= $width && $h <= $height) {
     $output .= 'width="' . $w . '" height="' . $h . '" ';
   }
   elseif ($aar <= $dar) {
     $output .= 'height="' . ($h > $height ? $height : $h) . '" ';
   }
   elseif ($aar > $dar) {
     $output .= 'width="' . ($w > $width ? $width : $w) . '" ';
   }

   $output .= $attributes . '/>';
   return $output;
 }

}




function getTopBoxDisplayFormat($datetime)
{
  if ( $datetime != '' )
  {
	$tmStamp = strtotime($datetime);
    $currentTimeStamp = time();
    $timeDiff = $currentTimeStamp - $tmStamp;
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
	    $ret = $ret. ' ago ';
	}
	return $ret;
  }
  else
  {
    return '';
  }
}




?>
