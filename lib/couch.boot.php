<?php

foreach(array(
   "TheNet",
   "CouchNet",
   "CouchDatabase",
   "CouchDocument",
   "CouchView") as $_curr){
  require_once(dirname(__FILE__)."/".$_curr.".php");
}
