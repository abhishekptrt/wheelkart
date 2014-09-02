<?php

class Cache
{

  private static $CachingServers = NULL;
  private $ConnectionPool = NULL;
  private $connection = NULL;
  private $connectionType;
  private $message;
  private static $cacheObj = NULL;

  private function __construct($pool=true)
  {
    $this->connectionType = $pool;

    if ( $pool != true )
    {
      ini_set('memcache.allow_failover', 1);
      ini_set('memcache.max_failover_attempts', count(self::$CachingServers));
      ini_set('memcache.redundancy', count(self::$CachingServers));
    }

if(class_exists('Memcache')) {
    $this->connection = new Memcache();

    foreach ( self::$CachingServers as $cachingServer ):
      $this->connection->addServer($cachingServer[0], $cachingServer[1]);
    endforeach;
}
  }

  public static function cacheInstance($index = 'indiatimes')
  {
    if($index=='indiatimes')
	  {
		global $memcache_servers;
		self::$CachingServers = $memcache_servers;  
	  }
	  else if($index=='mobile')
	  {
		  global $memcache_mobile_servers;
		  self::$CachingServers = $memcache_mobile_servers;  
	  }
      
    $class = __CLASS__;
    if ( !isset(self::$cacheObj) )
    {
      self::$cacheObj = new $class;
    }
    return self::$cacheObj;
  }

  public function set($key, $val, $expiry)
  {
	  if(is_object($this->connection)){
		return $this->connection->set($key, $val, false, $expiry);
	  }
  }

  public function delete($key)
  {
	if(is_object($this->connection)){
		return $this->connection->delete($key);
	}
  }

  public function get($key)
  {
	if(is_object($this->connection)){
		return $this->connection->get($key);
	}
  }

  public function __destruct()
  {
    
  }
  /* logging memcache keys for a group */
  public  function save($cache_key, $data, $group_key, $expire_time =10){
     self::cache_log_key($group_key, $cache_key);
	 self::set($cache_key, $data, $expire_time);   
  }
public function cache_log_key($group_key, $cache_key = null){
	$loggedKeys = self::cache_get_log($group_key);	
	if($loggedKeys !==null && is_array($loggedKeys)){
       $loggedKeys[] =  $cache_key;  
	} else{
		$loggedKeys =  array($cache_key);  
	}
	 self::set($group_key, $loggedKeys, 0);

}

public function cache_get_log($group_key){
    return self::get($group_key);
}
public function deleteGroup($group_key){
    $loggedKeys = cache_get_log($group_key);
    foreach($loggedKeys as $key){
        self::delete($key);
    }    
    self::delete($group);
}

}