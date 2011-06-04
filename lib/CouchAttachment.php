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
      
      
    /**
     * Get the Attachment File Name
     * @return type 
     */
    public function getName(){return $this->name;}
    
    /**
     * getContentType returns the content type property
     * @return string 
     */
    public function getContentType(){return $this->_data['content_type']; }
    
    
    /**
     * Get the Revision Position
     * @return string 
     */
    public function getRevPos(){return $this->_data['revpos']; }
    
    /**
     * Return the filesize of the attachment
     * 
     * @return int 
     */
    public function getLength(){return $this->_data['length']; }
    
    
    /**
     * Get the Document stub
     * @return type 
     */
    
    public function getStub(){return $this->_data['stub']; }
    
    
    /**
     * Upload a File into the database
     * @param string $filepath URL or PATH to a file
     * @param string $name Name to store it under.
     * @param string $mime_type Type of file, will try to autocalculate if not given.
     */
    
    public function upload($filepath, $name=null, $mime_type=null){
         $payload = null;
         if (stripos($filepath, "http:") === 0 || stripos($filepath, "https:") === 0 ){
             $f = fopen($filepath, "rb");
             $payload = '';
             while(!feof($f)){ 
               $payload .= fread($f, 8192); 
             }
            fclose($f);
            //echo "Payload Size: [" . strlen($payload) . "] Filename: [$name] FileType: [" . $mime_type . "]\n";
            
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
    
    /**
     * Returns the full url to the attachment
     * 
     * @return string 
     */
    public function getUrl(){ return $this->document->getUrl() . "/" . $this->getName(); }  
 
    /**
     * Returns an array from the document.
     * @param array $out
     * @return array 
     */
    public function toArray(&$out=array()){
      $out[$this->getName()] = $this->_data;
      return $out;
    }
}

