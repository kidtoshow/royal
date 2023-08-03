from socket import socket, AF_INET, SOCK_STREAM
import argparse

### Setup parsing info
parser = argparse.ArgumentParser()
parser.add_argument('--addr', type=str)
parser.add_argument('--port', type=int)
parser.add_argument('--cmd', type=str)
args = vars(parser.parse_args())

port = 40000 + int(args['addr'].split('.')[3])

### Setup socket connection
sock = socket(AF_INET, SOCK_STREAM)
sock.settimeout(2)

try:
    sock.connect(('', port))
except OSError:
    print('-1')
else:
    try:
        sock.send(bytes.fromhex(args['cmd']))
        msg = sock.recv(1024)
    except socket.timeout:
        print('-2')
    else:
        if msg == b'-3':
            print('-3')
        elif msg == b'-4':
            print('-4')
        elif msg == b'-5':
            print('-5')
        else:
            print(bytes.hex(msg))

sock.close()
        
