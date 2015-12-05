<?php
$buffer = '';
$tmp_data_dir = './tmp_data_cn';
mkdir($tmp_data_dir);
$input_file = 'QQWry.zh_CN.GBK.Dat';
$output_file = 'QQWry.zh_CN.UTF-8.Dat';
define('RECORD_END',chr(0));
define('MODE_1',chr(1));
define('MODE_2',chr(2));

$input_fp=fopen($input_file,'rb');
$location_utf8_data='';
$total_larger=0;
$offset_larger = array();

if (!file_exists($tmp_data_dir.'/step1')) {
    $index=fread($input_fp,8);
    $index=unpack('V2',$index);
    $index_start=$index[1];
    $index_end=$index[2];
    echo 'index from ',$index_start,' to ',$index_end,"\n";
    fseek($input_fp,$index_start);
    $index_end_bottom=$index_end+7;
    while ($index_end_bottom!=ftell($input_fp)) {
        fseek($input_fp,4,SEEK_CUR);
        $data_indexes[]=current(unpack('V',fread($input_fp,3).chr(0)));
    }
    storeVar('index_start',$index_start);
    storeVar('index_end',$index_end);
    storeVar('data_indexes',$data_indexes);
    touch($tmp_data_dir.'/step1');
} else {
    echo "skip step 1\n";
    loadVar('index_start');
    loadVar('index_end');
    loadVar('data_indexes');
    $index_end_bottom=$index_end+7;
}


echo count($data_indexes)," indexes\n";

//strpos() is better than in_array()
$data_indexes_str='.'.implode('.',$data_indexes).'.';

if (!file_exists($tmp_data_dir.'/step2')) {
    fseek($input_fp,8,SEEK_SET);
    $i=0;
    while ($index_start>ftell($input_fp)) {
        $c=fread($input_fp,1);
        $buffer=$c;
        if (strpos($data_indexes_str,'.'.(ftell($input_fp)-1).'.')!==false) {
            $buffer.=fread($input_fp,3);
        } else if ($c==MODE_1 || $c==MODE_2) {
            $buffer.=fread($input_fp,3);
        } else {
            while (ord($c=fread($input_fp,1))>0) {
                $buffer.=$c;
            }
            if ($c!=RECORD_END) {
                echo "fread error\n";
                exit(1);
            }
            $buffer_old_len=strlen($buffer);
            $buffer=iconv('GBK','UTF-8',$buffer);
            $total_larger += (strlen($buffer)-$buffer_old_len);
            $offset_larger[ftell($input_fp)]=$total_larger;
            //file_put_contents('tmp2.zh.utf8',$buffer."\n",FILE_APPEND);
            $buffer.=$c;
        }
        $l=strlen($buffer);
        $i++;
        echo "\r",$i,'-------';
        $location_utf8_data.=$buffer;
    }
    //exit;
    file_put_contents($tmp_data_dir.'/location_utf8_data',$location_utf8_data);
    storeVar('total_larger',$total_larger);
    storeVar('offset_larger',$offset_larger);
    touch($tmp_data_dir.'/step2');
} else {
    echo "skip step 2\n";
    $location_utf8_data=file_get_contents($tmp_data_dir.'/location_utf8_data');
    loadVar('total_larger');
    loadVar('offset_larger');
}
echo "\ntotal larger:",$total_larger,"\n";

$output_fp=fopen($output_file,'ab');

if (!file_exists($tmp_data_dir.'/step3')) {
    echo 'patching header ... ';
    fwrite($output_fp,pack('V',$index_start+$total_larger));
    fwrite($output_fp,pack('V',$index_end+$total_larger));
    echo "ok\n";
    touch($tmp_data_dir.'/step3');
} else {
    echo "skip step 3\n";
}

/*
    patching index block must before patching data block.

    because we must get new indexes data 
    to check a point in data block such as 0x01 is a redirect mod mark or a part of a ip.

    Of course I can get these data during step 2 
    if I create a new data indexes array.
    As you guys knows, large array is a bullshit. 
*/
if (!file_exists($tmp_data_dir.'/step4')) {
    echo 'patching index block (+',$total_larger,')... ';
    $i=0;
    fseek($input_fp,$index_start);
    $buffer='';
    while ($index_end_bottom!=ftell($input_fp)) {
        $buffer .= fread($input_fp,4);

        $f_offset=$data_indexes[$i];
        $f_offset_new=$f_offset;
        for ($n=0;$n<=$f_offset;$n++) {
            if (isset($offset_larger[$f_offset-$n])) {
                $f_offset_new+=$offset_larger[$f_offset-$n];
                break;
            }
        }
        if ($f_offset_new===$f_offset) {
            echo 'warning: new offset','===old offset(',$f_offset,') i:',$i,"\n";
        } else {
            /*
                update data_indexes  
                which will be used to check 
                whether a point in data block is the start of a ip
            */
            $data_indexes[$i]=$f_offset_new;
        }

        $buffer .= substr(pack('V',$f_offset_new),0,3);
        fseek($input_fp,3,SEEK_CUR);
        $i++;
    }
    file_put_contents($tmp_data_dir.'/index_block',$buffer);
    storeVar('data_indexes',$data_indexes);
    echo "ok\n";
    touch($tmp_data_dir.'/step4');
} else {
    loadVar('data_indexes');
    $buffer=file_get_contents($tmp_data_dir.'/index_block');
    echo "skip step 4\n";
}

//strpos() is better than in_array()
$data_indexes_str='.'.implode('.',$data_indexes).'.';
unset($data_indexes);

if (!file_exists($tmp_data_dir.'/step5')) {
    echo 'patching data block ... ';
    $f_offset=0;
    for ($i=0;$i<strlen($location_utf8_data);$i++) {
        $c=$location_utf8_data[$i];

        /*   
            check if is a 4 bytes ip. 
            (header is 8 bytes, so $i+8 is file offset .)
        */
        if (strpos($data_indexes_str,'.'.($i+8).'.')!==false) {
            echo "skip 001--------\n";
            /*echo $i+9;
            echo "\n";*/
            $i+=3;
        } else if ($c==MODE_1 || $c==MODE_2) {
            echo "skip 002--------\n";
            echo 'mode',ord($c),"\n";
            $f_offset=current(unpack('V',
                    $location_utf8_data[$i+1]
                    .$location_utf8_data[$i+2]
                    .$location_utf8_data[$i+3]
                    .chr(0)
                    ));
            $f_offset_new=$f_offset;
            for ($n=0;$n<=$f_offset;$n++) {
                if (isset($offset_larger[$f_offset-$n])) {
                    $f_offset_new+=$offset_larger[$f_offset-$n];
                    break;
                }
            }
            if ($f_offset_new===$f_offset) {
                echo 'warning: new offset','===old offset(',$f_offset,') i:',$i,"\n";
            }
            $f_offset_new=pack('V',$f_offset_new);
            $location_utf8_data[$i+1] = $f_offset_new[0];
            $location_utf8_data[$i+2] = $f_offset_new[1];
            $location_utf8_data[$i+3] = $f_offset_new[2];
            $i+=3;
        } else {
            /*echo "skip 003--------\n";*/
            while ($location_utf8_data[$i]!=RECORD_END) {
                echo ord($location_utf8_data[$i]),',';
                //echo $location_utf8_data[$i];
                $i++;
            }
            echo "\n";
        }
        sleep(1);
    }
    fwrite($output_fp,$location_utf8_data);
    echo "ok\n";
    touch($tmp_data_dir.'/step5');
} else {
    echo "skip step 5\n";
}

unset($location_utf8_data);
fwrite($output_fp,$buffer);


function storeVar($var,$val) {
    global $tmp_data_dir;
    file_put_contents($tmp_data_dir.'/'.$var,'<?php  $'.$var.'='.var_export($val,true).';');
}
function loadVar($var) {
    global $$var;
    global $tmp_data_dir;
    include $tmp_data_dir.'/'.$var;
}
