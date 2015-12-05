<?php
$buffer = '';
$data_file = 'QQWry.zh_CN.UTF-8.Dat';
$out = 'location';
$input_fp=fopen($data_file,'rb');
define('RECORD_END',chr(0));
define('MODE_1',chr(1));
define('MODE_2',chr(2));

$data_indexes=array();
$index=fread($input_fp,8);
$index=unpack('V2',$index);
$index_start=$index[1];
$index_end=$index[2];

fseek($input_fp,$index_start);
while ($index_end!=ftell($input_fp)) {
    fseek($input_fp,4,SEEK_CUR);
    $data_indexes[]=current(unpack('V',fread($input_fp,3).chr(0)));
}
echo count($data_indexes)," indexes\n";
fseek($input_fp,8);
while ($index_start>ftell($input_fp)) {
    //echo "step 0 ... ";
    $c=fread($input_fp,1);
    $buffer=$c;
    if (in_array(ftell($input_fp)-1,$data_indexes)) {
        fseek($input_fp,3,SEEK_CUR);
    } else if ($c==MODE_1 || $c==MODE_2) {
        fseek($input_fp,3,SEEK_CUR);
    } else {
        while (ord($c=fread($input_fp,1))>0) {
            $buffer.=$c;
        }
        if ($c!=RECORD_END) {
            echo "fread error\n";
            exit(1);
        }
        file_put_contents($out,$buffer."\n",FILE_APPEND);
    }
}
echo "ok\n";
