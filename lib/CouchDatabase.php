<?php

class CouchDatabase {
   const URL_ALL_DOCS = '/_all_docs';
   public $name;
   public $server = null;
   
   
   public function __construct($name, $server=null){
     $this->name = $name;
     $this->server = $server;
   }
   
   public function getUrl(){
      return $this->server->getUrl() . "/" . $this->name;
   
   }
   public function get($document_id, $revision=null){
      return new CouchDocument($this, array('_id' => $document_id));
      
   }
   
   public function __get($name){
      return $this->get($name);
    }
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
   protected function _as_doc($result){
      
      return new CouchDocument($this, $result);
   }
   
   protected function _as_docs_from_list($rows){
        $out = array();
        foreach($rows as $curr){
          $out[] = $this->_as_doc(array("_id" => $curr['id'], '_rev' => $curr['value']['rev']))->fetch();
        }
        return $out;
     
  }
   protected function _as_docs($results){
      
      return array_map(array($this, '_as_doc'), $results);
   }
   
   public function createDB(){
      return CouchNet::PUT($this->getUrl()."/");
  }


}
