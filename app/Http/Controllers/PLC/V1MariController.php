<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V1MariController extends Controller
{
    CONST OPEN_POINT = '01 06 00 EB %04s';
    CONST OPEN_POINT_SPEED = '01 06 01 09 00 32 D9 E1';
    CONST OPEN_POINT_CONTROL = '01 05 10 39 FF 00 58 F7';
    // 開洗分 所有壓得計分 大獎與小獎狀態紀錄
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    CONST WASH_POINT_CONTROL = '01 05 10 41 FF 00 D8 EE';
    CONST LEFT_BUTTON_LONG = '01 05 10 06 FF 00 68 FB';
    CONST LEFT_BUTTON_LONG_OFF = '01 05 10 06 00 00 29 0B';
    CONST LEFT_BUTTON_SHORT = '01 05 10 04 FF 00 C9 3B';
    CONST RIGHT_BUTTON_LONG = '01 05 10 10 FF 00 89 3F';
    CONST RIGHT_BUTTON_LONG_OFF = '01 05 10 10 00 00 C8 CF';
    CONST RIGHT_BUTTON_SHORT = '01 05 10 0E FF 00 E9 39';
    CONST SMALL_BUTTON = '01 05 10 18 FF 00 08 FD';
    CONST BIG_BUTTON = '01 05 10 22 FF 00 28 F0';
    CONST START_BUTTON = '01 05 10 2C FF 00 49 33';
    CONST BAR_BUTTON_LONG = '01 05 10 4C FF 00 49 2D';
    CONST BAR_BUTTON_LONG_OFF = '01 05 10 4C 00 00 08 DD';
    CONST BAR_BUTTON_SHORT = '01 05 10 4A FF 00 A9 2C';
    CONST APPLE_BUTTON_LONG = '01 05 10 56 FF 00 68 EA';
    CONST APPLE_BUTTON_LONG_OFF ='01 05 10 56 00 00 29 1A';
    CONST APPLE_BUTTON_SHORT = '01 05 10 54 FF 00 C9 2A';
    CONST WATERMELON_BUTTON_LONG = '01 05 10 60 FF 00 88 E4';
    CONST WATERMELON_BUTTON_LONG_OFF = '01 05 10 60 00 00 C9 14';
    CONST WATERMELON_BUTTON_SHORT = '01 05 10 5E FF 00 E9 28';
    CONST STAR_BUTTON_LONG = '01 05 10 6A FF 00 A8 E6';
    CONST STAR_BUTTON_LONG_OFF = '01 05 10 6A 00 00 E9 16';
    CONST STAR_BUTTON_SHORT = '01 05 10 68 FF 00 09 26';
    CONST BUTTON_77_LONG = '01 05 10 74 FF 00 C8 E0';
    CONST BUTTON_77_LONG_OFF = '01 05 10 74 00 00 89 10';
    CONST BUTTON_77_SHORT = '01 05 10 72 FF 00 28 E1';
    CONST BELL_BUTTON_LONG = '01 05 10 7E FF 00 E8 E2';
    CONST BELL_BUTTON_LONG_OFF = '01 05 10 7E 00 00 A9 12';
    CONST BELL_BUTTON_SHORT = '01 05 10 7C FF 00 49 22';
    CONST MELON_BUTTON_LONG = '01 05 10 88 FF 00 08 D0';
    CONST MELON_BUTTON_LONG_OFF = '01 05 10 88 00 00 49 20';
    CONST MELON_BUTTON_SHORT = '01 05 10 86 FF 00 69 13';
    CONST ORANGE_BUTTON_LONG = '01 05 10 92 FF 00 29 17';
    CONST ORANGE_BUTTON_LONG_OFF = '01 05 10 92 00 00 68 E7';
    CONST ORANGE_BUTTON_SHORT = '01 05 10 90 FF 00 88 D7';
    CONST LYCHEE_BUTTON_LONG = '01 05 10 9C FF 00 48 D4';
    CONST LYCHEE_BUTTON_LONG_OFF = '01 05 10 9C 00 00 09 24';
    CONST LYCHEE_BUTTON_SHORT = '01 05 10 9A FF 00 A8 D5';

    public function openPointBySec($ip, $port, $amount, $second)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_mari_open_point_by_second');
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
        $speed = sprintf('01 06 01 09 %04s',dechex((($second * 1000)/2)/10));
        $crc = Resource::calculateCrc($speed.'0000');
        while(!$speed_result && $speed_count < 5) {
            logger('指令：'.$speed.$crc);
            logger('second:'. $second);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $speed.$crc));
            logger('ip:'.$ip);
            logger('port:'.$port);
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
        usleep(500000);

        $result = 0;
        $count = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_mari_open_point_control');
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        return response()->json(['result' => 1]);
    }


    public function openPoint($ip, $port, $amount)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_mari_open_point');
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

    public function openPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_mari_open_point_control');
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

    public function openPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_ALL_STATUS));
            // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
            // $check = Resource::calculateCrc($return, 11);
            logger('v1_mari_open_point_status');
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

    public function washPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V1_mari_wash_point_control');
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
        return response()->json(['result' => $result]);
    }

    public function leftButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $result_count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_BUTTON_LONG));
            logger('V1_mari_left_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_BUTTON_LONG_OFF));
            logger('V1_mari_left_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }
    
    public function leftButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LEFT_BUTTON_SHORT));
            logger('V1_mari_left_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LEFT_BUTTON_SHORT);
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

    public function rightButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_BUTTON_LONG));
            logger('V1_mari_right_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_BUTTON_LONG);
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
        if($result!=1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_BUTTON_LONG_OFF));
            logger('V1_mari_right_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }

        return response()->json(['result' => $result && $result_off]);
    }

    public function rightButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::RIGHT_BUTTON_SHORT));
            logger('V1_mari_right_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::RIGHT_BUTTON_SHORT);
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

    public function smallButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SMALL_BUTTON));
            logger('V1_mari_small_button');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SMALL_BUTTON);
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

    public function bigButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BIG_BUTTON));
            logger('V1_mari_big_button');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BIG_BUTTON);
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

    public function startButton($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::START_BUTTON));
            logger('V1_mari_start_button');
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
    
    public function barButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BAR_BUTTON_LONG));
            logger('V1_mari_bar_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BAR_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BAR_BUTTON_LONG_OFF));
            logger('V1_mari_bar_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BAR_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }

        return response()->json(['result' => $result && $result_off]);
    }

    public function barButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BAR_BUTTON_SHORT));
            logger('V1_mari_bar_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BAR_BUTTON_SHORT);
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
    
    public function appleButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG));
            logger('V1_mari_apple_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG_OFF));
            logger('V1_mari_apple_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function appleButtonLongBySecond($ip, $port, $second)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG));
            logger('V1_mari_apple_button_long_by_second');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep($second*1000*1000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG_OFF));
            logger('V1_mari_apple_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function appleButtonShort($ip, $port)
    {
        // $result = 0;
        // $count = 0;
        // while(!$result && $count < 5) {
        //     $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_SHORT));
        //     logger('V1_mari_apple_button_short');
        //     logger('ip:'.$ip);
        //     logger('port:'.$port);
        //     logger('指令：'.self::APPLE_BUTTON_SHORT);
        //     logger('回傳值：'.$return);
        //     logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
        //     $check = Resource::calculateCrc($return);
        //     if (strcasecmp($check, substr($return, -4, 4)) === 0) {
        //         $result = 1;
        //         $count = 6;
        //     } else {
        //         usleep(50000);
        //         $count++;
        //         $result = $return;
        //     }
        // }
        // logger('result：'.$result.PHP_EOL);
        // return response()->json(['result' => $result]);
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG));
            logger('V1_mari_apple_button_shrot_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(300000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::APPLE_BUTTON_LONG_OFF));
            logger('V1_mari_apple_button_short_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::APPLE_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function watermelonButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WATERMELON_BUTTON_LONG));
            logger('V1_mari_watermelon_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WATERMELON_BUTTON_LONG);
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
        if( $result != 1 ) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WATERMELON_BUTTON_LONG_OFF));
            logger('V1_mari_watermelon_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WATERMELON_BUTTON_LONG_OFF);
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
        if( $result_off != 1 ) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function watermelonButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WATERMELON_BUTTON_SHORT));
            logger('V1_mari_watermelon_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WATERMELON_BUTTON_SHORT);
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

    public function starButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAR_BUTTON_LONG));
            logger('V1_mari_star_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAR_BUTTON_LONG);
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
        if( $result != 1 ) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAR_BUTTON_LONG_OFF));
            logger('V1_mari_star_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAR_BUTTON_LONG_OFF);
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
        if( $result_off != 1 ) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function starButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAR_BUTTON_SHORT));
            logger('V1_mari_star_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAR_BUTTON_SHORT);
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

    public function button77Long($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_77_LONG));
            logger('V1_mari_77_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BUTTON_77_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_77_LONG_OFF));
            logger('V1_mari_77_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BUTTON_77_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }

        return response()->json(['result' => $result && $result_off]);
    }

    public function button77Short($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_77_SHORT));
            logger('V1_mari_77_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BUTTON_77_SHORT);
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
    
    public function bellButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BELL_BUTTON_LONG));
            logger('V1_mari_bell_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BELL_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BELL_BUTTON_LONG_OFF));
            logger('V1_mari_bell_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BELL_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function bellButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BELL_BUTTON_SHORT));
            logger('V1_mari_bell_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BELL_BUTTON_SHORT);
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

    public function melonButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::MELON_BUTTON_LONG));
            logger('V1_mari_melon_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::MELON_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::MELON_BUTTON_LONG_OFF));
            logger('V1_mari_melon_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::MELON_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function melonButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::MELON_BUTTON_SHORT));
            logger('V1_mari_melon_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::MELON_BUTTON_SHORT);
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

    public function orangeButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ORANGE_BUTTON_LONG));
            logger('V1_mari_orange_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ORANGE_BUTTON_LONG);
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
        if( $result != 1 ){
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ORANGE_BUTTON_LONG_OFF));
            logger('V1_mari_orange_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ORANGE_BUTTON_LONG_OFF);
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
        if( $result_off != 1 ){
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function orangeButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ORANGE_BUTTON_SHORT));
            logger('V1_mari_orange_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ORANGE_BUTTON_SHORT);
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

    public function lycheeButtonLong($ip, $port)
    {
        $result = 0;
        $count = 0;
        $result_off = 0;
        $count_off = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LYCHEE_BUTTON_LONG));
            logger('V1_mari_lychee_button_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LYCHEE_BUTTON_LONG);
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
        if($result != 1) {
            return response()->json(['result' => $result]);
        }
        usleep(5000000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LYCHEE_BUTTON_LONG_OFF));
            logger('V1_mari_lychee_button_long_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LYCHEE_BUTTON_LONG_OFF);
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
        if($result_off != 1) {
            return response()->json(['result' => $result_off]);
        }
        return response()->json(['result' => $result && $result_off]);
    }

    public function lycheeButtonShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::LYCHEE_BUTTON_SHORT));
            logger('V1_mari_lychee_button_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::LYCHEE_BUTTON_SHORT);
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
