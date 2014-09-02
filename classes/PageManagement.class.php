<?php

class PageManagement
{

  public $db = null;
  const TABLE_NAME = 'pagemanagement';
  private $posts = array();

  public function __construct()
  {
    $this->db = Database::Instance();
  }

  public function getData($pageID=0, $blockID=0, $limit='', $storeInMemcached = false, $memcacheExpireTime=0,$contypeId =0,$param=array())
  {
	global $pageMappingArr;
    $fields = !empty($param['fields']) ? $param['fields'] : 'c.by_line_author_id,c.source_id,c.by_line_author_id_text,c.contype_name,c.by_line_columnist,pm.thumbnail as boximage,pm.thumbnail_alt as boximage_alt, pm.priority,c.id, c.headline1, c.headline2, c.author_id, c.author_name, c.summary, c.thumbnail, c.thumbnail_alt, c.is_exclusive, c.screenshot_alt, c.screenshot, c.erating , c.updatedate,c.source_url, c.source_alias, c.publishdate, c.contype_id, c.guid, c.is_aggregator_data, c.aggregator_url, c.videotype, csr.section_name, csr.section_id,csr.section_parentid,c.label_name, c.list_count, c.source_is_til_network as is_til_network, c.carousal_headline, c.carousal_summary, c.updatedate,c.card_id';

    $sql = ' select '.$fields;
    $sql = $sql. ' from ' . self::TABLE_NAME . ' as pm join ' . Content::TABLE_NAME . ' as c on pm.content_id = c.id join '.ContentSectionRelation::TABLE_NAME.' as csr on c.id=csr.content_id ';
    $sql .= ' where c.status=1 and pm.status =1 AND csr.is_primary=1  AND c.publishdate <= NOW() AND pm.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() )';
	
	if($pageID != 0){
		$sql .= ' and pm.page_id = ' . $pageID .' ';
	}
	
	if($blockID != 0){
		$sql .= ' and pm.block_id = ' . $blockID . ' ';
	} 
	if($contypeId != 0){
	 	$sql  .= ' AND c.contype_id='.$contypeId;
	}
            $sql  .=  ' order by pm.priority asc ';
            if ( $limit != '' )
            {
              $sql .= 'limit ' . $limit;
            }
        
    
   
	if($storeInMemcached)
	  {
		$this->posts = $this->db->executeSql($sql,1,$memcacheExpireTime);
	  }
	  else
	  {
		  $this->posts = $this->db->executeSql($sql);
	  }

    if ( $this->posts['data_count'] )
    {
      for ( $o = 0; $o < $this->posts['data_count']; $o++ )
      {
          //$this->posts['data'][$o]['publishdate'] = $this->posts['data'][$o]['updatedate'];
        if ( isset($this->posts['data'][$o]['by_line_author_id']) && 0 != $this->posts['data'][$o]['by_line_author_id'] && !empty($this->posts['data'][$o]['by_line_author_id_text']) )
        {
          $author_ids = explode(',', $this->posts['data'][$o]['by_line_author_id']);
          $author_name = explode(',', $this->posts['data'][$o]['by_line_author_id_text']);
          foreach ( $author_ids as $a_key => $a_val )
          {
            $this->posts['data'][$o]['by_line_authors'][$a_val] = $author_name[$a_key];
          }
        }
		
		
			
			if( empty($this->posts['data'][$o]['carousal_headline']) ){
					$this->posts['data'][$o]['carousal_headline'] = str_stop($this->posts['data'][$o]['headline1'],72);
					
					}
			
      }
    }

    return $this->posts;
  }

  
    public function getSourceId($pageID=0, $blockID=0, $limit='', $storeInMemcached = false, $memcacheExpireTime=0,$contypeId =0 )
  {
	//global $pageMappingArr;
    $sql = ' select c.id, c.alias , c.source, c.color_code ';
    $sql = $sql. ' from ' . self::TABLE_NAME . ' as pm join source as c on pm.content_id = c.id  ';
    $sql .= ' where c.status=1 and pm.status =1     ';
	
	if($pageID != 0){
		$sql .= ' and pm.page_id = ' . $pageID .' ';
	}
	
	if($blockID != 0){
		$sql .= ' and pm.block_id = ' . $blockID . ' ';
	} 
	

    $sql  .=  ' order by pm.priority asc ';
 	
    if ( $limit != '' )
    {
      $sql .= 'limit ' . $limit;
    }
   
   // echo $sql;
	
    $this->posts = $this->db->executeSql($sql);
    //print_r($this->posts);
    return $this->posts;
  }
  
  public function getDataOpinionColumnists($pageID, $blockID, $limit='', $author_id = 0, $storeInMemcached = false, $memcacheExpireTime=0)
  {
    global $pageMappingArr;
    $content_section_tbl = ContentSectionRelation::TABLE_NAME;
    $sql = ' SELECT c.by_line_author_id,c.contype_name,c.summary,c.by_line_columnist,c.by_line_author_id_text,c.tag_quote,csr.section_id,csr.section_parentname,csr.section_parentid, pm.thumbnail as boximage,pm.thumbnail_alt as boximage_alt, pm.priority,c.id, c.headline1, c.headline2, c.author_id, c.author_name, c.summary, c.thumbnail, c.thumbnail_alt, c.screenshot_alt, c.screenshot, c.erating , c.source_url, c.source_alias, c.publishdate, c.contype_id, c.guid, c.is_aggregator_data, c.is_exclusive, c.aggregator_url, c.videotype,c.by_line_author_id,c.by_line_author_id_text, c.source_id , c.source_is_til_network as is_til_network '
              . ' FROM ' . self::TABLE_NAME . ' as pm join ' . Content::TABLE_NAME . ' as c on pm.content_id = c.id'
              . " join $content_section_tbl AS csr ON csr.content_id=c.id "
              . ' WHERE c.status=1 and c.publishdate <= NOW() AND (c.expirydate IS NULL OR c.expirydate > NOW() ) and pm.status =1 and pm.block_id = ' . $blockID . ' and pm.page_id = ' . $pageID . ''
              . ' AND csr.is_primary = 1'
              . ' ORDER BY pm.priority asc ';

	if ( $limit != '' )
    {
      $sql .= 'limit ' . $limit;
    }

    $this->posts = $this->db->executeSql($sql);

    if ( $pageID == $pageMappingArr['home']['page'] && $blockID == $pageMappingArr['home']['opinion_columnists'] &&
            isset($this->posts['data_count']) && 0 < $this->posts['data_count'] )
    {
      $object_content_count = new ContentCount;
      $object_author = new Author;
      $temp_author = array();
      for ( $o = 0; $o < $this->posts['data_count']; $o++ )
      {
        if ( COLUMN == $this->posts['data'][$o]['contype_id'] )
        {
          $this->posts['data'][$o]['quote'] = NULL;
          if ( 0 != $this->posts['data'][$o]['tag_quote'] )
          {
            $object_content = new Content;
            $quote = $object_content->getQuote($this->posts['data'][$o]['tag_quote']);
            if ( isset($quote['headline1']) )
            {
              $this->posts['data'][$o]['quote'] = $quote['headline1'];
            }
          }
        }
        elseif ( QUOTES == $this->posts['data'][$o]['contype_id'] )
        {
          if ( !isset($temp_author[$this->posts['data'][$o]['author_id']]) )
          {
            $author = $object_author->getAuthor($this->posts['data'][$o]['author_id'], 'id,name,email,designation,thumbnail,biodata,facebook,twitter,websiteurl', false);
            if ( is_array($author) )
            {
              $this->posts['data'][$o]['author_detail'] = $author;
              $temp_author[$this->posts['data'][$o]['author_id']] = $author;
            }
          }
          else
          {
            $this->posts['data'][$o]['author_detail'] = $temp_author[$this->posts['data'][$o]['author_id']];
          }
        }

        if ( isset($this->posts['data'][$o]['by_line_author_id']) && 0 != $this->posts['data'][$o]['by_line_author_id'] && !empty($this->posts['data'][$o]['by_line_author_id_text']) )
        {
          $author_ids = explode(',', $this->posts['data'][$o]['by_line_author_id']);
          $author_name = explode(',', $this->posts['data'][$o]['by_line_author_id_text']);
          foreach ( $author_ids as $a_key => $a_val )
          {
            $this->posts['data'][$o]['by_line_authors'][$a_val] = '<a class="storyLink authName" href="' . get_columnists_url($a_val,$author_name[$a_key]) . '" > ' . $author_name[$a_key] . '</a>';
            $this->posts['data'][$o]['by_line_authors_data'][$a_val] = $author_name[$a_key];
          }
        }
      }
    }

    return $this->posts;
  }

}