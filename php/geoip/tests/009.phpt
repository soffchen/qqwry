--TEST--
Calling geoip_db_avail() with a non-existant database type within bound.
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

var_dump( geoip_db_avail(0) );

?>
--EXPECT--
bool(false)
