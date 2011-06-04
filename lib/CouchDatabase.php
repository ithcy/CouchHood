<?php

class CouchDatabase {
    
   const URL_ALL_DOCS = '/_all_docs';
   public static $_urls = array(
       "changes" => "_changes",	
       "compact" => "_compact",
       "design" => "_design",
       "temp_view" => "_temp_view",
       "view_cleanup" => "_view_cleanup"

   );
   public $name;
   public $server = null;
   
   
   public function __construct($name, $server=null){
     $this->name = $name;
     $this->server = $server;
   }
   
   /**
    * Get the base url for the Database
    * @return string
    */
   public function getUrl(){
      return $this->server->getUrl() . "/" . $this->name;
   
   }
  
   
   /**
    * Create a New Couch Document in this Database
    * @param type $document
    * @return CouchDocument 
    */
   public function create($document){
     $doc = new CouchDocument($this, $document);
     $doc->create();
     return $doc;
     
     }
   
/**
 *  all() returns all the documents from the database
 * @return array 
 */
   
   public function all(){
     //return $this->_as_docs(CouchNet::GET($this->getUrl().self::URL_ALL_DOCS));
     $res = CouchNet::GET($this->getUrl().self::URL_ALL_DOCS);
     if ($res['total_rows'] >= 1){
        return $this->_as_docs_from_list($res['rows']);
      }
     else {
        return array();
       }
   }
   

   /**
    * Perform a bulk create/update action on the DB
    * @param array $docs
    * @return array 
    */
   public function bulk($docs){
      $res = CouchNet::POST($this->getUrl()."/_bulk_docs", array("docs" => $docs));
      return $res;
     
    }
   
    
   /**
    * Create the DB in case it doesn't exist
    * 
    * 
    * @return CouchDatabase 
    */ 
    
   public function createDB(){ 
       $res = CouchNet::PUT($this->getUrl()."/"); 
       return $this;
   }
   
    /**
     * Compact a design document and its views.
     * 
     * @param string $design_name
     * @return array 
     */
   public function compact($design_name){
       $url = sprintf("%s/%s/%s", $this->getUrl(), self::$_urls["compact"], $design_name);
       return CouchNet::POST($url);
       
   }

   /**
    * Hydration of array into a CouchDocument
    * @param array $result
    * @return CouchDocument 
    */
   protected function _as_doc($result){
      return new CouchDocument($this, $result);
   }
   
   /**
    * Converts and array of arrays into an array of CouchDocuments.
    * Used to convert list results to an array of documents
    * @param array $rows
    * @return array 
    */
   
   protected function _as_docs_from_list($rows){
        $out = array();
        foreach($rows as $curr){
          $out[] = $this->_as_doc(array("_id" => $curr['id'], '_rev' => $curr['value']['rev']))->fetch();
        }
        return $out;
     
  }
   
  /**
   * Converts and array of arrays into an array of CouchDocuments
   * @param array $results
   * @return array 
   */
  
   protected function _as_docs($results){
      
      return array_map(array($this, '_as_doc'), $results);
   }
   
   /**
    * Cleanup the Views on this DB.  Could take a while but should save space.
    * @return array 
    */
   public function view_cleanup(){
       $url = sprintf("%s/%s", $this->getUrl(), self::$_urls["view_cleanup"]);
       return CouchNet::POST($url);
   }
      
/** Create a temporary_view during development.  This is much preffered over 
 * creating a real view and constantly modifying it.
 * 
 *
 * @param array $data
 * @return array 
 */
   
   public function temp_view($data=array()){
        $url = sprintf("%s/%s", $this->getUrl(), self::$_urls["temp_view"]);
       
       return CouchNet::POST($url, $data);
       
   }
   
   /**
    *
    * @param string $document_id
    * @param string $revision
    * @return CouchDocument 
    */
   public function get($document_id, $revision=null){
      return new CouchDocument($this, array('_id' => $document_id));
      
   }
   
   public function __get($name){
       
      return $this->get($name);
    }

}
