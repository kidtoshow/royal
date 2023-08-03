<?php

namespace App\Http\Controllers\SHUANGMEI\MULTIFUNCTION;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class V1Controller extends Controller
{

    CONST ALL_POINT = '00';                     //讀取點位狀態
    CONST JUMP_TABLE_1 = '01';                  //讀取跳表1
    CONST JUMP_TABLE_2 = '02';                  //讀取跳表2
    CONST JUMP_TABLE_3 = '03';                  //讀取跳表3
    CONST JUMP_TABLE_4 = '04';                  //讀取跳表4
    CONST JUMP_TABLE_5 = '05';                  //讀取跳表5
    CONST JUMP_TABLE_6 = '06';                  //讀取跳表6

    CONST CLEAN_TABLE_1 = '07';                 //清除跳表1
    CONST CLEAN_TABLE_2 = '08';                 //清除跳表2
    CONST CLEAN_TABLE_3 = '09';                 //清除跳表3
    CONST CLEAN_TABLE_4 = '0A';                 //清除跳表4
    CONST CLEAN_TABLE_5 = '0B';                 //清除跳表5
    CONST CLEAN_TABLE_6 = '0C';                 //清除跳表6

    CONST OUTPUT_POINT_1_LO = '10';             //輸出點位1LO
    CONST OUTPUT_POINT_2_LO = '11';             //輸出點位2LO
    CONST OUTPUT_POINT_3_LO = '12';             //輸出點位3LO
    CONST OUTPUT_POINT_4_LO = '13';             //輸出點位4LO
    CONST OUTPUT_POINT_5_LO = '14';             //輸出點位5LO
    CONST OUTPUT_POINT_6_LO = '15';             //輸出點位6LO
    CONST OUTPUT_POINT_7_LO = '16';             //輸出點位7LO
    CONST OUTPUT_POINT_8_LO = '17';             //輸出點位8LO
    CONST OUTPUT_POINT_9_LO = '18';             //輸出點位9LO
    CONST OUTPUT_POINT_10_LO = '19';            //輸出點位10LO
    CONST OUTPUT_POINT_11_LO = '1A';            //輸出點位11LO
    CONST OUTPUT_POINT_12_LO = '1B';            //輸出點位12LO
    CONST OUTPUT_POINT_13_LO = '1C';            //輸出點位13LO
    CONST OUTPUT_POINT_14_LO = '1D';            //輸出點位14LO
    CONST OUTPUT_POINT_15_LO = '1E';            //輸出點位15LO
    CONST OUTPUT_POINT_16_LO = '1F';            //輸出點位16LO

    CONST OUTPUT_POINT_1_LO_HI = '20';          //(短按)輸出點位1 LO > 延遲 > HI
    CONST OUTPUT_POINT_2_LO_HI = '21';          //(短按)輸出點位2 LO > 延遲 > HI
    CONST OUTPUT_POINT_3_LO_HI = '22';          //(短按)輸出點位3 LO > 延遲 > HI
    CONST OUTPUT_POINT_4_LO_HI = '23';          //(短按)輸出點位4 LO > 延遲 > HI
    CONST OUTPUT_POINT_5_LO_HI = '24';          //(短按)輸出點位5 LO > 延遲 > HI
    CONST OUTPUT_POINT_6_LO_HI = '25';          //(短按)輸出點位6 LO > 延遲 > HI
    CONST OUTPUT_POINT_7_LO_HI = '26';          //(短按)輸出點位7 LO > 延遲 > HI
    CONST OUTPUT_POINT_8_LO_HI = '27';          //(短按)輸出點位8 LO > 延遲 > HI
    CONST OUTPUT_POINT_9_LO_HI = '28';          //(短按)輸出點位9 LO > 延遲 > HI
    CONST OUTPUT_POINT_10_LO_HI = '29';         //(短按)輸出點位10 LO > 延遲 > HI
    CONST OUTPUT_POINT_11_LO_HI = '2A';         //(短按)輸出點位11 LO > 延遲 > HI
    CONST OUTPUT_POINT_12_LO_HI = '2B';         //(短按)輸出點位12 LO > 延遲 > HI
    CONST OUTPUT_POINT_13_LO_HI = '2C';         //(短按)輸出點位13 LO > 延遲 > HI
    CONST OUTPUT_POINT_14_LO_HI = '2D';         //(短按)輸出點位14 LO > 延遲 > HI
    CONST OUTPUT_POINT_15_LO_HI = '2E';         //(短按)輸出點位15 LO > 延遲 > HI
    CONST OUTPUT_POINT_16_LO_HI = '2F';         //(短按)輸出點位16 LO > 延遲 > HI

    CONST OUTPUT_POINT_1_HI = '30';             //輸出點位1 HI
    CONST OUTPUT_POINT_2_HI = '31';             //輸出點位2 HI
    CONST OUTPUT_POINT_3_HI = '32';             //輸出點位3 HI
    CONST OUTPUT_POINT_4_HI = '33';             //輸出點位4 HI
    CONST OUTPUT_POINT_5_HI = '34';             //輸出點位5 HI
    CONST OUTPUT_POINT_6_HI = '35';             //輸出點位6 HI
    CONST OUTPUT_POINT_7_HI = '36';             //輸出點位7 HI
    CONST OUTPUT_POINT_8_HI = '37';             //輸出點位8 HI
    CONST OUTPUT_POINT_9_HI = '38';             //輸出點位9 HI
    CONST OUTPUT_POINT_10_HI = '39';            //輸出點位10 HI
    CONST OUTPUT_POINT_11_HI = '3A';            //輸出點位11 HI
    CONST OUTPUT_POINT_12_HI = '3B';            //輸出點位12 HI
    CONST OUTPUT_POINT_13_HI = '3C';            //輸出點位13 HI
    CONST OUTPUT_POINT_14_HI = '3D';            //輸出點位14 HI
    CONST OUTPUT_POINT_15_HI = '3E';            //輸出點位15 HI
    CONST OUTPUT_POINT_16_HI = '3F';            //輸出點位16 HI


    public function allStatus($ip, $port){
        logger('多功能卡第一版讀取點位狀態IP：'. $ip);
        $result = $this->execCmd(self::ALL_POINT, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            $stat1 = array_reverse(str_split($result->stat1));
            $stat2 = array_reverse(str_split($result->stat2));
            $status = array_merge($stat1, $stat2);
            return response()->json(['result' => 1, 'status' => $status, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function loadTable($ip, $port, $table){
        logger('多功能卡第一版讀取跳表'.$table.' IP：'. $ip);
        $result = $this->execCmd('0'.$table, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'value' => $result->data, 'message' => '執行讀取跳表'.$table.'成功']);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

    }

    public function clearTable($ip, $port, $table){
        logger('多功能卡第一版清除跳表'.$table.' IP：'. $ip);
        $result = $this->execCmd('0'.$table, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'message' => '執行清除跳表'.$table.'成功']);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function outputPointLo($ip, $port, $point){
        logger('多功能卡第一版輸出點位'.$point.'LO IP：'. $ip);
        $result = $this->execCmd('1'.dechex($point-1), $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'message' => '執行輸出點位'.$point.'LO 成功']);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function outputPointLoHi($ip, $port, $point, $unit){
        logger('多功能卡第一版輸出點位'.$point.'LO >> '. $unit . ' >> HI IP：'. $ip);
        $result = $this->execCmd('2'.dechex($point-1), $ip, sprintf('%02s', dechex($unit)));
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'message' => '執行輸出點位'.$point.'LO>'.$unit.'>HI 成功']);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function outputPointHi($ip, $port, $point) {
        logger('多功能卡第一版輸出點位'.$point.'HI IP：'. $ip);
        $result = $this->execCmd('3'.dechex($point-1), $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'message' => '執行輸出點位'.$point.'HI 成功']);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    private function execCmd($cmd, $ip, $opt='00')
    {
        return json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.$cmd.' --opt='. $opt .' --mfc '));
    }

    private function logRecord($object)
    {
        logger('error:'.$object->error);
        logger('info:'.$object->info);
        logger('data:'.$object->data);
        logger('cmd:'.$object->cmd);
        logger('stat1:'. $object->stat1);
        logger('stat2:'. $object->stat2.PHP_EOL);
    }
}
