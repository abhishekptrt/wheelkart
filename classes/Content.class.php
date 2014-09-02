<?php
class Content {

  public $db = null;
  const TABLE_NAME = 'content';
  private $posts = array();
  private $__cache_tag = 0;

  function __construct() {
    $this->db = Database::Instance();
  }

  public function getQuote($id, $fields = 'id,headline1') {
    $return = array();
    $condition['id'] = $id;
    $condition['status'] = 1;
    $return = $this->db->getDataFromTable($condition, self::TABLE_NAME, $fields, '', '', 0);

    if ($return['data_count']) {
      $return = $return['data'][0];
    }

    return $return;
  }

  private function __createCacheKey($content_id) {
    return CONTENT_CACHE_KEY_STR . '_'. $content_id;
  }
 
  private function removePicCaption(){
	      if(!empty($this->posts['data'][0]['description'])){
			  $picCaptionpattern = '/<span\s*class="picCaption".*?<\/span>/si';
			  $this->posts['data'][0]['description'] = preg_replace($picCaptionpattern, '',  $this->posts['data'][0]['description']);
		  }
  }
  public function getDetailPageData($id, $flag = NULL, $option = array()) { 
    $cache = isset($option['cache']) ? (bool) $option['cache'] : true;
    $refresh_cache = isset($option['refresh_cache']) ? (bool) $option['refresh_cache'] : false;
    $set_cache = false;
    $cache_expiry = 864000;
    $coming_from_cache=0;

    $object_cache = Cache::cacheInstance();
    $cache_key = $this->__createCacheKey($id);

    $content_tbl = self::TABLE_NAME;
    $content_meta_tbl = ContentMetadata::TABLE_NAME;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;
	$content_count_tbl = ContentCount::TABLE_NAME;

    $id = $this->db->db_escape($id);
    $fields = !empty($param['fields']) ? $param['fields'] : 'c.*,c.sponsored_id,c.list_sort_order, c.source_is_til_network as is_til_network,cm.content_id,cm.description,cm.keywords,cm.metatitle,cm.metakeyword,
           cm.fbtitle, cm.fbdescription, cm.facebookthumbnail,cm.facebook_img_alt,cm.followkeywords,cm.descriptionkeywords,cm.metadescription,cm.object_code,csr.section_id,csr.section_name,csr.section_parentid,csr.section_parentname';
	$sql = "select $fields
				from $content_tbl AS c
				join $content_meta_tbl AS cm on c.id = cm.content_id
				join $content_section_tbl AS csr on c.id = csr.content_id
				where c.id=" . $id . " and csr.is_primary = 1";
    if ($flag != 'p') {
      if (0 && $cache && false === $refresh_cache) {
        $this->posts = $object_cache->get($cache_key);
        $this->__cache_tag = $cache_key;
      }

      if (empty($this->posts)) {
        $sql .=" and c.status=1 ";		
        $this->posts = $this->db->executeSql($sql, 0, 0);
        $set_cache = true;
        $this->__cache_tag = 0;
      }
      else {      	
      	$coming_from_cache=1;
      }
    } else { 
      $this->posts = $this->db->executeSql($sql, 0, 0); //In case of preview do not store in Memcache
      $this->__cache_tag = 0;
    }
    if ($this->posts['data_count']) {
      /* bof all database releated code in this  block */
    if($coming_from_cache != 1 )
	{
		  $this->setVideoTemplate(); //video formation @todo add publish check for video
		  $this->setMediaImages(); // image processing cached
		  $this->setByLineAuthor(); // author processing
		  $this->setFollowKeywords();
		  $this->setGoogleAdcodes();
          $this->setPoll();
          $this->setPollTemplate();
		  $this->cleartag(); // since we have not got the mockups for gallery + poll + quote within the article this function is used to remove those tag once we get do comment this line and uncommet the appropriate function.
		  if (1 == $this->posts['data'][0]['is_aggregator_data']) {
			$this->setSource(); // set source extra information
		  }
		  if ( !empty($this->posts['data'][0]['sponsored_id']))
		  {
			  $this->setSponsored();
		  }
		if (!isset($this->posts['data'][0]['media']) && in_array($this->posts['data'][0]['contype_id'], array(PHOTOGALLERY, PICTURESTORY))) {
			$object_media = new Media;
			$this->posts['data'][0]['media'] = $object_media->getMedia($this->posts['data'][0]['id']);
		}
	}
		/*code to remove blank p tags added by content team*/
	  $this->posts['data'][0]['description'] = str_replace("<p>&nbsp;</p>","",$this->posts['data'][0]['description']);
      /* eof all database releated code in this  block */
      $this->removePicCaption(); 
      $this->removeYoutubeThumbnailWithEmbed();
	  $this->replaceFacebookThumbnail();
	  $this->replaceInstagramThumbnail();

      if(!isset($this->posts['data'][0]['_description_full'])) {
      	$this->posts['data'][0]['_description_full'] = $this->posts['data'][0]['description'];
      }

      $this->posts['data'][0]['guid'] = $this->posts['data'][0]['guid'];
      $this->posts['data'][0]['description'] = explode('__PAGEBREAK__', $this->posts['data'][0]['_description_full']);
	 
	  $this->posts['data'][0]['totalpages'] = count($this->posts['data'][0]['description']);

      $pg = ( isset($option['pg']) && is_int((int) $option['pg'])) ? $option['pg'] : 1;

      $this->posts['data'][0]['guid_previous'] = NULL;
      $this->posts['data'][0]['guid_next'] = NULL;
      // if the flag=p then for next pagination we will consider php page url rather the the guid.
      $pageName = basename($_SERVER["SCRIPT_FILENAME"]);
      $pageFlag = '';
      if (isset($_GET['flag']))
        $pageFlag = $_GET['flag'];
      if ($pageFlag == 'p') {
        if ($pg != count($this->posts['data'][0]['description'])) {
          $this->posts['data'][0]['guid_next'] = SITEPATH . '/' . $pageName . '?id=' . $this->posts['data'][0]['content_id'] . '&flag=p&pg=' . ($pg + 1);
        }

        if ($pg != 1) {
          $this->posts['data'][0]['guid_previous'] = SITEPATH . '/' . $pageName . '?id=' . $this->posts['data'][0]['content_id'] . '&flag=p&pg=' . ($pg - 1);
        }
      } else {
        if ($pg != count($this->posts['data'][0]['description'])) {
          $this->posts['data'][0]['guid_next'] = getPagingGuid($this->posts['data'][0]['guid'], ($pg + 1));
        }

        if ($pg != 1) {

          $this->posts['data'][0]['guid_previous'] = ($pg == 2) ? $this->posts['data'][0]['guid'] : getPagingGuid($this->posts['data'][0]['guid'], ($pg - 1));
        }
      }
      if (isset($this->posts['data'][0]['description'][$pg - 1])) {
        $this->posts['data'][0]['description'] = $this->posts['data'][0]['description'][$pg - 1];
      }

      
	  
	  $this->posts['data'][0]['page_title'] = $this->posts['data'][0]['headline1'] . ' | ' . $this->posts['data'][0]['section_name'];
  	  $this->posts['data'][0]['page_description'] = strip_tags($this->posts['data'][0]['summary']);
	  $this->posts['data'][0]['fbtitle'] = $this->posts['data'][0]['fbtitle'];
      $this->posts['data'][0]['fbdescription'] = $this->posts['data'][0]['fbdescription'];
	  $this->posts['data'][0]['facebookthumbnail'] = $this->posts['data'][0]['facebookthumbnail'];



	  if($pg > 1){
		if($this->posts['data'][0]['contype_id'] == PICTURESTORY || $this->posts['data'][0]['contype_id'] == PHOTOGALLERY){
			$this->posts['data'][0]['page_title'] .= ' | Slide ' . $pg;
			$this->posts['data'][0]['page_description'] = isset($this->posts['data'][0]['media']['data'][$pg-1]['caption']) ? strip_tags($this->posts['data'][0]['media']['data'][$pg-1]['caption']) : "";
		}else if($this->posts['data'][0]['contype_id'] == LISTS){
			$this->posts['data'][0]['page_title'] .= ' | Slide ' . $pg;
		}else{
			$this->posts['data'][0]['page_title'] .= ' | Page ' . $pg;
		}
	  }
      $this->posts['data'][0]['page_title'] .=  ' | '. SITE_TITLE;

    //  $this->posts['data'][0]['cache_tag'] = displayMemcacheDiv($this->__cache_tag);
      $sObj = new SocialTracker();
      $id=166557;
      $sArr = $sObj->getStoryGraphData($id);
	  if(isset($sArr['data'][$id][0]['ShareCount']))
      $this->posts['data'][0]['ShareCount'] = $sArr['data'][$id][0]['ShareCount'];
      if ($cache && $set_cache) {
      	$r = $object_cache->set($cache_key, $this->posts, $cache_expiry);
      	$this->__cache_tag = 0;      	
      	// update articleNP cache
		if(($this->posts['data'][0]['contype_id']==NEWS || $this->posts['data'][0]['contype_id']==COLUMN || $this->posts['data'][0]['contype_id']== MATCHREPORT) && $this->posts['data'][0]['is_aggregator_data']==0)
		  {
      		$this->updateNextPrevArticles('a', $id, $this->posts['data'][0]['section_parentid']);
		  }
      }
    }   
    return $this->posts;
  }

  private function replaceInstagramThumbnail(){
	  if(!empty($this->posts['data'][0]['description'])){
		  $pattern_instagram = '/\<img\s*class="instagramThumbnail".*?alt="(.*?)"\s*\/>/';
		  $replacement_instagram = '<iframe title="instagram video player" width="'.$video_width.'"  height="710" src="//$1/embed/"  frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
		  $result = $this->posts['data'][0]['description'];
		  $this->posts['data'][0]['description'] = preg_replace($pattern_instagram, $replacement_instagram, $result);          
	  }
   }
   
    private function replaceFacebookThumbnail (){
    if(!empty($this->posts['data'][0]['description'])){
		 $pattern_fb = '/\<img\s*class="fbThumbnail".*?alt="(.*?)"\s*\/>/';
         $replacement_fb = '<div class="fb-post" data-href="$1"></div>';
	     $result = $this->posts['data'][0]['description'];
         $this->posts['data'][0]['description'] = preg_replace($pattern_fb, $replacement_fb, $result);
     
	}
    }	 

   private function removeYoutubeThumbnailWithEmbed (){
    if(!empty($this->posts['data'][0]['description'])){
		  $patterns = array();
		$patterns[0] = '/\<img\s*class="youtubesearchThumbnail".*?alt="(.*?)"\s*\/>\s*\<span\>Youtube\s*Title.*?\<\/span\>/';	
		$patterns[1] = '/\<img\s*class="youtubeThumbnail".*?alt="(.*?)"\s*\/>\s*/';
		  if($this->posts['data'][0]['contype_id'] == LISTS){
				$video_width = '100%';
		  }else{
				$video_width = '100%';
		  }
			
		  $replacement = '<iframe title="YouTube video player" width="'.$video_width.'" height="190" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';
		  $result = $this->posts['data'][0]['description'];
		  $this->posts['data'][0]['description'] = preg_replace($patterns, $replacement, $result);
	 }

  }

  public function cleartag(){
	    $html = NULL;
        $pattern = '/(__GALLERY__([0-9]*)__)|(__QUOTE__([0-9]*)__)/';//|(__POLL__([0-9]*)__)
        $this->posts['data'][0]['description'] = preg_replace($pattern, '', $this->posts['data'][0]['description']);
  }
  public function setGoogleAdcodes() {
        $html = NULL;
        $pattern = "/__GOOGLEADCODE__/";
       if(preg_match ($pattern ,$this->posts['data'][0]['description']))
	  {
		$html = $this->getGoogleCodeTemplate();
        $this->posts['data'][0]['description'] = preg_replace($pattern, $html, $this->posts['data'][0]['description']);
		$this->posts['data'][0]['adcode']=1;
	  }
	  else
	  {
		$this->posts['data'][0]['adcode']=0;
	  }
  }
  
  public function getGoogleCodeTemplate()
  {
    $return = NULL;
      ob_start();
	  include (GOOGLE_AD_ROOT_PATH . '/IT_articleshowpage_Middle.html');
      $return = ob_get_clean();
    return $return;
  }
  
  public function setFollowKeywords() {
    if (!isset($this->posts['data'][0]['set_follow_keywords_flag'])) {
      $this->posts['data'][0]['set_follow_keywords_flag'] = 'cached';
      $this->posts['data'][0]['followkeywords'] = trim($this->posts['data'][0]['followkeywords']);
      if (isset($this->posts['data'][0]['followkeywords']) && !empty($this->posts['data'][0]['followkeywords'])) {
        $this->posts['data'][0]['followkeywords'] = explode('`#`', $this->posts['data'][0]['followkeywords']);
      }
    }
  }

  public function setByLineAuthor() {
    if (!isset($this->posts['data'][0]['by_line_authors_flag'])) {
      $this->posts['data'][0]['by_line_authors_flag'] = 'cached';
      if (isset($this->posts['data'][0]['by_line_author_id']) && 0 != $this->posts['data'][0]['by_line_author_id'] && !empty($this->posts['data'][0]['by_line_author_id_text'])) {
        $author_ids = explode(',', $this->posts['data'][0]['by_line_author_id']);
        $author_name = explode(',', $this->posts['data'][0]['by_line_author_id_text']);
        foreach ($author_ids as $a_key => $a_val) {
          $this->posts['data'][0]['by_line_authors'][$a_val] = '<a href="' . SITEPATH . '/archive.php?aid=' . $a_val . '" > ' . $author_name[$a_key] . '</a>';
        }
      }
    }
  }

  public function setMediaImages() {
    //update images
    if (!isset($this->posts['data'][0]['media_cached'])) {
      $this->posts['data'][0]['media_cached'] = 'cached';
      $this->posts['data'][0]['description'] = str_replace('../../media', SITE_MEDIA_URL, $this->posts['data'][0]['description']);
      $this->posts['data'][0]['description'] = str_replace('../media', SITE_MEDIA_URL, $this->posts['data'][0]['description']);
    }
  }

  private function setCounters() {
    if (!isset($this->posts['data'][0]['counts'])) {
      $object_content_count = new ContentCount;
      $this->posts['data'][0]['counts'] = $object_content_count->getCounts($this->posts['data'][0]['id']);
    }
  }

  public function setTopicsTemplate() {
    //$this->posts['data'][0]['descriptionkeywords'] = 'Harry Potter`#`Emma Watson';
    if (in_array($this->posts['data'][0]['contype_id'], array(NEWS, REVIEWS, COLUMN, MATCHREPORT)) &&
            isset($this->posts['data'][0]['descriptionkeywords']) && !empty($this->posts['data'][0]['descriptionkeywords'])
    ) {
      $reload = false;
      if (isset($this->posts['data'][0]['topics'])) {
        $topics = $this->posts['data'][0]['topics'];
      } else {
        $topics = explode('`#`', $this->posts['data'][0]['descriptionkeywords']);
        $reload = true;
      }

      if ($reload && isset($topics) && 0 < count($topics)) {
        $topics = array_unique($topics);
        //print_r($topics);
        $description = $this->posts['data'][0]['description'];
        //print $description;
        $topics_cnt = count($topics);
        $match = '/^[a-zA-Z0-9_\s\(\)\.]*$/';
        $img_extract_pattern = "/<img.*>/siU";
        preg_match_all($img_extract_pattern, $description, $description_images);
        if (isset($description_images[0]) && 0 < $description_images[0]) {
          foreach ($description_images[0] as $temp_img) {
            $description = str_replace($temp_img, md5($temp_img), $description);
          }
        }
        for ($i = 0; $i < $topics_cnt; $i++) {
          $position = false;
          if (!empty($topics[$i])) {
            $tn = $topics[$i];
            $matches = array();
            preg_match($match, $tn, $matches);
            if (0 < count($matches)) {
              $temp_tn = str_replace(array('(', ')'), array('\(', '\)'), $tn);
              $topic_pattern = '/' . $temp_tn . '(?!.*<\/a>)/i';
              $link = TOPIC_SITE_URL . urlencode(trim($tn));
              $replace = "<a title=\"$tn\" href=\"$link\" target=\"_blank\">$tn</a>";
              $description = preg_replace($topic_pattern, $replace, $description, 1);
            }
          }
        }
        if (isset($description_images[0]) && 0 < $description_images[0]) {
          foreach ($description_images[0] as $temp_img) {
            $description = str_replace(md5($temp_img), $temp_img, $description);
          }
        }
        $this->posts['data'][0]['description'] = $description;
        $this->posts['data'][0]['topics'] = 'cached';
      }
    }
  }

  public function setSource() {
    $source_id = $this->posts['data'][0]['source_id'];
    $object_source = new Source;
    $source_data = $object_source->getSource($source_id);
    $this->posts['data'][0]['is_til_network'] = 0;
    if (is_array($source_data) && isset($source_data['is_til_network'])) {
      $this->posts['data'][0]['is_til_network'] = $source_data['is_til_network'];
    }
  }
  
  public function setSponsored()
  {
      if ( empty($this->posts['data'][0]['sponsor']) )
      {
          $object_sponsore = new Sponsored();
          $data = $object_sponsore->getSponsor($this->posts['data'][0]['sponsored_id']);
          if (is_array($data) && isset($data['logo'])) 
          {
             $this->posts['data'][0]['sponsor'] = $data;
          }
      }    
  }

  public function setPoll($user_id = 0) {
    if (in_array($this->posts['data'][0]['contype_id'], array(NEWS, REVIEWS, COLUMN, MATCHREPORT))) {
      $pattern = '/__POLL__([0-9]*)__/';
      $data = array();
      preg_match_all($pattern, $this->posts['data'][0]['description'], $data);
      if (isset($data[1]) && 0 < count($data[1])) {
        $data = array_unique($data[1]);
        $object_poll = new PollQuestion;
        foreach ($data as $pid) {
          $content = NULL;
          if (isset($this->posts['data'][0]['poll_data'][$pid])) {
            $content = $this->posts['data'][0]['poll_data'][$pid];
          } else {
            $poll_args = array();
            $poll_args['poll_id'] = $pid;
            $poll_args['user_id'] = $user_id;
            $poll_args['return_type'] = 'data';
            $content = $object_poll->getPollTemplate($poll_args);
          }

          $html = NULL;
          $pattern = "/__POLL__{$pid}__/";

          if (isset($content)) {
            $this->posts['data'][0]['poll_data'][$pid] = $content;
            $html = $content;
          }
        }
      }
    }
  }

  public function setPhotoGallary() {
    if (in_array($this->posts['data'][0]['contype_id'], array(NEWS, REVIEWS, COLUMN, MATCHREPORT))) {
      $pattern = '/__GALLERY__([0-9]*)__/';
      $data = array();
      preg_match_all($pattern, $this->posts['data'][0]['description'], $data);
      if (isset($data[1]) && 0 < count($data[1])) {
        $data = array_unique($data[1]);
        $object_poll = new PollQuestion;
        foreach ($data as $pid) {
          $content = NULL;
          if (isset($this->posts['data'][0]['poll_data'][$pid])) {
            $content = $this->posts['data'][0]['photo_gallery_data'][$pid];
          } else {
            $object_con_temp = new Content;
            $content = $object_con_temp->getDetailPageData($pid);
          }

          $html = NULL;
          $pattern = "/__GALLERY__{$pid}__/";

          if (isset($content[0])) {
            $content = $content[0];
            $this->posts['data'][0]['photo_gallery_data'][$pid] = $content;
          }
        }
      }
    }
  }

  public function setPhotoGalleryTemplate() {
    $tpl = '/tplPhotoGallary.php';

    if (isset($this->posts['data'][0]['photo_gallery_data']) && 0 < count($this->posts['data'][0]['photo_gallery_data'])) {
      $object_poll = new PollQuestion;
      $html = NULL;
      foreach ($this->posts['data'][0]['photo_gallery_data'] as $pid => $article) {
        $pattern = "/__GALLERY__{$pid}__/";
        $photo_plugin = true;
        $slides = $article['media'];
        $noOfSlides = $slides['data_count'];
        unset($slides['data_count']);
        $previewFlag = false;
        $sid = 0;
        $current_slide_no = 1;
        $slide = $slides[$sid];
        $articleGuide = $article['guid'];
        ob_start();
        include TPL_DIR_PATH . $tpl;
        $html = ob_get_clean();
        $this->posts['data'][0]['description'] = preg_replace($pattern, $html, $this->posts['data'][0]['description']);
      }
    }
  }

  public function setPollTemplate() {
    if (isset($this->posts['data'][0]['poll_data']) && 0 < count($this->posts['data'][0]['poll_data'])) {
      $object_poll = new PollQuestion;
      $html = NULL;
      foreach ($this->posts['data'][0]['poll_data'] as $pid => $poll) {
        $pattern = "/__POLL__{$pid}__/";
        $html = $object_poll->getTemplate($poll);
        $this->posts['data'][0]['description'] = preg_replace($pattern, $html, $this->posts['data'][0]['description']);
      }
    }
  }

  public function setVideoTemplate() {
    if (in_array($this->posts['data'][0]['contype_id'], array(NEWS, REVIEWS, LISTS, COLUMN, MATCHREPORT))) {
      $pattern = '/__VIDEO__([0-9]*)__/';
      $data = array();
      preg_match_all($pattern, $this->posts['data'][0]['description'], $data);
      if (isset($data[1]) && 0 < count($data[1])) {
        $data = array_unique($data[1]);
        $object_content_meta = new ContentMetadata;
        foreach ($data as $vid) {
          if (isset($this->posts['data'][0]['video_data'][$vid])) {
            $content = $this->posts['data'][0]['video_data'][$vid];
          } else {
            $content = $object_content_meta->getMeta($vid, 'id,description,keywords,object_code');
          }

          $html = NULL;
          $pattern = "/__VIDEO__{$vid}__/";

          if (is_array($content) && isset($content['object_code'])) {
            $this->posts['data'][0]['video_data'][$vid] = $content;
            $html = '<a href="javascript:;" class="lc TurnOff">Turn Off Lights</a><div class="clr"></div>';
            $html .= '<div align="center" style="position: relative; z-index: 9999999;">' . $content['object_code'] . '</div>';
          }

          $this->posts['data'][0]['description'] = preg_replace($pattern, $html, $this->posts['data'][0]['description']);
        }
      }
    }
  }

  public function setQuoteTemplate() {

    if (in_array($this->posts['data'][0]['contype_id'], array(NEWS, REVIEWS, COLUMN, MATCHREPORT))) {
      $q_pattern = '/__QUOTE__([0-9]*)__/';
      $q_data = array();
      preg_match_all($q_pattern, $this->posts['data'][0]['description'], $q_data);

      if (isset($q_data[1]) && 0 < count($q_data[1])) {
        $q_data = array_unique($q_data[1]);
        foreach ($q_data as $qid) {
          if (isset($this->posts['data'][0]['quote_data'][$qid])) {
            $qcontent = $this->posts['data'][0]['quote_data'][$qid];
          } else {
            $qcontent = $this->getQuote($qid);
          }

          $q_html = NULL;
          $q_pattern = "/__QUOTE__{$qid}__/";

          if (is_array($qcontent) && isset($qcontent['headline1'])) {
            $this->posts['data'][0]['quote_data'][$qid] = $qcontent;
            $q_html = str_replace(array('[[QUOTE]]'), array($qcontent['headline1']), QUOTE_HTML_TEMPLATE);
          }

          $this->posts['data'][0]['description'] = preg_replace($q_pattern, $q_html, $this->posts['data'][0]['description']);
        }
      }
    }
    /* eof quote formation */
  }

  public function getList($option, $args = array()) {
    $results = array();
    switch ($option) {
      case 'BY_IDS':
        $ids = ( isset($args['ids']) && ( 0 < count($args['ids'])) ) ? $args['ids'] : array();
        if (0 < count($ids)) {
          $c_sql = 'SELECT id, headline1, guid, source_id FROM ' . Content::TABLE_NAME . " WHERE publishdate <= NOW() AND id='%s' ";
          foreach ($ids as $id) {
            $temp_data = array();
            $id = $this->db->db_escape($id);
            $temp_data = $this->db->executeSql(sprintf($c_sql, $id));
            if ($temp_data['data_count']) {
              $results[] = current($temp_data['data']);
            }
          }
        }
        break;
    }
    return $results;
  }

  public function getOriginalnAggregatedData($args = array()){
	$returnArr['data_count'] = 0;
	if(isset($args['original'])  && $args['original'] != 0){
		$args['is_aggregator_data'] = 0;
		$args['limit'] = $args['original'];
		$originalDataArr = $this->getContentList($args);
		if($originalDataArr['data_count']){
			$returnArr['data_count'] = $returnArr['data_count'] + $originalDataArr['data_count'];
			foreach($originalDataArr['data'] as $key => $val){
				$returnArr['data'][] = $val;
				if(isset($args['id_not_in']) && $args['id_not_in'] != ""){
					$args['id_not_in'] .= ",";
					$args['id_not_in'] .=  $val['id'];
				}else{
					$args['id_not_in'] =  $val['id'];
				}
			}
		}
	}
	if(isset($args['aggregated']) && $args['aggregated'] != 0){
		unset($args['is_aggregator_data']);
		$args['aggregator_url'] = 1;
		$args['limit'] = $args['aggregated'];
		$aggregatedDataArr = $this->getContentList($args);
		if($aggregatedDataArr['data_count']){
			$returnArr['data_count'] = $returnArr['data_count'] + $aggregatedDataArr['data_count'];
			foreach($aggregatedDataArr['data'] as $key => $val){
				$returnArr['data'][] = $val;
			}
		}
	}
	return $returnArr;
  }

  public function getContentList($args = array(), $setmem=true) { // , 1 ,7200
     //echo '<pre>'; print_r($args); echo '</pre>';
    //$setmem = false;
	
	$require_section_join  = ( isset($args['require_section_join']) ) ? intval($args['require_section_join']) : 1;
	
    $result_type = isset($args['result_type']) ? $args['result_type'] : 'data';

    $con_type_id = ( isset($args['contype_id']) ) ? intval($args['contype_id']) : 0;

	$country_id = ( isset($args['country_id']) ) ? intval($args['country_id']) : 0;

	$erating = ( isset($args['erating']) ) ? $args['erating'] : '';

    $section_id = ( isset($args['section_id']) ) ? intval($args['section_id']) : 0;

    $videotype = ( isset($args['videotype']) ) ? intval($args['videotype']) : 0;
	
	$videotype_not_in = ( isset($args['videotype_not_in'])) ? $args['videotype_not_in'] : '';

    $author_id = ( isset($args['author_id']) ) ? intval($args['author_id']) : 0;

    $author_name_src = ( isset($args['author_name']) ) ? $args['author_name'] : '';

    $reaction = ( isset($args['reaction']) ) ? $args['reaction'] : '';

    $is_aggregator_data = ( isset($args['is_aggregator_data']) ) ? $args['is_aggregator_data'] : '';

	$sponsored_id = ( isset($args['sponsored_id']) ) ? $args['sponsored_id'] : '';

	$source_is_til_network = ( isset($args['source_is_til_network']) ) ? intval($args['source_is_til_network']) : 0;
	
    $id_not_in = ( isset($args['id_not_in'])) ? $args['id_not_in'] : '';

	$id_in = ( isset($args['id_in'])) ? $args['id_in'] : '';
        $id_less_than = ( isset($args['id_less_than'])) ? $args['id_less_than'] : '';

    $contype_id_in = ( isset($args['contype_id_in'])) ? $args['contype_id_in'] : '';

    $section_id_not_in = ( isset($args['section_id_not_in'])) ? $args['section_id_not_in'] : '';
    
    $section_id_in = ( isset($args['section_id_in'])) ? $args['section_id_in'] : '';
	
	$source_id_not_in = ( isset($args['source_id_not_in'])) ? $args['source_id_not_in'] : '';

    $contype_name = ( isset($args['contype_name'])) ? $args['contype_name'] : '';

    $section_parent_id = ( isset($args['section_parent_id']) ) ? intval($args['section_parent_id']) : 0;

    $is_columnist = isset($args['is_columnist']) ? $args['is_columnist'] : 0;
	$is_exclusive = isset($args['is_exclusive']) ? $args['is_exclusive'] : 0;
    $column_author_id = isset($args['column_author_id']) ? $args['column_author_id'] : 0;
	
	$dateFilter = isset($args['dateFilter']) ? $args['dateFilter'] : '';
	$dateFilternew = isset($args['dateFilternew']) ? $args['dateFilternew'] : '';

	$source_id = ( isset($args['source_id']) ) ? $args['source_id'] : 0;
    $source_alias = ( isset($args['source_alias'])) ? $args['source_alias'] : 0;

    $limit = ( isset($args['limit']) ) ? intval($args['limit']) : 10;
    $start = ( isset($args['start']) ) ? intval($args['start']) : 0;
	$groupby = ( isset($args['groupby']) ) ? $args['groupby'] : '';

    $tableContentCount = ( isset($args['tableContentCount']) ) ? intval($args['tableContentCount']) : 0;
	$contnet_meta_table_required = ( isset($args['contnet_meta_table_required']) ) ? intval($args['contnet_meta_table_required']) : 0;
    $fields = isset($args['fields']) ? implode(',', $args['fields']) : 'c.*,csr.section_name,csr.section_id,csr.section_parentname,csr.section_parentid';
	
	$group_by = '';
    $limit_sql = " LIMIT $start, $limit";
	if($groupby != ""){
		$group_by = ' group by ' . $groupby;
	}
    $order_by_sql = NULL;
    $order_by = isset($args['order_by']) ? $args['order_by'] : array('id' => 'desc');
	if($require_section_join == 1 &&  ($section_id_in != ""  || 0 != $section_parent_id || 0 != $section_id)){
		$order_by = isset($args['order_by']) ? $args['order_by'] : array('csr.content_id' => 'desc');
	}
    //$order_by['c.insertdate'] = 'desc';
    $cnt_order_by = count($order_by);        
    if ('count' != $result_type && 0 < $cnt_order_by) {
      $order_by_sql = ' ORDER BY ';  
      $tmp_cnt = 1;      
      foreach ($order_by as $o_key => $order_by) {          
        if ((strpos($o_key, '.') !== false))
          $order_by_sql .= " {$o_key} $order_by";
        else
          $order_by_sql .= " c.{$o_key} $order_by";
          
        if ( $tmp_cnt != $cnt_order_by ) {
            $order_by_sql .= ',';
        }
        $tmp_cnt++;
      }
      //$order_by_sql = rtrim($order_by_sql, ',');
    }

    if ('count' == $result_type) {
       $fields = ' count(distinct c.id) as cnt ';
      $limit_sql = NULL;
	  $group_by = NULL;
    }else{
		$fields .= ', c.contype_id'. ', c.source_is_til_network as is_til_network,c.publishdate,c.updatedate  ';
	}


    $sql = 'SELECT ' . $fields .' FROM ' ;
    $sql .= self::TABLE_NAME . ' AS c  '; 

	if($require_section_join == 1){
		$sql .=' JOIN ' . ContentSectionRelation::TABLE_NAME .
            ' AS csr ON c.id=csr.content_id ';
	}
    if ($tableContentCount == 1) { // only if fetching content counts
      $sql .= ' JOIN ' . ContentCount::TABLE_NAME . ' AS cc ON c.id=cc.content_id ';
    }
	if($contnet_meta_table_required == 1){
	  $sql .= ' JOIN ' . ContentMetadata::TABLE_NAME . ' AS cm ON c.id=cm.content_id ';
	}

    if ($is_columnist) {
      $sql .= 'JOIN ' . Author::TABLE_NAME . ' AS a ON FIND_IN_SET(a.id,c.by_line_author_id)!=0 ';
    }
	
   $condition = ' WHERE c.status=1 AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() ) ';
		 
    if ($id_not_in != "") {
      $condition .= " AND c.id not in (" . $id_not_in . ") ";
    }

	if ($id_in != "") {
      $condition .= " AND c.id in (" . $id_in . ") ";
    }
	if ($id_less_than != "") {
      $condition .= " AND c.id < $id_less_than";
    }    
	if($sponsored_id!='')
	  {
		 $condition .= " AND c.sponsored_id=" . $sponsored_id ;
	  }
    if ($contype_id_in != "") {
      $condition .= " AND c.contype_id  in (" . $contype_id_in . ") ";
    }

	if ($erating != "") {
      $condition .= " AND c.erating!=0 ";
    }
	if ("" != $contype_name) {
      $condition .= " AND c.contype_name='" . $contype_name . "' ";
    }
    if (0 != $videotype && $videotype != '') {
      $condition .= " AND c.videotype=" . $videotype . " ";
    }
	if (0 != $videotype_not_in && $videotype_not_in != '') {
      $condition .= " AND c.videotype not in (" . $videotype_not_in . ") ";
    }	
	if ($is_aggregator_data === 1 || $is_aggregator_data === 0) {
      $condition .= " AND c.is_aggregator_data = " . $is_aggregator_data;
    }

	if (isset($args['aggregator_url'])) {
      $condition .= " AND c.aggregator_url != ''" ;
    }

	if (0 != $source_is_til_network && $source_is_til_network != '') {
      $condition .= " AND c.source_is_til_network = '1' ";
    }
	
    if (0 != $con_type_id) {
      $condition .= " AND c.contype_id=" . $con_type_id . " ";
    } else {
      $condition .= " AND c.contype_id!=" . QUOTES . " ";
    }

    if (0 != $section_id) {
		$condition .= " AND csr.section_id=" . $section_id . " ";
    }else if($require_section_join == 1){
		$condition .= " AND csr.is_primary=1 ";
	}

    if ($source_id != 0) {
      $condition .= " AND c.source_id in (" . $source_id . ") ";
    }
	
	if ($country_id != 0) {
      $condition .= " AND c.country_id =" . $country_id ." ";
    }

    if ('' != $source_alias) {
      $condition .= " AND c.source_alias = '" . $source_alias . "' ";
    }

    if (0 != $author_id) {
      $condition .= " AND  FIND_IN_SET ('" . $author_id . "',c.by_line_author_id ) ";
    }
	 if (0 != $is_exclusive) {
      $condition .= " AND c.is_exclusive=" . $is_exclusive . " ";
    }

    if ('' != $author_name_src) {
      $condition .= " AND  FIND_IN_SET ('" . $author_name_src . "',c.by_line_author_id_text ) ";
    }

    if ('' != $reaction) {
      $condition .= " AND  " . $reaction . " > 0 ";
    }
	
	if('' != $dateFilter){
	  //$condition .= " AND publishdate like '".$dateFilter."' ";
            $condition .= " AND DATE(c.publishdate) = '".$dateFilter."' ";
	}
	
	if('' != $dateFilternew){
	  //$condition .= " AND publishdate like '".$dateFilter."' ";
            $condition .= " AND DATE(c.publishdate) LIKE '".$dateFilternew."' ";
	}
       $headline1 = ( isset($args['headline1'])) ? $args['headline1'] : '';
       if('' != $headline1){
	  $condition .= " AND headline1 like '%".$headline1."%' ";
          //$condition .= " AND DATE(c.publishdate) = '".$dateFilter."' ";
	}

    if ($section_id_not_in != "") {
      $condition .= " AND csr.section_id not in (" . $section_id_not_in . ") ";
    }
    
    if ($section_id_in != "") {
      $condition .= " AND csr.section_id  IN (" . $section_id_in . ") ";
    }
	
	if ($source_id_not_in != "") {
      $condition .= " AND c.source_id not in (" . $source_id_not_in . ") ";
    }
	
    if (0 != $section_parent_id) {
      $condition .= " AND csr.section_parentid=" . $section_parent_id . " ";
    }

    if ($is_columnist) {
      $condition .= ' AND a.is_columnist =1 ';
      if (0 != $column_author_id) {
        $condition .= " AND FIND_IN_SET('$column_author_id',c.by_line_author_id)!=0 ";
      }
    }
	$sql = $sql . $condition . $group_by . $order_by_sql . $limit_sql;
	
    //debug($sql, 1);
	if(isset($args['debug']) && $args['debug'] == 2){
		echo $sql;
	}
//echo $sql;        
    if ($setmem) {
      $data = $this->db->executeSql($sql, 1, '300');
    } else {
      $data = $this->db->executeSql($sql);
    }
    //unset($data['data_count']);

    if ('count' == $result_type) {
      $data = (isset($data['data'][0]['cnt'])) ? $data['data'][0]['cnt'] : 0;
    } else {
      if (0 < $data['data_count']) {
	
        for ($i = 0; $i < $data['data_count']; $i++) {
           // $data['data'][$i]['publishdate'] = $data['data'][$i]['updatedate'];
          if(isset($data['data'][$i]['by_line_author_id']))
		{
		  $author_ids = explode(',', $data['data'][$i]['by_line_author_id']);
          $author_name = explode(',', $data['data'][$i]['by_line_author_id_text']);
          foreach ($author_ids as $a_key => $a_val) {
            $data['data'][$i]['by_line_authors'][$a_val] = $author_name[$a_key];
          }
		}

				
			
			if( empty($data['data'][$i]['carousal_headline']) ){
					$data['data'][$i]['carousal_headline'] = str_stop($data['data'][$i]['headline1'],72);
					
					}
			
			
        }
      }
	  
	 
    }
	
	
    return $data;
  }

  public function refreshContentCache($content_id = array()) {
    $r_flag = true;

    if (0 < count($content_id)) {
      foreach ($content_id as $key => $c_id) {
        $article_option = array();
        $article_option['cache'] = true;
        $article_option['refresh_cache'] = true;
        $article = $this->getDetailPageData($c_id, 1, $article_option);
      }
    }

    return $r_flag;
  }

  public function deleteContentCache($content_id = array()) {
    $r_flag = true;

    if (0 < count($content_id)) {
      $object_cache = Cache::cacheInstance();
      foreach ($content_id as $key => $c_id) {
        $cache_key = $this->__createCacheKey($c_id);
        $object_cache->delete($cache_key);

        // remove from NP article cache
        $this->updateNextPrevArticles('d', $c_id);
      }
    }

    return $r_flag;
  }

 public function deletemobileContentCache($content_id = array()) {
    $r_flag = true;

    if (0 < count($content_id)) {
      $object_cache = Cache::cacheInstance('mobile');
      foreach ($content_id as $key => $c_id) {
        $cache_key = $this->__createCacheKey($c_id);
        $object_cache->delete($cache_key);

        // remove from NP article cache
        $this->updateNextPrevArticlesMobile('d', $c_id);
      }
    }

    return $r_flag;
  }

  public function getRelatedArticles($filters) {
      $solr =  Solr::solrInstance();
      $solr_query  .= '(';
      $solr_query  .= ' keywords:('.Solr::escape($filters['keywords']).')^290';
      $solr_query  .= ' OR keywords: [* TO *] ';
	  $solr_query  .= ' AND contype_id:'.$filters['contype_id'];
	  $solr_query  .= ')'; 
	  $solr_query  .= ' AND !id:'.$filters['id'];
	  $solr_query.= '  AND _val_:"ord(publishdate)"^346000';

	 $options =array();	
	 if(isset($filters['start']) && $filters['start']!=''){$start=$filters['start'];}else{$start=0;}
	 if(isset($filters['limit']) && $filters['limit']!=''){$limit=$filters['limit'];}else{$limit=10;}
	 $result = $solr->con->search($solr_query, $start, $limit,$options);	
	 
	 return $result->response->docs;
  }

  /**
   * Gives you most popular content based on type
   * top commented, visited,
   * @params $type
   * @return array
   */
  public function getMostSectionContent($args = array()) {
    $data = array();
    $content_tbl = self::TABLE_NAME;
    $content_counts_tpl = ContentCount::TABLE_NAME;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;  
    


    $interval = ( isset($args['interval']) ) ? intval($args['interval']) : 5;
    $type = (isset($args['type']) && !empty($args['type'])) ? $args['type'] : 'commented';
    $type = $this->db->db_escape($type);
    $start = ( isset($args['start']) ) ? intval($args['start']) : ''; 
    $limit = ( isset($args['limit']) ) ? intval($args['limit']) : 5;    
    $cache = (isset($args['cache']) && !empty($args['cache'])) ? $args['cache'] : false;
	$cache_expiry = (isset($args['cache_expiry']) && !empty($args['cache_expiry'])) ? $args['cache_expiry'] : 900;	
    $section_id = (isset($args['section_id']) && !empty($args['section_id'])) ? $args['section_id'] : NULL;
    $section_id = $this->db->db_escape($section_id);

    $section_parent_id = (isset($args['section_parent_id']) && !empty($args['section_parent_id'])) ? $args['section_parent_id'] : NULL;
    $section_parent_id = $this->db->db_escape($section_parent_id);
    $section_id_in = ( isset($args['section_id_in'])) ? $args['section_id_in'] : '';
    $section_parent_id_in = ( isset($args['section_parent_id_in'])) ? $args['section_parent_id_in'] : '';

    $thumbnail_flag = (isset($args['thumbnail_flag']) && !empty($args['thumbnail_flag'])) ? $args['thumbnail_flag'] : false;

    $contype_id = (isset($args['contype_id']) && !empty($args['contype_id'])) ? $args['contype_id'] : NULL;
    $source_id = ( isset($args['source_id']) ) ? $args['source_id'] : 0;

    if (NULL != $contype_id && !is_array($contype_id)) {
      $contype_id = $this->db->db_escape($contype_id);
    }
  	$id_not_in = (isset($args['id_not_in']) && !empty($args['id_not_in'])) ? $args['id_not_in'] : '';
	
    if (isset($args['videoObj'])) {
      $content_meta_tbl = ContentMetadata::TABLE_NAME;
      $sql = "SELECT cc.{$type},cm.object_code,c.contype_id, c.video_width, c.video_height,csr.section_id,csr.section_name,csr.section_parentid,csr.section_parentname, c.id, c.headline1,c.source_id, c.headline2, c.author_id, c.author_name, c.summary, c.thumbnail, c.thumbnail_alt, c.screenshot_alt, c.screenshot, c.erating , c.source_url, c.is_exclusive, c.source_alias, c.publishdate, c.updatedate, c.contype_id, c.guid, c.is_aggregator_data, c.aggregator_url, c.videotype,c.source_is_til_network as is_til_network, c.news_letter_headline FROM $content_counts_tpl AS cc JOIN $content_tbl AS c ON cc.content_id=c.id JOIN $content_section_tbl AS csr ON csr.content_id=c.id JOIN  $content_meta_tbl AS cm on c.id = cm.content_id ";
    } else {
      $sql = "SELECT cc.{$type},csr.section_id,csr.section_name,csr.section_parentid,csr.section_parentname, c.id, c.headline1,c.source_id, c.headline2, c.author_id, c.author_name, c.summary, c.thumbnail, c.thumbnail_alt,c.contype_id, c.screenshot_alt, c.screenshot, c.erating , c.source_url, c.is_exclusive, c.source_alias, c.publishdate, c.updatedate, c.contype_id, c.guid, c.is_aggregator_data, c.aggregator_url, c.videotype,c.source_is_til_network as is_til_network,c.news_letter_headline FROM $content_counts_tpl AS cc JOIN $content_tbl AS c ON cc.content_id=c.id JOIN $content_section_tbl AS csr ON csr.content_id=c.id ";
    }

    $where = " WHERE c.status=1 AND c.publishdate between DATE_SUB(CURDATE(), INTERVAL {$interval} DAY) AND now() AND csr.is_primary = 1 AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() ) and c.is_aggregator_data = 0";

    if (NULL != $section_parent_id) {
      $where .= " AND csr.section_parentid='$section_parent_id'";
    }

	if ($id_not_in != '') {
      $where .= " AND c.id not in (" . $id_not_in . ") ";
    }
	
    if (NULL != $section_id) {
      $where .= " AND csr.section_id='$section_id'";
    }
    
   if ($source_id != 0) {
      $where  .= " AND c.source_id in (" . $source_id . ") ";
    }
    if ($section_id_in != "") {
      $where .= " AND csr.section_id  IN (" . $section_id_in . ") ";
    }   
     
   if ($section_parent_id_in != "") {
      $where .= " AND csr.section_parentid  IN (" . $section_parent_id_in . ") ";
    }


    if (NULL != $contype_id) {
      if (is_array($contype_id)) {
        $contype_id = implode(',', $contype_id);
        $where .= " AND cc.contype_id IN ($contype_id) ";
      } else {
        $where .= " AND cc.contype_id='$contype_id' ";
      }
    }

    if ($thumbnail_flag === true) {
      $where .= " AND c.thumbnail!='' ";
    }

    $order_by = " ORDER BY cc.{$type} DESC";
   if( $start != '' ){
      $limit = " LIMIT $start, $limit";
   }else{
      $limit = " LIMIT $limit";
   } 

    $sql = $sql . $where . $order_by . $limit;
    $data = $this->db->executeSql($sql, $cache, $cache_expiry);    
    if ( 0 < $data['data_count'] )
    {
        for ( $i = 0; $i < $data['data_count']; $i++ )
        {
            $data['data'][$i]['publishdate'] = $data['data'][$i]['updatedate'];
        }
    }

    return $data;
  }

  public function getAlsoContent($args) {
    $data = array();
    $content_tbl = self::TABLE_NAME;
    $content_counts_tpl = ContentCount::TABLE_NAME;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;
    $cache = (isset($args['cache']) && !empty($args['cache'])) ? $args['cache'] : false;
    $limit = ( isset($args['limit']) ) ? intval($args['limit']) : 5;
    $id_not_in = (isset($args['id_not_in']) && !empty($args['id_not_in'])) ? $args['id_not_in'] : '';
    $source_id = (isset($args['source_id']) && !empty($args['source_id'])) ? $args['source_id'] : '';
    $is_aggregator_data = (isset($args['is_aggregator_data']) && !empty($args['is_aggregator_data'])) ? $args['is_aggregator_data'] : '';
    if (isset($args['is_aggregator_data'])) {
      $is_aggregatorval = $args['is_aggregator_data'];
    }
    $also_section_id = (isset($args['also_section_id']) && !empty($args['also_section_id'])) ? $args['also_section_id'] : 0;
    $also_section_id = $this->db->db_escape($also_section_id);
    
    $also_section_parent_id = (isset($args['also_section_parent_id']) && !empty($args['also_section_parent_id'])) ? $args['also_section_parent_id'] : 0;
    $also_section_parent_id = $this->db->db_escape($also_section_parent_id);

    $fields = 'c.guid,c.id,c.is_aggregator_data,c.aggregator_url,c.by_line_columnist,c.by_line_author_id_text,c.by_line_author_id,c.headline1,c.thumbnail,c.thumbnail_alt,c.source_id, c.is_exclusive,
      c.source_url,c.source_alias,c.contype_id,c.contype_name,c.publishdate ,c.source_is_til_network as is_til_network';
    $sql = "SELECT $fields FROM $content_tbl AS c
            JOIN $content_section_tbl AS csr on c.id = csr.content_id";

    $where = ' WHERE c.status=1 AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() ) AND c.contype_id!=' . QUOTES . ' AND csr.is_primary = 1 ';

    if ($id_not_in != '') {
      $where .= " AND c.id not in (" . $id_not_in . ") ";
    }

    if ($source_id != '') {
      $where .= " AND c.source_id = " . $source_id;
    }
    if (isset($is_aggregatorval)) {
      $where .= " AND c.is_aggregator_data = " . $is_aggregatorval;
    }

    if (0 != $also_section_id) {
      $where .= " AND csr.section_id='$also_section_id' ";
    }
    
    if (0 != $also_section_parent_id) {
    	$where .= " AND csr.section_parentid='$also_section_parent_id' ";
    }

    $order_by = " ORDER BY c.id DESC";
    $limit = " LIMIT $limit";

    $sql = $sql . $where . $order_by . $limit;

    $data = $this->db->executeSql($sql, 0);

    return $data;
  }

  public function getNextPrevArticles($section_id, $article_id) {
    $object_cache = Cache::cacheInstance();
    $ids = $object_cache->get('section_np_list');

    if (empty($ids)) {
      $ids = array();
    }

    if (empty($ids[$section_id])) { // set cache
	
      $ids = $this->setNextPrevArticles($section_id);
    }
    
    $index = array_search($article_id, $ids[$section_id]);

    if ($index !== false) {
      $arr = array();
      $next = $prev = '';
      if (isset($ids[$section_id][$index + 1])) {
        $next = $ids[$section_id][$index + 1];
        $arr[] = $next;
      }
      if (isset($ids[$section_id][$index - 1])) {
        $prev = $ids[$section_id][$index - 1];
        $arr[] = $prev;
      }

      $in = implode(',', $arr);

      $sql = 'select content.id, headline1, guid, is_aggregator_data, aggregator_url, contype_id, source_id, source_is_til_network as is_til_network  from content where content.id in (' . $in . ') AND publishdate <= NOW() AND (expirydate IS NULL OR expirydate > NOW() )';
      $recs = $this->db->executeSql($sql, 0, 0);

      unset($recs['data_count']);
      unset($recs['fromMemcache']);

      $arrR = array();
      foreach ($recs['data'] as $r) {
        if ($r['id'] == $next) {
          $arrR['next'] = $r;
        } else if ($r['id'] == $prev) {
          $arrR['prev'] = $r;
        }
      }

      return $arrR;
    } else {
      return array();
    }
  }

  public function setNextPrevArticles($section_id) {
    $object_cache = Cache::cacheInstance();
    //echo 'here--';
    $sql = "SELECT DISTINCT c.id ";
    $sql .= "FROM content c ";
    $sql .= "JOIN content_section_relation csr ON csr.content_id = c.id ";
    $sql .= "WHERE csr.section_parentid = $section_id AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() ) AND c.status = 1 AND c.is_aggregator_data = 0 AND c.contype_id in (" . NEWS . ", " . COLUMN .", ".MATCHREPORT. ") ";
    $sql .= "ORDER BY c.id;";
   // echo $sql;
    $ids = $this->db->executeSql($sql, 0, 0);

    unset($ids['data_count']);
    unset($ids['fromMemcache']);
    $tmp = array();
    foreach ($ids['data'] as $id) {
      $tmp[] = $id['id'];
    }

    $object_cache = Cache::cacheInstance();
    $cids = $object_cache->get('section_np_list');
    if (empty($cids)) {
      $cids = array();
    }

    $cids[$section_id] = $tmp;
    $object_cache->set('section_np_list', $cids, 0);

    return $cids;
  }

  public function updateNextPrevArticles($flag, $article_id, $section_id='') {
    $object_cache = Cache::cacheInstance();
    $ids = $object_cache->get('section_np_list');
    if (empty($ids)) {
      $ids = array();
    }

    if ($flag == 'a') {
      if (empty($ids[$section_id])) { // set cache for all
        $ids = $this->setNextPrevArticles($section_id);
      } else {
        if (array_search($article_id, $ids[$section_id]) !== false) {
          return true;
        } else {
          $ids[$section_id][] = $article_id;
          sort($ids[$section_id]);
          $object_cache->set('section_np_list', $ids, 0);
        }
      }
    } else if ($flag == 'd') {
      $tmp = $ids;
      foreach ($ids as $sid => $sart) {
        $idx = array_search($article_id, $sart);
        if ($idx !== false) {
          unset($tmp[$sid][$idx]);
          sort($tmp[$sid]);
        }
      }

      $object_cache->set('section_np_list', $tmp, 0);
    }
  }
   public function updateNextPrevArticlesMobile($flag, $article_id, $section_id='') {
    $object_cache = Cache::cacheInstance('mobile');
    $ids = $object_cache->get('section_np_list');
    if (empty($ids)) {
      $ids = array();
    }

    if ($flag == 'a') {
      if (empty($ids[$section_id])) { // set cache for all
        $ids = $this->setNextPrevArticles($section_id);
      } else {
        if (array_search($article_id, $ids[$section_id]) !== false) {
          return true;
        } else {
          $ids[$section_id][] = $article_id;
          sort($ids[$section_id]);
          $object_cache->set('section_np_list', $ids, 0);
        }
      }
    } else if ($flag == 'd') {
      $tmp = $ids;
      foreach ($ids as $sid => $sart) {
        $idx = array_search($article_id, $sart);
        if ($idx !== false) {
          unset($tmp[$sid][$idx]);
          sort($tmp[$sid]);
        }
      }

      $object_cache->set('section_np_list', $tmp, 0);
    }
  }

  public function getRecentContent($args = array()) {
    $data = array();
    $content_tbl = self::TABLE_NAME;
    $content_counts_tpl = ContentCount::TABLE_NAME;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;

    $cache = (isset($args['cache']) && !empty($args['cache'])) ? $args['cache'] : false;
    $limit = ( isset($args['limit']) ) ? intval($args['limit']) : 22;

    $fields = 'c.guid,c.id,c.is_aggregator_data,c.aggregator_url,c.by_line_columnist,c.headline1,c.thumbnail,c.thumbnail_alt,c.source_id, c.is_exclusive,
      c.source_url,c.source_alias,c.contype_id,c.contype_name,c.publishdate ,c.source_is_til_network as is_til_network';
    $sql = "SELECT $fields FROM $content_tbl c";

    $where = ' WHERE c.contype_id!="' . QUOTES . '" AND c.status=1  AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() )';

    $order_by = " ORDER BY c.id DESC";
    $limit = " LIMIT $limit";

    $sql = $sql . $where . $order_by . $limit;

    $data = $this->db->executeSql($sql, 0);

    return $data;
  }

  /**
   * @Desc: Gives you in case you missed it,
   * @params: $type
   * @return: array
   */
  public function getInCaseMissedContent($args = array()) {

    $data = array();
    $content_tbl = self::TABLE_NAME;
    $content_counts_tpl = ContentCount::TABLE_NAME;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;

    $section_parent_id = (isset($args['section_parent_id']) && !empty($args['section_parent_id'])) ? $args['section_parent_id'] : NULL;
    $section_parent_id = $this->db->db_escape($section_parent_id);
    $contype_id = (isset($args['contype_id']) && !empty($args['contype_id'])) ? $args['contype_id'] : NULL;
    $videotype = ( isset($args['videotype']) ) ? intval($args['videotype']) : 0;

    $sql = "SELECT
              (cc.visits+cc.commented+cc.shared+cc.heart+cc.fail+cc.wow+cc.omg+cc.geeky+cc.lol-cc.broken_heart) as popularity,
              csr.section_id,
              csr.section_name,
              csr.section_parentid,
              c.id,
              c.headline1,
              c.source_id,              
              c.author_id,
              c.author_name,
              c.thumbnail,
              c.thumbnail_alt,
              c.source_url,
              c.is_exclusive,
              c.source_alias,
              c.publishdate,
              c.updatedate,
              c.contype_id,
              c.guid,
              c.is_aggregator_data,
              c.aggregator_url,
              c.videotype,
			  c.source_is_til_network as is_til_network
            FROM $content_counts_tpl AS cc
            JOIN $content_tbl AS c ON cc.content_id=c.id
            JOIN $content_section_tbl AS csr ON csr.content_id=c.id";

    $sql .= ' WHERE c.status=1 AND c.publishdate <= NOW() AND c.publishdate between DATE_SUB(NOW() , INTERVAL 3 DAY) AND DATE_SUB(NOW() , INTERVAL 1 DAY)  AND csr.is_primary = 1 AND (c.expirydate IS NULL OR c.expirydate > NOW() ) and c.is_aggregator_data = 0';

    if (NULL != $section_parent_id) {
      $sql .= " AND csr.section_parentid='$section_parent_id'";
    }

    if (NULL != $contype_id) {
      if (is_array($contype_id)) {
        $contype_id = implode(',', $contype_id);
        $sql .= " AND cc.contype_id IN ($contype_id) ";
      } else {
        $sql .= " AND cc.contype_id='$contype_id' ";
      }
    }

    if (NULL != $videotype) {
      $sql .= " AND c.videotype='$videotype'";
    }

    $sql .= ' ORDER BY popularity desc';
    $sql .= ' LIMIT 6';

    $data = $this->db->executeSql($sql, true, 43200);

    return $data;
  }

  public function getMailerContentList($args = array(), $setmem=false) { 
    
	//print_r($args); die;
    $setmem = false;
	$result_type = isset($args['result_type']) ? $args['result_type'] : 'data'; 
    $require_section_join  = ( isset($args['require_section_join']) ) ? intval($args['require_section_join']) : 1;
    $con_type_id = ( isset($args['contype_id']) ) ? intval($args['contype_id']) : 0;

    $section_id = ( isset($args['section_id']) ) ? intval($args['section_id']) : 0;
    $contype_id_in = ( isset($args['contype_id_in'])) ? $args['contype_id_in'] : '';
	$contype_id_not_in = ( isset($args['contype_id_not_in'])) ? $args['contype_id_not_in'] : '';
    $id_not_in = ( isset($args['id_not_in'])) ? $args['id_not_in'] : '';
    $videotype = ( isset($args['videotype']) ) ? intval($args['videotype']) : 0; 
    $videotype_not = ( isset($args['videotype_not']) ) ? intval($args['videotype_not']) : 0; 
    
    $section_parent_id = ( isset($args['section_parent_id']) ) ? intval($args['section_parent_id']) : 0;
	$dateRange = isset($args['dateRange']) ? $args['dateRange'] : '';
    $limit = ( isset($args['limit']) ) ? intval($args['limit']) : 10;
    $start = ( isset($args['start']) ) ? intval($args['start']) : 0;
	$groupby = ( isset($args['groupby']) ) ? $args['groupby'] : '';
    $tableContentCount = ( isset($args['tableContentCount']) ) ? intval($args['tableContentCount']) : 0;
    $fields = isset($args['fields']) ? implode(',', $args['fields']) : 'c.*,cc.visits,csr.section_name,csr.section_id';
	
	$group_by = '';
    $limit_sql = " LIMIT $start, $limit";
	if($groupby != ""){
		$group_by = ' group by ' . $groupby;
	}
    $order_by_sql = NULL;
    $order_by = isset($args['order_by']) ? $args['order_by'] : array('id' => 'desc');
    $cnt_order_by = count($order_by);        
	if ('count' != $result_type && 0 < $cnt_order_by) {
      $order_by_sql = ' ORDER BY ';  
      $tmp_cnt = 1;      
      foreach ($order_by as $o_key => $order_by) {          
        if ((strpos($o_key, '.') !== false))
          $order_by_sql .= " {$o_key} $order_by";
        else
          $order_by_sql .= " c.{$o_key} $order_by";
          
        if ( $tmp_cnt != $cnt_order_by ) {
            $order_by_sql .= ',';
        }
        $tmp_cnt++;
      }
      //$order_by_sql = rtrim($order_by_sql, ',');
    }

    if ('count' == $result_type) {
       $fields = ' count(distinct c.id) as cnt ';
      $limit_sql = NULL;
	  $group_by = NULL;
    }else{
		$fields .= ', c.contype_id'. ', c.source_is_til_network as is_til_network,c.publishdate,c.updatedate  ';
	}


    $sql = 'SELECT ' . $fields .' FROM ' ;
    $sql .= self::TABLE_NAME . ' AS c  '; 

	if($require_section_join == 1){
		$sql .=' JOIN ' . ContentSectionRelation::TABLE_NAME .
            ' AS csr ON c.id=csr.content_id ';
	}
   
      $sql .= ' JOIN ' . ContentCount::TABLE_NAME . ' AS cc ON c.id=cc.content_id ';
   
	if($contnet_meta_table_required == 1){
	  $sql .= ' JOIN ' . ContentMetadata::TABLE_NAME . ' AS cm ON c.id=cm.content_id ';
	}

    if ($is_columnist) {
      $sql .= 'JOIN ' . Author::TABLE_NAME . ' AS a ON FIND_IN_SET(a.id,c.by_line_author_id)!=0 ';
    }

    $condition = ' WHERE c.status=1 AND c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() )';

    if ($id_not_in != "") {
      $condition .= " AND c.id not in (" . $id_not_in . ") ";
    }
    if ($contype_id_in != "") {
      $condition .= " AND c.contype_id  in (" . $contype_id_in . ") ";
    } 	
     if ($contype_id_not_in != "") {
      $condition .= " AND c.contype_id not in (" . $contype_id_not_in . ") ";
    } 

    if (0 != $videotype_not && $videotype_not != '') {
      $condition .= " AND c.videotype !=" . $videotype_not . " ";
    }
    if (0 != $videotype && $videotype != '') {
      $condition .= " AND c.videotype=" . $videotype . " ";
    }    
    
    if (0 != $con_type_id) {
      $condition .= " AND c.contype_id=" . $con_type_id . " ";
    } 

    if (0 != $section_id) {
		$condition .= " AND csr.section_id=" . $section_id . " ";
    }else if($require_section_join == 1){
		$condition .= " AND csr.is_primary=1 ";
	}
	if('' != $dateRange){
	  $date = date('Y-m-d H:i:s');
	  $condition .= " AND c.publishdate between '$dateRange' AND '$date' ";
	}   
    if (0 != $section_parent_id) {
      $condition .= " AND csr.section_parentid=" . $section_parent_id . " ";
    }
    
    $sql = $sql . $condition . $group_by . $order_by_sql . $limit_sql;
    
	if(isset($args['debug']) && $args['debug'] == 2){
		echo $sql;
	}
	
    if ($setmem) {
      $data = $this->db->executeSql($sql, 0, '7200');
    } else {
      $data = $this->db->executeSql($sql);
    }
    //unset($data['data_count']);

    if ('count' == $result_type) {
      $data = (isset($data['data'][0]['cnt'])) ? $data['data'][0]['cnt'] : 0;
    } else {
      if (0 < $data['data_count']) {
        for ($i = 0; $i < $data['data_count']; $i++) {
           // $data['data'][$i]['publishdate'] = $data['data'][$i]['updatedate'];
          if(isset($data['data'][$i]['by_line_author_id']))
		{
		  $author_ids = explode(',', $data['data'][$i]['by_line_author_id']);
          $author_name = explode(',', $data['data'][$i]['by_line_author_id_text']);
          foreach ($author_ids as $a_key => $a_val) {
            $data['data'][$i]['by_line_authors'][$a_val] = $author_name[$a_key];
          }
		}
        }
      }
    }
    return $data;
  }
  public function get_prev_next_ids($source_ids = 0, $section_id =0, $id = 0) {
    if($source_ids == 0 || $section_id == 0 || $id ==0){  
      return array('prev_id'=>null,'next_id'=>null ); 
    }   
    $sql  =  " SELECT DISTINCT c.id "; 
    $sql .=  " FROM content c ";
    $sql .=  " JOIN content_section_relation csr ON csr.content_id = c.id ";
    $sql .=  " WHERE c.source_id IN($source_ids) AND csr.section_parentid = $section_id";
    $sql .=  " AND c.publishdate <= NOW()";
    $sql .=  " AND (c.expirydate IS NULL OR c.expirydate > NOW() )"; 
    $sql .=  " AND c.status = 1";
    $sql .=  " AND c.is_aggregator_data = 0";
  //  $sql .=  " AND c.contype_id in (" . NEWS . ", " . COLUMN .", ".MATCHREPORT. ") ";
    $sql .=   " ORDER BY c.id;"; 
    $data = $this->db->executeSql($sql,1, 3600);
    //$data = $this->db->executeSql($sql); 
    $idArr = array_map(
            create_function('$person', 'return $person["id"];'), $data['data']
    );  
    $key = array_search($id, $idArr); 
    if($key === false || $key === null){
      return array('prev_id'=>null,'next_id'=>null );
    } 
    $prev_id = $key > 0 ? $idArr[$key-1]: null; 
    $next_id = (count($idArr) -1) > $key ? $idArr[$key+1]: null; 
    return array('prev_id'=>$prev_id,'next_id'=>$next_id );
  }

  public function get_prev_next_ids_mobile($source_ids = 0, $section_id =0, $id = 0) {
    if($source_ids == 0 || $section_id == 0 || $id ==0){  
      return array('prev_id'=>null,'next_id'=>null ); 
    }   
    $sql  =  " SELECT DISTINCT c.id "; 
    $sql .=  " FROM content c ";
    $sql .=  " JOIN content_section_relation csr ON csr.content_id = c.id ";

    $sql .=  " WHERE c.source_id IN($source_ids) AND csr.section_id = $section_id";
    $sql .=  " AND c.publishdate <= NOW()";
    $sql .=  " AND (c.expirydate IS NULL OR c.expirydate > NOW() )"; 
    $sql .=  " AND c.status = 1";
    $sql .=  " AND c.is_aggregator_data = 0";
  //  $sql .=  " AND c.contype_id in (" . NEWS . ", " . COLUMN .", ".MATCHREPORT. ") ";
    $sql .=   " ORDER BY c.id;";  
   // $data = $this->db->executeSql($sql,1, 7200);
    $data = $this->db->executeSql($sql); 
    $idArr = array_map(
            create_function('$person', 'return $person["id"];'), $data['data']
    );  
    $key = array_search($id, $idArr); 
    if($key === false || $key === null){
      return array('prev_id'=>null,'next_id'=>null );
    } 
    $prev_id = $key > 0 ? $idArr[$key-1]: null; 
    $next_id = (count($idArr) -1) > $key ? $idArr[$key+1]: null; 
    return array('prev_id'=>$prev_id,'next_id'=>$next_id );
  }

 //function to get liveuppdates
 public function getLiveupdate($content_id) {
    $return = array();
    $condition['content_id'] = $content_id;
    $condition['status'] = 1;
    $return = $this->db->getDataFromTable($condition, "live_updates", "id,title,description,fbtitle,fbdescription,facebookthumbnail,updateddate", 'updateddate DESC', '');
	
     return $return;
  }
  
   public function getQuiz($content_id) {
    
	$newArr = array();   
    
	 $sql="select cqq.title as question,cqq.input_type,cqo.title as options,cqq.thumbnail as question_image,cqo.thumbnail as option_image, cqo.ques_id ,cqq.display_type,cqo.score from content_quiz_questions as cqq LEFT JOIN content_quiz_options as cqo ON cqq.id=cqo.ques_id where cqq.quiz_id=".intval($content_id) ." and cqq.status=1";
	

	 $data = $this->db->executeSql($sql);
		if(!empty($data['data']))
		{
			foreach($data['data'] as $k=>$v )
			{
				$newArr[$v['ques_id']][]=$v;
			
			}
		}
	
     return $newArr;
  }
  
  
    public function getAnswers($content_id) {
     $return = array();
    $condition['quiz_id'] = $content_id;
    $condition['status'] = 1;
    $return = $this->db->getDataFromTable($condition, "content_quiz_answers", "id,title,description,fbtitle,fbdescription,thumbnail", 'id ASC', '');
	
     return $return;
  }

public static function getContentlink($id){
	$db = Database::Instance();
	$data = $db->getDataFromTable(array('id'=>$id), self::TABLE_NAME, "guid,headline1");
	$data = $data['data'][0];
	if(empty($data)){
		return;
	}
	return '<a href="'.$data['guid'].'" target="_blank">'.$data['headline1'].'</a>';
}


 public static function getSearchContents($filters, $start = 0, $limit=12, $count= false) {
	 global $log_config;
      $solr =  Solr::solrInstance();
	   //Logger::configure($log_config);
	   //$logger = Logger::getLogger('Solr');	 
	   $string = trim($filters['q']);
	   if($string){
		   $solr_query= ' (headline1:("'.$string.'")^33453200  OR summary:("'.$string.'")^33443200 OR  author_name:("'.$string.'") OR section_name:("'.$string.'") OR headline2:("'.$string.'") OR byline:("'.$string.'") OR source_name:("'.$string.'") OR keywords:("'.$string.'") OR metakeywords:("'.$string.'") OR followkeywords:("'.$string.'"))';			
		   $solr_query.= ' AND is_aggregator_data :0  AND _val_:"ord(publishdate)"^346000';	
		   //$logger->info('solr_query:'.$solr_query);
	   }else{
		   $solr_query.= '_val_:"ord(publishdate)"^346000';	
	   }
	   $options =array();		 
	  
	   if( $count ){  
			 $result = $solr->con->search($solr_query);	 
			 return  $result->response->numFound;
		} else { //'section_name,thumbnail,guid,headline1,id'
			  $result = $solr->con->search($solr_query, $start, $limit,array('fl' => '*')); 
			// p($result->response);
			  return $result->response;
		}
  }
}