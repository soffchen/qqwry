<?php
if ($_SERVER['argc']<3) {
    usage();
}
define('QQWRY_PATH',$_SERVER['argv'][1]);
if (!file_exists($_SERVER['argv'][1])) {
    usage();
}
//tow implementations in native php language,
//used them to compare and test extension
require $_SERVER['argv'][2];

$arr=array();
for ($i=0;$i<500;$i++) {
    $arr[]=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
}
$qqwry=new qqwry(QQWRY_PATH);
$coolcode=new  IpLocation(QQWRY_PATH);
initConvertIp(QQWRY_PATH);

echo "效率测试开始 ... ";
$times=array();

$t=microtime(true);
foreach ($arr as $ip) {
    convertip($ip);
}
$times[0]=microtime(true)-$t;

$t=microtime(true);
foreach ($arr as $ip) {
    $coolcode->getlocation($ip);
}
$times[1]=microtime(true)-$t;

$t=microtime(true);
foreach ($arr as $ip) {
    $qqwry->q($ip);
}
$times[2]=microtime(true)-$t;
echo "测试结束\n";
echo '是discuz的',$times[0]/$times[2],"倍\n";
echo '是coolcode的',$times[1]/$times[2],"倍\n";


echo "正确性测试 ... ";
foreach ($arr as $ip) {
    $s1=convertip($ip);
    $s2=implode('',$coolcode->getlocation($ip));
    $s3=implode('',$qqwry->q($ip));
    if ($s3!=$s1 || $s3!=$s2) {
        echo $ip,"\n";
        var_dump($s1);
        var_dump($s2);
        var_dump($s3);
        echo "错误发生\n";
        exit(1);
    }

    //echo '|';
    //var_dump(qqwry($ip,'QQWry.Dat'));
    //echo "\n";
}
echo "一切ok\n";

function usage() {
    echo "php test.php QQWry.Dat libqqwry.php\n";
    exit;
}
