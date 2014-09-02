<?php

//Functions for Image Upload
function imagefolderpath($imagepath) {

  if (is_dir($imagepath . '/' . date('Y')) == false) {
    $parentfolder = mkdir($imagepath . '/' . date('Y'));
    $ftpresult = shell_exec("chmod 744  $imagepath/" . date('Y'));
    if (is_dir($imagepath . '/' . date('Y') . '/' . date('M')) == false) {
      $monthfolder = mkdir($imagepath . '/' . date('Y') . '/' . date('M'));
      $ftpresult = shell_exec("chmod 744 $imagepath/" . date('Y') . '/' . date('M'));
    }
  } else if (is_dir($imagepath . '/' . date('Y') . '/' . date('M')) == false) {
    $monthfolder = mkdir($imagepath . '/' . date('Y') . '/' . date('M'));
    $ftpresult = shell_exec("chmod 744  $imagepath/" . date('Y') . '/' . date('M'));
  }
  $folderpath = date('Y') . '/' . date('M');
  return ($folderpath);
}

//Folder creation for image Datewise ends Resizing function Starts
function gcd($a, $b) {
  global $ratio_globals;
  if ($a == $b) {
    $ratio_globals = $a;
    return;
  } elseif ($a > $b) {
    $x = $a;
    $y = $b;
  } else {
    $x = $b;
    $y = $a;
  }
  $x = $x - $y;
  gcd($x, $y);
}

function resizeimage2($filename, $destination_filename, $dest_width, $dest_height) {
  
  $q = 90; 

  list($original_width, $original_height) = getimagesize($filename);
  $wm = $original_width / $dest_width;
  $hm = $original_height / $dest_height;
  $h_height = $dest_width / 2;
  $w_height = $dest_height / 2;

  if ($original_width > $original_height) {
    if($dest_width==509)
	  {
		$image_height = $dest_height;
	  }
	  else
	  {
		$image_height = floor($original_height / $wm);
	  }
    $image_width = floor($dest_width);
    
    //$half_width = $image_width / 2;
    //$int_width = $half_width - $w_height;    
  } elseif (($original_width < $original_height ) || ($original_width == $original_height )) {
    //$image_width = $original_width / $hm;
    if($dest_width==509)
	  {
		$image_height = $dest_height;
	  }
	  else
	  {
		$image_height = floor($original_height / $wm);
	  }
    $image_width = floor($dest_width);
  } else {
    $image_height = floor($dest_height);
    $image_width = floor($dest_width);
  } 

  $thumbnail = imagecreatetruecolor($image_width, $image_height);
  $white = imagecolorallocate($thumbnail, 255, 255, 255);
  imagefill($thumbnail, 0, 0, $white);

  $stype = strrchr($filename, '.');
  $stype = strtolower($stype);

  switch ($stype) {
    case '.gif' :
      $image = imagecreatefromgif($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $original_width, $original_height);
      imagegif($thumbnail, $destination_filename, $q);
      break;
    case '.jpg' :
    case '.jpeg' :
      $image = imagecreatefromjpeg($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $original_width, $original_height);
     $im = imagejpeg($thumbnail, $destination_filename, $q);	 
      break;
    case '.png' :
      $image = imagecreatefrompng($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $original_width, $original_height);
      imagepng($thumbnail, $destination_filename);
      break;
  }
  
  return array($image_width, $image_height);
}

function resizeimage($filename, $destination_filename, $dest_width, $dest_height) {
  
  $q = IMAGE_QUALITY;

  $ratio_globals = 1;
  list($width, $height) = getimagesize($filename);

  $source_gcd = gcd($width, $height);
  $source_width_ratio = $width / $ratio_globals;
  $source_height_ratio = $height / $ratio_globals;

  $temp1 = ($dest_width / $source_width_ratio);
  $temp2 = ($dest_height / $source_height_ratio);
  if ($temp1 < $temp2) {
    $multiple = $temp1;
  } else {
    $multiple = $temp2;
  }
  $new_width = $dest_width;
  $new_height = $dest_height;
  $image_width = round($source_width_ratio * $multiple);
  $image_height = round($source_height_ratio * $multiple);

  $width_difference = ($new_width - $image_width) / 2;
  $height_difference = ($new_height - $image_height) / 2;

  if ($width <= $dest_width) {
    $image_width = $width;
    $image_height = $height;
  }
  $thumbnail = imagecreatetruecolor($image_width, $image_height);
  $white = imagecolorallocate($thumbnail, 255, 255, 255);
  imagefill($thumbnail, 0, 0, $white);
  //$stype=substr($filename,-4);
  //$stype=str_replace('.','',$stype);
  //$stype = 'jpg';

  $stype = strrchr($filename, '.');
  $stype = strtolower($stype);

  $size = getimagesize($filename);
  $w = $size[0];
  $h = $size[1];
  switch ($stype) {
    case '.gif' :
      $image = imagecreatefromgif($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $width, $height);
      imagegif($thumbnail, $destination_filename, $q);
      break;
    case '.jpg' :
    case '.jpeg' :
      $image = imagecreatefromjpeg($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $width, $height);
      imagejpeg($thumbnail, $destination_filename, $q);
      break;
    case '.png' :
      $image = imagecreatefrompng($filename);
      imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $image_width, $image_height, $width, $height);
      imagepng($thumbnail, $destination_filename);
      break;
  }
}

function uploadimage($file, $modulename) {

  global $sizearray;

  $modulename = strtolower($modulename);

  $sep = '/';

  $serverContentPath_final = SITE_MEDIA_PATH . $sep . $modulename;
  $sizes = $sizearray[$modulename];

  $folderpath = imagefolderpath($serverContentPath_final);
  $path = $serverContentPath_final . $sep . $folderpath;


  $xfile_type = $file["type"];
  if (!isset($modulename)) {
    if (ereg("^image/", $xfile_type) != true) {
      $retarray = 'Error';
    }
  }
  if (ereg("^image/", $xfile_type) != true) {
    $retarray = 'Error';
  } else {
    $filename = strtolower($file["name"]);  //echo $filename;
    $filename = updateimagename($filename);
    $filename = str_replace(".", "_" . time() . ".", $filename);
    $img = "$path/$filename";
    $result = move_uploaded_file($file['tmp_name'], $img);  //echo "<p>tmp=".$file['tmp_name'];	//echo "<p>result=".$result."<p>img=".$img;
    $filepath = "$path/$filename";
    $retarray = array();
    $retarray[0] = $modulename . $sep . $folderpath . $sep . $filename;
    $wcnt = count($sizes['width']);
    
    $extension = strrchr($filename, '.');
    $extension = strtolower($extension);
    $onlyname = str_replace($extension, '', $filename);
    
    for ($i = 0; $i < $wcnt; $i++) {
      $dimension = "_" . $sizes['width'][$i] . "x" . $sizes['height'][$i];
      //$newfilename = substr($filename, 0, -4) . $dimension . substr($filename, -4);
      $newfilename = $onlyname . $dimension . $extension;
      $newfilenamepath = $path . $sep . $newfilename;//substr($filename, 0, -4) . $dimension . substr($filename, -4); //echo "<p>filename=".$newfilenamepath;
      resizeimage($filepath, $newfilenamepath, $sizes['width'][$i], $sizes['height'][$i], $xfile_type);
      $retarray["new_filename" . $dimension] = $modulename . $sep . $folderpath . $sep . $newfilename;
    }
  }
  return($retarray);
}

function replacefileupload($str) {
  $str = trim($str);
  $str = str_replace(".", " ", $str);
  $str = str_replace("[", "", $str);
  $str = str_replace("]", "", $str);
  $str = str_replace("(", "", $str);
  $str = str_replace(")", "", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("&quot;", '_', $str);
  $str = ereg_replace("&#039;", "_", $str);
  $str = ereg_replace("&", "_", $str);
  $str = ereg_replace("\;", "_", $str);
  $str = ereg_replace("\(", "_", $str);
  $str = ereg_replace("\)", "_", $str);
  $str = ereg_replace(" ", "_", $str);
  //$str=ereg_replace("-","&#8212;",$str);
  $str = ereg_replace("#", "_", $str);
  $str = ereg_replace("\?", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  $str = ereg_replace("�", "_", $str);
  return $str;
}

//This function updates the image filename, replaces JPEG to JPG & other specialchars,spaces Starts
function updateimagename($filename) {

  $extension = strrchr($filename, '.');
  $extension = strtolower($extension);

  //REPLACE JPEG WITH JPG  
  $filename = strtolower($filename); //CONVERT TO LOWERCASE  
  if ($extension == '.jpeg') {
    //$filename = ereg_replace(".jpeg", ".jpg", $filename);
  }

  // END REPLACE JPEG WITH JPG  
  $onlyname = str_replace($extension, '', $filename);
  $newfilename = replacefileupload($onlyname);
  $newfilename .= $extension;
  return $newfilename;
}

//------- function to be used in media plugin
//-- download image from remote
function downloadImageFromURL($url, $dam_image_ext = '') {
  $q = 100;//IMAGE_QUALITY;
  
  $extension = parse_url($url);  
  if($dam_image_ext === ''){
	$extension = pathinfo($extension['path'], PATHINFO_EXTENSION);
	$extension = strtolower($extension);
  }else{
	$extension = $dam_image_ext;
  }

  $name = rand(1000000, 2000000) . '_' . time() . "." . $extension;
  $tmpPath = ROOTPATH . DIRECTORY_SEPARATOR .  'temp' . DIRECTORY_SEPARATOR . $name;

  //$img_r = imagecreatefromjpeg($url);
  //$is_downloaded = imagejpeg($img_r, $tmpPath, $q);
  $is_downloaded = 0;
  switch ($extension) {
    case 'gif' :
      $image = imagecreatefromgif($url);
      $is_downloaded = imagegif($image, $tmpPath, $q);
      break;
    case 'jpg' :
    case 'jpeg' :
	  $image = imagecreatefromjpeg($url);
      $is_downloaded = imagejpeg($image, $tmpPath, $q);
      break;
    case 'png' :
      $image = imagecreatefrompng($url);
      $is_downloaded = imagepng($image, $tmpPath, $q);
      break;
  }

  return array($is_downloaded, $tmpPath, $name);
}

//-- move image from tmp to media - media plugin
function mpUseImage($name, $tmpPath, $modulename, $type, $contenttype = '', $section = '') {

  global $sizearrayPlugin;
  global $movieSection;

  $modulename = strtolower($modulename);

  $sep = '/';

  $serverContentPath_final = SITE_MEDIA_PATH . $sep . $modulename;

  $sizes = $sizearrayPlugin[$modulename][$type];

  if ($modulename == 'blocks' && isset($_POST['bid'])) {
    $sizes = $sizearrayPlugin[$modulename][$type][$_POST['bid']];
  }

  /*
    if ($modulename == 'content' && $type == 'thumbnail' && $contenttype == REVIEWS && in_array($section, $movieSection)) { //  for movie review
    $sizes = $sizearrayPlugin[$modulename]['thumbnail_review'];
    }
   */
  
  if($type == 'editor') {
    if($contenttype == NEWS || $contenttype == COLUMN) {
      //$maxW = 0;
      //$maxH = 0;
    } else {      
      $sizes = array (
        "width"  => array(540),
        "height" => array(540));
    }  
  }

  $folderpath = imagefolderpath($serverContentPath_final);
  $path = $serverContentPath_final . $sep . $folderpath;

  $filename = strtolower($name);
  $filename = updateimagename($filename);

  $filepath = $path . $sep . $filename;
  
  copy($tmpPath, $filepath);

  $retarray = array();
  $retarray[0] = $modulename . $sep . $folderpath . $sep . $filename;

  $wcnt = count($sizes['width']);
  $ts = array();

  $extension = strrchr($filename, '.');
  $extension = strtolower($extension);
  $onlyname = str_replace($extension, '', $filename);
  
  for ($i = 0; $i < $wcnt; $i++) {
    //print_r($s);
    $dimension = '_' . $sizes['width'][$i] . 'x' . $sizes['height'][$i];    
    $newfilename = $onlyname . $dimension . $extension;    
    $newfilenamepath = $path . $sep . $newfilename;
    $ts["new_filename" . $dimension] = resizeimage2($filepath, $newfilenamepath, $sizes['width'][$i], $sizes['height'][$i]);
    $retarray["new_filename" . $dimension] = $modulename . $sep . $folderpath . $sep . $newfilename;
  }

  if ($type == 'gallary') {
    list($width, $height) = getimagesize($tmpPath);
    $h = PHOTOGALLERY_STRIP_HEIGHT;
    $w = ceil($h * ($width / $height));

    $dimension = '_pthumb';    
    $newfilename = $onlyname . $dimension . $extension;
    $newfilenamepath = $path . $sep . $newfilename;
    $ts["new_filename" . $dimension] = resizeimage2($filepath, $newfilenamepath, $w, $h);
    $retarray["new_filename" . $dimension] = $modulename . $sep . $folderpath . $sep . $newfilename;
  }
  
  $retarray['sizes'] = $ts;

  unlink($tmpPath);

  return($retarray);
}

// upload image into tmp folder
function mpUploadImage($file) {

  $path = ROOTPATH . DIRECTORY_SEPARATOR . 'temp';

  $xfile_type = $file["type"];
  
  $filename = strtolower($file["name"]);  //echo $filename;
  $filename = updateimagename($filename);
  $filename = str_replace(".", "_" . time() . ".", $filename);

  $filepath = $path . DIRECTORY_SEPARATOR . $filename;
  $result = move_uploaded_file($file['tmp_name'], $filepath);
  
  return(SITEPATH . '/temp/' . $filename);
}

function mpAddToSolr($data, $contentId) {
  $objAM = new AggregatedMedia();
  $doc = array();
  $doc['media_content'] = $data['path'];
  $doc['media_type'] = 1; // images
  $doc['image_alt'] = $data['pImgAlt'];
  $doc['caption'] = $data['pImgCaption'];
  
  // $document->credits = $doc['credits'];  
  // $document->insertdate = date(self::$__solr_date_format);
  // $document->title = $doc['title'];
  if ($data['is_editorial'] == 2) {
    $doc['is_editorial'] = 1;
  } else {
    $doc['is_editorial'] = $data['is_editorial'];
  }

  $doc['content_id'] = $contentId;
  $doc['is_stored'] = $data['is_stored'];

  $objAM->addDoc($doc);
  //$objAM->doCommit();
}

function mpShowImageForEdit($module, $control, $type, $displayImage, $contenttype = '', $section = '') {
  global $sizearrayPlugin;
  global $movieSection;

  if (!empty($displayImage)) {

    if ($type == 'thumbnail') {
      $widthval = min($sizearrayPlugin[$module][$type]['width']);
      $heigthval = min($sizearrayPlugin[$module][$type]['height']);

      $sizeval = $widthval . 'x' . $heigthval;
      $flag = 1;
      $tname = getthumbnail($displayImage, $sizeval);
      if (file_exists(SITE_MEDIA_PATH . '/' . $tname)) {
        $displayImage = SITE_MEDIA_URL . '/' . $tname;
      } else {
        $displayImage = SITE_MEDIA_URL . '/' . $displayImage;
      }
    } else {
      $sizeval = '';
      $flag = 0;
      $displayImage = SITE_MEDIA_URL . '/' . $displayImage;
    }

    $tHtml = "<div id='" . $control . "_container'>";
    $tHtml .= "<div style='float:left'>";
    if ($flag) {
      $tHtml .= "<img src='" . $displayImage . "' width='" . $widthval . "px' height='" . $heigthval . "px'/>";
    } else {
      $tHtml .= "<img src='" . $displayImage . "' width='100px' height='100px'/>";
    }
    $tHtml .= "</div>";
    $tHtml .= "<div style='float:left; margin-left: 10px'>";
    $tHtml .= "<a href='#' onclick='mpRemoveImage(\"" . $module . "\",\"" . $control . "\",\"" . $type . "\")'>X</a>";
    $tHtml .= "</div>";
    $tHtml .= "</div>";
    $tHtml .= "</div>";
  } else {
    $tHtml = "<div id='" . $control . "_container'>";
    $tHtml .= "<a href='javascript:void(0);' onclick='mpOpenPlugin(\"" . $module . "\",\"" . $control . "\",\"" . $type . "\");'>Upload or Search</a>";
    $tHtml .= "</div>";
  }

  return $tHtml;
}

function mpShowGallaryForEdit($module, $control, $type, $images) {
  $objMedia = new Media();
  global $sizearrayPlugin;

  $tHtml = "<div id='" . $control . "_container'>";

  if (is_array($images) && count($images) > 0) {
    $tHtml .= '<div id="gal-edit"><a onclick="mpOpenPlugin(\'' . $module . '\',\'' . $control . '\',\'' . $type . '\');" href="javascript:void(0);">Add / Edit Slides</a></div>';
    $tHtml .= '<input type="hidden" id="imageCount" value="' . count($images) . '" name="imageCount">';

    $order = array();
    //$html2 = '<ul style="height: 160px; overflow-x: auto; width: 90%;" id="gallary-images" class="ui-sortable">';
    $width = 78 * count($images);
    $html2 = '<div style="height: 120px; width: 800px; overflow-x: auto;">';
    $html2 .= '<ul style="height: 100px; width:' . $width . 'px" id="gallary-images" class="ui-sortable">';
    $id = 0;

    foreach ($images as $img) {
      $html2 .= '<li id="strip_' . $id . '" style="float:left;padding: 10px; margin-left:0px; list-style: none;">';
      $html2 .= '<div>';
      $url = SITE_MEDIA_URL . '/' . $img['thumbnail'];

      $widthval = min($sizearrayPlugin[$module][$type]['width']);
      $heigthval = min($sizearrayPlugin[$module][$type]['height']);
      $sizeval = $widthval . 'x' . $heigthval;
      $tname = getthumbnail($img['thumbnail'], $sizeval);
      if (file_exists(SITE_MEDIA_PATH . '/' . $tname)) {
        $tURL = SITE_MEDIA_URL . '/' . $tname;
      } else {
        $tURL = SITE_MEDIA_URL . '/' . $img['thumbnail'];
      }

      $html2 .= '<img width="50px" height="50px" src="' . $tURL . '">';
      $html2 .= '</div>';
      $html2 .= '<div class="mp-delete" style="padding-top: 5px; display: none;">';
      $html2 .= '<a onclick="mpRemoveImageFromStrip(\'' . $id . '\')" href="#">';
      $html2 .= '<img title="Remove" alt="Remove" src="' . IMAGEPATH . '/media-remove.gif">';
      $html2 .= '</a>';
      $html2 .= '<a onclick="mpEditFromStrip(\'' . $id . '\')" href="#">';
      $html2 .= '<img title="Edit" alt="Edit" src="' . IMAGEPATH . '/media-edit.png">';
      $html2 .= '</a>';
      $html2 .= '</div>';

      $pD = explode('-', $img['publish_date']);

      $html2 .= '<div style="clear:both; display: none">';
      $html2 .= '<input type="text" value="' . $img['thumbnail'] . '" name="' . $control . '[' . $id . '][path]" id="photogalleryimages"/>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][id]" id="id_' . $id . '">' . $img['id'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][action]" id="action_' . $id . '">use</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][url]" id="url_' . $id . '">' . $url . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][is_editorial]" id="is_editorial_' . $id . '">' . $img['is_editorial'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][is_stored]" id="is_stored_' . $id . '">' . $img['is_stored'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][downloaded_url]" id="downloaded_url_' . $id . '"></textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][origonal_url]" id="origonal_url_' . $id . '">' . $url . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][module]" id="module_' . $id . '">' . $module . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][type]" id="type_' . $id . '">' . $type . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pImgAlt]" id="pImgAlt_' . $id . '">' . $img['alt'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pCredit]" id="pCredit_' . $id . '">' . $img['credit'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pCaption]" id="pCaption_' . $id . '">' . $img['caption'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pAuthor]" id="pAuthor_' . $id . '">' . $img['author'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pDate]" id="pDate_' . $id . '">' . $pD[2] . '-' . $pD[1] . '-' . $pD[0] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pKeywords]" id="pKeywords_' . $id . '">' . $img['keyword'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pHeadline]" id="pHeadline_' . $id . '">' . $img['headline'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pHeight]" id="pHeight_' . $id . '">' . $img['height'] . '</textarea>';
      $html2 .= '<textarea name="' . $control . '[' . $id . '][pWidth]" id="pWidth_' . $id . '">' . $img['width'] . '</textarea>';
      $html2 .= '</div>';

      $html .= '</li>';
      $order[] = 'strip_' . $id;

      $id++;
    }

    $tHtml .= '<input type="hidden" id="imageOrder" value="' . implode(',', $order) . '" name="imageOrder">';
    $tHtml .= $html2;

    $tHtml .= '</ul>';
    $tHtml .= '</div>';
  } else {
    $tHtml .= "<a href='javascript:void(0);' onclick='mpOpenPlugin(\"" . $module . "\",\"" . $control . "\",\"" . $type . "\");'>Add Images</a>";
  }

  $tHtml .= "</div>";

  return $tHtml;
}

function mpBlockThumb($module, $control, $type, $bid, $rid, $displayImage, $alt) {
  global $sizearrayPlugin;
  global $movieSection;

  if (!empty($displayImage)) {

    $img = $displayImage;
    if ($type == 'thumbnail') {
      $widthval = min($sizearrayPlugin[$module][$type]['width']);
      $heigthval = min($sizearrayPlugin[$module][$type]['height']);

      $sizeval = $widthval . 'x' . $heigthval;
      $flag = 1;
      $displayImage = SITE_MEDIA_URL . '/' . getthumbnail($displayImage, $sizeval);
    } else {
      $sizeval = '';
      $flag = 0;
      $displayImage = SITE_MEDIA_URL . '/' . $displayImage;
    }

    $tHtml .= "<a id='media" . $rid . "' class='mp-edit' href='javascript:void(0);' onclick='mpOpenPlugin(\"" . $module . "\",\"" . $control . "\",\"" . $type . "\",\"" . $bid . "\",\"" . $rid . "\",\"" . $img . "\",\"" . $alt . "\" );'></a>";
  } else {
    $tHtml .= "<a id='media" . $rid . "' class='mp-add' href='javascript:void(0);' onclick='mpOpenPlugin(\"" . $module . "\",\"" . $control . "\",\"" . $type . "\",\"" . $bid . "\",\"" . $rid . "\");'></a>";
  }

  return $tHtml;
}


function mpShowDaylifeImages($images) {
  
  $tHtml = "<div>";
  
  $width = 78 * count($images);
  $html2 = '<div style="height: 120px; width: 800px; overflow-x: auto;">';
  $html2 .= '<ul style="height: 100px; width:' . $width . 'px" id="gallary-images" class="ui-sortable">';  

  foreach ($images as $img) {
    $html2 .= '<li style="float:left;padding: 10px; margin-left:0px; list-style: none;">';
    $html2 .= '<div>';    

    $sizeval = '50x50';
    $tURL = str_replace('650x.jpg', $sizeval.'.jpg', $img['thumbnail']);                              
    if($img['width'] < $img['height']) {
      $tURL .= '?fit=scale&background=000000';
    } else {
      $tURL .= '?center=0.5,0&background=000000';
    }

    $html2 .= '<img width="50px" height="50px" src="' . $tURL . '">';
    $html2 .= '</div>';

    $html2 .= '</li>';
  }

  $tHtml .= $html2;

  $tHtml .= '</ul>';
  $tHtml .= '</div>';

  $tHtml .= "</div>";

  return $tHtml;
}

function uihelper_resize_img_old($pic, $max_x, $max_y){  
	 $file_path = SITE_MEDIA_PATH.'/'.$pic; 	
	 $file_path_arr = explode('/', $file_path);
	 $file_name = end($file_path_arr);  
     $dimension = '_' . $max_x . 'x' . $max_y.'.';    
	 $file_split = explode('.', $file_name); 
 	 $new_filename = $file_split[0]. $dimension.$file_split[1];
	 $newfilenamepath = SITE_MEDIA_PATH . '/' . $new_filename;

   if( is_file($newfilenamepath) ){ 
	    $image_file = end(explode('/', $newfilenamepath));
	    $media_url =  SITE_MEDIA_URL.'/'.$image_file;
     	 return $media_url;
    } else if( ($_SERVER['HTTP_HOST'] == 'dev.indiatimes.com') ){
		  $newfilenamepath_arr = explode('/', $newfilenamepath);
		  $image_file = end($newfilenamepath_arr);
 	      $media_url =  "http://alfa.indiatimes.com/media/".$image_file;
	      return $media_url;
   } else {  
        $image_size_arr = getimagesize($file_path);  
		$width = $image_size_arr[0];
		$height = $image_size_arr[1];
	  if($max_x <= $width ){ 
		  if($max_y <= $height){				
				$image_data = resizeimage2($file_path, $newfilenamepath, $max_x, $max_y);	
				$image_file = end(explode('/', $newfilenamepath));
			    $media_url =  SITE_MEDIA_URL.'/'.$image_file;
     	        return $media_url;				
		  }else{
             // need to check
		    $media_url =  SITE_MEDIA_URL.'/'.$pic;
	      }
 	   }else{
		 // need to check
		  $media_url =  SITE_MEDIA_URL.'/'.$pic;
	  } 
	 return $media_url;	 
    }	
 
}


function uihelper_resize_img($pic, $max_x, $max_y){  
	 $file_path = $pic; 	
	 $file_path_arr = explode('/', $file_path);
	 $file_name = end($file_path_arr);    
	 $file_name_prefix_array = explode('/', $file_path, -1);
	 $file_name_prefix = implode('/', $file_name_prefix_array); 
     $dimension = '_' . $max_x . 'x' . $max_y.'.';    
	 $file_split = explode('.', $file_name); 
 	 $new_filename = $file_name_prefix.'/'.$file_split[0]. $dimension.$file_split[1];
	 $newfilenamepath = SITE_MEDIA_PATH . '/' . $new_filename; 

   if( is_file($newfilenamepath) ){ 	    
	     $media_url =  SITE_MEDIA_URL.'/'.$new_filename;
     	 return $media_url;
    } else if( ($_SERVER['HTTP_HOST'] == 'w.indiatimes.com') || ($_SERVER['HTTP_HOST'] == 'branch.indiatimes.com') || ($_SERVER['HTTP_HOST'] == 'trunk.indiatimes.com' ) ){

              return $media_url =  'http://revamp.indiatimes.com/media/'.$pic;
        
   } else {  
        $image_size_arr = getimagesize(SITE_MEDIA_PATH.'/'.$file_path);  		
		$width = $image_size_arr[0];
		$height = $image_size_arr[1];
	  if($max_x <= $width ){ 
		  if($max_y <= $height){			
				$image_data = resizeimage2(SITE_MEDIA_PATH.'/'.$file_path, $newfilenamepath, $max_x, $max_y);	
				$image_file = end(explode('/', $newfilenamepath));
			    $media_url =  SITE_MEDIA_URL.'/'.$new_filename; 
     	        return $media_url;				
		  }else{
		    return SITE_MEDIA_URL.'/'.$pic;
		     // need to chek  
	      }
 	   }else{
	      return SITE_MEDIA_URL.'/'.$pic; 
		 // need to chek
	  } 
		 
    }	
 
}