<?php

/*
 * To maintain sponsored option
 */

/**
 * Description of class
 *
 * @author priteshloke
 */
class Sponsored
{

    private $__db;

    const TABLE_NAME = 'sponsored';

    public function __construct()
    {
        $this->__db = Database::Instance();
    }

    public function getSponsor($id)
    {
        $result = array( );

        $fields = 'id,alias,sponsored,logo';
        $condition = array( );
        $condition[ 'id' ] = $id;
        $condition[ 'status' ] = 1;
        $result = $this->__db->getDataFromTable( $condition, self::TABLE_NAME, $fields );

        if ( $result[ 'data_count' ] )
        {
            $result = $result['data'][ 0 ];
        }

        return $result;
    }

}
