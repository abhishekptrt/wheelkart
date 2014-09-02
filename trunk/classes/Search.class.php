<?php

/*
 * To maintain sponsored option
 */

/**
 * Description of class
 *
 * @author priteshloke
 */
class Search
{

  private $resultCount;
	private $parameterArray;
	private $facets;
	private $returnData;
	private $highlight;
	private $colaspeFeature;
	
	const apiFileName = '/SolrApi/mobile_search_content.php' ;
	



	public function __construct($colaspeFeature=true, $highlight=false){
		$this->highlight = $highlight;
		$this->colaspeFeature = $colaspeFeature;
		
		
	}

	public function __desctruct(){

	}

	public function setParameters($parameterArray=''){
		$this->parameterArray = $parameterArray;
		
		$this->createResultSet(); // call this function for result
	}


	private function createResultSet(){
      require_once(VENDORS.'/SolrPhpClient/Apache/Solr/Service.php');	    
		  $sqlparameter= $this->parameterArray;		   
		 	$solr_Sql ='http://ssa.indiatimes.in' . self::apiFileName . "?" . $sqlparameter ;	
			$xml='';			
			try{
			$ch = curl_init();		
			curl_setopt($ch, CURLOPT_URL, $solr_Sql);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT,1);
			$xml_content= curl_exec($ch);			
			curl_close($ch);
			$xml_returndata=explode('</xml>',$xml_content);
			if($xml_returndata[0]){
				$xml_datacontent2=$xml_returndata[0]."</xml>";
			}else{
				$xml_datacontent2="";
			}
			//$xml_datacontent2=$xml_returndata[0]."</xml>";
			$xml = new SimpleXMLElement($xml_datacontent2);
			} catch (Exception $e) {
			   // echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
			if(isset($xml->length))			
				$this->resultCount=$xml->length;
			else 
				$this->resultCount='0';
			$xml_returndata_val = ( isset($xml_returndata[1]) ) ? $xml_returndata[1] : '';		
			$facetmonitorarrdata=explode('~~~~',$xml_returndata_val);			
			$this->returnData=$xml;
			$this->facets=$facetmonitorarrdata;		
			
	}

	public function fetchData(){
		return  $this->returnData;

	}

	public function fetchFacets(){
		 return $this->facets;
	}

	public function getCount(){
		return $this->resultCount;
	}

}
