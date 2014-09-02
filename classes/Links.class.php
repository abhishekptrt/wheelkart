<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 
class Links {
 
  const TABLE_NAME = 'links';
  public static $link_type = array('1'=>'Left Menu', '2'=>'Header Link', '3'=>'Top Link');
  public function __construct(){    
  }

    
  
  public static function getLinks($link_type=1){    
    $db = Database::Instance();
      $links = $db->getDataFromTable(array('link_type'=>$link_type,"status"=>1), self::TABLE_NAME, '*', "parent_id ASC,link_order ASC","", false);    
      $parent_links = array();
      if(!empty($links['data'])){
        foreach ($links['data'] as $key => $value) {
          if($value['parent_id'] == 0){
            $parent_links[$value['id']] = $value;    
          } else {
            $parent_links[$value['parent_id']]['childs'][] = $value;
          }     
      }      
    }
    return $parent_links;
  }
}