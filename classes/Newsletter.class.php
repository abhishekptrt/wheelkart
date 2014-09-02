<?php

class Newsletter {

  protected $finalData = array();
  private $db;
  private $tableName;
  /*   * ******************* START OF CONSTRUCTOR ****************************** */

  public function __construct() {
    $this->tableName = 'newsletter';
    $this->db = Database::Instance('indiatimes_cms');
  }

  /*   * ************************** END OF CONSTRUCTOR ************************* */

  public function insertTable($values) {
    return $this->db->insertDataIntoTable($values, $this->tableName);
  }
	
	public function checkEmailSubmit($email) {
    
    $sql = "SELECT count(id) AS cnt FROM newsletter WHERE email = '".$this->db->db_escape($email)."'";
    
    $data = $this->db->executeSql($sql);
    
    return $data['data'][0]['cnt'];
    
  }
 
}

?>