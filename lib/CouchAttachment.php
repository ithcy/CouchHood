<?php

class CouchAttachment {
    public $document ;
    protected $_data = array();
    public $name;
    public function __construct($document, $name, $params=array()){
      $this->document = $document;
      $this->name = $name;
      $this->_data = $params;
      
      }
    public function getName(){return $this->name;}
    public function getContentType(){return $this->_data['content_type']; }
    public function getRevPos(){return $this->_data['revpos']; }
    public function getLength(){return $this->_data['length']; }
    public function getStub(){return $this->_data['stub']; }
    public function toArray(&$out=array()){
      $out[$this->getName()] = $this->_data;
      return $out;
    }
    public function upload($filepath, $name=null, $mime_type=null){
         $payload = null;
         if (stripos($filepath, "http:") === 0 || stripos($filepath, "https:") === 0 ){
             $f = fopen($filepath, "rb");
             $payload = '';
             while(!feof($f)){ 
               $payload .= fread($f, 8192); 
             }
            fclose($f);
            echo "Payload Size: [" . strlen($payload) . "] Filename: [$name] FileType: [" . $mime_type . "]\n";
            
            if (is_null($name) ) $this->name = basename($filepath);
        }
        
        
        if (is_null($payload) && file_exists($filepath)){
          if (is_null($name) ) $this->name = basename($filepath);
          $mime_type = mime_content_type($filepath);
          $payload = file_get_contents($filepath);
        }
        if (!is_null($payload) && is_string($mime_type)){
           $res = CouchNet::PUT_RAW($this->getUrl()."?rev=" . $this->document->getRev(), $payload, $mime_type);
           if (array_key_exists("id", $res)){
             $this->document->setRev($res['rev']);
             $this->document->setId($res['id']);
             }
            
        }
    
    }
    
    public function getUrl(){ return $this->document->getUrl() . "/" . $this->getName(); }  
  
  
}

