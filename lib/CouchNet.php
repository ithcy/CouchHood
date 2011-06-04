<?php

class CouchNet extends TheNet {
  public static $json_as_array = true;
  public static function GET($url){return json_decode(parent::GET($url), self::$json_as_array);}
  public static function DELETE($url){return json_decode(parent::DELETE($url), self::$json_as_array);}  
  public static function POST($url, $params=array()){return json_decode(parent::POST($url, $params), self::$json_as_array);}
  public static function PUT($url, $params=array()){return json_decode(parent::PUT($url, $params), self::$json_as_array); }
  public static function COPY($url, $destination){return json_decode(parent::COPY($url, $destination), self::$json_as_array); }
  public static function PUT_RAW($url, $payload, $content_type){return json_decode(parent::PUT_RAW($url, $payload, $content_type), self::$json_as_array); }
}