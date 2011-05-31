<?php
require_once(dirname(__FILE__)."/"."couch.boot.php");
class Couch {
   const URL_DBS = '/_all_dbs';
   public $server_url = "http://127.0.0.1:5984";
   
   public function __construct($url=null){
     if(!is_null($url)) $this->server_url = $url;
   
   }

   public function getUrl(){
      return $this->server_url;
   
   }
   
   public function getDbs(){
      return CouchNet::GET($this->getUrl().self::URL_DBS);
   }
   public function getDB($name){
       return new CouchDatabase($name, $this);
   }
   
   public function createDB($name){
      $db = new CouchDatabase($name, $this);
      $db->createDB();
      return $db;
   }
   
   public function __get($name){
      return $this->getDB($name);
      
   }
}

