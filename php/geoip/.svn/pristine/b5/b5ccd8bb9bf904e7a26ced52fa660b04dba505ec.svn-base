--TEST--
Checking if all db info and name match
--SKIPIF--
<?php if (!extension_loaded("geoip")) print "skip"; ?>
--FILE--
<?php

$t = geoip_db_get_all_info();

var_dump( $t[GEOIP_COUNTRY_EDITION]['description'] );
var_dump( $t[GEOIP_REGION_EDITION_REV0]['description'] );
var_dump( $t[GEOIP_CITY_EDITION_REV0]['description'] );
var_dump( $t[GEOIP_ORG_EDITION]['description'] );
var_dump( $t[GEOIP_ISP_EDITION]['description'] );
var_dump( $t[GEOIP_CITY_EDITION_REV1]['description'] );
var_dump( $t[GEOIP_REGION_EDITION_REV1]['description'] );
var_dump( $t[GEOIP_PROXY_EDITION]['description'] );
var_dump( $t[GEOIP_ASNUM_EDITION]['description'] );
var_dump( $t[GEOIP_NETSPEED_EDITION]['description'] );
var_dump( $t[GEOIP_DOMAIN_EDITION]['description'] );

?>
--EXPECT--
string(21) "GeoIP Country Edition"
string(27) "GeoIP Region Edition, Rev 0"
string(25) "GeoIP City Edition, Rev 0"
string(26) "GeoIP Organization Edition"
string(17) "GeoIP ISP Edition"
string(25) "GeoIP City Edition, Rev 1"
string(27) "GeoIP Region Edition, Rev 1"
string(19) "GeoIP Proxy Edition"
string(19) "GeoIP ASNum Edition"
string(22) "GeoIP Netspeed Edition"
string(25) "GeoIP Domain Name Edition"
