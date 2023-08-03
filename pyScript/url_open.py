import urllib.request
import time

url = "http://game-899.net/reward"

checkTime = 0

while True:
    if (time.time() - checkTime) >= 1:
        urllib.request.urlopen(url)
        checkTime = time.time()

    time.sleep(0.2)

