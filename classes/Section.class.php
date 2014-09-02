<?php
class Section
{
	public $db = null;	
    const TABLE_NAME = 'section';
	private $posts = array();
	
	public function __construct()
	{
		$this->db = Database::Instance();		
	}
	
	public function getData($keyValueArray, $fields = '*', $orderBy = '', $limit = '', $storeInMemcached=0, $memcacheExpireTime=7200)
	{
     $keyValueArray['status'] = 1;
	 $keyValueArray['sqlclause'] = "id>130";
			$this->posts = $this->db->getDataFromTable($keyValueArray, self::TABLE_NAME, $fields, $orderBy, $limit, $storeInMemcached, $memcacheExpireTime);	
			return $this->posts;
	}
	
	public function getChildSections() {
	    
	    $sql = 'select * from '.self::TABLE_NAME.' where status = 1 and parentid != 0 order by parentid, priority';	    
	    
	    $this -> db -> query($sql);
	    $dataArr = $this->db->getResultSet();	
	
	    return $dataArr;
	}
	
	public function getParentSections() {
	    
	    $sql = 'select * from '.self::TABLE_NAME.' where status = 1 and parentid = 0 and id>130 order by priority';	    
	    
	    $this -> db -> query($sql);
	    $dataArr = $this->db->getResultSet();	
	
	    return $dataArr;
	}
	 public function refreshSectionCache()
	{
		
		 $sql = 'SELECT LOWER(s1.name) as name,s1.id,s1.parentid,LOWER(s2.name) AS parentname FROM '.self::TABLE_NAME.' s1 INNER JOIN '.self::TABLE_NAME.' s2 ON s1.parentid=s2.id WHERE s1.status = 1 ORDER BY s1.parentid';
		 $object_cache = Cache::cacheInstance();
		 $cache_key = 'section_name';
		 $cache_expiry = 864000;
		 $this->posts = $this->db->executeSql($sql, 0, 0);
		 $r = $object_cache->set($cache_key, $this->posts, $cache_expiry);
	}
	 public function ApiSectionCache(){

		 $object_cache = Cache::cacheInstance();
		 $cache_key = 'section_name';
		 $cache_expiry = 864000;

		    $feed = array();
			$ps = array();
			$i = $j= 0;
		    $sql = 'SELECT LOWER(s1.name) AS NAME,s1.id FROM '.self::TABLE_NAME.' s1  WHERE s1.status = 1 AND s1.section_order <> 0 AND parentid = 0 ORDER BY s1.section_order ASC';
		    $parent_sectiondata = $this->db->executeSql($sql);   
			$parent_sectiondata = $parent_sectiondata['data'];
			if(!empty($parent_sectiondata)){
				foreach($parent_sectiondata  as $key => $parent_data){
					$feed[$i]['Sections']['id'] = $parent_data['id'];
					$feed[$i]['Sections']['name'] = $parent_data['NAME'];
				    $sql_child = 'SELECT LOWER(s1.name) AS NAME,s1.id FROM '.self::TABLE_NAME.' s1  WHERE parentid = '.$parent_data['id'].' AND  s1.status = 1 AND s1.section_order <> 0  ORDER BY s1.section_order ASC';				   
					$child_sectiondata = $this->db->executeSql($sql_child);
					$child_sectiondata = $child_sectiondata['data'];
                    $j = 0;
					if(!empty($child_sectiondata)){
						foreach($child_sectiondata as $key =>$child_data){
							$feed[$i]['Sections']['Subsections'][$j]['section_id']   = $child_data['id'];
							$feed[$i]['Sections']['Subsections'][$j]['section_name'] = $child_data['NAME']; 
							$j++;
						}
					}
					$i++;
				}
			}
		 $object_cache = Cache::cacheInstance();
		 $cache_key = 'api_section_name_list';
		 $cache_expiry = 86;
         $r = $object_cache->set($cache_key, $this->posts, $cache_expiry);
		 return $feed;
	}

	public function getSectionTree() {
		$arrSectionTree = array();
		$arrWhere = array();
		$arrWhere['status'] = 1;
		$arrWhere['sqlclause'] = 'id > 130';
		$arrWhere['parentid'] = 0;
		$orderby = 'priority';
		$limit = '';
		$dataArr = $this -> db -> getDataFromTable($arrWhere, self::TABLE_NAME, "id, name, parentid, guid", $orderby, $limit);

		foreach ($dataArr['data'] as $r) {
			$arrSectionTree[$r['id']]['id'] = $r['id'];
			$arrSectionTree[$r['id']]['name'] = $r['name'];
			$arrSectionTree[$r['id']]['guid'] = $r['guid'];

			$arrWhere['parentid'] = $r['id'];
			$dataArr1 = $this -> db -> getDataFromTable($arrWhere, self::TABLE_NAME, "id, name, parentid, guid", $orderby, $limit);
		
			$i=0;
			foreach ($dataArr1['data'] as $r1) {
				$arrSectionTree[$r['id']]['subsection'][$i]['id'] = $r1['id'];
				$arrSectionTree[$r['id']]['subsection'][$i]['name'] = $r1['name'];
				$arrSectionTree[$r['id']]['subsection'][$i]['guid'] = $r1['guid'];
				
				$i++;
			}
		}

		return $arrSectionTree;
	}
}