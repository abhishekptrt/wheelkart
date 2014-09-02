<?php
class Contype
{
	public $db = null;	
    const TABLE_NAME = 'contype';
	private $posts = array();
	
	public function __construct()
	{
		$this->db = Database::Instance();		
	}
	
	public function getData($keyValueArray, $fields = '*', $orderBy = '', $limit = '', $storeInMemcached=0, $memcacheExpireTime=7200)
	{
			$this->posts = $this->db->getDataFromTable($keyValueArray, self::TABLE_NAME, $fields, $orderBy, $limit, $storeInMemcached, $memcacheExpireTime);	
			return $this->posts;
	}

	public static function getContypeIdsByName($contype_value = null){
		switch ($contype_value){
			case 'article':
			$contype_id	= NEWS;								
			
			break;
			case 'photogallery':
				$contype_id	 =  PHOTOGALLERY .','. PICTURESTORY;
			break;
			case 'video':
				$contype_id		= VIDEO; 
			break;
			case 'lists': 
			case 'list': 
				$contype_id		= LISTS; 
			break;
			case 'reviews': 
				$contype_id		= REVIEWS; 
			break;
			case 'picturestory': 
				$contype_id	 =  PHOTOGALLERY .','. PICTURESTORY;
			break;
			case 'columns': 
				$contype_id		= COLUMN; 								
			break;
			default
				$contype_id = 0;
            break;
		}
		return $contype_id;
	}
}