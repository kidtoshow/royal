<?php

namespace App\Http\Controllers\SHUANGMEI\SLOT;

use App\Http\Controllers\Controller;
use App\Library\Crc8Maxim;
use Illuminate\Http\Request;

class V5Controller extends Controller
{
    // 自動卡
    CONST START_AUTO = 'AA5708000001150D';
    CONST STOP_AUTO = 'AA5708000002F70D';
    CONST PRESS = 'AA5708000003A90D';
    CONST START = 'AA57080000042A0D';
    CONST STOP1 = 'AA5708000005740D';
    CONST STOP2 = 'AA5708000006960D';
    CONST STOP3 = 'AA5708000007C80D';
    // 開分卡
    CONST OPEN1 = 'A241000000000000000005050000';
    CONST OPEN10 = 'A242000000000000000005050000';
    CONST WASH_ZERO = 'A243000000000000000005050000';
    CONST WASH_REMAINDER = 'A244000000000000000005050000';
    CONST MOVE_ON = 'A245000000000000000005050000';
    CONST MOVE_OFF = 'A246000000000000000005050000';
    CONST STAKE_ZERO = 'A247000000000000000005050000';
    CONST SCORE_ZERO = 'A248000000000000000005050000';
    CONST BB_RB_ZERO = 'A249000000000000000005050000';
    CONST LOAD_SCORE_1 = 'A221000000000000000005050000';
    CONST LOAD_SCORE_2 = 'A222000000000000000005050000';
    CONST LOAD_SCORE = 'A223000000000000000005050000';
    CONST LOAD_STAKE = 'A224000000000000000005050000';
    CONST LOAD_BB_RB = 'A225000000000000000005050000';

    public function startAuto(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::START_AUTO);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '啟動自動執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '啟動自動執行失敗回傳值：'.$return]);
    }

    public function stopAuto(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::STOP_AUTO);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '停止自動執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '停止自動執行失敗回傳值：'.$return]);
    }

    public function press(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::PRESS);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '壓分執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '壓分執行失敗回傳值：'.$return]);
    }

    public function start(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::START);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '開始執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '開始執行失敗回傳值：'.$return]);
    }

    public function stop1(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::STOP1);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '停止1執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '停止1執行失敗回傳值：'.$return]);
    }

    public function stop2(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::STOP2);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '停止2執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '停止2執行失敗回傳值：'.$return]);
    }

    public function stop3(Request $request) {
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8000 --cmd '. self::STOP3);
        if(!in_array($return,[-1,-2,-3,-4,-5])) {
            return response()->json(['result' => 1, 'message' => '停止3執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '停止3執行失敗回傳值：'.$return]);
    }

    public function open1(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::OPEN1);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::OPEN1.$crc);
        logger(self::OPEN1.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '開分1下執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '開分1下執行失敗回傳值：'.$return]);
    }

    public function open10(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::OPEN10);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::OPEN10.$crc);
        logger(self::OPEN10.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '開分10下執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '開分10下執行失敗回傳值：'.$return]);
    }

    public function washZero(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::WASH_ZERO);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::WASH_ZERO.$crc);
        logger(self::WASH_ZERO.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '洗分歸零執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '洗分歸零執行失敗回傳值：'.$return]);
    }

    public function washRemainder(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::WASH_REMAINDER);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::WASH_REMAINDER.$crc);
        logger(self::WASH_REMAINDER.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '洗分留餘數執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '洗分留餘數執行失敗回傳值：'.$return]);
    }

    public function moveOn(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::MOVE_ON);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::MOVE_ON.$crc);
        logger(self::MOVE_ON.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '移分ON執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '移分ON執行失敗回傳值：'.$return]);
    }

    public function moveOff(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::MOVE_OFF);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::MOVE_OFF.$crc);
        logger(self::MOVE_OFF.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '移分OFF執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '移分OFF執行失敗回傳值：'.$return]);
    }

    public function stakeZero(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::STAKE_ZERO);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::STAKE_ZERO.$crc);
        logger(self::STAKE_ZERO.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '押分歸零執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '押分歸零執行失敗回傳值：'.$return]);
    }

    public function scoreZero(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::SCORE_ZERO);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::SCORE_ZERO.$crc);
        logger(self::SCORE_ZERO.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '得分歸零執行成功']);
        }

        return response()->json(['result' => 0, 'message' => '得分歸零執行失敗回傳值：'.$return]);
    }

    public function bbRbZero(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::BB_RB_ZERO);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::BB_RB_ZERO.$crc);
        logger(self::BB_RB_ZERO.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => 'BB-RB歸零執行成功']);
        }

        return response()->json(['result' => 0, 'message' => 'BB-RB歸零執行失敗回傳值：'.$return]);
    }

    public function loadScore1(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::LOAD_SCORE_1);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::LOAD_SCORE_1.$crc);
        logger(self::LOAD_SCORE_1.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '讀取分數1為：'.$this->transformScore($return)]);
        }

        return response()->json(['result' => 0, 'message' => '讀取分數1執行失敗回傳值：'.$return]);
    }

    public function loadScore2(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::LOAD_SCORE_2);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::LOAD_SCORE_2.$crc);
        logger(self::LOAD_SCORE_2.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '讀取分數2為：'.$this->transformScore($return)]);
        }

        return response()->json(['result' => 0, 'message' => '讀取分數2執行失敗回傳值：'.$return]);
    }

    public function loadScore(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::LOAD_SCORE);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::LOAD_SCORE.$crc);
        logger(self::LOAD_SCORE.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '讀取得分為：'.$this->transformScore($return)]);
        }

        return response()->json(['result' => 0, 'message' => '讀取得分執行失敗回傳值：'.$return]);
    }

    public function loadStake(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::LOAD_STAKE);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::LOAD_STAKE.$crc);
        logger(self::LOAD_STAKE.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '讀取押分為：'.$this->transformScore($return)]);
        }

        return response()->json(['result' => 0, 'message' => '讀取押分執行失敗回傳值：'.$return]);
    }

    public function loadBbRb(Request $request) {
        $crc = (new Crc8Maxim)->ComputeCrc(self::LOAD_BB_RB);
        $return = exec('/usr/bin/python3 /home/alex/pyScript/send_socket_v2.py --addr '.$request->get('ip').' --port 8001 --cmd '. self::LOAD_BB_RB.$crc);
        logger(self::LOAD_BB_RB.$crc);
        if(!in_array($return,[-1,-2,-3,-4,-5]) && $this->checkCrc($return)) {
            return response()->json(['result' => 1, 'message' => '讀取BB-RB為：'.$this->transformScore($return)]);
        }

        return response()->json(['result' => 0, 'message' => '讀取BB-RB執行失敗回傳值：'.$return]);
    }

    private function transformScore($string) {
        $string = str_replace(' ', '', $string);
        return hexdec($string[19].$string[17].$string[15].$string[13].$string[11].$string[9]);
    }

    private function checkCrc($return)
    {
        return strcasecmp((new Crc8Maxim)->ComputeCrc(substr($return, 0, -4)), substr($return, -4)) === 0;
    }
}
