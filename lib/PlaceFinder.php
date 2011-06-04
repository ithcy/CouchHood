<?php

//include_once("ApiService.php");

class ApiServiceConfig {
     public $name;
     public $path = "/";
     public $params = array();
     
     public function __construct($name, $path=null, $params=array()){
            $this->name = $name;
            if (!is_null($path)) $this->path = $path;
            $this->params = $params;
    }
  
  
  }



/**
 * @param flags string response format, response fields, and search scope can be set
Character in Flags  Description
C Only return coordinate data and match quality elements; do not return address data elements.
E Do not return woeid element. This impacts the radius output.
G Return global area elements instead of US-specific elements.
J Return data in JSON format. Default format is XML. See Supported Formats..
P Return data in Serialized PHP format. Default format is XML. See Supported Formats..
Q Return nearest commercial airport code element for each result.
R Return telephone area code element for each result.
S Return detailed street attributes (Prefix, Body, Suffix, etc).
T Return timezone information element for each result.
X Return bounding box element for each area result.

* 
* example:  ->setFlags("JC")  equiv to /geocode?flags=JC
* 
* 
*/

/**
 * GFLAGS parameter:
 * 
Character in gflags Description
A Return neighborhood names for each result.
C Look up cross streets for each result.
L Limit results to the locale country.
Q Quick mode, enable exact matches only for free-form input.
R Reverse geocode coordinates for each result. To perform reverse geocoding, specify the latitude and longitude in the 
* location parameter. The response will include information such as the street address.
* 
* 
* 
*/


class PlaceFinder extends ApiService{
   const FLAG_LATLNG_ONLY = "C";
   const FLAG_NO_WOEID = "E";
   const FLAG_GLOBAL_AREA = "G";
   const FLAG_JSON = "J";
   const FLAG_PHP = "P";
   const FLAG_AIRPORT = "Q";
   const FLAG_AREA_CODE = "R";
   const FLAG_DETAILS = "S";
   const FLAG_TIMEZONE = "T";
   const FLAG_BOUNDING_BOX = "X";
   
   const GFLAG_REVERSE_GEOCODE = "R";
   const GFLAG_QUICK = "Q";
   const GFLAG_CROSS_STREET = "C";
   const GFLAG_LOCALE_ONLY = "L";
   const GFLAG_NEIGHBORHOOD = "A";
   
   
   public static $_api_host = "http://where.yahooapis.com";
   protected static $_appid = null;
   protected static $_response_format = "J";
   
   public static $_cfg = array(
                  "flags" => "J",
                  "gflags" => ""
          );
      
   public static function _configure(){
      self::$_services = array (
          "geocode" => new ApiServiceConfig("geocode", "/geocode", array() )
      );     
  }
  public static function addGFlag($flag){
        $flag = strtoupper($flag);
        if (strpos(self::$_cfg['gflags'], $flag) === false)  self::$_cfg['gflags'] .= $flag;
        return $this;
  }
  
  public static function addFlag($flag){
        $flag = strtoupper($flag);
        if (strpos(self::$_cfg['flags'], $flag) === false)  self::$_cfg['flags'] .= $flag;
        
        switch($flag){
            case self::FLAG_JSON:
                self::$_response_format = $flag;
                break;
            case self::FLAG_PHP:
                self::$_response_format = $flag;
                break;
            default:
                
        }
       return $this;
  }
  
  public static function removeFlag($flag){
      self::$_cfg['flags'] = str_replace(strtoupper($flag), "", self::$_cfg['flags']);
  }
  public static function removeGFlag($flag){
      self::$_cfg['gflags'] = str_replace(strtoupper($flag), "", self::$_cfg['gflags']);
  }  
  public static function geocode($params=array()){
        if (is_string($params)){$params = array("q" => $params);}
         $attr = array_merge(self::$_cfg, $params);
         $url = self::buildUrl("/geocode", $attr) . sprintf("&appid=%s", self::$_appid); 
        switch(self::$_response_format){
            case self::FLAG_JSON:
               $result = self::getJSON($url);
               break;
            case self::FLAG_PHP:
                $result = unserialize(self::GET($url));
                break;
            default:
               $result = self::GET($url);
        }
        
        
        return $result;        
  }
  public static function reverse_geocode($lat, $lng){
        self::addGFlag(self::GFLAG_REVERSE_GEOCODE);
        $arr = array("location" => sprintf("%s %s", $lat, $lng));
        $res = self::geocode($arr);
        self::removeGFlag(self::GFLAG_REVERSE_GEOCODE);
        return $res;
    }
  public static function buildUrl($path, $data=array()){
    return sprintf("%s%s?%s", self::$_api_host , $path , http_build_query($data));
    
    }
   
  public static function configure($name, $value=null){
       if ($name == "appid") self::$_appid = $value;
       else self::$_cfg[$name] = $value;
      
  
  }
    
  public function find($params){
     return self::geocode($params);
     
 
  }

}
