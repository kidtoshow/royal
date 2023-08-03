from socket import socket, AF_INET, SOCK_STREAM
import argparse
import subprocess

### Setup parsing info
parser = argparse.ArgumentParser()
parser.add_argument('--addr', type=str)
parser.add_argument('--port', type=int)
parser.add_argument('--point', type=int)
args = vars(parser.parse_args())

port = 40000 + int(args['addr'].split('.')[3])

pak7seg = '02 03 00 00 00 01 84 39'
plusSpd = '01 06 00 F7 00 06 B8 3A'
plusAct = '01 05 08 AD FF 00 1F BB'
minusSpd = '01 06 00 F6 00 06 E9 FA'
minusAct = '01 05 08 A3 FF 00 7E 78'

### main
### do conn check
sock = socket(AF_INET, SOCK_STREAM)
sock.settimeout(2)

try:
    sock.connect(('', port))
except OSError:
    print('-1')
else:
    try:
        sock.send(bytes.fromhex(pak7seg))
        msg = sock.recv(1024)
    except socket.timeout:
        print('-2')
    else:
        sock.close()
        if msg == b'-3':
            print('-3')
        elif msg == b'-4':
            print('-4')
        elif msg == b'-5':
            print('-5')
        else:
            currentPoint = int(((msg[3] * 256) + msg[4]) / 100)
            subprocess.Popen(['/usr/bin/python3', '/home/alex/pyScript/pak_open_point_verify_step2.py', '--addr', str(args['addr']), '--port', str(args['port']), '--point', str(args['point']), '--curPoint', str(currentPoint)])
            print(bytes.hex(msg), end='')

            
            '''
            currentPoint = int(((msg[3] * 256) + msg[4]) / 100)
            targetPoint = currentPoint + (args['point'])
            tryCnt = 0

            while (currentPoint != targetPoint and tryCnt < 5):
                #print('Current Point {}'.format(currentPoint))
                #print('Target Point {}'.format(targetPoint))
                
                setPoint = targetPoint - currentPoint
                if setPoint > 0:
                    do_plus_point(setPoint)
                elif setPoint < 0:
                    do_minus_point(setPoint)

                #time.sleep(1)

                msg = send_socket(pak7seg)
                currentPoint = int(((msg[3] * 256) + msg[4]) / 100)
                tryCnt += 1
                
                #print('Result Point {}'.format(currentPoint))
'''

            
        

    
