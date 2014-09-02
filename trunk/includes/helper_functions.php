<?php
if (!function_exists("define_once")) {
  function define_once($k, $v) {
    if (!defined($k)) return define($k, $v);
  }
}
if (!function_exists('property_exists')) {
  function property_exists($class, $property) {
   if (is_object($class))
     $class = get_class($class);
   return array_key_exists($property, get_class_vars($class));
  }
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
?>
