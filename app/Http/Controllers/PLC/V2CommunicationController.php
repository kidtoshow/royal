<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class V2CommunicationController extends Controller
{
	//取值韋峯部分
	// 洗分控制
    CONST WASH_POINT_CONTROL = '01 05 08 14 FF 00 CE 5E';
    // 壓分
    CONST OPEN_POINT_AMOUNT = '01 06 00 E6 00';
    // 開分控制
    CONST OPEN_POINT_CONTROL = '01 05 08 15 FF 00 9F 9E';
    // 退幣ＯＮ
    CONST REFUND_ON = '01 05 08 16 FF 00 6F 9E';
    // 退幣ＯＦＦ
    CONST REFUND_OFF = '01 05 08 16 00 00 2E 6E';
    // 投幣ＯＮ
    CONST COIN_ON = '01 05 08 17 FF 00 3E 5E';
    // 退幣ＯＦＦ
    CONST COIN_OFF = '01 05 08 17 00 00 7F AE';
    // 大獎狀態
    CONST BIG_REWARD_STATUS = '01 04 00 DE 00 01 51 F0';
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
    CONST FLASH_1_ON = '01 05 08 18 FF 00 0E 5D';
    // 大閃燈key1_OFF
    CONST FLASH_1_OFF = '01 05 08 18 00 00 4F AD';
    // 大閃燈key2_ON 20200305後改為 CHANCE_ON
    CONST FLASH_2_ON = '01 05 08 19 FF 00 5F 9D';
    // 大閃燈key2_OFF 20200305後改為 CHANCE_OFF
    CONST FLASH_2_OFF = '01 05 08 19 00 00 1E 6D';
    // 取得壓分
    CONST GET_PRESSURE = '01 04 00 D6 00 02 90 33';
    // 取得得分
    CONST GET_SCORE = '01 04 00 D8 00 02 F1 F0';
    ////自動卡阿勳部分/////////////////////////////////////////
    // 檢查自動卡
    CONST CHECK_AUTO_CARD = '02 14 AA 57 08 00 00 00 4B 0D';
    // 啟動自動
    CONST START_AUTO = '02 14 AA 57 08 00 00 01 15 0D';
    // 停止自動
    CONST STOP_AUTO = '02 14 AA 57 08 00 00 02 F7 0D';
    // 壓分
    CONST STAKE_POINT = '02 14 AA 57 08 00 00 03 A9 0D';
    // 開始
    CONST GAME_START = '02 14 AA 57 08 00 00 04 2A 0D';
    // 停1
    CONST STOP_1 = '02 14 AA 57 08 00 00 05 74 0D';
    // 停2
    CONST STOP_2 = '02 14 AA 57 08 00 00 06 96 0D';
    // 停3
    CONST STOP_3 = '02 14 AA 57 08 00 00 07 C8 0D';
    //抓牌 停123 紅7	
    CONST STOP_123_RED_7 = '02 14 AA 57 08 0A 00 08 E3 0D';
    //抓牌 停123 藍7	
    CONST STOP_123_BLUE_7 = '02 14 AA 57 08 0B 00 08 48 0D';
    // 抓牌 停123 BAR
    CONST STOP_123_BAR = '02 14 AA 57 08 0C 00 08 32 0D';
    //抓牌 停321 紅7
    CONST STOP_321_RED_7 = '02 14 AA 57 08 0A 00 09 BD 0D';
    //抓牌 停321 藍7	
    CONST STOP_321_BULE_7 = '02 14 AA 57 08 0B 00 09 16 0D';
    //抓牌 停321 BAR
    CONST STOP_321_BAR = '02 14 AA 57 08 0C 00 09 6C 0D';
    //七段顯示器
    CONST SEVEN_DISPLAY = '02 03 00 00 00 01 84 39';

    public function washPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::WASH_POINT_CONTROL));
            // $return = Resource::send_socket($ip, $port, self::WASH_POINT_CONTROL);
            logger('wash_point_control');
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
            }
        }

        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }

        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function openPoint($ip, $port, $amount)
    {
        $result = 0;
        $count = 0;


        $string = self::OPEN_POINT_AMOUNT. ' '. sprintf('%02s',dechex($amount));
        $check_sum = $this->calculateCrc($string.'0000');
        $string .= $check_sum;
        logger('open_point');
        while(!$result && $count < 5) {
            logger('before socket');
            logger('指令：'.$string);
            logger('crc值:'. $check_sum);
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', $string));
            //$return = Resource::send_socket($ip, $port, $string);
            logger('after socket');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function openPointControl($ip, $port)
    {
        $result = 0;
        $count = 0;
        while (!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::OPEN_POINT_CONTROL));
            // $return = Resource::send_socket($ip, $port, self::OPEN_POINT_CONTROL);
            logger('open_point_control');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::OPEN_POINT_CONTROL);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            // logger('check:'.$check);
            // logger('後四碼:'.substr($return, -4, 4));
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function refundOn($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5 ) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::REFUND_ON));
            // $return = Resource::send_socket($ip, $port, self::REFUND_ON);
            logger('refund_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::REFUND_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function refundOff($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::REFUND_OFF));
            // $return = Resource::send_socket($ip, $port, self::REFUND_OFF);
            logger('refund_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::REFUND_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function coinOn($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::COIN_ON));
            // $return = Resource::send_socket($ip, $port, self::COIN_ON);
            logger('coin_on');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function coinOff($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::COIN_OFF));
            // $return = Resource::send_socket($ip, $port, self::COIN_OFF);
            logger('coin_off');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
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
            // $check = $this->calculateCrc($return, 11);
            logger('open_point_status');
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
            // $return = Resource::send_socket($ip, $port, self::OPEN_POINT_ZERO);
            logger('open_point_zero');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function checkAutoCard($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::CHECK_AUTO_CARD.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::CHECK_AUTO_CARD . $crc));
            // $return = Resource::send_socket($ip, $port, self::CHECK_AUTO_CARD);
            logger('eheck_auto_card'.PHP_EOL);
            logger('ip:'.$ip.PHP_EOL);
            logger('port:'.$port);
            logger('指令：'.self::CHECK_AUTO_CARD.$crc.PHP_EOL);
            logger('回傳值：'.$return.PHP_EOL);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);

            if(strcasecmp($check, substr($return, -4, 4)) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result);
        return response()->json(['result' => $result]);
    }

    public function startAuto($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::START_AUTO.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::START_AUTO .$crc ));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('start_auto');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::START_AUTO . $crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::START_AUTO.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function stopAuto($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_AUTO.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_AUTO.$crc));
            // $return = Resource::send_socket($ip, $port, self::STOP_AUTO);
            logger('stop_auto');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_AUTO.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_AUTO.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function stakePoint($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STAKE_POINT.'0000');

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_POINT.$crc));
            // $return = Resource::send_socket($ip, $port, self::STAKE_POINT);
            logger('stake_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAKE_POINT.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STAKE_POINT.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function startGame($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::GAME_START.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GAME_START.$crc));
            // $return = Resource::send_socket($ip, $port, self::GAME_START);
            logger('start_game');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GAME_START.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::GAME_START.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function stop1($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_1.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_1. $crc));
            // $return = Resource::send_socket($ip, $port, self::STOP_1);
            logger('stop_1');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_1.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_1.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function stop2($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_2.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_2.$crc));
            // $return = Resource::send_socket($ip, $port, self::STOP_2);
            logger('stop_2');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_2.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_2.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function stop3($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_3.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_3.$crc));
            // $return = Resource::send_socket($ip, $port, self::STOP_3);
            logger('stop_3');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_3.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_3.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
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
            // $return = Resource::send_socket($ip, $port, self::ZERO_SCORE_AND_REWARD_STATUS);
            logger('score_reward_status_zero');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function scoreAndRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result || $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_SCORE_AND_REWARD));
            // $return = Resource::send_socket($ip, $port, self::ZERO_SCORE_AND_REWARD);
            logger('score_zero');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function grandRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_GRAND_REWARD));
            // $return = Resource::send_socket($ip, $port, self::ZERO_GRAND_REWARD);
            logger('big_reward_status_zero');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function smallRewardZero($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5 ) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::ZERO_SMALL_REWARD));
            // $return = Resource::send_socket($ip, $port, self::ZERO_SMALL_REWARD);
            logger('small_reward_status_zero');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    // public function flash1On($ip, $port)
    // {
    //     $result = 0;
    //     $count = 0;
    //     while(!$result && $count < 5) {
    //         $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_ON));
    //         // $return = Resource::send_socket($ip, $port, self::FLASH_1_ON);
    //         logger('flash_1_on');
    //         logger('ip:'.$ip);
    //         logger('port:'.$port);
    //         logger('指令：'.self::FLASH_1_ON);
    //         logger('回傳值：'.$return);
    //         logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
    //         $check = $this->calculateCrc($return);
    //         if (strcasecmp($check, substr($return, -4, 4)) === 0) {
    //             $result = 1;
    //             $count = 6;
    //         } else {
    //             usleep(50000);
    //             $count++;
    //         }
    //     }
    //     // if(strcasecmp($check, substr($return, -4, 4)) === 0)
    //     // {
    //     //     $result = 1;
    //     // }
    //     logger('result：'.$result.PHP_EOL);
    //     return response()->json(['result' => $result]);
    // }

    // public function flash1Off($ip, $port)
    // {
    //     $result = 0;
    //     $count = 0;
    //     while(!$result && $count < 5) {
    //         $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_OFF));
    //         // $return = Resource::send_socket($ip, $port, self::FLASH_1_OFF);
    //         logger('flash_1_off');
    //         logger('ip:'.$ip);
    //         logger('port:'.$port);
    //         logger('指令：'.self::FLASH_1_OFF);
    //         logger('回傳值：'.$return);
    //         logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
    //         $check = $this->calculateCrc($return);
    //         if (strcasecmp($check, substr($return, -4, 4)) === 0) {
    //             $result = 1;
    //             $count = 6;
    //         } else {
    //             usleep(50000);
    //             $count++;
    //         }
    //     }
    //     // if(strcasecmp($check, substr($return, -4, 4)) === 0)
    //     // {
    //     //     $result = 1;
    //     // }
    //     logger('result：'.$result.PHP_EOL);
    //     return response()->json(['result' => $result]);
    // }

    public function chance($ip, $port)
    {
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;

        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_2_ON));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_ON);
            logger('chance_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_2_ON);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_on= 1;
                $count_on = 6;
            } else {
                usleep(50000);
                $count_on++;
            }
        }
        logger('result：'.$result_on.PHP_EOL);
        usleep(500000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_2_OFF));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_ON);
            logger('chance_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_2_OFF);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            $check = $this->calculateCrc($return);
            if (strcasecmp($check, substr($return, -4, 4)) === 0) {
                $result_off = 1;
                $count_off = 6;
            } else {
                usleep(50000);
                $count_off++;
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result_off.PHP_EOL);
        return response()->json(['result' => ($result_on && $result_off) ? 1 : 0]);
    }

    public function rewardSwitch($ip, $port){
        $result_on = 0;
        $count_on = 0;
        $result_off = 0;
        $count_off = 0;

        while(!$result_on && $count_on < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_ON));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_ON);
            logger('switch_on');
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
            }
        }
        logger('result：'.$result_on.PHP_EOL);
        usleep(500000);
        while(!$result_off && $count_off < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_OFF));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_ON);
            logger('switch_off');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        // }
        logger('result：'.$result_off.PHP_EOL);
        return response()->json(['result' => ($result_on && $result_off) ? 1 : 0]);
    }

    // public function flash2Off($ip, $port)
    // {
    //     $result = 0;
    //     $count = 0;

    //     while(!$resutl && $count < 5) {
    //         $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_2_OFF));
    //         // $return = Resource::send_socket($ip, $port, self::FLASH_2_OFF);
    //         logger('flash_2_off');
    //         logger('ip:'.$ip);
    //         logger('port:'.$port);
    //         logger('指令：'.self::FLASH_2_OFF);
    //         logger('回傳值：'.$return);
    //         logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
    //         $check = $this->calculateCrc($return);
    //         if (strcasecmp($check, substr($return, -4, 4)) === 0) {
    //             $result = 1;
    //             $count = 6;
    //         } else {
    //             usleep(50000);
    //             $count++;
    //         }
    //     }
    //     // if(strcasecmp($check, substr($return, -4, 4)) === 0)
    //     // {
    //     //     $result = 1;
    //     // }
    //     logger('result：'.$result.PHP_EOL);
    //     return response()->json(['result' => $result]);
    // }

    public function pressure($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_PRESSURE));
            // $return = Resource::send_socket($ip, $port, self::GET_PRESSURE);
            logger('pressure');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        //     $pressure = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768);
        //     return response()->json(['result' => $result, 'pressure' => $pressure]);
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function score($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5 ){
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GET_SCORE));
            // $return = Resource::send_socket($ip, $port, self::GET_SCORE);
            logger('score');
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
            }
        }
        // if(strcasecmp($check, substr($return, -4, 4)) === 0)
        // {
        //     $result = 1;
        //     $score = base_convert(substr($return,6,4), 16, 10) + (base_convert(substr($return,10, 4), 16, 10) * 32768);
        //     return response()->json(['result' => $result, 'score' => $score]);
        // }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop123Red7($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_123_RED_7.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_123_RED_7.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_card_stop_123_red_7');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_123_RED_7.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_123_RED_7.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop123Blue7($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_123_BLUE_7.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_123_BLUE_7.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_card_stop_123_blue_7');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_123_BLUE_7.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_123_BLUE_7.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop123Bar($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_123_BAR."0000");
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_123_BAR.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_card_stop_123_bar');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_123_BAR.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_123_BAR.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop321Red7($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_321_RED_7.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_321_RED_7.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_card_stop_321_red_7');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_321_RED_7.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_321_RED_7.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop321Blue7($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_321_BULE_7.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_321_BULE_7.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_card_stop_321_blue_7');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_321_BULE_7.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_321_BULE_7.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function drawCardStop321Bar($ip, $port)
    {
        $result = 0;
        $count = 0;
        $crc = $this->calculateCrc(self::STOP_321_BAR.'0000');
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_321_BAR.$crc));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('draw_crad_stop_321_bar');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_321_BAR.$crc);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_321_BAR.$crc), $return) === 0)
            {
                $result = 1;
                $count = 6;
            } else {
                usleep(50000);
                $count++;
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
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('seven_display');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::SEVEN_DISPLAY);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
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
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function big_reward_status($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::BIG_REWARD_STATUS));
            // $return = Resource::send_socket($ip, $port, self::BIG_REWARD_STATUS);
            logger('big_reward_status');
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
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }

    public function small_reward_status($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::SMALL_REWARD_STATUS));
            // $return = Resource::send_socket($ip, $port, self::SMALL_REWARD_STATUS);
            logger('samll_reward_status');
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
            }
        }
        logger('result：'.$result.PHP_EOL);
        return response()->json(['result' => $result]);
    }
}
