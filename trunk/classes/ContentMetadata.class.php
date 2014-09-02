<?php

/**
 * To maintain content meda data information
 *
 * @author priteshloke
 */
class ContentMetadata
{

  private $__db = NULL;

  const TABLE_NAME = 'content_metadata';

  public function __construct()
  {
    $this->__db = Database::Instance();
  }

  public function getMeta($conten_id, $fields = 'id,description,keywords,object_code')
  {
    $return = array();
    $condition['content_id'] = $conten_id;    
    $return = $this->__db->getDataFromTable($condition, self::TABLE_NAME, $fields, '', '', 0);

    if ( $return['data_count'] )
    {
      $return = $return['data'][0];
    }

    return $return;
  }

}