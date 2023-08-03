<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V17pkController extends Controller
{
    CONST OPEN_POINT = '01 06 00 EF %04s';
    // CONST OPEN_POINT_SPEED = '01 06 01 0D 00 0A 99 F2';
    CONST OPEN_POINT_SPEED = '01 06 01 0D 00 0F 59 F1';
    CONST OPEN_POINT_CONTROL = '01 05 10 61 FF 00 D9 24';
    // 開洗分 所有壓得計分 大獎與小獎狀態紀錄
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    CONST WASH_POINT_CONTROL = '01 05 10 68 FF 00 09 26';
    CONST PRESS_POINT = '01 05 10 04 FF 00 C9 3B';
    CONST PRESS_SMALL = '01 05 10 0E FF 00 E9 39';
    CONST PRESS_BIG = '01 05 10 18 FF 00 08 FD';
    CONST SET_RATIO = '01 05 10 22 FF 00 28 F0';
    CONST GET_POINT = '01 05 10 2C FF 00 49 33';
    CONST COLLECT_POINT = '01 05 10 36 FF 00 68 F4';
    CONST ALL_OPEN = '01 05 10 40 FF 00 89 2E';
    CONST REVEAL_CARD = '01 05 10 4A FF 00 A9 2C';

    public function openPoint($ip, $port, $amount)
    {
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_7pk_open_point');
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
        $speed = sprintf('01 06 01 0D %04s', dechex(((0.5 * 1000)/2)/10));
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
        
        return response()->json(['result' => 1]);
    }

    public function openPointBYSec($ip, $port, $amount, $second)
    {
        set_time_limit(0);
        $amount_result = 0;
        $amount_count = 0;
        $speed_result = 0;
        $speed_count = 0;

        $string = sprintf(self::OPEN_POINT,dechex($amount));
        $check_sum = Resource::calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v1_7pk_open_point');
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
        $speed = sprintf('01 06 01 0D %04s', dechex((($second * 1000)/2)/10));
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
        sleep(500000);
        $result_control = 0;
        $count_control = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_7pk_open_point_control');
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
        if($result_control != 1)
        {
            return response()->json(['result' => $result_control]);
        }
        return response()->json(['result' => 1]);
    }

    public function openPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            logger('v1_7pk_open_point_control');
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
            logger('v1_7pk_open_point_status');
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
            logger('V1_7pk_wash_point_control');
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

    public function pressPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PRESS_POINT));
            logger('V1_7pk_press_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PRESS_POINT);
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

    public function pressSmall($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PRESS_SMALL));
            logger('V1_7pk_press_small');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PRESS_SMALL);
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

    public function pressBig($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::PRESS_BIG));
            logger('V1_7pk_press_big');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::PRESS_BIG);
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

    public function setRatio($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SET_RATIO));
            logger('V1_7pk_set_ratio');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SET_RATIO);
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

    public function getPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_POINT));
            logger('V1_7pk_get_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_POINT);
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

    public function collectPoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::COLLECT_POINT));
            logger('V1_7pk_collect_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::COLLECT_POINT);
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

    public function allOpen($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ALL_OPEN));
            logger('V1_7pk_all_open');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ALL_OPEN);
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

    public function revealCard($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::REVEAL_CARD));
            logger('V1_7pk_reveal_card');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::REVEAL_CARD);
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
