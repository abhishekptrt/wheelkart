<?php

/*
 * To maintain media mdoel class
*/

/**
 * Description of class
 *
 * @author deepak goyal
 */
class Media {

    private $__db;
    const TABLE_NAME = 'media';

    public function __construct() {
        $this->__db = Database::Instance();
    }

    public function getMedia($contentId) {
        $results = array();
        $condition = array();
        $condition['content_id'] = $contentId;
        $fields = 'id, content_id, caption, thumbnail, priority, headline, description, insertdate, updatedate, alt, credit, author, keyword, width, height';
        $orderby = 'priority';
        $limit = '';
        $results = $this->__db->getDataFromTable($condition, self::TABLE_NAME, $fields, $orderby, $limit);

        return $results;
    }

    // save images into media
    public function insertBodyImageIntoMedia($data) {
        
        $objDb = Database::Instance('indiatimes_cms');
        
        $sql = "select id from   ".self::TABLE_NAME." where  thumbnail='". $data['thumbnail']."' and content_id='".$data['content_id']."'";
        $objDb->query($sql);
        $result = $objDb->getResultSet();
        
        if(count($result) == 0) { // insert new
            $sql = "insert into  ".self::TABLE_NAME." SET  thumbnail='". $data['thumbnail']."' ,content_id='".$data['content_id']."',status=1,insertdate=now(),updatedate=now(),is_editorial=1,is_stored=1";
            $res = $objDb->query($sql);
            $InsertedAutoId = 0;
            if ($res) {
                $InsertedAutoId = $objDb->getInsertedAutoId();
            }
        } else { // if exists
            $InsertedAutoId = $result['data'][0]['id'];
        }
        return $InsertedAutoId;

    }
}
