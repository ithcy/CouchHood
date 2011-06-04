<?php

class CouchDocument {
  public $database = null;
  protected $_data = array();
  
  public function __construct($database, $data=array()){
      $this->database = $database;
      $this->_data = $data;
  }
  
  /** 
   * Get the Documents unique Id
   *
   * @return mixed
   */
  public function getId(){ return $this->_data['_id']; }
  /**
   *
   * @param string $value
   * @return self 
   */
  public function setId($value){ return $this->set("_id", $value);}
  /**
   * Get the Current Revision Number
   * @return string 
   */
  
  public function getRev(){ return $this->_data['_rev'];}
  
  /**
   * Set the Current Revision 
   * 
   * @param string $revision
   * @return self 
   */
  public function setRev($revision){ return $this->set("_rev", $revision);}
  
  
  /**
   * Store the document back to couchdb.  If succseful, it will auto update the _id column.
   * @return CouchDocument 
   * 
   */
  public function save(){
     $url = $this->getUrl();
     if (!is_null($this->getRev())){
       $url .= "?rev=" . $this->getRev();
       }
     $res = CouchNet::PUT($url, $this->_data);

     if (array_key_exists("id", $res)){
      $this->setRev($res['rev']);
     }
     
     return $this;
  
  }
  
  
  /**
   * Create a new document using POST, which will auto assign an _id if one is not given.
   * 
   * @param array $data
   * @return CouchDocument 
   */
  public function create($data=null){
     if (!is_null($data)) $this->_data = $data;
     
     $res = CouchNet::POST($this->database->getUrl()."/", $this->_data); 
     if ( array_key_exists("id", $res) ){
      $this->setId($res['id']);
      $this->setRev($res['rev']);
    }
    return $this;
  }
  
  /**
   * Copy this document to another document within the DB.
   * 
   * @param string $document_id
   * @return CouchDocument 
   */
  public function copyTo($document_id){
      $res = CouchNet::COPY($this->getUrl(), $document_id);
      
      if (array_key_exists('id', $res) === true){
             return new self($this->database, array("_id" => $res['id'], '_rev' => $res['rev']));
      }
      else if (array_key_exists("error", $res) === true){
         throw new Exception($res['error'] . " Error: " . $res['reason']); 
       }
  }
  
  /**
   * Get the document from couchdb. Pass a $rev to target a specific revision
   * 
   * @param string $rev Revision of the document you want to fetch from couchdb
   * @return CouchDocument 
   */
  public function fetch($rev=null){
      $url = $this->getUrl();
      if ($rev === true) { $rev = $this->getRev();}
      if (!is_null($rev) && is_string($rev)) $url .= "?rev=$rev";
      
      $this->_data = CouchNet::GET($url);
      return $this;
    
  }

  /**
   * Upload an attachment for this document.  Filepath accepts local and http paths
   * 
   * @param string $filepath
   * @param string $name
   * @param string $mime_type 
   */
  public function attach($filepath, $name=null, $mime_type=null){
      $name = is_null($name) ? basename($filepath) : $name ;
      $a = new CouchAttachment($this, $name);
      $a->upload($filepath, $name, $mime_type);
      
      
  }
  
  
  /**
   * Get the Full Url to this document. It will rely on its $this->database
   * to provide most of the URL, then it its is id.
   * @return string 
   */
  public function getUrl(){
    return $this->database->getUrl() . "/" . $this->getId();
  }  
  /**
   * 
   */
  
  public function setUrl($url){
      
      
  }
  
  
  /**
   * Remove an attachment from a document. 
   * @param string $name Name of the Attachment File to remove
   * @return CouchDocument 
   */
  public function detach($name){
    $url = $this->getUrl()."/".$name."?rev=".$this->getRev();
    $res = CouchNet::DELETE($url);
    if (array_key_exists("id", $res)){
        $this->setRev($res['rev']);
      unset($this->_data['_attachments'][$name]);
      }
    return $this;
    
  }
  
  
  /**
   * Set a value on the document
   * 
   * @param string $name
   * @param mixed $value
   * @return CouchDocument 
   */
  public function set($name, $value){
      $this->_data[$name] = $value;
      return $this;
  }  
  /**
   *
   * @param type $name
   * @return type 
   */
  
  public function get($name){
      return $this->_data[$name];
  }
  /**
   *
   * @param type $name
   * @return type 
   */
  public function __get($name){
      return $this->get($name);
    
  }
  
  /**
   *
   * @param type $name
   * @param type $value
   * @return type 
   */
  public function __set($name, $value){
     return $this->set($name, $value);
  }
  
  /** 
   * Get the Document as a native php array.
   *
   * @return array 
   */
  public function toArray(){return $this->_data;}
  
  
}
