<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V120PakController extends Controller
{
    // 七段顯示器
    // 轉分
    CONST TURN_POINT = '02 03 00 01 00 02 95 F8';
    // 得分
    CONST SCORE = '02 03 00 03 00 02 34 38';
    // 開分
    CONST OPEN_POINT = '02 03 00 00 00 01 84 39';
    // 取得七段顯示轉分得分開分
    CONST GET_DISPLAY_ALL = '02 03 00 00 00 05 85 FA';

    // PLC
    // 取得轉分數量
    CONST TURN_POINT_AMOUNT = '01 04 00 D6 00 02 90 33';
    // 開獎狀態
    CONST BIG_REWARD_STATUS = '01 04 00 DE 00 01 51 F0';
    // 連莊狀態
    CONST SMALL_REWARD_STATUS = '01 04 00 E3 00 01 C0 3C';
    // PUSH
    CONST PUSH = '01 05 10 2C FF 00 49 33';
    // 下轉
    CONST DOWN_TURN = '01 05 10 54 FF 00 C9 2A';
    // 上轉
    CONST UP_TRUN = '01 05 10 5E FF 00 E9 28';
    // 啟動 停止
    CONST START_OR_STOP =  '01 05 10 68 FF 00 09 26';
    // 下分
    CONST SUB_POINT = '01 05 10 69 FF 00 58 E6';
    // 洗分
    // CONST WASH_POINT = '01 05 08 A1 FF 00 DF B8';
    CONST WASH_POINT_ON = '01 05 10 7E FF 00 E8 E2';
    CONST WASH_POINT_OFF = '01 05 10 7E 00 00 A9 12';
    // 設定開分數量
    CONST CONTROL_OPEN_POINT_AMOUNT = '01 06 00 F3 %04s';
    CONST CONTROL_OPEN_POINT_SPEED = '01 06 01 11 00 0F 98 37';
    // 開分
    CONST CONTROL_OPEN_POINT = '01 05 10 89 FF 00 59 10';
    // 大賞燈切換
    CONST REWARD_SWITCH = '01 05 10 04 FF 00 C9 3B';
    // 開洗分狀態
    CONST OPEN_POINT_STATUS = '01 04 00 D2 00 06 D0 31';

    // 開洗分歸零
    CONST OPEN_POINT_ZERO = '01 05 0F AA FF 00 AF 0E';
    // 轉數歸零
    CONST TURN_POINT_ZERO = '01 10 00 D6 00 02 04 00 00 00 00 7E D9';
    // CONST TURN_POINT_ZERO_1 = '01 06 00 D6 00 00 68 32';
    // CONST TURN_POINT_ZERO_2 = '01 06 00 D7 00 00 39 F2';
    // 下轉長按
    CONST PLC_DOWN_TURN_LONG = '01 05 10 55 FF 00 98 EA';
    // 上轉長按
    CONST PLC_UP_TURN_LONG = '01 05 10 5F FF 00 B8 E8';

    public function displayTurnPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::TURN_POINT));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_display_turn_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::TURN_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $turn_point = base_convert(substr($return,6,4), 16, 10) * 65536 + (base_convert(substr($return,10, 4), 16, 10));
                return response()->json(['result' => $result, 'turn_point' => $turn_point]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function displayScore($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SCORE));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_display_score');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SCORE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $score = base_convert(substr($return,6,4), 16, 10) * 65536 + (base_convert(substr($return,10, 4), 16, 10));
                return response()->json(['result' => $result, 'score' => $score]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function displayOpenPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_display_open_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $open_point = base_convert(substr($return,6,4), 16, 10) * 1;
                return response()->json(['result' => $result, 'open_point' => $open_point]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function displayAllStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_DISPLAY_ALL));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_display_get_all');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_DISPLAY_ALL);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $open_point = base_convert(substr($return,6,4), 16, 10) * 1 ;
                $turn_point = base_convert(substr($return,10,4), 16, 10) * 65536 + (base_convert(substr($return,14, 4), 16, 10));
                $score = base_convert(substr($return,18,4), 16, 10) * 65536 + base_convert(substr($return, 22, 4), 16, 10);
                return response()->json(['result' => $result, 'open_point' => $open_point, 'turn_point' => $turn_point, 'score' => $score]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcTurnPointAmount($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::TURN_POINT_AMOUNT));
            logger('v1_20pak_plc_turn_point_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::TURN_POINT_AMOUNT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result = 1;
                $count = 6;
                $amount = base_convert(substr($return, 6, 4), 16, 10) + (base_convert(substr($return, 10, 4), 16, 10) * 32768);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'amount' => $amount]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcBigRewardStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BIG_REWARD_STATUS));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_big_reward_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BIG_REWARD_STATUS);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $status = base_convert(substr($return, 6, 4), 16, 10) * 1;
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'status' => $status]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcSmallRewardStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SMALL_REWARD_STATUS));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_small_reward_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SMALL_REWARD_STATUS);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $status = base_convert(substr($return, 6, 4), 16, 10) * 1;
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'status' => $status]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcPush($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PUSH));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_push');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PUSH);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    // 下轉
    public function plcDownTurn($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_TURN));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_down_turn');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_TURN);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    // 上轉
    public function plcUpTurn($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::UP_TRUN));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_up_turn');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::UP_TRUN);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    // 啟動 停止
    public function plcStartOrStop($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::START_OR_STOP));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_start_stop');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::START_OR_STOP);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    // 下分
    public function plcSubPoint($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SUB_POINT));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_sub_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SUB_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    // 洗分
    public function plcWashPoint($ip, $port){
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_ON));
            logger('v1_20pak_plc_wash_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        if($result_on!=1) {
            return response()->json(['result' => $result_on]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_OFF));
            logger('v1_20pak_wash_point_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }

        return response()->json(['result' => $result_on&$result_off]);
    }

    // 設定開分數量
    public function plcControlOpenPointAmount($ip, $port, $amount){
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;
        $str = sprintf(self::CONTROL_OPEN_POINT_AMOUNT, base_convert($amount, 10, 16));
        $crc = Resource::calculateCrc($str.'0000');
        while(!$amount_result && $amount_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $str.$crc));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_open_point_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.$str.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $amount_result = 1;
                $amount_count = 6;
            } else {
                usleep(50000);
                $amount_count++;
                $amount_result = $return;
            }
        }
        if($amount_result != 1) {
            return response()->json(['result' => $amount_result]);
        }
        usleep(500000);
        while(!$speed_result && $speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::CONTROL_OPEN_POINT_SPEED));
            logger('v1_20pak_plc_open_point_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::CONTROL_OPEN_POINT_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $speed_result = 1;
                $speed_count = 6;
            } else {
                usleep(50000);
                $speed_count++;
                $speed_result = $return;
            }
        }
        if($speed_result != 1) {
            return response()->json(['result' => $speed_result]);
        }

        return response()->json(['result' => 1 ]);
    }
    // 開分
    public function plcControlOpenPoint($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::CONTROL_OPEN_POINT));
            logger('v1_20pak_plc_open_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::CONTROL_OPEN_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function rewardSwitch($ip, $port){
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::REWARD_SWITCH));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_reward_switch');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::REWARD_SWITCH);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcOpenPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_STATUS));
            logger('v1_20pak_open_point_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_STATUS);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $wash_point = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768 ) ;
                $open_point = base_convert(substr($return,14,4), 16, 10) + (base_convert(substr($return,18, 4), 16, 10) * 32768 );
                $turn_point = base_convert(substr($return,22,4), 16, 10) + (base_convert(substr($return, 26, 4), 16, 10) * 32768 );
                return response()->json(['result' => $result, 'wash_point' => $wash_point, 'open_point' => $open_point, 'turn_point' => $turn_point]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    //開洗分歸零
    public function openPointZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_ZERO));
            // $return = Resource::send_socket($ip, $port, self::OPEN_POINT_ZERO);
            logger('v1_20pak_open_point_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_ZERO);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    // 轉數歸零
    public function plcTurnPointZero($ip, $port){
        $off1_result = 0;
        $off1_count = 0;
        // $off2_result = 0;
        // $off2_count = 0;
        while(!$off1_result && $off1_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::TURN_POINT_ZERO));
            logger('v1_20pak_plc_turn_point_zero_1');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::TURN_POINT_ZERO);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            logger('check:'.$check);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $off1_result = 1;
                $off1_count = 6;
            } else {
                usleep(50000);
                $off1_count++;
                $off1_result = $return;
            }
        }
        if($off1_result != 1) {
            return response()->json(['result' => $off1_result]);
        }
        return response()->json(['result' => 1 ]);
    }

    // 下轉長按
    public function plcDownTurnLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PLC_DOWN_TURN_LONG));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_down_turn_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PLC_DOWN_TURN_LONG);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        return response()->json(['result' => $result]);
    }

    // 上轉長按
    public function plcUpTurnLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PLC_UP_TURN_LONG));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('v1_20pak_plc_up_turn_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PLC_UP_TURN_LONG);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcPakOpenPointVerify($ip, $port, $amount)
    {
        // logger('v1_20pak_plc_pak_open_point_verify');
        // return $this->pakOpenVerifyV2($ip, $port, $amount);

        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $handle = popen('/usr/bin/python3 /home/alex/pyScript/pak_open_point_verify.py --addr '.$ip.' --port '.$port.' --point '. intval($amount), 'r');
            $return = fread($handle, 2096);
            pclose($handle);
            logger('v1_20pak_open_point_verify');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('次數：'.$amount);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result:'.$result);
        return response()->json(['result' => $result]);
    }

    public function pakOpenVerifyV2($ip, $port, $amount)
    {
        logger('v1_20pak_open_verify_v2');
        logger('ip:'.$ip);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/pak_open_verify_v2.py --ip '.$ip.' --point '.$amount);
        logger('回傳值：'.$return);
        logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));

        return response()->json(['result' => $return]);
    }
}
