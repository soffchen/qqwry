--TEST--
Calling geoip_database_info() with a non-existant database type within bound.
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

geoip_database_info(0);

?>
--EXPECTF--
Warning: geoip_database_info(): Required database not available. in %s on line %d
