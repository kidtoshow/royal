from MySQLdb import _mysql
import socket
import time
import argparse
import libscrc

### Setup parser
parser = argparse.ArgumentParser()
parser.add_argument('--ip', type=int)
args = vars(parser.parse_args())

### Setup ip and port
HOST = 'localhost'
PORT = 40000 + args['ip']
DEST = '192.168.20.' + str(args['ip'])
#PORT = 8106
#DEST = '192.168.20.106'

### Setup socket server
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
server.bind((HOST, PORT))
server.setblocking(0)
server.listen(1)

### Modbus commands
cmd7seg = '02 03 00 00 00 01 84 39'
cmdPLC = '01 04 00 D2 00 12 D0 3E'
cmdBigReset = '01 06 00 DE 00 00 E9 F0'
cmdSmallReset = '01 06 00 E3 00 00 78 3C'

### Initialize variables
value7seg = 0
value7segLast = 0
plcValue = {'wash': 0, 'open': 0,
            'press': 0, 'score': 0,
            'bigPress': 0, 'bigScore': 0, 'bigStatus': 0,
            'smallPress': 0, 'smallScore': 0, 'smallStatus': 0,
            'rotateTOT' : 0, 'rotateNP': 0}
plcValueLast = plcValue
rotate32count = 0
beginTime = time.time()


def calc_crc(data):
    if len(data) < 2:
        return False
    crcResult = libscrc.modbus(data[:-2])
    if int(crcResult % 256) == data[-2] and int(crcResult / 256) == data[-1]:
        return True
    else:
        return False

def send_socket(cmd):
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(1.5)

    try:
        sock.connect((DEST, 8000))
    except OSError:
        return b'-3'
    else:
        try:
            if isinstance(cmd, bytes):
                sock.send(cmd)
            else:
                sock.send(bytes.fromhex(cmd))
            msg = sock.recv(1024)
        except socket.timeout:
            return b'-4'
        else:
            sock.close()
            if calc_crc(msg):
                return msg
            else:
                return b'-5'

def get_7seg_data():
    global value7seg
    
    msg = send_socket(cmd7seg)
    try:
        value7seg = (msg[3] * 256) + msg[4]
    except:
        pass
    

def get_plc_data():
    global plcValue
    
    msg = send_socket(cmdPLC)  
    try:
        plcValue['wash'] = (msg[5]*8388608) + (msg[6]*32768) + (msg[3]*256) + msg[4]
        plcValue['open'] = (msg[9]*8388608) + (msg[10]*32768) + (msg[7]*256) + msg[8]
        plcValue['press'] = (msg[13]*8388608) + (msg[14]*32768) + (msg[11]*256) + msg[12]
        plcValue['score'] = (msg[17]*8388608) + (msg[18]*32768) + (msg[15]*256) + msg[16]
        plcValue['bigPress'] = (msg[21]*8388608) + (msg[22]*32768) + (msg[19]*256) + msg[20]
        plcValue['bigScore'] = (msg[25]*8388608) + (msg[26]*32768) + (msg[23]*256) + msg[24]
        plcValue['bigStatus'] = msg[28]
        plcValue['smallPress'] = (msg[31]*8388608) + (msg[32]*32768) + (msg[29]*256) + msg[30]
        plcValue['smallScore'] = (msg[35]*8388608) + (msg[36]*32768) + (msg[33]*256) + msg[34]
        plcValue['smallStatus'] = msg[38]
        plcValue['rotateNP'] = int((plcValue['press'] - plcValue['bigPress'] - plcValue['smallPress']) / 3 )
        plcValue['rotateTOT'] = int(plcValue['press'] / 3)
    except:
        pass

def check_32rotate():
    global rotate32count
    
    if plcValue['bigStatus'] == 1 or plcValue['smallStatus'] == 1:
        rotate32count = plcValue['rotateNP']

    elif plcValue['bigStatus'] == 2 or plcValue['smallStatus'] == 2:
        if (plcValue['rotateNP'] - rotate32count) > 32:
            send_socket(cmdBigReset)
            send_socket(cmdSmallReset)

def update_db():
    global plcValueLast
    global value7segLast
    
    if plcValue != plcValueLast or value7seg != value7segLast:
        value7segLast = value7seg
        plcValueLast = plcValue
        db = _mysql.connect('game-899.net', 'royal', 'royal@2020', 'royal')
        db.query('''UPDATE machines SET
                    rs_led={},
                    rs_wash={}, rs_open={}, rs_pressure={}, rs_score={},
                    rs_big_pressure={}, rs_big_reward={}, rs_during_big_reward_time={},
                    rs_small_pressure={}, rs_small_reward={}, rs_during_small_reward_time={},
                    rs_rpm={}
                    WHERE id={}'''.format(value7seg,
                                         plcValue['wash'], plcValue['open'], plcValue['press'], plcValue['score'],
                                         plcValue['bigPress'], plcValue['bigScore'], plcValue['bigStatus'],
                                         plcValue['smallPress'], plcValue['smallScore'], plcValue['smallStatus'],
                                         plcValue['rotateTOT'],
                                         args['ip']) )
        db.close()

            
###########################
########## Main ###########
        
while True:
    try:
        client, addr = server.accept()
        #print('client IP: {}, PORT: {}'.format(addr[0], addr[1]))
    except:
        pass
    else:
        msg = client.recv(1024)
        msg2 = send_socket(msg)
        client.send(msg2)

    '''
    ### Poll plc info and update DB
    if (time.time() - beginTime >= 1):
        get_7seg_data()
        get_plc_data()
        check_32rotate()
        update_db()
        #print(time.strftime('%H:%M:%S'))
        beginTime = time.time()
    '''
    time.sleep(0.05)
    
