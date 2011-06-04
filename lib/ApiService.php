<?php

/** ApiService.php


**/

//require_once("TheNet.php");
abstract class ApiService {
  protected $_host;
  public static $_services = array();
  
  abstract public function find($params);
  
  public static function _parseJson($data){
    return json_decode($data, true);
  }
  
  protected function _buildUrl($url, $data){
        return sprintf("%s%s?%s", $this->_host , $url, http_build_query($data));
  }
  
  public  function getJSON($url,$callback=null){
        return self::_parseJson(self::GET($url, $callback) );
  }
  
  public function setHost($host){ $this->_host = $host;}
  public function getHost(){ return $this->_host;}
  public static function GET($url, $callback=null, $callback_params=array()){
       $res = TheNet::GET($url);
       if (is_callable($callback)){return call_user_func_array($callback, $callback_params);}
       else
         return $res;
  }
}
