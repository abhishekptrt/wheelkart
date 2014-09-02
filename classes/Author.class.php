<?php

/*
 * To maintain author mdoel class 
 */

/**
 * Description of class
 *
 * @author priteshloke
 */
class Author
{

  private $__db;
  const TABLE_NAME = 'author';

  public function __construct()
  {
    $this->__db = Database::Instance();
  }

  public function getAuthor($id, $fields = 'id,name,email,thumbnail',$setmem=0)
  {
    $results = array();
    $condition = array();
    $condition['id'] = $id;
    $results = $this->__db->getDataFromTable($condition, self::TABLE_NAME, $fields,'','',$setmem);
    

    if ( $results['data_count'] )
    {
      $results = $results['data'][0];
      
      if ( isset($results['facebook']) && !empty($results['facebook']) )
      {
        $results['facebook'] = FACEBOOK_PROFILE_LINK . $results['facebook'];
      }
      
      if ( isset($results['twitter']) && !empty($results['twitter']) )
      {
        $results['twitter'] = TWITTER_PROFILE_LINK . $results['twitter'];
      }
    }

    return $results;
  }

  public function getColumnAuthors($args = array())
  {
    $fields = isset($args['fields']) ? implode(',', $args['fields']) : 'id,name,email,designation,thumbnail';
    $limit = ( isset($args['limit']) && is_int($args['limit'])) ? $args['limit'] : '';
    $results = array();
    $condition = array();
    $condition['status'] = 1;
    $condition['is_columnist'] = 1;
    $results = $this->__db->getDataFromTable($condition, self::TABLE_NAME, $fields, 'name ASC', $limit);

    //unset($results['data_count']);
    if ( $results['data_count'] )
    {
      $temp = array();
      for ($i = 0; $i < $results['data_count']; $i++)
      {
        $r_val = $results['data'][$i];
        $temp[$r_val['id']] = $r_val;
      }
      $results = $temp;
    }
    return $results;
  }
}