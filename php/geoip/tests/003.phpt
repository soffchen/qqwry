--TEST--
Checking for database filename
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

var_dump( geoip_db_filename(GEOIP_COUNTRY_EDITION) );

?>
--EXPECTF--
string(%d) "%sGeoIP.dat"
