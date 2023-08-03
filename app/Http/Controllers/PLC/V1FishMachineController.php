<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V1FishMachineController extends Controller
{
    CONST UP_SINGLE = '01 05 08 64 FF 00 CF 85';
    CONST DOWN_SINGLE = '01 05 08 6E FF 00 EF 87';
    CONST DOWN_DOUBLE = '01 05 08 71 FF 00 DE 41';
    CONST DOWN_FIVE_ON = '01 05 08 70 FF 00 8F 81';
    CONST DOWN_FIVE_OFF = '01 05 08 70 00 00 CE 71';
    CONST DOWN_ON = '01 05 08 70 FF 00 8F 81';
    CONST DOWN_OFF = '01 05 08 70 00 00 CE 71';
    CONST SHOOT_ON = '01 05 08 98 FF 00 0F B5';
    CONST SHOOT_OFF = '01 05 08 98 00 00 4E 45';
    CONST LEFT_SINGLE = '01 05 08 7B FF 00 FE 43';
    CONST RIGHT_SINGLE = '01 05 08 85 FF 00 9F B3';
    CONST STAKE_POINT = '01 05 08 8F FF 00 BF B1';
    CONST SHOOT_SINGLE = '01 05 08 96 FF 00 6E 76';
    CONST SHOOT_FIVE_ON = '01 05 08 98 FF 00 0F B5';
    CONST SHOOT_FIVE_OFF = '01 05 08 98 00 00 4E 45';
    CONST OPEN_POINT_AMOUNT = '01 06 00 EC %04s';
    CONST OPEN_POINT_SPEED = '01 06 00 F6 00 0A E9 FF';
    CONST OPEN_POINT_EXEC = '01 05 08 A3 FF 00 7E 78';
    CONST WASH_POINT_ON = '01 05 08 AC FF 00 4E 7B';
    CONST WASH_POINT_OFF = '01 05 08 AC 00 00 0F 8B';

    CONST DOWN_AMOUNT = '01 06 00 E7 00 02 B8 3C';
    CONST DOWN_SPEED = '01 06 00 F1 00 02 59 F8';
    CONST LEFT_AMOUNT = '01 06 00 E8 00 01 C8 3E';
    CONST LEFT_SPEED = '01 06 00 F2 00 06 A8 3B';
    CONST RIGHT_AMOUNT = '01 06 00 E9 00 01 99 FE';
    CONST RIGHT_SPEED = '01 06 00 F3 00 06 F9 FB';
    CONST STAKE_AMOUNT = '01 06 00 EA 00 01 69 FE';
    CONST STAKE_SPEED = '01 06 00 F4 00 0A 48 3F';


    public function calculateCrc($string)
    {
        $string = str_replace(' ', '', $string);
        
        $calculate = substr($string, 0, -4);

        return Resource::crc16($calculate);
    }

    public function upSingle($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::UP_SINGLE));
            logger('v1_fish_up_single');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::UP_SINGLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function downSingle($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_SINGLE));
            logger('v1_fish_down_single');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_SINGLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function downDouble($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_DOUBLE));
            logger('v1_fish_down_double');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_DOUBLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        if($result!= 1) {
            return response()->json(['result' => $result]);
        }
        // usleep(700000);
        // $result = 0;
        // $count = 0;
        // while (!$result && $count < 5){
        //     $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_SINGLE));
        //     logger('v1_fish_down_double_2');
        //     logger('ip:'.$ip);
        //     logger('port:'.$port);
        //     logger('指令：'.self::DOWN_SINGLE);
        //     logger('回傳值：'.$return);
        //     logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
        //     $check = $this->calculateCrc($return);
        //     if (strcasecmp($check, substr($return, -4, 4)) === 0) {
        //         $result = 1;
        //         $count = 6;
        //     } else {
        //         usleep(50000);
        //         $count++;
        //         $result = $return;
        //     }
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function downFive($ip, $port) {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while (!$result_on && $count_on < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_FIVE_ON));
            logger('v1_fish_down_five_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_FIVE_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('down_five_on_result：'.$result_on.PHP_EOL);
        if($result_on!= 1) {
            return response()->json(['result' => $result_on]);
        }
        usleep(5000000);
        while (!$result_off && $count_off < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_FIVE_OFF));
            logger('v1_fish_down_five_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_FIVE_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('down_five_off_result：'.$result_off.PHP_EOL);
        logger('result：'.$result_on & $result_off.PHP_EOL);
        return response()->json(['result' => $result_on & $result_off]);
    }

    public function shootFive($ip, $port) {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while (!$result_on && $count_on < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SHOOT_FIVE_ON));
            logger('v1_fish_shoot_five_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SHOOT_FIVE_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('down_five_on_result：'.$result_on.PHP_EOL);
        if($result_on!= 1) {
            return response()->json(['result' => $result_on]);
        }
        usleep(5000000);
        while (!$result_off && $count_off < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SHOOT_FIVE_OFF));
            logger('v1_fish_shoot_five_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SHOOT_FIVE_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('down_five_off_result：'.$result_off.PHP_EOL);
        logger('result：'.$result_on & $result_off.PHP_EOL);
        return response()->json(['result' => $result_on & $result_off]);
    }

    public function washPoint($ip, $port) {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while (!$result_on && $count_on < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_ON));
            logger('v1_fish_wash_point_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('down_five_on_result：'.$result_on.PHP_EOL);
        if($result_on!= 1) {
            return response()->json(['result' => $result_on]);
        }
        usleep(5000000);
        while (!$result_off && $count_off < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_OFF));
            logger('v1_fish_wash_point_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('down_five_off_result：'.$result_off.PHP_EOL);
        logger('result：'.$result_on & $result_off.PHP_EOL);
        return response()->json(['result' => $result_on & $result_off]);
    }

    public function leftSingle($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_SINGLE));
            logger('v1_fish_left_single');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_SINGLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function rightSingle($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_SINGLE));
            logger('v1_fish_right_single');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_SINGLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function stakePoint($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_POINT));
            logger('v1_fish_stake_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAKE_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function shootSingle($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SHOOT_SINGLE));
            logger('v1_fish_shoot_single');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SHOOT_SINGLE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function openPoint($ip, $port, $amount)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;
        $exec_result = 0;
        $exec_count = 0;
        $down_amount_result = 0;
        $down_amount_count = 0;
        $down_speed_result = 0;
        $down_speed_count = 0;
        $left_amount_result = 0;
        $left_amount_count = 0;
        $left_speed_result = 0;
        $left_speed_count = 0;
        $right_amount_result = 0;
        $right_amount_count = 0;
        $right_speed_result = 0;
        $right_speed_count = 0;
        $stake_amount_result = 0;
        $stake_amount_count = 0;
        $stake_speed_result = 0;
        $stake_speed_count = 0;

        $string = sprintf(self::OPEN_POINT_AMOUNT,dechex($amount));
        $check_sum = $this->calculateCrc($string.'0000');
        $string .= $check_sum;
        while(!$amount_result && $amount_count < 5) {
            logger('指令：'.$string);
            logger('crc值:'. $check_sum);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $string));
            logger('v1_fish_open_point_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $amount_result = 1;
                $amount_count = 6;
            } else {
                usleep(50000);
                $amount_count++;
                $amount_result = $return;
            }
        }
        logger('amount_result:'.$amount_result);
        if($amount_result != 1) {
            return response()->json(['result' => $amount_result]);
        }
        usleep(50000);
        while(!$speed_result && $speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_SPEED));
            logger('v1_fish_open_point_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $speed_result = 1;
                $speed_count = 6;
            } else {
                usleep(50000);
                $speed_count++;
                $speed_result = $return;
            }
        }
        logger('speed_result:'.$speed_result);
        if($speed_result != 1) {
            return response()->json(['result' => $speed_result]);
        }
        usleep(50000);
        while(!$exec_result && $exec_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_EXEC));
            logger('v1_fish_open_point_exec');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_EXEC);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $exec_result = 1;
                $exec_count = 6;
            } else {
                usleep(50000);
                $exec_count++;
                $exec_result = $return;
            }
        }
        logger('exec_result:'.$exec_result);
        usleep(50000);
        while(!$down_amount_result && $down_amount_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_AMOUNT));
            logger('v1_fish_down_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_AMOUNT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $down_amount_result = 1;
                $down_amount_count = 6;
            } else {
                usleep(50000);
                $down_amount_count++;
                $down_amount_result = $return;
            }
        }
        if($down_amount_result != 1) {
            return response()->json(['result' => $down_amount_result]);
        }
        usleep(50000);
        while(!$down_speed_result && $down_speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_SPEED));
            logger('v1_fish_down_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $down_speed_result = 1;
                $down_speed_count = 6;
            } else {
                usleep(50000);
                $down_speed_count++;
                $down_speed_result = $return;
            }
        }
        if($down_speed_result != 1) {
            return response()->json(['result' => $down_speed_result]);
        }
        usleep(50000);
        while(!$left_amount_result && $left_amount_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_AMOUNT));
            logger('v1_fish_left_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_AMOUNT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $left_amount_result = 1;
                $left_amount_count = 6;
            } else {
                usleep(50000);
                $left_amount_count++;
                $left_amount_result = $return;
            }
        }
        usleep(50000);
        while(!$left_speed_result && $left_speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_SPEED));
            logger('v1_fish_left_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $left_speed_result = 1;
                $left_speed_count = 6;
            } else {
                usleep(50000);
                $left_speed_count++;
                $left_speed_result = $return;
            }
        }
        usleep(50000);
        while(!$right_amount_result && $right_amount_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_AMOUNT));
            logger('v1_fish_right_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_AMOUNT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $right_amount_result = 1;
                $right_amount_count = 6;
            } else {
                usleep(50000);
                $right_amount_count++;
                $right_amount_result = $return;
            }
        }
        usleep(50000);
        while(!$right_speed_result && $right_speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_SPEED));
            logger('v1_fish_right_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $right_speed_result = 1;
                $right_speed_count = 6;
            } else {
                usleep(50000);
                $right_speed_count++;
                $right_speed_result = $return;
            }
        }
        usleep(50000);
        while(!$stake_amount_result && $stake_amount_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_AMOUNT));
            logger('v1_fish_stake_amount');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAKE_AMOUNT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $stake_amount_result = 1;
                $stake_amount_count = 6;
            } else {
                usleep(50000);
                $stake_amount_count++;
                $stake_amount_result = $return;
            }
        }
        usleep(50000);
        while(!$stake_speed_result && $stake_speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_SPEED));
            logger('v1_fish_stake_speed');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAKE_SPEED);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $stake_speed_result = 1;
                $stake_speed_count = 6;
            } else {
                usleep(50000);
                $stake_speed_count++;
                $stake_speed_result = $return;
            }
        }
        
        
        return response()->json(['result' => $amount_result & $speed_result & $exec_result]);
    }

    public function downOn($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_ON));
            logger('v1_fish_down_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function downOff($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::DOWN_OFF));
            logger('v1_fish_down_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::DOWN_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function shootOn($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SHOOT_ON));
            logger('v1_fish_shoot_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SHOOT_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function shootOff($ip, $port) {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SHOOT_OFF));
            logger('v1_fish_shoot_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SHOOT_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
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

    public function getScore($ip, $port)
    {
        logger('v1_fish_get_score');
        logger('ip:'.$ip);
        logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
        $return = exec('/usr/bin/python3 /home/alex/fish_infer/send_socket_fish.py --ip='.$ip);
        logger('回傳值：'.$return);
        logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));

        return response()->json(json_decode($return, true));
    }
}
