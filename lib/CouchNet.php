<?php

class CouchNet extends TheNet {
  public static $json_as_array = true;
  
  /**
   * Returns the result of a GET after json_decoding it to an array
   * 
   * @param string $url
   * @return array 
   */
  public static function GET($url){return json_decode(parent::GET($url), self::$json_as_array);}
  
  /**
   * Returns the result of a POST after json_decoding it to an array
   * @param type $url
   * @param type $params
   * @return type 
   */
  public static function POST($url, $params=array()){return json_decode(parent::POST($url, $params), self::$json_as_array);}
  
  /**
   *
   * @param type $url
   * @param type $params
   * @return type 
   */
  public static function PUT($url, $params=array()){return json_decode(parent::PUT($url, $params), self::$json_as_array); }
  
  /**
   *
   * @param string $url
   * @param string $destination
   * @return array 
   */
  public static function COPY($url, $destination){return json_decode(parent::COPY($url, $destination), self::$json_as_array); }
  
  /**
   *
   * @param type $url
   * @return type 
   */
  public static function DELETE($url){return json_decode(parent::DELETE($url), self::$json_as_array);} 
  /**
   *
   * @param string $url
   * @param string $payload
   * @param string $content_type
   * @return array
   */
  public static function PUT_RAW($url, $payload, $content_type){return json_decode(parent::PUT_RAW($url, $payload, $content_type), self::$json_as_array); }
}