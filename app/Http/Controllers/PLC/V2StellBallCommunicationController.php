<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V2StellBallCommunicationController extends Controller
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
    CONST PUSH = '01 05 08 6E FF 00 EF 87';
    // 下轉
    CONST DOWN_TURN = '01 05 08 82 FF 00 2E 72';
    // 上轉
    CONST UP_TRUN = '01 05 08 8C FF 00 4F B1';
    // 啟動 停止
    CONST START_OR_STOP =  '01 05 08 96 FF 00 6E 76';
    // 下分
    CONST SUB_POINT = '01 05 08 97 FF 00 3F B6';
    // 洗分
    CONST WASH_POINT = '01 05 08 A1 FF 00 DF B8';
    // CONST WASH_POINT_ON = '01 05 08 A2 FF 00 2F B8';
    // CONST WASH_POINT_OFF = '01 05 08 A2 00 00 6E 48';
    // 設定開分數量
    CONST CONTROL_OPEN_POINT_AMOUNT = '01 06 00 ED %04s';
    CONST CONTROL_OPEN_POINT_SPEED = '01 06 00 F7 00 0F 78 3C';
    // 開分
    CONST CONTROL_OPEN_POINT = '01 05 08 AD FF 00 1F BB';
    // 大賞燈切換
    CONST REWARD_SWITCH = '01 05 08 64 FF 00 CF 85';
    // 開洗分狀態
    CONST OPEN_POINT_STATUS = '01 04 00 D2 00 06 D0 31';
    // 下轉長按
    CONST PLC_DOWN_TURN_LONG = '01 05 08 83 FF 00 7F B2';

    public function calculateCrc($string)
    {
        $string = str_replace(' ', '', $string);
        
        $calculate = substr($string, 0, -4);

        return Resource::crc16($calculate);
    }

    public function displayTurnPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::TURN_POINT));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_display_turn_point');
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
            logger('stell_ball_display_score');
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
            logger('stell_ball_display_open_point');
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
            logger('stell_ball_display_get_all');
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
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_plc_turn_point_amount');
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
            logger('stell_ball_plc_big_reward_status');
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
            logger('stell_ball_plc_small_reward_status');
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
            logger('stell_ball_plc_push');
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
            logger('stell_ball_plc_down_turn');
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
            logger('stell_ball_plc_up_turn');
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
            logger('stell_ball_plc_start_stop');
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
            logger('stell_ball_plc_sub_point');
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
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_plc_wash_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT);
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
        return response()->json(['result' => $result_on]);
        // if($result_on!=1) {
        //     return response()->json(['result' => $result_on]);
        // }
        // usleep(5000000);
        // while(!$result_off && $count_off < 5) {
        //     $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_OFF));
        //     // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
        //     logger('stell_ball_plc_wash_point_off');
        //     logger('ip:'.$ip);
        //     logger('port:'.$port);
        //     logger('指令：'.self::WASH_POINT_OFF);
        //     logger('回傳值：'.$return);
        //     logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
        //     $check = Resource::calculateCrc($return);
        //     if (strcasecmp($check, substr($return, -4, 4)) === 0 ) {
        //         $result_off = 1;
        //         $count_off = 6;
        //     } else {
        //         usleep(50000);
        //         $count_off++;
        //         $result_off = $return;
        //     }
        // }

        // return response()->json(['result' => $result_on&$result_off]);
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
            logger('stell_ball_plc_open_point_amount');
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
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_plc_open_point_speed');
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
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_plc_open_point');
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
            logger('stell_ball_reward_switch');
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

    public function plcPakOpenPointVerify($ip, $port, $amount)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $handle = popen('/usr/bin/python3 /home/alex/pyScript/pak_open_point_verify.py --addr '.$ip.' --port '.$port.' --point '. intval($amount), 'r');
            $return = fread($handle, 2096);
            pclose($handle);
            logger('stell_ball_pak_open_point_verify');
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

    public function plcOpenPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_STATUS));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_open_point_status');
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
                $turn_point = base_convert(substr($return,22,4), 16, 10) + (base_convert(substr($return, 26, 4), 16, 10) * 32768);
                return response()->json(['result' => $result, 'wash_point' => $wash_point, 'open_point' => $open_point, 'turn_point' => $turn_point]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function plcDownTurnLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PLC_DOWN_TURN_LONG));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('stell_ball_plc_down_turn_long');
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
}
