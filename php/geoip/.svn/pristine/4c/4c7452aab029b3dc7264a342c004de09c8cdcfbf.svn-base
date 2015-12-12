--TEST--
Checking for custom directory INI entry
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--INI--
geoip.custom_directory="/test"
--FILE--
<?php

var_dump( geoip_db_filename(GEOIP_COUNTRY_EDITION) );

?>
--EXPECT--
string(15) "/test/GeoIP.dat"
