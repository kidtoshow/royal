<?php

namespace App\Http\Controllers\SHUANGMEI\SLOT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class V1Controller extends Controller
{
    CONST LOAD_AUTO_STATUS = '20';
    CONST LOAD_SCORE = '21';
    CONST LOAD_BET = '23';
    CONST LOAD_WIN = '24';
    CONST LOAD_BB = '25';
    CONST LOAD_RB = '26';
    CONST LOAD_OPEN = '27';
    CONST LOAD_WASH = '28';

    CONST OPEN_1 = '41';
    CONST OPEN_5 = '49';
    CONST OPEN_10 = '42';
    CONST WASH_ZERO = '43';
    CONST WASH_REMAINDER = '44';
    CONST MOVE_ON = '45';
    CONST MOVE_OFF = '46';

    CONST START_AUTO = '01';
    CONST STOP_AUTO = '02';
    CONST PRESS = '03';
    CONST START = '04';
    CONST STOP_1 = '05';
    CONST STOP_2 = '06';
    CONST STOP_3 = '07';

    CONST DRAW_STOP_123 = '08';    //抓牌停123
    CONST DRAW_STOP_321 = '09';    //抓牌停321
    CONST RED_7 = '0A';
    CONST BLUE_7 = '0B';
    CONST BAR = '0C';

    CONST OUTPUT = '4B';
    CONST OUT_ALL_OFF = '00';
    CONST OUT_1_ON = '01';
    CONST OUT_2_ON = '02';
    CONST OUT_3_ON = '03';
    CONST OUT_4_ON = '04';
    CONST OUT_5_ON = '05';
    CONST OUT_6_ON = '06';
    CONST OUT_7_ON = '07';
    CONST OUT_8_ON = '08';

    CONST OUT_1_PULSE = '21';
    CONST OUT_2_PULSE = '22';
    CONST OUT_3_PULSE = '23';
    CONST OUT_4_PULSE = '24';
    CONST OUT_5_PULSE = '25';
    CONST OUT_6_PULSE = '26';
    CONST OUT_7_PULSE = '27';
    CONST OUT_8_PULSE = '28';

    CONST REWARD_SWITCH = '2D';// 大賞燈切換
    CONST REWARD_SWITCH_OPT = '64';

    CONST OPEN_ANY_POINT = '4A';
    CONST CLEAR_STATUS = '4F';
    CONST CLEAR_STATUS_OPT = 'FF';

    public function getAll($ip, $port)
    {
        logger('雙美斯洛第一版開洗分狀態紀錄');
        $open_result = $this->execCmd(self::LOAD_OPEN, $ip);
        $wash_result = $this->execCmd(self::LOAD_WASH, $ip);
        $bet_result = $this->execCmd(self::LOAD_BET, $ip);
        $win_result = $this->execCmd(self::LOAD_WIN, $ip);
        $big_reward_result = $this->execCmd(self::LOAD_BB, $ip);
        $small_reward_result = $this->execCmd(self::LOAD_RB, $ip);

        $this->logRecord($open_result);
        $this->logRecord($wash_result);
        $this->logRecord($bet_result);
        $this->logRecord($win_result);
        $this->logRecord($big_reward_result);
        $this->logRecord($small_reward_result);

        if( $open_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $open_result->info]);
        }

        if( $wash_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $wash_result->info]);
        }
        if( $bet_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $bet_result->info]);
        }
        if( $win_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $win_result->info]);
        }
        if( $big_reward_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $big_reward_result->info]);
        }
        if( $small_reward_result->error != 0) {
            return response()->json(['result' => 0, 'message' => $small_reward_result->info]);
        }

        return response()->json([
            'result' => 1,
            'open' => $open_result->data,
            'wash' => $wash_result->data,
            'pressure' => $bet_result->data,
            'score' => $win_result->data,
            'during_big_reward_time' => $big_reward_result->data,
            'during_small_reward_time' => $small_reward_result->data
        ]);


    }

    public function loadScore($ip, $port)
    {
        logger('雙美斯洛第一版讀取開分'. $ip);
        $result = $this->execCmd(self::LOAD_SCORE, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            return response()->json(['result' => 1, 'score' => $result->data, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function loadBet($ip, $port)
    {
        logger('雙美斯洛第一版讀取BET'. $ip);
        $result = $this->execCmd(self::LOAD_BET, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'pressure' => $result->data, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function loadWin($ip, $port)
    {
        logger('雙美斯洛第一版讀取WIN'. $ip);
        $result = $this->execCmd(self::LOAD_WIN, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'score' => $result->data, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function loadBb($ip, $port)
    {
        logger('雙美斯洛第一版讀取BB'. $ip);
        $result = $this->execCmd(self::LOAD_BB, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'status' => $result->data, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function loadRb($ip, $port)
    {
        logger('雙美斯洛第一版讀取RB'. $ip);
        $result = $this->execCmd(self::LOAD_RB, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'status' => $result->data, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function open1($ip, $port)
    {
        logger('雙美斯洛第一版開分一次'. $ip);
        $result = $this->execCmd(self::OPEN_1, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function open5($ip, $port)
    {
        logger('雙美斯洛第一版開分五次'. $ip);
        $result = $this->execCmd(self::OPEN_5, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function open10($ip, $port)
    {
        logger('雙美斯洛第一版開分十次'. $ip);
        $result = $this->execCmd(self::OPEN_10, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function washZero($ip, $port)
    {
        logger('雙美斯洛第一版洗分清零'. $ip);
        $result = $this->execCmd(self::WASH_ZERO, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function washRemainder($ip, $port)
    {
        logger('雙美斯洛第一版洗分餘數'. $ip);
        $result = $this->execCmd(self::WASH_REMAINDER, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function moveOn($ip, $port)
    {
        logger('雙美斯洛第一版移分ON'. $ip);
        $result = $this->execCmd(self::MOVE_ON, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function moveOff($ip, $port)
    {
        logger('雙美斯洛第一版移分OFF'. $ip);
        $result = $this->execCmd(self::MOVE_OFF, $ip);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function startAuto($ip, $port)
    {
        logger('雙美斯洛第一版啟動自動'. $ip);
        $result = $this->execCmd(self::START_AUTO, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function stopAuto($ip, $port)
    {
        logger('雙美斯洛第一版停止自動'. $ip);
        $result = $this->execCmd(self::STOP_AUTO, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function press($ip, $port)
    {
        logger('雙美斯洛第一版壓分'. $ip);
        $result = $this->execCmd(self::PRESS, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function start($ip, $port)
    {
        logger('雙美斯洛第一版開始'. $ip);
        $result = $this->execCmd(self::START, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function stop1($ip, $port)
    {
        logger('雙美斯洛第一版停1'. $ip);
        $result = $this->execCmd(self::STOP_1, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function stop2($ip, $port)
    {
        logger('雙美斯洛第一版停2'. $ip);
        $result = $this->execCmd(self::STOP_2, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function stop3($ip, $port)
    {
        logger('雙美斯洛第一版停3'. $ip);
        $result = $this->execCmd(self::STOP_3, $ip, '00', true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop123Red7($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123紅7'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_123, $ip, self::RED_7, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop123Blue7($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123藍7'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_123, $ip, self::BLUE_7, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop123Bar($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123Bar'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_123, $ip, self::BAR, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop321Red7($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123Bar'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_321, $ip, self::RED_7, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop321Blue7($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123Bar'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_321, $ip, self::BLUE_7, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function drawStop321Bar($ip, $port)
    {
        logger('雙美斯洛第一版抓牌停123Bar'. $ip);
        $result = $this->execCmd(self::DRAW_STOP_321, $ip, self::BAR, true);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function outAllOff($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位全OFF'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_ALL_OFF);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out1On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位1_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_1_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out2On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位2_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_2_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out3On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位3_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_3_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out4On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位4_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_4_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out5On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位1_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_5_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out6On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位6_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_6_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out7On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位7_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_7_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out8On($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位8_ON'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_8_ON);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out1Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位1_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_1_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out2Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位2_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_2_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out3Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位3_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_3_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out4Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位4_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_4_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out5Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位5_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_5_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out6Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位6_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_6_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out7Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位7_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_7_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function out8Pulse($ip, $port)
    {
        logger('雙美斯洛第一版輸出點位8_PULSE'. $ip);
        $result = $this->execCmd(self::OUTPUT, $ip, self::OUT_8_PULSE);
        $this->logRecord($result);

        if($result->error == 0)
        {
            // return response()->json(['result' => 1, 'turn_point' => $result->data]);
            return response()->json(['result' => 1, 'message' => $result->data]);
        } else {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
    }

    public function rewardSwitch($ip, $port)
    {
        logger('雙美斯洛第一版大賞燈切換'. $ip);
        $result = $this->execCmd(self::REWARD_SWITCH, $ip, self::REWARD_SWITCH_OPT);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '大賞燈切換執行成功']);
    }

    public function clearStatus($ip, $port)
    {
        logger('雙美斯洛第一版大賞燈切換'. $ip);
        $result = $this->execCmd(self::CLEAR_STATUS, $ip, self::CLEAR_STATUS_OPT);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response()->json(['result' => 1, 'message' => '清除狀態執行成功']);
    }

    public function openTimes($ip, $port, $number)
    {
        logger('雙美斯洛第一版開分幾次：'.$ip. '次數：'.$number);
        if($number > 5000) {
            logger('雙美斯洛第一版開分'.$number.'次超過開分次數');
            return response(['result' => 0, 'message' => '超過開分次數']);
        }
        $ten_exec_times = intval($number/10);
        $residue10 = intval($number%10);
        $fine_exec_times = intval($residue10 / 5);
        $one_exec_times = intval($residue10 % 5);

        $ten_exec_count = 0;
        $fine_exec_count = 0;
        $one_exec_count = 0;

        while($ten_exec_count < $ten_exec_times) {
            $result = json_decode($this->open10($ip, $port)->getContent());
            if($result->result === 1) {
                $ten_exec_count +=1;
            }
        }

        while($fine_exec_count < $fine_exec_times) {
            $result = json_decode($this->open5($ip, $port)->getContent());
            if($result->result === 1) {
                $fine_exec_count +=1;
            }
        }

        while($one_exec_count < $one_exec_times) {
            $result = json_decode($this->open1($ip, $port)->getContent());
            if($result->result === 1) {
                $one_exec_count +=1;
            }
        }

        return response()->json(['result' => 1, 'message' => '執行開分'.$number.'次成功']);
    }

    public function loadCheckWashStatus($ip, $port)
    {
        logger('雙美斯洛第一版洗分核准狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        //檢查 stat1 第6,7位元 及 stat2 第5,6位元全0 才可以洗分
        //$statCheck = array_merge(str_split(substr($result->stat1, 5,2)), str_split(substr($result->stat2, 4,2)));
        //
        $statCheck = str_split(substr($result->stat1, 5,2));
        $check = array_sum($statCheck) === 0?1:0;
        $index = array_search(1, $statCheck);
        //$message = ['自動', '移分', '小獎', '大獎'];
        $message = ['自動', '移分'];
        return response()->json(['result' => $check, 'message' => $check==1?'OK': '正在'.$message[$index].'中不允許下分', 'data' => $check]);
    }

    public function loadCheckWashKeyStatus($ip, $port)
    {
        logger('雙美斯洛第一版洗分核准狀態值'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

        //檢查 stat2 第234567是否皆為1 若此狀態雙美卡異常
        if(array_sum(str_split($result->stat2)) === 7){
            return response()->json(['result' => 0, 'message' => '雙美卡狀態異常']);
        }

        return response()->json([
            'result' => 1,
            'auto' => substr($result->stat1, 5,1),
            'move_point' => substr($result->stat1, 6,1),
            'small_reward' => substr($result->stat2, 4,1),
            'big_reward' => substr($result->stat2, 5,1),
            'message' => ''
        ]);
    }

    public function grantApproval($ip, $port)
    {
        logger('雙美斯洛第一版開贈核准'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        //檢查 stat2 第5,6位元全0 才可以開贈
        $check = array_sum(str_split(substr($result->stat2, 4,2))) === 0?1:0;
        if($check === 0) {
            return response()->json(['result' => 0, 'message' => '不允許開贈']);
        }
        //檢查 loadScore data 為0 才可以洗分
        $checkScore = $this->loadScore($ip, $port);
        $resultScore = json_decode($checkScore->getContent());
        if ($resultScore->result != 1 || $resultScore->score != 0) {
            return response()->json(['result' => 0, 'message' => '不允許開贈']);
        }

        return response()->json(['result' => $check, 'message' => '允許洗分', 'data' => '']);
    }

    public function bbStatus($ip, $port){
        logger('雙美斯洛第一版大獎狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

        return response()->json(['result' => 1, 'status' => substr($result->stat2, 5,1)]);
    }

    public function rbStatus($ip, $port){
        logger('雙美斯洛第一版大獎狀態'. $ip);
        $result = $this->execCmd(self::LOAD_AUTO_STATUS, $ip);
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }

        return response()->json(['result' => 1, 'status' => substr($result->stat2, 4,1)]);
    }

    public function combineStatus($ip, $port){
        logger('雙美斯洛第一版機台分數狀態'. $ip);
        $sevenCheck = $this->loadScore($ip, $port);
        $sevenResult = json_decode($sevenCheck->getContent());
        if($sevenResult->result != 1)
        {
            return response()->json(['result' => 0, 'message' => $sevenResult->message]);
        }

        usleep(50);
        $betCheck = $this->loadBet($ip, $port);
        $betResult = json_decode($betCheck->getContent());
        if($betResult->result != 1)
        {
            return response()->json(['result' => 0, 'message' => $betResult->message]);
        }

        usleep(50);
        $scoreCheck = $this->loadWin($ip, $port);
        $scoreResult = json_decode($scoreCheck->getContent());
        if($scoreResult->result != 1)
        {
            return response()->json(['result' => 0, 'message' => $scoreResult->message]);
        }

        return response()->json([
           'result' => 1,
           'seven_display' => $sevenResult->score,
           'pressure' => $betResult->pressure,
           'score' => $scoreResult->score
        ]);

    }

    public function checkLottery($ip, $port)
    {
        logger('雙美斯洛第一版確認開獎連莊'. $ip);
        $checkBigReward = $this->bbStatus($ip, $port);
        $checkBigRewardResult = json_decode($checkBigReward->getContent());
        if($checkBigRewardResult->result != 1) {
            return response()->json(['result' => 1]);
        }

        $checkSmallReward = $this->rbStatus($ip, $port);
        $checkSmallRewardResult = json_decode($checkSmallReward->getContent());
        if($checkSmallRewardResult->result != 1) {
            return response()->json(['result' => 1]);
        }

        return response()->json(['result' => ($checkBigRewardResult->status | $checkSmallRewardResult->status)]);
    }

    public function openAnyPoint($ip, $port, $score) {
        logger('雙美斯洛第一版開任意分'. $ip. '開'.$score.'分');
        $result = json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.self::OPEN_ANY_POINT.' --data='. $score));
        $this->logRecord($result);
        if($result->error != 0)
        {
            return response()->json(['result' => 0, 'message' => $result->info]);
        }
        return response(['result' => 1, 'message' => '開'.$score.'分執行成功']);
    }

    private function execCmd($cmd, $ip, $opt='00', $at=false)
    {
        if($at) {
            return json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.$cmd.' --opt='. $opt . ' --at'));
        }
        return json_decode(exec('python3 /home/alex/pyScript/send_socket_mei.py --ip='.$ip.' --cmd='.$cmd.' --opt='. $opt));
    }

    private function logRecord($object)
    {
        logger('error:'.$object->error);
        logger('info:'.$object->info);
        logger('data:'.$object->data);
        logger('cmd:'.$object->cmd);
        logger('stat1:'.$object->stat1);
        logger('stat2:'.$object->stat2.PHP_EOL);
    }
}
