--TEST--
Checking geoip_region_name_by_code with (some) empty fields
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--POST--
--GET--
--FILE--
<?php

var_dump(geoip_region_name_by_code('CA',''));
var_dump(geoip_region_name_by_code('CA',NULL));
var_dump(geoip_region_name_by_code('CA',1));
var_dump(geoip_region_name_by_code('','QC'));
var_dump(geoip_region_name_by_code(NULL,'QC'));
var_dump(geoip_region_name_by_code(1,'QC'));

?>
--EXPECTF--

Warning: geoip_region_name_by_code(): You need to specify the country and region codes. in %s on line %d
bool(false)

Warning: geoip_region_name_by_code(): You need to specify the country and region codes. in %s on line %d
bool(false)
bool(false)

Warning: geoip_region_name_by_code(): You need to specify the country and region codes. in %s on line %d
bool(false)

Warning: geoip_region_name_by_code(): You need to specify the country and region codes. in %s on line %d
bool(false)
bool(false)
