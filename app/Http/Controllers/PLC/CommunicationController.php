<?php

namespace App\Http\Controllers\PLC;

use App\Http\Controllers\Controller;
use App\Library\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    CONST WASH_POINT_CONTROL = '01 05 0F B4 FF 00 CF 08';
    CONST OPEN_POINT_AMOUNT = '01 06 00 E6 00';
    CONST OPEN_POINT_CONTROL = '01 05 0F B5 FF 00 9E C8';
    CONST REFUND_ON = '01 05 0F B6 FF 00 6E C8';
    CONST REFUND_OFF = '01 05 0F B6 00 00 2F 38';
    CONST COIN_ON = '01 05 0F B7 FF 00 3F 08';
    CONST COIN_OFF = '01 05 0F B7 00 00 7E F8';
    CONST BIG_REWARD_STATUS = '01 04 00 D2 00 04 51 F0';
    CONST SMALL_REWARD_STATUS = '01 04 00 E3 00 01 C0 3C';
    CONST OPEN_POINT_ZERO = '01 05 0F AA FF 00 AF 0E';
    CONST CHECK_AUTO_CARD = 'AA 57 08 00 00 00 4B 0D';
    CONST START_AUTO = 'AA 57 08 00 00 01 15 0D';
    CONST STOP_AUTO = 'AA 57 08 00 00 02 F7 0D';
    CONST STAKE_POINT = 'AA 57 08 00 00 03 A9 0D';
    CONST GAME_START = 'AA 57 08 00 00 04 2A 0D';
    CONST STOP_1 = 'AA 57 08 00 00 05 74 0D';
    CONST STOP_2 = 'AA 57 08 00 00 06 96 0D';
    CONST STOP_3 = 'AA 57 08 00 00 07 C8 0D';
    CONST GET_ALL_STATUS = '01 04 00 D2 00 12 D0 3E';
    CONST ZERO_SCORE_AND_REWARD_STATUS = '01 05 0F AB FF 00 FE CE';
    CONST ZERO_SCORE_AND_REWARD = '01 05 0F AC FF 00 4F 0F';
    CONST ZERO_GRAND_REWARD = '01 05 0F AD FF 00 1E CF';
    CONST ZERO_SMALL_REWARD = '01 05 0F AE FF 00 EE CF';
    CONST FLASH_1_ON = '01 05 0F B8 FF 00 0F 0B';
    CONST FLASH_1_OFF = '01 05 0F B8 00 00 4E FB';
    CONST FLASH_2_ON = '01 05 0F B9 FF 00 5E CB';
    CONST FLASH_2_OFF = '01 05 0F B9 00 00 1F 3B';
    CONST GET_PRESSURE = '01 04 00 D6 00 02 90 33';
    CONST GET_SCORE = '01 04 00 D8 00 02 F1 F0';
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::CHECK_AUTO_CARD));
            // $return = Resource::send_socket($ip, $port, self::CHECK_AUTO_CARD);
            logger('eheck_auto_card'.PHP_EOL);
            logger('ip:'.$ip.PHP_EOL);
            logger('port:'.$port);
            logger('指令：'.self::CHECK_AUTO_CARD.PHP_EOL);
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::START_AUTO));
            // $return = Resource::send_socket($ip, $port, self::START_AUTO);
            logger('start_auto');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::START_AUTO);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::START_AUTO), $return) === 0)
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_AUTO));
            // $return = Resource::send_socket($ip, $port, self::STOP_AUTO);
            logger('stop_auto');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_AUTO);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_AUTO), $return) === 0)
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_POINT));
            // $return = Resource::send_socket($ip, $port, self::STAKE_POINT);
            logger('stake_point');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STAKE_POINT);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STAKE_POINT), $return) === 0)
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::GAME_START));
            // $return = Resource::send_socket($ip, $port, self::GAME_START);
            logger('start_game');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::GAME_START);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::GAME_START), $return) === 0)
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
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_1));
            // $return = Resource::send_socket($ip, $port, self::STOP_1);
            logger('stop_1');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_1);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_1), $return) === 0)
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_2));
            // $return = Resource::send_socket($ip, $port, self::STOP_2);
            logger('stop_2');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_2);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_2), $return) === 0)
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

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STOP_3));
            // $return = Resource::send_socket($ip, $port, self::STOP_3);
            logger('stop_3');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::STOP_3);
            logger('回傳值：'.$return);
            logger('time:'.Carbon::now()->format('Y-m-d H:i:s:v'));
            // $check = $this->calculateCrc($return, 6);
            if(strcasecmp(str_replace(' ', '', self::STOP_3), $return) === 0)
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

    public function flash1On($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_ON));
            // $return = Resource::send_socket($ip, $port, self::FLASH_1_ON);
            logger('flash_1_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_1_ON);
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

    public function flash1Off($ip, $port)
    {
        $result = 0;
        $count = 0;
        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_1_OFF));
            // $return = Resource::send_socket($ip, $port, self::FLASH_1_OFF);
            logger('flash_1_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_1_OFF);
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

    public function flash2On($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$result && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_2_ON));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_ON);
            logger('flash_2_on');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_2_ON);
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

    public function flash2Off($ip, $port)
    {
        $result = 0;
        $count = 0;

        while(!$resutl && $count < 5) {
            $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::FLASH_2_OFF));
            // $return = Resource::send_socket($ip, $port, self::FLASH_2_OFF);
            logger('flash_2_off');
            logger('ip:'.$ip);
            logger('port:'.$port);
            logger('指令：'.self::FLASH_2_OFF);
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

    public function auto_test(Request $request)
    {
        // dd($request->all());
        $result = 0;
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$ip.' --port '.$port.' --cmd '. str_replace(' ', '', self::STAKE_POINT));
        // $return = Resource::send_socket($request->get('ip'), $request->get('port'), self::STAKE_POINT);
        logger('押分測試頁面');
        logger('ip:'.$request->get('ip'));
        logger('port:'.$request->get('port'));
        logger('指令：'.self::STAKE_POINT);
        logger('自動卡回傳值：'.$return.PHP_EOL);
        
        return view('auto_test')->with(['message' => '自動卡回傳值：'.$return.PHP_EOL]);
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
}
