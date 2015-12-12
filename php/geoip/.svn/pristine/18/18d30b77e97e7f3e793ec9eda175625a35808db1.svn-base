--TEST--
Checking geoip_region_name_by_code for Canada/Quebec
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--POST--
--GET--
--FILE--
<?php

var_dump(geoip_region_name_by_code('CA','QC'));

?>
--EXPECT--
string(6) "Quebec"
