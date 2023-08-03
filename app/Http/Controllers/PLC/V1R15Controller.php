<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V1R15Controller extends Controller
{
    CONST OPEN_POINT = '01 06 00 EF %04s';
    CONST OPEN_POINT_SPEED = '01 06 01 0D 00 0A 99 F2';
    CONST OPEN_POINT_CONTROL = '01 05 10 61 FF 00 D9 24';
    // 開洗分 所有壓得計分 大獎與小獎狀態紀錄
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    CONST WASH_POINT_CONTROL = '01 05 10 69 FF 00 58 E6';
    CONST STOP_1 = '01 05 10 04 FF 00 C9 3B';
    CONST STOP_2 = '01 05 10 0E FF 00 E9 39';
    CONST STOP_3 = '01 05 10 18 FF 00 08 FD';
    CONST STOP_4 = '01 05 10 22 FF 00 28 F0';
    CONST STOP_5 = '01 05 10 2C FF 00 49 33';
    CONST STOP_5_ON = '01 05 10 2E FF 00 E8 F3';
    CONST STOP_5_OFF = '01 05 10 2E 00 00 A9 03';
    CONST START_BUTTON = '01 05 10 36 FF 00 68 F4';
    CONST AUTO_BUTTON = '01 05 10 40 FF 00 89 2E';
    CONST MAX_BET = '01 05 10 4A FF 00 A9 2C';
    CONST INFO_BUTTON = '01 05 10 54 FF 00 C9 2A';

    public function openPoint($ip, $port, $amount)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_15r_open_point');
        while(!$amount_result && $amount_count < 5) {
            logger('指令：'.$string);
            logger('crc值:'. $check_sum);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $string));
            logger('ip:'.$ip);
            logger('port:'.$port);
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
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_SPEED));
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_SPEED);
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

        return response()->json(['result' => 1]);
    }

    public function openPointBySec($ip, $port, $amount, $second)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_15r_open_point');
        while(!$amount_result && $amount_count < 5) {
            logger('指令：'.$string);
            logger('crc值:'. $check_sum);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $string));
            logger('ip:'.$ip);
            logger('port:'.$port);
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
        $speed = sprintf('01 06 01 0D %04s',dechex((($second * 1000)/2)/10));
        $crc = Resource::calculateCrc($speed.'0000');
        while(!$speed_result && $speed_count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $speed.$crc));
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.$speed.$crc);
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
        $result_control = 0;
        $count_control = 0;
        while (!$result_control && $count_control < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_15r_open_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_CONTROL);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_control = 1;
                $count_control = 6;
            } else {
                usleep(50000);
                $count_control++;
                $result_control = $return;
            }
        }
        logger('result：'.$result_control.PHP_EOL);
        if($result_control != 1)
        {
            response()->json(['result' => $result_control]);
        }
        return response()->json(['result' => 1]);
    }

    public function openPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_ALL_STATUS));
            // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
            // $check = Resource::calculateCrc($return, 11);
            logger('v1_r15_open_point_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_ALL_STATUS);
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

        if(strcasecmp($check, substr($return, -4, 4)) === 0)
        {
            $result = 1;
            $wash = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768);
            $open = base_convert(substr($return,14,4), 16, 10) + (base_convert(substr($return,18, 4), 16, 10) * 32768);
            $pressure = base_convert(substr($return,22,4), 16, 10) + (base_convert(substr($return,26, 4), 16, 10) * 32768);
            $score = base_convert(substr($return,30,4), 16, 10) + (base_convert(substr($return,34, 4), 16, 10) * 32768);
            $big_pressure = base_convert(substr($return,38,4), 16, 10) + (base_convert(substr($return,42, 4), 16, 10) * 32768);
            $big_reward = base_convert(substr($return,46,4), 16, 10) + (base_convert(substr($return,50, 4), 16, 10) * 32768);
            $during_grand = base_convert(substr($return,54,4), 16, 10);

            $small_pressure = base_convert(substr($return,58,4), 16, 10) + (base_convert(substr($return,62, 4), 16, 10) * 32768);
            $small_reward = base_convert(substr($return,66,4), 16, 10) + (base_convert(substr($return,70, 4), 16, 10) * 32768);
            $during_small = base_convert(substr($return,74,4), 16, 10);

            return response()->json([
                'result' => $result,
                'wash' => $wash,
                'open' => $open,
                'pressure' => $pressure,
                'score' => $score,
                'big_pressure' => $big_pressure,
                'big_reward' => $big_reward,
                'during_big_reward_time' => ($during_grand == 1)?1:0,
                'small_pressure' => $small_pressure,
                'small_reward' => $small_reward,
                'during_small_reward_time' => ($during_small == 1)?1:0
            ]);
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function openPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_15r_open_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_CONTROL);
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

    public function washPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_1 = 0;
        $count_1 = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V1_15r_wash_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_CONTROL);
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
        if($result!= 1){
            return response()->json(['result' => $result]);
        }
        usleep(3000000);
        while(!$result_1 && $count_1 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V1_15r_wash_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_CONTROL);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_1 = 1;
                $count_1 = 6;
            } else {
                usleep(50000);
                $count_1++;
                $result_1 = $return;
            }
        }
        logger('result：'.$result_1.PHP_EOL);
        if($result_1 != 1) {
            return response()->json(['result' => $result_1]);
        }
        return response()->json(['result' => 1]);
    }

    public function washPointControlBySec($ip, $port, $second)
    {
        $result = 0;
        $count = 0;
        $result_1 = 0;
        $count_1 = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V1_15r_wash_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_CONTROL);
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
        if($result!= 1){
            return response()->json(['result' => $result]);
        }
        usleep($second*1000*1000);
        while(!$result_1 && $count_1 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V1_15r_wash_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_CONTROL);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_1 = 1;
                $count_1 = 6;
            } else {
                usleep(50000);
                $count_1++;
                $result_1 = $return;
            }
        }
        logger('result：'.$result_1.PHP_EOL);
        if($result_1 != 1) {
            return response()->json(['result' => $result_1]);
        }
        return response()->json(['result' => 1]);
    }

    public function stop1($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_1));
            logger('V1_15r_stop_1');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_1);
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

    public function stop2($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_2));
            logger('V1_15r_stop_2');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_2);
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

    public function stop3($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_3));
            logger('V1_15r_stop_3');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_3);
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

    public function stop4($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_4));
            logger('V1_15r_stop_4');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_4);
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

    public function stop5($ip, $port)
    {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_5_ON));
            logger('V1_15r_stop_5');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_5_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('result_on：'.$result_on.PHP_EOL);
        if($result_on != 1){
            return response()->json(['result' => $result_on]);
        }
        usleep(10000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_5_OFF));
            logger('V1_15r_stop_5_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_5_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('result_off：'.$result_off.PHP_EOL);
        if($result_off != 1){
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => 1]);
    }

    public function stop5BySec($ip, $port, $second)
    {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_5_ON));
            logger('V1_15r_stop_5_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_5_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('result_on：'.$result_on.PHP_EOL);
        if($result_on != 1){
            return response()->json(['result' => $result_on]);
        }
        usleep($second * 1000 * 1000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_5_OFF));
            logger('V1_15r_stop_5_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_5_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('result_off：'.$result_off.PHP_EOL);
        if($result_off != 1){
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => 1]);
    }

    public function startButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::START_BUTTON));
            logger('V1_15r_start_button');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::START_BUTTON);
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

    public function autoButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::AUTO_BUTTON));
            logger('V1_15r_auto_button');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::AUTO_BUTTON);
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

    public function maxBet($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::MAX_BET));
            logger('V1_15r_max_bet');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::MAX_BET);
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

    public function infoButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::INFO_BUTTON));
            logger('V1_15r_info_button');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::INFO_BUTTON);
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
}
