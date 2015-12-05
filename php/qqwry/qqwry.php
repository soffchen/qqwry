<?php
$br = (php_sapi_name() == "cli")? "":"<br>";
if(!extension_loaded('qqwry')) {
	dl('qqwry.' . PHP_SHLIB_SUFFIX);
}
$qqwry=new qqwry('/Users/surfchen/projects/qqwry/nali/php/QQWry.Dat');

list($addr1,$addr2)=$qqwry->q('127.0.0.1');
$addr1=iconv('GB2312','UTF-8',$addr1);
$addr2=iconv('GB2312','UTF-8',$addr2);
echo $addr1,'|',$addr2,"\n";

$arr=$qqwry->q('222.216.47.4');
$arr[0]=iconv('GB2312','UTF-8',$arr[0]);
$arr[1]=iconv('GB2312','UTF-8',$arr[1]);
echo $arr[0],'|',$arr[1],"\n";

$arr=$qqwry->q('64.233.187.99');
$arr[0]=iconv('GB2312','UTF-8',$arr[0]);
$arr[1]=iconv('GB2312','UTF-8',$arr[1]);
echo $arr[0],'|',$arr[1],"\n";
?>
