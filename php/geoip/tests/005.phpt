--TEST--
Checking for out of bound type with geoip_db_avail()
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

geoip_db_avail(-1000);
geoip_db_avail(1000);

?>
--EXPECTF--
Warning: geoip_db_avail(): Database type given is out of bound. in %s on line %d

Warning: geoip_db_avail(): Database type given is out of bound. in %s on line %d

