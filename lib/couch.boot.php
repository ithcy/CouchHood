<?php

foreach(array(
   "TheNet",
   "CouchNet",
   "CouchDatabase",
   "CouchDocument",
   "CouchView",
   "CouchAttachment",
   "ApiService",
   "PlaceFinder"
   ) as $_curr){
  require_once(dirname(__FILE__)."/".$_curr.".php");
}
