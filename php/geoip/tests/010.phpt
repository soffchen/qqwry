--TEST--
Checking for out of bound type with geoip_database_info()
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

geoip_database_info(-1000);
geoip_database_info(1000);

?>
--EXPECTF--
Warning: geoip_database_info(): Database type given is out of bound. in %s on line %d

Warning: geoip_database_info(): Database type given is out of bound. in %s on line %d

