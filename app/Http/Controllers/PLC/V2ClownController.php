<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V2ClownController extends Controller
{
    //取值韋峯部分
    // 洗分控制
    CONST WASH_POINT_CONTROL = '01 05 08 6F FF 00 BE 47';
    // 壓分
    CONST OPEN_POINT_AMOUNT = '01 06 00 E6 %04s';
    CONST OPEN_POINT_SPEED = '01 06 00 F0 00 0A 09 FE';
    // 開分控制
    CONST OPEN_POINT_CONTROL = '01 05 08 67 FF 00 3F 85';
    // 投幣ＯＮ
    CONST COIN_ON = '01 05 08 7A FF 00 AF 83';
    // 投幣ＯＦＦ
    CONST COIN_OFF = '01 05 08 7A 00 00 EE 73';
    // 大獎狀態
    CONST BIG_REWARD_STATUS = '01 04 00 D2 00 04 51 F0';
    // 小獎狀態
    CONST SMALL_REWARD_STATUS = '01 04 00 E3 00 01 C0 3C';
    // 開洗分歸零
    CONST OPEN_POINT_ZERO = '01 05 08 0A FF 00 AE 58';
    // 開洗分 所有壓得計分 大獎與小獎狀態紀錄
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    // 所有壓得計分 大獎與小獎狀態紀錄歸零
    CONST ZERO_SCORE_AND_REWARD_STATUS = '01 05 08 0B FF 00 FF 98';
    // 所有壓得計分狀態紀錄歸零
    CONST ZERO_SCORE_AND_REWARD = '01 05 08 0C FF 00 4E 59';
    // 大獎狀態紀錄歸零
    CONST ZERO_GRAND_REWARD = '01 05 08 0D FF 00 1F 99';
    // 小獎狀態紀錄歸零
    CONST ZERO_SMALL_REWARD = '01 05 08 0E FF 00 EF 99';
    // 大閃燈key1_ON 20200325後改為 大賞燈切換
    CONST FLASH_1_ON = '01 05 08 AA FF 00 AE 7A';
    // 大閃燈key1_OFF
    CONST FLASH_1_OFF = '01 05 08 18 00 00 4F AD';
    // 取得壓分
    CONST GET_PRESSURE = '01 04 00 D6 00 02 90 33';
    // 取得得分
    CONST GET_SCORE = '01 04 00 D8 00 02 F1 F0';
    //七段顯示器
    CONST SEVEN_DISPLAY = '02 03 00 00 00 01 84 39';
    //搖桿_短
    CONST JOYSTICK_SHORT = '01 05 08 A0 FF 00 8E 78';
    //搖桿_長
    // CONST JOYSTICK_LONG = '01 05 08 8D FF 00 1E 71'; //2秒後停
    CONST JOYSTICK_LONG = '01 05 08 A2 FF 00 2F B8';
    CONST JOYSTICK_LONG_STOP = '01 05 08 A2 00 00 6E 48';
    //停1
    CONST STOP_1 = '01 05 08 82 FF 00 2E 72';
    //停2
    CONST STOP_2 = '01 05 08 8C FF 00 4F B1';
    //停3
    CONST STOP_3 = '01 05 08 96 FF 00 6E 76';

    public function washPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            logger('v2_clown_wash_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::WASH_POINT_CONTROL);
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

        $string = sprintf(self::OPEN_POINT_AMOUNT,dechex($amount));
        $check_sum = $this->calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('v2_clown_open_point');
        while(!$amount_result && $amount_count < 5) {
            logger('指令：'.$string);
            logger('crc值:'. $check_sum);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $string));
            logger('after socket');
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
            logger('v2_clown_open_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_CONTROL);
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

    public function coinOn($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::COIN_ON));
            logger('v2_clown_coin_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::COIN_ON);
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

    public function coinOff($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::COIN_OFF));
            logger('v2_clown_coin_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::COIN_OFF);
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

    public function openPointStatus($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_ALL_STATUS));
            logger('v2_clown_open_point_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_ALL_STATUS);
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
            logger('result：'.$result.PHP_EOL);
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

    public function openPointZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_ZERO));
            logger('v2_clown_open_point_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_ZERO);
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

    

    public function calculateCrc($string)
    {
        $string = str_replace(' ', '', $string);
        
        $calculate = substr($string, 0, -4);

        return Resource::crc16($calculate);
    }

    public function scoreAndRewardStatusZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_SCORE_AND_REWARD_STATUS));
            logger('v2_clown_score_reward_status_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ZERO_SCORE_AND_REWARD_STATUS);
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

    public function scoreAndRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result || $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_SCORE_AND_REWARD));
            logger('v2_clown_score_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ZERO_SCORE_AND_REWARD);
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

    public function grandRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_GRAND_REWARD));
            logger('v2_clown_big_reward_status_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ZERO_GRAND_REWARD);
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

    public function smallRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5 ) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_SMALL_REWARD));
            logger('v2_clown_small_reward_status_zero');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::ZERO_SMALL_REWARD);
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

    public function rewardSwitch($ip, $port){
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;

        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_ON));
            logger('v2_clown_switch_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_1_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on= 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result = $return;
            }
        }
        logger('result：'.$result_on.PHP_EOL);
        usleep(500000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_OFF));
            logger('v2_clown_switch_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_1_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result = $return;
            }
        }
        logger('result：'.$result_off.PHP_EOL);
        return response()->json(['result' => ($result_on && $result_off) ? 1 : 0]);
    }

    public function pressure($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_PRESSURE));
            logger('v2_clown_pressure');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_PRESSURE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $pressure = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'pressure' => $pressure]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function score($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5 ){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_SCORE));
            logger('v2_clown_score');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GET_SCORE);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $score = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'score' => $score]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function sevenDisplay($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SEVEN_DISPLAY));
            logger('v2_clown_seven_display');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SEVEN_DISPLAY);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $score = base_convert(substr($return,6,4), 16, 10);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'score' => $score]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function bigRewardStatus($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BIG_REWARD_STATUS));
            logger('v2_clown_big_reward_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::BIG_REWARD_STATUS);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $status = base_convert(substr($return,6,4), 16, 10);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'status' => $status]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function smallRewardStatus($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SMALL_REWARD_STATUS));
            logger('v2_clown_samll_reward_status');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SMALL_REWARD_STATUS);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
                $status = base_convert(substr($return,6,4), 16, 10);
                logger('result：'.$result.PHP_EOL);
                return response()->json(['result' => $result, 'status' => $status]);
            } else {
                usleep(50000);
                $count++;
                $result = $return;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function joystickShort($ip, $port)
    {
        $result = 0;
        $count = 0;
        // $crc = $this->calculateCrc(self::JOYSTICK_SHORT);
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::JOYSTICK_SHORT));
            logger('v2_clown_joystick_short');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::JOYSTICK_SHORT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::JOYSTICK_SHORT), $return) === 0)
            {
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

    public function joystickLong($ip, $port)
    {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;
        // $crc = $this->calculateCrc(self::JOYSTICK_LONG);
        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::JOYSTICK_LONG));
            logger('v2_clown_joystick_long');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::JOYSTICK_LONG);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::JOYSTICK_LONG), $return) === 0)
            {
                $result_on = 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
                $result_on = $return;
            }
        }
        logger('result_on：'.$result_on.PHP_EOL);
        if($result_on == 0) {
            return response()->json(['result' => $result_on]);
        }
        sleep(5);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::JOYSTICK_LONG_STOP));
            logger('v2_clown_joystick_long_stop');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::JOYSTICK_LONG_STOP);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::JOYSTICK_LONG_STOP), $return) === 0)
            {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
                $result_off = $return;
            }
        }
        logger('result_off：'.$result_off.PHP_EOL);
        return response()->json(['result' => ($result_on & $result_off)]);
    }

    public function stop1($ip, $port)
    {
        $result = 0;
        $count = 0;
        // $crc = $this->calculateCrc(self::STOP_1.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_1));
            logger('v2_clown_stop_1');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_1);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::STOP_1), $return) === 0)
            {
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
        //$crc = $this->calculateCrc(self::STOP_2.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_2));
            logger('v2_clown_stop_2');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_2);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::STOP_2), $return) === 0)
            {
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
        // $crc = $this->calculateCrc(self::STOP_3.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_3));
            logger('v2_clown_stop_3');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_3);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            if(strcasecmp(str_replace(' ', '', self::STOP_3), $return) === 0)
            {
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

    public function stakePoint($ip, $port){
        return response()->json(['result' => 1]);
    }
}
