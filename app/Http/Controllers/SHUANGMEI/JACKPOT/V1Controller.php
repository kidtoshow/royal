<?php

namespace App\Http\Controllers\SHUANGMEI\JACKPOT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class V1Controller extends Controller
{
    CONST TURN_POINT = '23';     // 轉分
    CONST SCORE = '22';          // 得分
    CONST OPEN_POINT = '21';     // 開分
    CONST TURN_POINT_AMOUNT = '24';  // 轉數數量
    CONST LOAD_OPEN = '25'; // 讀取機台累計開分
    CONST LOAD_WASH = '26'; // 讀取機台累計洗分
    CONST LOAD_AUTO_STATUS = '20'; //讀取自動狀態

    CONST PUSH = '2E';         // push 需加 opt
    CONST PUSH_OPT = '01';
    CONST PUSH_OPT_2HZ = '02';
    CONST PUSH_OPT_5HZ = '03';
    CONST PUSH_OPT_STOP = '00';
    CONST TURN_DOWN = '48';      // 下轉
    CONST TURN_UP_100 = '49';    // 上轉 100
    //CONST TURN_UP_500 = '47';    // 上轉 500
    CONST START_STOP = '45';     // 開始\停止
    CONST START_1 = '46';          // 開始一次
    CONST STEEL_DOWN = '4B';   // 下珠
    CONST AUTO_TURN_UP = '4C'; // 自動上轉
    CONST WASH_ZERO = '43';      // 洗分歸零
    CONST WASH_REMAINDER = '44'; // 洗分餘數
    CONST OPEN_1 = '41';         // 開分1次
    CONST OPEN_10 = '42';        // 開分10次
    CONST REWARD_SWITCH = '2D';// 大賞燈切換
    CONST REWARD_SWITCH_OPT = '64';

    CONST OPEN_ANY_POINT = '4A';
    CONST ALL_UP_TURN = '4C';
    CONST ALL_DOWN_TURN = '47';
    CONST CLEAR_STATUS = '4F';
    CONST CLEAR_STATUS_OPT = 'FF';

    public function turnPoint($ip, $port)
    {
        logger('雙美鋼珠第一版轉分'. $ip);
        $result = $this->execCmd(self::TURN_POINT, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'turn_point' => $result->data, 'message' => $result->data]);
            // return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function Score($ip, $port)
    {
        logger('雙美鋼珠第一版得分'. $ip);
        $result = $this->execCmd(self::SCORE, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'score' => $result->data, 'message' => $result->data]);
            // return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function openPoint($ip, $port)
    {
        logger('雙美鋼珠第一版開分'. $ip);
        $result = $this->execCmd(self::OPEN_POINT, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'open_point' => $result->data, 'message' => $result->data]);
            // return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function getAllStatus($ip, $port)
    {
        logger('雙美鋼珠第一版一次取全部'. $ip);
        $result = $this->execCmd(self::TURN_POINT, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

        $score = $this->execCmd(self::SCORE, $ip);
        $this->logRecord($score);
        if($score->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $score->info]);
        }

        $open = $this->execCmd(self::OPEN_POINT, $ip);
        $this->logRecord($open);
        if($open->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $open->info]);
        }

        return response()->json(['result' => 1, 'turn_point' => $result->data, 'score' => $score->data, 'open_point' => $open->data, 'message' => '轉分：'. $result->data . '得分：' . $score->data . '開分：' . $open->data]);
        // return response()->json(['result' => 1, 'message' => '轉分：'. $result->data . '得分：' . $score->data . '開分：' . $open->data]);
    }

    public function turnPointAmount($ip, $port)
    {
        logger('雙美鋼珠第一版取得轉數數量狀態'. $ip);
        $result = $this->execCmd(self::TURN_POINT_AMOUNT, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'amount' => $result->data, 'message' => $result->data]);
        // return response()->json(['result' => 1, 'message' => $result->data]);
    }

    public function plcBigRewardStatus($ip, $port)
    {
        logger('雙美鋼珠第一版開獎狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'status' => (substr($result->stat2, 3, 1) == 1 ? 1 : 0 ), 'message' => substr($result->stat2, 3, 1)]);
        // return response()->json(['result' => 1, 'message' => $result->data]);
    }

    public function plcSmallRewardStatus($ip, $port)
    {
        logger('雙美鋼珠第一版連莊狀態'. $ip);
        $result = $this->execCmd(self::TURN_POINT_AMOUNT, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'status' => (substr($result->stat2, 2, 1) == 1 ?  1 : 0 ), 'message' => substr($result->stat2, 2, 1)]);
        // return response()->json(['result' => 1, 'message' => $result->data]);
    }

    public function push($ip, $port)
    {
        logger('雙美鋼珠第一版PUSH'. $ip);
        $result = $this->execCmd(self::PUSH, $ip, self::PUSH_OPT);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => 'push 執行成功']);
    }

    public function push2Hz($ip, $port)
    {
        logger('雙美鋼珠第一版PUSH2Hz'. $ip);
        $result = $this->execCmd(self::PUSH, $ip, self::PUSH_OPT_2HZ);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => 'push2hz執行成功']);
    }

    public function push5Hz($ip, $port)
    {
        logger('雙美鋼珠第一版PUSH5Hz'. $ip);
        $result = $this->execCmd(self::PUSH, $ip, self::PUSH_OPT_5HZ);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => 'push5hz執行成功']);
    }

    public function pushStop($ip, $port)
    {
        logger('雙美鋼珠第一版PUSH_STOP'. $ip);
        $result = $this->execCmd(self::PUSH, $ip, self::PUSH_OPT_STOP);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => 'push_stop執行成功']);
    }

    public function turnDown($ip, $port)
    {
        logger('雙美鋼珠第一版下轉'. $ip);
        $result = $this->execCmd(self::TURN_DOWN, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '下轉執行成功']);
    }

    public function turnUp100($ip, $port)
    {
        logger('雙美鋼珠第一版上轉100'. $ip);
        $result = $this->execCmd(self::TURN_UP_100, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '上轉100執行成功']);
    }

    public function turnUp500($ip, $port)
    {
        logger('雙美鋼珠第一版上轉500'. $ip);
        $result = $this->execCmd(self::TURN_UP_500, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '上轉500執行成功']);
    }

    public function startStop($ip, $port)
    {
        logger('雙美鋼珠第一版開始 停止'. $ip);
        $result = $this->execCmd(self::START_STOP, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '自動開始停止執行成功']);
    }

    public function start1($ip, $port)
    {
        logger('雙美鋼珠第一版開始一次'. $ip);
        $result = $this->execCmd(self::START_1, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '開始一次執行成功']);
    }

    public function steelDown($ip, $port)
    {
        logger('雙美鋼珠第一版下珠'. $ip);
        $result = $this->execCmd(self::STEEL_DOWN, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '下珠執行成功']);
    }

    public function washZero($ip, $port)
    {
        logger('雙美鋼珠第一版洗分清零'. $ip);
        $result = $this->execCmd(self::WASH_ZERO, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '洗分清零執行成功', 'data' => $result->data]);
    }

    public function washRemainder($ip, $port)
    {
        logger('雙美鋼珠第一版洗分餘數'. $ip);
        $result = $this->execCmd(self::WASH_REMAINDER, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '洗分餘數執行成功', 'data' => $result->data]);
    }

    public function open1($ip, $port)
    {
        logger('雙美鋼珠第一版開分一次'. $ip);
        $result = $this->execCmd(self::OPEN_1, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '開分1次執行成功']);
    }

    public function open10($ip, $port)
    {
        logger('雙美鋼珠第一版開分十次'. $ip);
        $result = $this->execCmd(self::OPEN_10, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '開分10次執行成功']);
    }

    public function rewardSwitch($ip, $port)
    {
        logger('雙美鋼珠第一版大賞燈切換'. $ip);
        $result = $this->execCmd(self::REWARD_SWITCH, $ip, self::REWARD_SWITCH_OPT);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '大賞燈切換執行成功']);
    }

    public function autoUpTurn($ip, $port)
    {
        logger('雙美鋼珠第一版自動上轉'. $ip);
        $result = $this->execCmd(self::AUTO_TURN_UP, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '自動上轉執行成功']);
    }

    public function clearStatus($ip, $port)
    {
        logger('雙美鋼珠第一版大賞燈切換'. $ip);
        $result = $this->execCmd(self::CLEAR_STATUS, $ip, self::CLEAR_STATUS_OPT);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '清除狀態執行成功']);
    }

    public function loadOpen($ip, $port)
    {
        logger('雙美鋼珠第一版讀取機台累計開分'. $ip);
        $result = $this->execCmd(self::LOAD_OPEN, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '讀取機台累計開分執行成功', 'data' => $result->data]);
    }

    public function loadWash($ip, $port)
    {
        logger('雙美鋼珠第一版讀取機台累計洗分'. $ip);
        $result = $this->execCmd(self::LOAD_WASH, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '讀取機台累計洗分執行成功', 'data' => $result->data]);
    }

    public function openTimes($ip, $port, $number)
    {
        logger('雙美鋼珠第一版開分幾次：'.$ip. '次數：'.$number);
        if($number > 5000) {
            logger('雙美鋼珠第一版開分'.$number.'次超過開分次數');
            return response(['result' => 0, 'message' => '超過開分次數']);
        }
        $ten_exec_times = intval($number/10);
        $ten_exec_count = 0;
        $one_exec_times = intval($number%10);
        $one_exec_count = 0;
        while($ten_exec_count < $ten_exec_times) {
            $result = json_decode($this->open10($ip, $port)->getContent());
            if($result->result === 1) {
                $ten_exec_count +=1;
            }
        }
        while($one_exec_count < $one_exec_times) {
            $result = json_decode($this->open1($ip, $port)->getContent());
            if($result->result === 1) {
                $one_exec_count +=1;
            }
        }

        return response()->json(['result' => 1, 'message' => '執行開分'.$number.'次成功']);
    }

    public function loadAutoStatus($ip, $port)
    {
        logger('雙美鋼珠第一版讀取自動狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '讀取自動狀態執行成功', 'data' => substr($result->stat2, 5,1)]);
    }

    public function loadCheckWashStatus($ip, $port)
    {
        logger('雙美鋼珠第一版洗分核准狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

        //檢查 stat2 第234567是否皆為1 若此狀態雙美卡異常
        if(array_sum(str_split($result->stat2)) === 7){
            return response()->json(['result' => 0, 'message' => '雙美卡狀態異常']);
        }

        //檢查 stat2 第3,4,5,6位元全0 才可以洗分
        $check = array_sum(str_split(substr($result->stat2, 2,4))) === 0?1:0;

        return response()->json(['result' => $check, 'message' => $check==1?'OK':'不允許洗分', 'data' => $check]);
    }

    public function grantApproval($ip, $port)
    {
        logger('雙美鋼珠第一版開贈核准'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        //檢查 stat2 第3,4,5,6位元全0 才可以洗分
        $check = array_sum(str_split(substr($result->stat2, 2,2))) === 0?1:0;
        if($check === 0) {
            return response()->json(['result' => 0, 'message' => '不允許開贈', 'data' => '']);
        }

        //檢查21 open_point 小於9才可以開贈
        $checkOpenPoint = $this->openPoint($ip, $port);
        $resultOpenPoint = json_decode($checkOpenPoint->getContent());
        if($resultOpenPoint->result != 1 || $resultOpenPoint->open_point >= 9) {
            return response()->json(['result' => 0, 'message' => '不允許開贈', 'data' => '']);
        }

        //檢查22 score 等於0才可以開贈
        $checkScore = $this->Score($ip, $port);
        $resultScore = json_decode($checkScore->getContent());
        if($resultScore->result!=1 || $resultScore->score != 0){
            return response()->json(['result' => 0, 'message' => '不允許開贈', 'data' => '']);
        }

        //檢查23 turn_point 等於0才可以開贈
        $checkTurnPoint = $this->turnPoint($ip, $port);
        $resultTurnPoint = json_decode($checkTurnPoint->getContent());
        if($resultTurnPoint->result != 1 || $resultTurnPoint->turn_point != 0) {
            return response()->json(['result' => 0, 'message' => '不允許開贈', 'data' => '']);
        }

        return response()->json(['result' => 1, 'message' => '允許開贈', 'data' => '']);
    }

    public function combineStatus($ip, $port){
        logger('雙美鋼珠第一版機台分數狀態'. $ip);
        //檢查21 open_point
        $checkOpenPoint = $this->openPoint($ip, $port);
        $resultOpenPoint = json_decode($checkOpenPoint->getContent());
        if($resultOpenPoint->result != 1) {
            return response()->json(['result' => 0, 'message' => '取得開分錯誤', 'data' => '']);
        }

        usleep(50);
        //檢查22 score
        $checkScore = $this->Score($ip, $port);
        $resultScore = json_decode($checkScore->getContent());
        if($resultScore->result!=1){
            return response()->json(['result' => 0, 'message' => '取得得分錯誤', 'data' => '']);
        }

        usleep(50);
        //檢查23 turn_point
        $checkTurnPoint = $this->turnPoint($ip, $port);
        $resultTurnPoint = json_decode($checkTurnPoint->getContent());
        if($resultTurnPoint->result != 1) {
            return response()->json(['result' => 0, 'message' => '取得轉分錯誤', 'data' => '']);
        }

        usleep(50);
        //檢查24 total_turn (amount)
        $checkTotalTurnPoint = $this->turnPointAmount($ip, $port);
        $resultTotalTurnPoint = json_decode($checkTotalTurnPoint->getContent());
        if($resultTotalTurnPoint->result != 1) {
            return response()->json(['result' => 0, 'message' => '取得總轉分錯誤', 'data' => '']);
        }

        return response()->json([
            'result' => 1,
            'open_point' => $resultOpenPoint->open_point,
            'score_point' => $resultScore->score,
            'turn_point' => $resultTurnPoint->turn_point,
            'total_turn' => $resultTotalTurnPoint->amount
        ]);
    }

    public function allUpTurn($ip, $port)
    {
        logger('雙美鋼珠第一版全數上轉：'. $ip);
        $result = $this->execCmd(self::ALL_UP_TURN, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response(['result' => 1, 'message' => '全數上轉執行成功']);
    }

    public function allDownTurn($ip, $port, $ratio)
    {
        logger('雙美鋼珠第一版全數下轉：'. $ip);
        $result = $this->execCmd(self::ALL_DOWN_TURN, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response(['result' => 1, 'message' => '全數下轉執行成功']);
    }

    public function checkLottery($ip, $port)
    {
        logger('雙美鋼珠第一版確認開獎連莊：'. $ip);
        $checkBigReward = $this->plcBigRewardStatus($ip, $port);
        $checkBigRewardResult = json_decode($checkBigReward->getContent());
        if($checkBigRewardResult->result != 1) {
            return response()->json(['result' => 1]);
        }

        $checkSmallReward = $this->plcSmallRewardStatus($ip, $port);
        $checkSmallRewardResult = json_decode($checkSmallReward->getContent());
        if($checkSmallRewardResult->result != 1) {
            return response()->json(['result' => 1]);
        }

        return response()->json(['result' => ($checkBigRewardResult->status | $checkSmallRewardResult->status)]);
    }

    public function openAnyPoint($ip, $port, $score) {
        logger('雙美鋼珠第一版開任意分'. $ip. '開'.$score.'分');
        $result = json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.self::OPEN_ANY_POINT.' --data='. $score));
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response(['result' => 1, 'message' => '開'.$score.'分執行成功']);
    }

    private function execCmd($cmd, $ip, $opt='00')
    {
        return json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.$cmd.' --opt='. $opt));
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
