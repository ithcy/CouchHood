<?php

class CouchDocument {
  public $database = null;
  protected $_data = array();
  
  public function __construct($database, $data=array()){
      $this->database = $database;
      $this->_data = $data;
    
    }
    
  public function getId(){ return $this->_data['_id']; }
  public function getRev(){ return $this->_data['_rev'];}
  public function setRev($revision){ return $this->set("_rev", $revision);}
  public function setId($value){ return $this->set("_id", $value);}
  
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
  public function create($data=null){
     if (!is_null($data)) $this->_data = $data;
     
     $res = CouchNet::POST($this->database->getUrl()."/", $this->_data); 
     if ( array_key_exists("id", $res) ){
      $this->setId($res['id']);
      $this->setRev($res['rev']);
    }
    return $this;
  }
  public function copyTo($document_id){
      $res = CouchNet::COPY($this->getUrl(), $document_id);
      
      if (array_key_exists('id', $res) === true){
             return new self($this->database, array("_id" => $res['id'], '_rev' => $res['rev']));
      }
      else if (array_key_exists("error", $res) === true){
         throw new Exception($res['error'] . " Error: " . $res['reason']); 
       }
  }
  public function set($name, $value){
      $this->_data[$name] = $value;
      return $this;
  }  
  public function fetch($rev=null){
      $url = $this->getUrl();
      if ($rev === true) { $rev = $this->getRev();}
      if (!is_null($rev) && is_string($rev)) $url .= "?rev=$rev";
      
      $this->_data = CouchNet::GET($url);
      return $this;
    
  }
  public function get($name){
      return $this->_data[$name];
  }
  public function attach($filepath, $name=null, $mime_type=null){
      $name = is_null($name) ? basename($filepath) : $name ;
      $a = new CouchAttachment($this, $name);
      $a->upload($filepath, $name, $mime_type);
      
      
  }
  public function getUrl(){
    return $this->database->getUrl() . "/" . $this->getId();
  
  }  
  
  public function detach($name){
    $url = $this->getUrl()."/".$name."?rev=".$this->getRev();
    $res = CouchNet::DELETE($url);
    if (array_key_exists("id", $res)){
        $this->setRev($res['rev']);
      unset($this->_data['_attachments'][$name]);
      }
    return $this;
    
  }
  public function __get($name){
      return $this->get($name);
    
  }
  
  
  public function __set($name, $value){
     return $this->set($name, $value);
  }
  public function toArray(){return $this->_data;}
  
  
}
