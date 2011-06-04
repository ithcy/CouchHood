<?php

DEFINE('HTTPMETHOD', CURLOPT_CUSTOMREQUEST);

class TheNet {
  protected $_curl;
  protected $_url = null;
  public $_opts = array();
  
  public function __construct($url=null , $httpMethod="GET"){
    $this->_url = $url;
    $this->_startCurl($httpMethod);
  }
  
  protected function _setOpt($name, $val){
   return  curl_setopt($this->_curl, $name, $val);
    
  }
  protected function _startCurl($httpMethod="GET"){
    $this->_curl = curl_init($this->_url);
    $this->_setOpt(CURLOPT_RETURNTRANSFER, 1);
    $this->_setOpt(HTTPMETHOD, $httpMethod);
  }
  /**
   *
   * @return mixed 
   */
  public function run(){
    return $this->_run();
  }
  
  /**
   *
   * @return string 
   */
  protected function _run(){
    $res = curl_exec($this->_curl);
    curl_close($this->_curl);
    return $res;
  }
  
  /**
   * make a curl request and return the results
   * 
   * @param string $url
   * @param string $method
   * @return self 
   */
  public static function request($url, $method="GET"){
    return new self($url, $method);
  }
  
  /**
   * Perform a generic GET request, passing url as a string, including get parameters as a string
   * 
   * @param string $url
   * @return string 
   */
  public static function GET($url){
    return call_user_func_array(array('self', 'request'), array($url, "GET"))->run();
  }
  /**
   * Make a POST REQUEST.  Always Encodes the array as json and set the content-type for the header.
   * 
   * @param string $url
   * @param array $params
   * @return string 
   */
  public static function POST($url, $params=array()){
    
    $res =  call_user_func_array(array('self', 'request'), array($url, "POST"));
    $payload = json_encode($params);
    $res->_setOpt(CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    
    }
  /**
   * Send a PUT request, usually to save a document you have an ID for.
   * @param string $url
   * @param array $params optional
   * @return string 
   */  
  public static function PUT($url, $params=array()){
    
    $res =  call_user_func_array(array('self', 'request'), array($url, "PUT"));
    $payload = json_encode($params);
    $res->_setOpt(CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    
    
    }
  /**
   * Pass thru the content raw to the url.  content_type should be like 'application/json'.  Be careful with the function, its RAW.
   * @param string $url
   * @param string $payload
   * @param string $content_type
   * @return string 
   */  
  public static function PUT_RAW($url, $payload, $content_type){
    $res =  call_user_func_array(array('self', 'request'), array($url, "PUT"));
    $res->_setOpt(CURLOPT_HTTPHEADER, array(sprintf('Content-type: %s', $content_type ), 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    }  
  /**
   * Send a DELETE request to the given url
   * 
   * @param string $url
   * @return string 
   */
  public static function DELETE($url){
    return call_user_func_array(array('self', 'request'), array($url, "DELETE"))->run();
    }
 /**
  *
  * @param string $url
  * @param string $destination
  * @return string 
  */
  public static function COPY($url, $destination){
    $res =  call_user_func_array(array('self', 'request'), array($url, "COPY"));
    
    $res->_setOpt(CURLOPT_HTTPHEADER, array(sprintf('Destination: %s', $destination)));
    return $res->run();
  }
}
