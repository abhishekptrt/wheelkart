<?php

class SocialTracker {

    private $db;
    private $tableName;
    private $iBeatUrl;

    /*     * *************** START OF CONSTRUCTOR ************************* */

    public function __construct() {
        $this->iBeatUrl = 'http://ibeatserv.indiatimes.com/iBeat/socialGraphData.html?host=indiatimes.com';
        //$this->tableName = 'media';
        //$this->db = Database::Instance();
    }

    /*     * *********************** END OF CONSTRUCTOR ********************* */

    public function isExit($contentId, $type, $db) {
        $sql = "SELECT COUNT(*) AS cnt FROM content_ibeat WHERE content_id=$contentId AND type='$type'";
        $db->query($sql);
        //echo $db->getRowCount();
        if ($db->getRowCount()) {
            $arr = $db->getResultSet();
            if ($arr[0]['cnt'] != 0) {
                return 1;
            }
        } else {
            return 0;
        }
    }

    public function getMostViewed($sectionId, $limit, $db) {
        //$db = Database::Instance('indiatimes_comments');
        $content_sql = "SELECT cs.content_id,cs.section_id, cs.section_name,c.guid
                        FROM content AS c INNER JOIN content_section_relation AS cs 
                        ON c.id=cs.content_id 
                        WHERE cs.section_id =$sectionId  ORDER BY cs.id DESC Limit $limit";
        $db->query($content_sql);
        if ($db->getRowCount()) {
            $dataArr = $db->getResultSet();
            //echo '<pre>'; print_r($dataArr);
            foreach ($dataArr as $key => $valueArr) {
                $mostViralBySubSection[$valueArr['content_id']][0]['time'] = time();
                $mostViralBySubSection[$valueArr['content_id']][0]['Tag'] = $valueArr['section_name'];
                $mostViralBySubSection[$valueArr['content_id']][0]['URL'] = $valueArr['guid'];
                $mostViralBySubSection[$valueArr['content_id']][0]['Virality'] = '';
                $mostViralBySubSection[$valueArr['content_id']][0]['ShareCount'] = '';
                $mostViralBySubSection[$valueArr['content_id']][0]['viewCount'] = '';
            }

            return $mostViralBySubSection;
        } else {
            //echo "There is some problem in sql with id $id <br>";
        }
    }

    public function getContentDetail($arr, $db) {
        //$db = Database::Instance();
        $id = $arr['id'];
        $tag = $arr['tag'];
        $sortorder = 'id desc';
        $limit = 1;
        $whereArray['id'] = $id;
        //$dataArr = $db->getDataFromTable($whereArray, 'content', "*", $sortorder, $limit, false);
        $content_sql = "SELECT cs.content_id,cs.section_id,cs.section_name,cs.section_parentid,cs.section_parentname,c.headline1,c.guid,c.thumbnail 
                        FROM content AS c INNER JOIN content_section_relation AS cs 
                        ON c.id=cs.content_id 
                        WHERE c.id IN ($id) Limit 1";
        $db->query($content_sql);
        if ($db->getRowCount()) {
            $dataArr = $db->getResultSet();
            return $dataArr[0];
        } else {
            echo "There is some problem in sql with id $id <br>";
        }
    }

//-----------      consolidate    ----------------------------------------
    public function getGraphData($start = '', $end = '', $tags = '', $consolidate = 'cons') {
        $query['param'] = 'HS';
        $query['cons'] = $consolidate;
        $query['start'] = $start;
        $query['end'] = $end;
        $query['cat'] = $tags;
        $url = $this->makeUrl($query);
        $mostViralData = $this->getData($url);
        //print_r($mostViralData);
        return array('url' => $url, 'data' => $mostViralData);
        //return $mostViralData;
    }

    public function getStoryGraphData($storyId, $start = '', $end = '') {
        $query['param'] = 'HS';
        $query['aid'] = $storyId;
        $query['start'] = $start;
        $query['end'] = $end;
        $url = $this->makeUrl($query);
        $mostViralData = $this->getData($url);
        //print_r($mostViralData);
        return array('url' => $url, 'data' => $mostViralData);
        //return $mostViralData;
    }

    public function getMostViral($tags = '', $limit = 20) {
        $query['param'] = 'VS';
        $query['cat'] = $tags;
        $query['limit'] = $limit;
        echo $url = $this->makeUrl($query);
        $mostViralData = $this->getData($url);
        return $mostViralData;
    }

    public function getMostShared($tags = '', $limit = 20) {
        $query['param'] = 'MS';
        $query['cat'] = $tags;
        $query['limit'] = $limit;
        $url = $this->makeUrl($query);
        $mostSharedData = $this->getData($url);
        return $mostSharedData;
    }

    private function getData($url) {
        $data = $this->makeCall($url);
        return $dataArr = json_decode($data, true);
    }

    private function makeUrl($query = array()) {
        // echo $this->iBeatUrl;
        //$url = $this->iBeatUrl . "&param=$type&limit=20";
        $query_array = array();
        foreach ($query as $key => $key_value) {
            if ($key_value != '') {
                $query_array[] = urlencode($key) . '=' . urlencode($key_value);
            }
        }
        $query_String = implode('&', $query_array);
        return $this->iBeatUrl . '&' . $query_String;
    }

    private function makeCall($url = null) {
        if (empty($url)) {
            throw new Exception('Url is blank');
        }
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($ch);
            curl_close($ch);
        } else {
            $res = file_get_contents($url);
        }
        return $res;
    }

}

?>
