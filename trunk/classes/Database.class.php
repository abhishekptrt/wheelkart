<?php

class Database
{

  private static $instance = array();
  private $connection;
  private $ConnectionIdentifier;
  private $result;
  private $row;
  private $sql;
  private $error;
  public  $mem_version = null;
  const STATS_QUERY = false;
  private static $__exe_queries = array();
  private static $__clearMemcache = NULL;
  private static $__cache_object = NULL;

  private function __construct($index)
  {
    global $dbdetails;
    $index = self::connectionSwitcher($index);    
    $this->connection = new mysqli($dbdetails[$index]['host'], $dbdetails[$index]['user'], $dbdetails[$index]['password'], $dbdetails[$index]['database']);
    $this->ConnectionIdentifier = $index;
  }

  private static function connectionSwitcher($connection)
  {
    global $dbdetails;
    if ( 'indiatimes' == $connection && defined('T_CMD_RUNNING')
            && true === T_CMD_RUNNING && isset($dbdetails['indiatimes_cms']) )
    {
      $connection = 'indiatimes_cms';
    }
    return $connection;
  }

  private function __clone()
  {
    $this->connection->close();
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

  public function __toString()
  {
    $this->connection->close();
    trigger_error('Print is not allowed.', E_USER_ERROR);
  }

  public static function Instance($index = 'indiatimes')
  {
    if ( NULL === self::$__clearMemcache )  
    {
        self::$__clearMemcache = isset($_GET['clearmemcache']) ? $_GET['clearmemcache'] : 0;
    }
    
    if ( !isset(self::$instance[$index]) )
    {
      self::$instance[$index] = new Database($index);
    }
    return self::$instance[$index];
  }

  public function query($sql, $debug = false)
  {
    if ( is_object($this->result) )
    {
      $this->result->close();
    }
    $this->sql = $sql;
    self::$__exe_queries[] = $sql;
 	if($debug){
		echo $this->error;
	}

    if ( $this->result = $this->connection->query($this->sql) )
    {
      return true;
    }
    else
    {
      $this->error = $this->connection->error;
  	  if($debug){
			echo $this->error;
	  }
      return false;
    }
  }

  public function getRowCount()
  {
    return $this->result->num_rows;
  }

  public function getInsertedAutoId()
  {
    return $this->connection->insert_id;
  }

  public function getAffectedRowCount()
  {
    return $this->connection->affected_rows;
  }

  public function fetch()
  {
    return $this->result->fetch_array(MYSQLI_ASSOC);
  }

  public function getResultSet()
  {
    $resultSet = array();
    while ( $row = $this->result->fetch_array(MYSQLI_ASSOC) )
    {
      $resultSet[] = $row;
    }
    return $resultSet;
  }

  public function insertDataIntoTable($keyValueArray, $table, $debug=false)
  {
    $countTableData = count($keyValueArray);
    $sql = "INSERT INTO `{$table}` SET ";
    $i = 0;
    $insertID = 0;
    foreach ( $keyValueArray as $key => $val )
    {
      $i++;
      $sql .= $key . "='" . $this->db_escape($val) . "'";
      if ( $countTableData != $i )
      {
        $sql .= ", ";
      }
    }

    $res = $this->query($sql, $debug);
    if ( $res )
    {
      $insertID = $this->getInsertedAutoId();
    }
    return $insertID;
  }

  public function updateDataIntoTable($keyValueArray, $whereClauseKeyValArray, $table, $debug=false)
  {
    $countTableData = count($keyValueArray);
    $sql = "UPDATE `{$table}` SET ";
    $i = 0;
    $w = 0;
    $rowCount = 0;

    foreach ( $keyValueArray as $key => $val )
    {
      $i++;
      if ( $key == 'countupdate' )
      {
        $sql .= $this->db_escape($val);
        break;
      }
      else
      {
        $sql .= $key . "='" . $this->db_escape($val) . "'";
        if ( $countTableData != $i )
        {
          $sql .=", ";
        }
      }
    }

    $countWhereClauseData = count($whereClauseKeyValArray);
    if ( $countWhereClauseData > 0 )
    {
      $sql .=" where ";
      foreach ( $whereClauseKeyValArray as $key => $val )
      {
        $w++;
        $sql .= $key . "='" . $this->db_escape($val) . "'";
        if ( $countWhereClauseData != $w )
        {
          $sql .=" and ";
        }
      }
    } 
    $res = $this->query($sql, $debug);
    if ( $res )
    {
      $rowCount = $this->getAffectedRowCount();
    }
    return $rowCount;
  }

  public function getDataFromTable($keyValueArray, $table, $fields='*', $orderBy = "", $limit = "", $storeInMemcached=0, $memcacheExpireTime=7200)
  {
    $posts = array();
    $countTableData = count($keyValueArray);
    $sql = "SELECT $fields FROM $table";
    $i = 0;
    foreach ( $keyValueArray as $key => $val )
    {
      $i++;
      if ( $i == 1 )
      {
        $sql .=" where ";
      }

      if ( $key == 'sqlclause' )
      {
        $sql .= $val;
      }
      else
      {
       // $sql .= $key . "='" . $val . "'";
       $sql .= $key . "='" . $this->db_escape($val) . "'";
      }

      if ( $countTableData != $i )
      {
        $sql .=" and ";
      }
    }
    if ( $orderBy != "" )
    {
      $sql .=" order by " . $orderBy;
    }
    if ( $limit != "" )
    {
      $sql .=" limit " . $limit;
    }
    $posts = $this->executeSql($sql);
    return $posts;
  }

  	public function executeSql($sql='', $storeInMemcached = false, $memcacheExpireTime=7200, $debug = false)
	{ //echo $sql;
		$fromMemcache = 0;
		$posts = array();
		$posts['data'] = array();
		if ( $sql != "" )
		{
			$clearMemcache = self::$__clearMemcache;
			if ( $storeInMemcached )
			{
       if ( NULL == self::$__cache_object )
       {
           self::$__cache_object = Cache::cacheInstance();
       }       
       $memcacheKey = md5(trim($sql));	  
       $memcacheKey = SITE_NAME . '_' . $memcacheKey;
	   if($this->mem_version){
	     $memcacheKey .= '_'.$this->mem_version;
	   }
		//echo $memcacheKey;
				if ( $clearMemcache == 1 )
				{
					$oMemSql = false; //set fresh content in memcache 
				}
				else
				{
					$oMemSql = self::$__cache_object->get($memcacheKey);
					$fromMemcache = $memcacheKey;
				}
				if ( $oMemSql === false || count($oMemSql) == 1 || count($oMemSql) == 0 )
				{
					$fromMemcache = 0;
					$rs = $this->query($sql, $debug);
					if ( $rs )
					{
						$this->count = $this->getRowCount();
						$posts['data_count'] = $this->count;
						if ( $this->count > 0 )
						{
							while ( $row = $this->fetch() )
							{
								if ( isset($row['guid']) )
								{
									$row['target'] = '';
									$row['guid'] = SITEPATH . '/' . $row['guid'];
									if(!empty($row['aggregator_url']) && $row['is_aggregator_data'] == 1){
										$row['guid'] = $row['aggregator_url'];
										$row['target'] = 'target="_blank"';
									}
								}
								array_push($posts['data'], $row);
							}
						}
						$result = self::$__cache_object->set($memcacheKey, $posts, $memcacheExpireTime);
					}
				}else{
					$posts = $oMemSql;
				}
			}else{
				$rs = $this->query($sql, $debug);
				if ( $rs )
				{
					$fromMemcache = 0;
					$this->count = $this->getRowCount();
					$posts['data_count'] = $this->count;
					if ( $this->count > 0 )
					{
						while ( $row = $this->fetch() )
						{
							if ( isset($row['guid']) )
							{
								$row['target'] = '';
								$row['guid'] = SITEPATH . '/' . $row['guid'];
								if(isset($row['aggregator_url']) && $row['aggregator_url'] != '' && $row['is_aggregator_data'] == 1){
									$row['guid'] = $row['aggregator_url'];
									$row['target'] = 'target="_blank"';
								}
							}
							array_push($posts['data'], $row);
						}
					}
				}
			}
		}
		$posts['fromMemcache'] = $fromMemcache; // to check whether data actually came from memcache or not
		return $posts;
	}
  public function deleteDataFromTable($whereClauseKeyValArray, $table)
  {
    $sql = "DELETE FROM `{$table}` ";
    $w = 0;
    $rowCount = 0;
    $countWhereClauseData = count($whereClauseKeyValArray);
    if ( $countWhereClauseData > 0 )
    {
      $sql .=" where ";
      foreach ( $whereClauseKeyValArray as $key => $val )
      {
        $w++;
       // $sql .= $key . "='" . $val . "'";
  	    $sql .= $key . "='" . $this->db_escape($val) . "'";
        if ( $countWhereClauseData != $w )
        {
          $sql .=" and ";
        }
      }
    }
    $res = $this->query($sql);
    if ( $res )
    {
      $rowCount = $this->getAffectedRowCount();
    }
    return $rowCount;
  }

  public function __destruct()
  {
    if ( is_object($this->result) )
    {
      $this->result->close();
    }
    $this->connection->close();
    self::debug_queries();
  }

  private static function debug_queries()
  {
    if ( self::STATS_QUERY && 'development' == APPLICATION_ENV )//added by priteshloke
    {
      print "Total Quries: " . count(self::$__exe_queries) . "<br \>\n";
      foreach ( self::$__exe_queries as $q_key => $v_query )
      {
        print ($q_key + 1) . ') ' . $v_query . "<br />\n";
      }
      self::$__exe_queries = array();
    }
  }

  public function db_escape($string)
  {
    if ( !is_array($string) )
    {
      return $this->connection->real_escape_string(trim($string));
    }    
  }

	public function get_resultset($sql) {
		if ( $result = $this->connection->query($sql) )
		{
		  return $result;
		}
		else
		{
		  $this->error = $this->connection->error;
		  return false;
		}
	  }


}

// eof class