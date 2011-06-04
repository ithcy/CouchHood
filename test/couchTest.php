<?php
# TODO: write some tests
require_once(dirname(__FILE__)."/../lib/"."couch.boot.php");

PlaceFinder::configure("appid", "a1aVjX7k");
PlaceFinder::addFlag(PlaceFinder::FLAG_NO_WOEID);
//PlaceFinder::addFlag(PlaceFinder::FLAG_BOUNDING_BOX);
$res = PlaceFinder::geocode(array("unit" => "2112" , "unittype" => "Apt", "house" => "2283", "street" =>"Primrose Lane", "city" => "Clearwater", "state" => "FL" , "postal" => "33763" ));

echo "Finding My House:\n";

print_r($res);

echo "\n===========================\n";

//$res = PlaceFinder::reverse_geocode(28.0266235, -82.7412951);

//echo "\n Reverse Geocoding\n";
//print_r($res);
echo "\n Done ========\n";

?>
