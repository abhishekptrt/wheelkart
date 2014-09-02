<?php

class Solr
{
  public static $solr = null;
  public $con = null;
  private function __construct(){
	  require_once(VENDORS.'/SolrPhpClient/Apache/Solr/Service.php');
      $this->con = new Apache_Solr_Service(SOLR_SERVER_HOST, '8080', 'indiatimes/content');
	  
  }

  public static function solrInstance()
  {  
    
	$class = __CLASS__;
	$solr = null;
    if ($solr == null )
    {
      $solr = new $class; 
    }     
    return $solr;
  }

  static public function escape($value)
	{
		//list taken from http://lucene.apache.org/java/docs/queryparsersyntax.html#Escaping%20Special%20Characters
		$pattern = '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\*|\?|:|\\\)/';
		$replace = '\\\$1';

		return preg_replace($pattern, $replace, $value);
	}

  
  public function __destruct()
  {
   
  }
  

}

