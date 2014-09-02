<?php
/**
 * To maintain the content stats like visits, shared, commented counts
 *
 */
class ContentCount
{

  private $__db;
  private $__db_read = NULL;
  private $__object_cache = NULL;
  private static $__fields = array('content_id', 'visits', 'shared', 'commented');
  const TABLE_NAME = 'content_counts';
  
  public function __construct()
  {
    $this->__db = Database::Instance();
	$this->__object_cache = Cache::cacheInstance();
  }
 
  public function getCounts($id, $fields = 'visits,shared,commented')
  {
    $return = array();
    $condition['content_id'] = $id;
    $cache_key = self::TABLE_NAME . '_' . $id;
	$return = $this->__object_cache->get($cache_key);
	$cache_key_comment_count = 'comments_' . $id;
	$return_count = $this->__object_cache->get($cache_key_comment_count);
	if( $return == null ){
	    $return = $this->__db->getDataFromTable($condition, self::TABLE_NAME, $fields, '', '', 0, true);
	    if ( $return['data_count'] )
		{
			$return = $return['data'][0];
			$this->__object_cache->set($cache_key, $return, 0);
		}
	}
	if( isset($return_count['total_comments']) ){
	$return['commented']=$return_count['total_comments'];
	}
    return $return;
  }
  /**
   * To get most visits, shared, commented content
   * 
   * $params = array();
   * $params['type'] = 'visits';//shared, commented
   * $params['contype_id'] = 4;   
   * $params['limit'] = 2;
   * 
   * @param array $args
   * @return array 
   */
  public function getMostContent($args = array())
  {
    $contents = array();
    $con_type = isset($args['contype_id']) ? $args['contype_id'] : NEWS;
    $con_type = $this->__db->db_escape($con_type);
    $type = isset($args['type']) ? $args['type'] : 'visits';
    $limit = ( isset($args['limit']) && is_numeric($args['limit']) ) ? $args['limit'] : 5;
    $sql = 'SELECT content_id FROM ' . self::TABLE_NAME . " WHERE contype_id='$con_type' ORDER BY $type DESC LIMIT $limit";
    $results = $this->__db->executeSql($sql);
    if ( $results['data_count'] )
    {
      $c_sql = 'SELECT id, headline1, guid FROM ' . Content::TABLE_NAME . " WHERE id='%s' ";
      foreach ( $results['data'] as $r_key => $result )
      {
        $ids[] = $result['content_id'];       
      }
      $object_content = new Content;
      $contents = $object_content->getList('BY_IDS', array('ids' => $ids));
    }

    return $contents;
  }

  public function updateCounters($content_id, $counters = array(), $contype_id = 1)
  {
    $r_flag = false;
    $update_data = array();
	$date = date('Y-m-d H:i:s');
    if ( count($counters) > 0 )
    {
      $this->__db_read = Database::Instance('indiatimes_cms'); 
	  if ( $this->isExists($content_id) )
      {
        if(isset($counters['preview']) && ($counters['preview'] != '1')){
			$content_id = $this->__db->db_escape($content_id);
			$u_fields = NULL;
			unset($counters['preview']);
			foreach ( $counters as $c_key => $counter )
			{
			  if ( is_numeric($counter) )
			  {
				$field = $c_key;
			  }
			}
			$u_fields .= "updatedate='$date'";
			$sql = "call content_count__update('".$field."', '".$date."', $content_id)";	//stored procedure call
			if ( $this->__db_read->query($sql))
			{
				$r_flag = true;
				$cache_arr = $this->__object_cache->get(self::TABLE_NAME . '_' . $content_id);
				if($field != 'visits'){
					$cache_arr[$field] = $cache_arr[$field] + 1;
					$this->__object_cache->set(self::TABLE_NAME . '_' . $content_id, $cache_arr, 0);
				}
			}
		}
      }
      else
      {
		$sql ="call content_count__insert(".$content_id.", ".$contype_id.", '".$date."')"; //stored procedure call
		if ( $this->__db_read->query($sql) ){
			  $r_flag = true;
		}
      }
    }
	//var_dump($r_flag);
    return $r_flag;
  }

  public function isExists($content_id)
  {
	$data = $this->__object_cache->get(CONTENT_CACHE_KEY_STR . '_'. $content_id);
	if($data == null){
		$r_flag = false;
	}else{
		$r_flag = true;
	}
    return $r_flag;
  }
   public function updateCountersInRedis($content_id, $counters = array(), $contype_id = 1)
  {
    $date = date('Y-m-d H:i:s');
    if ( count($counters) > 0 )
    {  Redis_Client_Predis::setPredisAutoload(); 
      $redisClient = RedisClient::getClient(); 
      $redisClient->set('abhishek','tiwari'); 
	 echo  $redisClient->get('sas'); 
	 echo $redisClient->get('abhishek'); 
	  	  die;

	  if ( 0  )
      {
        if(isset($counters['preview']) && ($counters['preview'] != '1')){
			
			
		}
      }
      else
      {
		
		
      }
    }
	//var_dump($r_flag);
    return $r_flag;
  }
}