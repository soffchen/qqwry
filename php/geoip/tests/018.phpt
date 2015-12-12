--TEST--
Checking geoip_setup_custom_directory() (with trailing slash)
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--INI--
geoip.custom_directory="/test/"
--FILE--
<?php

geoip_setup_custom_directory('/some/other/place');
var_dump( geoip_db_filename(GEOIP_COUNTRY_EDITION) );
var_dump( ini_get('geoip.custom_directory') );

?>
--EXPECT--
string(27) "/some/other/place/GeoIP.dat"
string(6) "/test/"