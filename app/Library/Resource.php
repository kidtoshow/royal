<?php
namespace App\Library;

class Resource
{
    public static function send_socket($address = '', $port = '', $string)
    {
        $sendStrArray = str_split(str_replace(' ', '', $string), 2);
        $socket = @socket_create ( AF_INET, SOCK_STREAM, SOL_TCP );
        // socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 0, 'usec' => 200]);
        // socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 0, 'usec' => 800]);
        $aaa=array();
        // $timeout = 5;
        // $time = time();
        // @socket_set_nonblock($socket);
        // while (!@socket_connect($socket, $address, $port)) {
        //     $err = socket_last_error($socket);
        //     if ($err === 10056) {
        //       break;
        //     }
        //     if ((time() - $time) >= $timeout) {
        //       socket_close($socket);
        //       return 0;
        //       exit();
        //     }
        //     usleep(250000);
        // }
        // @socket_set_block($socket);
        @socket_connect($socket, $address, $port);
        // @socket_read($socket, 1024, PHP_BINARY_READ);
        for ($j = 0; $j < count($sendStrArray); $j++) {
            array_push($aaa,chr(hexdec($sendStrArray[$j])));
        }
        $bbb=implode("",$aaa);
        socket_write($socket, $bbb, strlen($bbb));
        $receiveStr = "";
        $receiveStr = socket_read($socket, 1024, PHP_BINARY_READ);
        $receiveStrHex = bin2hex($receiveStr);
        socket_close($socket); 
        return $receiveStrHex;
    }

    public static function crc16($string)
    {
        $data = pack('H*', str_replace(' ', '', $string));
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]);
            for ($j = 8; $j != 0; $j--) {
                if (($crc & 0x0001) != 0) {
                    $crc >>= 1;
                    $crc ^= 0xA001;
                } else {
                    $crc >>= 1;
                }
            }
        }
        $result = sprintf('%04X', $crc);
        
        $modbusCrc = substr($result, 2, 2) . substr($result, 0, 2);
        return $modbusCrc;
    }

    public static function calculateCrc($string)
    {
        $string = str_replace(' ', '', $string);
        
        $calculate = substr($string, 0, -4);

        return self::crc16($calculate);
    }
}