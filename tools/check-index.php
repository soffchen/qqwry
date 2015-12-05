<?php
$r1_file='QQWry.zh_CN.GBK.Dat';
$r2_file='QQWry.zh_CN.UTF-8.Dat';


$r1_fp=fopen($r1_file,'rb');
$r2_fp=fopen($r2_file,'rb');
$index=fread($r1_fp,8);
$index=unpack('V2',$index);
$r1_index_start=$index[1];
$r1_index_end=$index[2];

$index=fread($r2_fp,8);
$index=unpack('V2',$index);
$r2_index_start=$index[1];
$r2_index_end=$index[2];

$r1_size=filesize($r1_file);
$r2_size=filesize($r2_file);

if ($r2_size!=$r2_index_end+7) {
    echo "index block is invalid(s:$r2_size,e:$r2_index_end)\n";
    exit(1);
}
if ((filesize($r2_file)-filesize($r1_file))!=($r2_index_start-$r1_index_start)) {
    echo "header or index block is invalid\n";
    exit(1);
}
    echo $r1_index_end-$r1_index_start;
if (($r1_index_end-$r1_index_start)!=($r2_index_end-$r2_index_start)) {
    echo "header is invalid\n";
}

for ($i=0;$i<10;$i++) {
}
echo "\n";
