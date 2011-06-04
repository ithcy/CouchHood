<?php

class CouchDesign extends CouchDocument {
    public $_name = "";
    public static $_urls = array(
        "info" => "_info"
        
        
    );
    
    
    /**
     * getUrl for the designDocument
     * return string
     */
    public function getUrl(){
       return sprintf("%s/%s", $this->database->getUrl(), $this->getId());
    }
    
    /**
     * Return the View Langauge type.  Usually 'javascript', but if configured
     * could be 'ruby','php','python','erlang' ... etc
     * @return string 
     */
    
    public function getLanguage(){
        return $this->get("language");
    }
    
    /**
     * Get the Design Document's views as an array of CouchView objects.
     * 
     * 
     * @return array CouchView 
     */
    
    public function getViews(){
        return $this->get("views");
    }
    
    /**
     *
     * @param type $name
     * @return  CouchView
     */
    
    protected static function _blank_design_doc_array($name){
        return array(
            "_id" => "_design/$name",
            "language" => "javascript",
            "views" => array(
                "all" => array(
                    "map" => "function(doc){emit(null,doc) }",
                )
                
            )
        );
        
        
    }
    
    
    
}