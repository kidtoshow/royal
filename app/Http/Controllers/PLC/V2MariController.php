<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V2MariController extends Controller
{
    CONST OPEN_POINT = '01 05 10 07 FF 00 39 3B';
    // CONST OPEN_POINT_SPEED = '01 06 01 09 00 32 D9 E1';
    // CONST OPEN_POINT_CONTROL = '01 05 10 39 FF 00 58 F7';
    // 開洗分 所有壓得計分 大獎與小獎狀態紀錄
    CONST LONG_BUTTON_DELAY = 5000000; // 長按需停5秒後按關 1秒1000豪秒 1豪秒 1000微秒
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    CONST WASH_POINT_CONTROL = '01 05 10 0E FF 00 E9 39';
    CONST LEFT_BUTTON_LONG = '01 05 10 2E FF 00 E8 F3';
    CONST LEFT_BUTTON_LONG_OFF = '01 05 10 2E 00 00 A9 03';
    CONST LEFT_BUTTON_SHORT = '01 05 10 2F FF 00 B9 33';
    CONST RIGHT_BUTTON_LONG = '01 05 10 38 FF 00 09 37';
    CONST RIGHT_BUTTON_LONG_OFF = '01 05 10 38 00 00 48 C7';
    CONST RIGHT_BUTTON_SHORT = '01 05 10 39 FF 00 58 F7';
    CONST SMALL_BUTTON_LONG = '01 05 10 42 FF 00 28 EE';
    CONST SMALL_BUTTON_LONG_OFF = '01 05 10 42 00 00 69 1E';
    CONST SMALL_BUTTON_SHORT = '01 05 10 43 FF 00 79 2E';
    CONST BIG_BUTTON_LONG = '01 05 10 4C FF 00 49 2D';
    CONST BIG_BUTTON_LONG_OFF = '01 05 10 4C 00 00 08 DD';
    CONST BIG_BUTTON_SHORT = '01 05 10 4D FF 00 18 ED';
    CONST START_BUTTON_LONG = '01 05 10 56 FF 00 68 EA';
    CONST START_BUTTON_LONG_OFF = '01 05 10 56 00 00 29 1A';
    CONST START_BUTTON_SHORT = '01 05 10 57 FF 00 39 2A';
    CONST BAR_BUTTON_LONG = '01 05 10 60 FF 00 88 E4';
    CONST BAR_BUTTON_LONG_OFF = '01 05 10 60 00 00 C9 14';
    CONST BAR_BUTTON_SHORT = '01 05 10 61 FF 00 D9 24';
    CONST APPLE_BUTTON_LONG = '01 05 10 6A FF 00 A8 E6';
    CONST APPLE_BUTTON_LONG_OFF ='01 05 10 6A 00 00 E9 16';
    CONST APPLE_BUTTON_SHORT = '01 05 10 6B FF 00 F9 26';
    CONST WATERMELON_BUTTON_LONG = '01 05 10 74 FF 00 C8 E0';
    CONST WATERMELON_BUTTON_LONG_OFF = '01 05 10 74 00 00 89 10';
    CONST WATERMELON_BUTTON_SHORT = '01 05 10 75 FF 00 99 20';
    CONST STAR_BUTTON_LONG = '01 05 10 7E FF 00 E8 E2';
    CONST STAR_BUTTON_LONG_OFF = '01 05 10 7E 00 00 A9 12';
    CONST STAR_BUTTON_SHORT = '01 05 10 7F FF 00 B9 22';
    CONST BUTTON_77_LONG = '01 05 10 88 FF 00 08 D0';
    CONST BUTTON_77_LONG_OFF = '01 05 10 88 00 00 49 20';
    CONST BUTTON_77_SHORT = '01 05 10 89 FF 00 59 10';
    CONST BELL_BUTTON_LONG = '01 05 10 92 FF 00 29 17';
    CONST BELL_BUTTON_LONG_OFF = '01 05 10 92 00 00 68 E7';
    CONST BELL_BUTTON_SHORT = '01 05 10 93 FF 00 78 D7';
    CONST MELON_BUTTON_LONG = '01 05 10 9C FF 00 48 D4';
    CONST MELON_BUTTON_LONG_OFF = '01 05 10 9C 00 00 09 24';
    CONST MELON_BUTTON_SHORT = '01 05 10 9D FF 00 19 14';
    CONST ORANGE_BUTTON_LONG = '01 05 10 A6 FF 00 68 D9';
    CONST ORANGE_BUTTON_LONG_OFF = '01 05 10 A6 00 00 29 29';
    CONST ORANGE_BUTTON_SHORT = '01 05 10 A7 FF 00 39 19';
    CONST LYCHEE_BUTTON_LONG = '01 05 10 B0 FF 00 89 1D';
    CONST LYCHEE_BUTTON_LONG_OFF = '01 05 10 B0 00 00 C8 ED';
    CONST LYCHEE_BUTTON_SHORT = '01 05 10 B1 FF 00 D8 DD';

    CONST BUTTON_INITIALIZE1 = '01 06 00 E6 00 01 A9 FD';
    CONST BUTTON_INITIALIZE2 = '01 06 01 04 00 0A 49 F0';
    CONST BUTTON_INITIALIZE3 = '01 10 00 EA 00 0E 1C 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 00 01 09 EA';
    CONST BUTTON_INITIALIZE4 = '01 10 01 08 00 0E 1C 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A 00 0A C1 D0';

    public function openPoint($ip, $port, $amount)
    {
        set_time_limit(0);
        $first = Carbon::now();
        logger('v2_mari_open_point');
        logger('ip:'.$ip);
        logger('port:'.$port);
        logger('amount:'.$amount);
        $this->buttonInitialize($ip, $port);
        for($i = 0; $i < $amount; $i++)
        {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT));
            logger('第'. ($i+1) .'次指令：' . self::OPEN_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) !== 0) {
                return response()->json(['result' => $return]);
            }
            usleep(2500000);
        }
        logger(Carbon::now()->diffForHumans($first));
        return response()->json(['result' => 1]);
    }

    public function openPointBySec($ip, $port, $amount, $second)
    {
        set_time_limit(0);
        $first = Carbon::now();
        logger('v2_mari_open_point_by_second');
        logger('ip:'.$ip);
        logger('port:'.$port);
        logger('amount:'.$amount);
        logger('second:'.$second);
        $this->buttonInitialize($ip, $port);
        for($i = 0; $i < $amount; $i++)
        {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT));
            logger('第'. ($i+1) .'次指令：' . self::OPEN_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) !== 0) {
                return response()->json(['result' => $return]);
            }
            usleep($second * 1000 * 1000);
        }
        logger(Carbon::now()->diffForHumans($first));
        return response()->json(['result' => 1]);
    }

    public function leftButtonLong($ip, $port)
    {
        logger('V2_mari_left_button_long');
        return $this->pressLong($ip, self::LEFT_BUTTON_LONG, self::LEFT_BUTTON_LONG_OFF);
    }

    public function leftButtonShort($ip, $port)
    {
        logger('V2_mari_left_button_short');
        return $this->pressShort($ip, self::LEFT_BUTTON_SHORT);
    }

    public function rightButtonLong($ip, $port)
    {
        logger('V2_mari_right_button_long');
        return $this->pressLong($ip, self::RIGHT_BUTTON_LONG, self::RIGHT_BUTTON_LONG_OFF);
    }

    public function rightButtonShort($ip, $port)
    {
        logger('V2_mari_right_button_short');
        return $this->pressShort($ip, self::RIGHT_BUTTON_SHORT);
    }

    public function smallButtonLong($ip, $port)
    {
        logger('V2_mari_small_button_long');
        return $this->pressLong($ip, self::SMALL_BUTTON_LONG, self::SMALL_BUTTON_LONG_OFF);
    }

    public function smallButtonShort($ip, $port)
    {
        logger('V2_mari_small_button_short');
        return $this->pressShort($ip, self::SMALL_BUTTON_SHORT);
    }

    public function bigButtonLong($ip, $port)
    {
        logger('V2_mari_big_button_long');
        return $this->pressLong($ip, self::BIG_BUTTON_LONG, self::BIG_BUTTON_LONG_OFF);
    }

    public function bigButtonShort($ip, $port)
    {
        logger('V2_mari_big_button_short');
        return $this->pressShort($ip, self::BIG_BUTTON_SHORT);
    }

    public function startButtonLong($ip, $port)
    {
        logger('V2_mari_start_button_long');
        return $this->pressLong($ip, self::START_BUTTON_LONG, self::START_BUTTON_LONG_OFF);
    }

    public function startButtonShort($ip, $port)
    {
        logger('V2_mari_start_button_short');
        return $this->pressShort($ip, self::START_BUTTON_SHORT);
    }

    public function barButtonLong($ip, $port)
    {
        logger('V2_mari_bar_button_long');
        return $this->pressLong($ip, self::BAR_BUTTON_LONG, self::BAR_BUTTON_LONG_OFF);
    }

    public function barButtonShort($ip, $port)
    {
        logger('V2_mari_bar_button_short');
        return $this->pressShort($ip, self::BAR_BUTTON_SHORT);
    }

    public function appleButtonLongBySecond($ip, $port, $second)
    {
        logger('V2_mari_apple_button_long_by_second');
        return $this->pressLongBySecond($ip, self::APPLE_BUTTON_LONG, self::APPLE_BUTTON_LONG_OFF, $second*1000*1000);
    }

    public function appleButtonLong($ip, $port)
    {
        logger('V2_mari_apple_button_long');
        return $this->pressLong($ip, self::APPLE_BUTTON_LONG, self::APPLE_BUTTON_LONG_OFF);
    }

    public function appleButtonShort($ip, $port)
    {
        logger('V2_mari_apple_button_short');
        return $this->pressShort($ip, self::APPLE_BUTTON_SHORT);
    }

    public function watermelonButtonLong($ip, $port)
    {
        logger('V2_mari_watermelon_button_long');
        return $this->pressLong($ip, self::WATERMELON_BUTTON_LONG, self::WATERMELON_BUTTON_LONG_OFF);
    }

    public function watermelonButtonShort($ip, $port)
    {
        logger('V2_mari_watermelon_button_short');
        return $this->pressShort($ip, self::WATERMELON_BUTTON_SHORT);
    }

    public function starButtonLong($ip, $port)
    {
        logger('V2_mari_star_button_long');
        return $this->pressLong($ip, self::STAR_BUTTON_LONG, self::STAR_BUTTON_LONG_OFF);
    }

    public function starButtonShort($ip, $port)
    {
        logger('V2_mari_star_button_short');
        return $this->pressShort($ip, self::STAR_BUTTON_SHORT);
    }

    public function button77Long($ip, $port)
    {
        logger('V2_mari_button77_button_long');
        return $this->pressLong($ip, self::BUTTON_77_LONG, self::BUTTON_77_LONG_OFF);
    }

    public function button77Short($ip, $port)
    {
        logger('V2_mari_button77_button_short');
        return $this->pressShort($ip, self::BUTTON_77_SHORT);
    }

    public function bellButtonLong($ip, $port)
    {
        logger('V2_mari_bell_button_long');
        return $this->pressLong($ip, self::BELL_BUTTON_LONG, self::BELL_BUTTON_LONG_OFF);
    }

    public function bellButtonShort($ip, $port)
    {
        logger('V2_mari_bell_button_short');
        return $this->pressShort($ip, self::BELL_BUTTON_SHORT);
    }

    public function melonButtonLong($ip, $port)
    {
        logger('V2_mari_melon_button_long');
        return $this->pressLong($ip, self::MELON_BUTTON_LONG, self::MELON_BUTTON_LONG_OFF);
    }

    public function melonButtonShort($ip, $port)
    {
        logger('V2_mari_melon_button_short');
        return $this->pressShort($ip, self::MELON_BUTTON_SHORT);
    }

    public function orangeButtonLong($ip, $port)
    {
        logger('V2_mari_orange_button_long');
        return $this->pressLong($ip, self::ORANGE_BUTTON_LONG, self::ORANGE_BUTTON_LONG_OFF);
    }

    public function orangeButtonShort($ip, $port)
    {
        logger('V2_mari_orange_button_short');
        return $this->pressShort($ip, self::ORANGE_BUTTON_SHORT);
    }

    public function lycheeButtonLong($ip, $port)
    {
        logger('V2_mari_lychee_button_long');
        return $this->pressLong($ip, self::LYCHEE_BUTTON_LONG, self::LYCHEE_BUTTON_LONG_OFF);
    }

    public function lycheeButtonShort($ip, $port)
    {
        logger('V2_mari_lychee_button_short');
        return $this->pressShort($ip, self::LYCHEE_BUTTON_SHORT);
    }

    public function washPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('V2_mari_wash_point_control');
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

    public function openPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_ALL_STATUS));
            // $return = Resource::send_socket($ip, $port, self::GET_ALL_STATUS);
            // $check = Resource::calculateCrc($return, 11);
            logger('v2_mari_open_point_status');
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

    public function buttonInitialize($ip, $port)
    {
        $result_1 = 0;
        $count_1 = 0;
        $result_2 = 0;
        $count_2 = 0;
        $result_3 = 0;
        $count_3 = 0;
        $result_4 = 0;
        $count_4 = 0;

        while (!$result_1 && $count_1 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_INITIALIZE1));
            logger('ip:'.$ip);
            logger('指令：'.self::BUTTON_INITIALIZE1);
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
        logger('return_1'.$result_1.PHP_EOL);
        if($result_1 != 1)
        {
            return response()->json(['result' => $result_1]);
        }
        usleep(60000);

        while (!$result_2 && $count_2 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_INITIALIZE2));
            logger('ip:'.$ip);
            logger('指令：'.self::BUTTON_INITIALIZE2);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_2 = 1;
                $count_2 = 6;
            } else {
                usleep(50000);
                $count_2++;
                $result_2 = $return;
            }
        }
        logger('return_2'.$result_2.PHP_EOL);
        if($result_2 != 1)
        {
            return response()->json(['result' => $result_2]);
        }
        usleep(60000);

        while (!$result_3 && $count_3 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_INITIALIZE3));
            logger('ip:'.$ip);
            logger('指令：'.self::BUTTON_INITIALIZE3);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_3 = 1;
                $count_3 = 6;
            } else {
                usleep(50000);
                $count_3++;
                $result_3 = $return;
            }
        }
        logger('return_3'.$result_3.PHP_EOL);
        if($result_3 != 1)
        {
            return response()->json(['result' => $result_3]);
        }
        usleep(60000);

        while (!$result_4 && $count_4 < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BUTTON_INITIALIZE4));
            logger('ip:'.$ip);
            logger('指令：'.self::BUTTON_INITIALIZE4);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = Resource::calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_4 = 1;
                $count_4 = 6;
            } else {
                usleep(50000);
                $count_4++;
                $result_4 = $return;
            }
        }
        logger('return_4'.$result_4.PHP_EOL);
        if($result_4 != 1)
        {
            return response()->json(['result' => $result_4]);
        }
        return response()->json(['result' => 1]);
    }

    private function pressLong($ip, $on, $off, $sleep_second=self::LONG_BUTTON_DELAY)
    {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        while (!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port 8000 --cmd '. str_replace(' ', '', $on));
            logger('ip:'.$ip);
            logger('指令：'.$on);
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
        logger('return_on'.$result_on.PHP_EOL);
        if($result_on != 1)
        {
            return response()->json(['result' => $result_on]);
        }
        usleep($sleep_second);
        while (!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port 8000 --cmd '. str_replace(' ', '', $off));
            logger('ip:'.$ip);
            logger('指令：'.$off);
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
        logger('return_off'.$result_off.PHP_EOL);
        if($result_off != 1)
        {
            return response()->json(['result' => $result_off]);
        }
        
        return response()->json(['result' => 1]);
    }

    private function pressShort($ip, $cmd)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port 8000 --cmd '. str_replace(' ', '', $cmd));
            logger('ip:'.$ip);
            logger('指令：'.$cmd);
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
