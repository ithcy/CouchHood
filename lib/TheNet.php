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

  public function run(){
    return $this->_run();
  }
  protected function _run(){
    $res = curl_exec($this->_curl);
    curl_close($this->_curl);
    return $res;
  }
  public static function request($url, $method="GET"){
    return new self($url, $method);
  }
  public static function GET($url){
    return call_user_func_array(array('self', 'request'), array($url, "GET"))->run();
  }
  public static function DELETE($url){
    return call_user_func_array(array('self', 'request'), array($url, "DELETE"))->run();
    }
  public static function POST($url, $params=array()){
    
    $res =  call_user_func_array(array('self', 'request'), array($url, "POST"));
    $payload = json_encode($params);
    
    $res->_setOpt(CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    }
  public static function PUT($url, $params=array()){
    
    $res =  call_user_func_array(array('self', 'request'), array($url, "PUT"));
    $payload = json_encode($params);
    $res->_setOpt(CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    }
  public static function PUT_RAW($url, $payload, $content_type){
    $res =  call_user_func_array(array('self', 'request'), array($url, "PUT"));
    $res->_setOpt(CURLOPT_HTTPHEADER, array(sprintf('Content-type: %s', $content_type ), 'Content-length: ' . strlen($payload)));
    $res->_setOpt(CURLOPT_POSTFIELDS, $payload);
    return $res->run();
    }
  public static function COPY($url, $destination){
    $res =  call_user_func_array(array('self', 'request'), array($url, "COPY"));
    
    $res->_setOpt(CURLOPT_HTTPHEADER, array(sprintf('Destination: %s', $destination)));
    return $res->run();
  }
}
