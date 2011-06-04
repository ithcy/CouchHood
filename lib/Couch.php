<?php
require_once(dirname(__FILE__)."/"."couch.boot.php");

/**
 * class Couch
 * 
 * Couch is the Server class for CouchHood. Use it to establish a "Connection"
 * to couchdb.  This class also provides access to the uuid service from couchdb.
 * 
 * Finally it can create, fetch, and list Databases on the CouchDB Server.
 * 
 * The magic __get method can be used to lazily get a database....
 *   $c =  new Couch();
 *   $c->customers->all(); // Returns an array of CouchDocuments from the database named "customers"
 *  
 * 
 * It is responsible for maintaining the
 * base url for that server.  This is referenced by all its databases and documents.
 *   
 * 
 * 
 */

class Couch {
   const URL_DBS = '/_all_dbs';
   const URL_STATS = '/_stats';
   public static $_urls = array(
        "stats" => "_stats",
        "all_dbs" => "_all_dbs",
        "uuids" => "_uuids",
        "active_tasks" => "_active_tasks",
        "welcome" => "",
        "restart" => "_restart",
       "replicate" => "_replicate",
       "session" => "_session"
       
   );
   
   public $server_url = "http://127.0.0.1:5984";
      
   
   public function __construct($url=null){
     if(!is_null($url)) $this->server_url = $url;
   
   }

   /**
    * Get the server's base url including the port.
    * 
    * @return string 
    */
   public function getUrl(){
      return $this->server_url;
   
   }
   
   /**
    *
    * 
    * @return array 
    */
   public function getDbs(){
      return CouchNet::GET($this->getUrl().self::URL_DBS);
   }
   
   /**
    * Get a CouchDB using the magic methods
    * 
    * @param string $name Database Name
    * @return CouchDatabase 
    */
   
   public function getDB($name){
       return new CouchDatabase($name, $this);
   }
   
   /**
    * Create a new CouchDatabase Object, the model class.  
    * 
    * @param string $name
    * @return CouchDatabase 
    */
   public function createDB($name){
      $db = new CouchDatabase($name, $this);
      $db->createDB();
      return $db;
   }
   
   
   /**
    * Get a CouchDB using the magic methods
    * 
    * @param string $name Database Name
    * @return CouchDatabase 
    */
   public function __get($name){
      return $this->getDB($name);
   }

   
   
}