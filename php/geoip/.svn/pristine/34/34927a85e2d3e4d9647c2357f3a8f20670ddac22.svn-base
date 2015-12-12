--TEST--
Checking Country (Free) DB availability
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--POST--
--GET--
--FILE--
<?php

var_dump(geoip_db_avail(GEOIP_COUNTRY_EDITION));

?>
--EXPECT--
bool(true)
