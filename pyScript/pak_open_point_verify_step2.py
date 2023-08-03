from socket import socket, AF_INET, SOCK_STREAM
import argparse
import libscrc
import time

#st = time.time()
### Setup parsing info
parser = argparse.ArgumentParser()
parser.add_argument('--addr', type=str)
parser.add_argument('--port', type=int)
parser.add_argument('--point', type=int)
parser.add_argument('--curPoint', type=int)
args = vars(parser.parse_args())

port = 40000 + int(args['addr'].split('.')[3])

pak7seg = '02 03 00 00 00 01 84 39'
plusSpd = '01 06 00 F7 00 08 39 FE'
plusAct = '01 05 08 AD FF 00 1F BB'
minusSpd = '01 06 00 F6 00 08 68 3E'
minusAct = '01 05 08 A3 FF 00 7E 78'

def add_crc(data):
    crcResult = libscrc.modbus(bytes.fromhex(data))
    crc1 = '%02X' % int(crcResult % 256)
    crc2 = '%02X' % int(crcResult / 256)
    result = data + ' ' +crc1 + ' ' + crc2
    return result

def send_socket(cmd):
    sock = socket(AF_INET, SOCK_STREAM)
    sock.settimeout(2)

    try:
        sock.connect(('', port))
    except OSError:
        return b'-1'
    else:
        try:
            sock.send(bytes.fromhex(cmd))
            msg = sock.recv(1024)
        except socket.timeout:
            return b'-2'
        else:
            sock.close()
            return(msg)

def do_plus_point(point):
    setPointCmd = '01 06 00 ED 00 ' + ('%02X' % point)
    setPointCmd = add_crc(setPointCmd)

    send_socket(setPointCmd)
    send_socket(plusSpd)
    send_socket(plusAct)

    
def do_minus_point(point):
    point = abs(point)
    setPointCmd = '01 06 00 EC 00 ' + ('%02X' % point)
    setPointCmd = add_crc(setPointCmd)

    send_socket(setPointCmd)
    send_socket(minusSpd)
    send_socket(minusAct)

### main
currentPoint = args['curPoint']
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

    time.sleep(setPoint * 0.25)

    msg = send_socket(pak7seg)
    currentPoint = int(((msg[3] * 256) + msg[4]) / 100)
    tryCnt += 1
    
    #print('Result Point {}'.format(currentPoint))

#et = time.time()

#print('Done')
#print(et-st)


            
        

    
