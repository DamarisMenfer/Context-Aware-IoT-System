## LoPy Wifi @MAC : 30:ae:a4:4e:50:90

from network import WLAN
from network import Bluetooth
import ubinascii
import utime
import json
from microWebCli import MicroWebCli as wc #https://github.com/jczic/MicroWebCli || https://microwebcli.hc2.fr/
from L76GNSS import L76GNSS
from pytrack import Pytrack

##############################################################################################################
### Constant Variables
##############################################################################################################
SERVER_URL = "http://smartcampus.ddns.net:55555/testsimplequery.php"
SERVER_LOGIN = "ISSProject"
SERVER_PWD = "ISSSmartCampus"
NB_SCAN_WIFI = 3            #Number of scan for wifi networks to perform to build the wifi payload
WIFI_DELAY_SCAN_SEC = 10    #Delay between the wifi scans
NB_SCAN_BL = 3              #Number of scan for bluetooth networks to perform to build the bluetooth payload
BL_DELAY_SCAN_SEC = 10      #Delay between the bluetooth scans

################################################################################################################
### Function: Connect to a wifi network.
### Params:
###     - ssid (string): ssid of the wifi network to connect
###     - auth_type: authentification system of the wifi network (None, WLAN.WEP, WLAN.WPA, WLAN.WPA2)
###     - pwd (string): security key the wifi network
################################################################################################################
def connectToWifi(ssid, auth_type, pwd):
    wlan.connect(ssid, auth=(auth_type, pwd))
    utime.sleep(5) #Wait for connexion to be establish
    if wlan.isconnected():
        print("Wifi: Connection Succeeded!")
    else:
        raise Exception("ERROR in connectToWifi - Connection Failed!")


################################################################################################################
### Function: Performs NB_SCAN_WIFI scans and store the wifi networks found in the wifi_networks dict.
### Return: wifi_networks
################################################################################################################
def getWifiNetworks():
    wifi_networks = {}
    wifi_index = 0
    nb_scan = 0

    while nb_scan < NB_SCAN_WIFI:
        nets = wlan.scan() #Performs a network scan and returns a list of named tuples with (ssid, bssid, sec, channel, rssi)
        for net in nets:
            wifi_networks[str(wifi_index)] = {"id": str(ubinascii.hexlify(net.bssid, ":"))[2:19],"rssi": str(net.rssi)}
            wifi_index+=1
        nb_scan+=1
        utime.sleep(WIFI_DELAY_SCAN_SEC)

    return wifi_networks


#################################################################################################################
### Function: Performs NB_SCAN_BL scans and store the bluetooth networks found in the bl_networks dict.
### Return: bl_networks
################################################################################################################
#https://github.com/pycom/pycom-micropython-sigfox/blob/master/docs/library/network.Bluetooth.rst
def getBluetoothNetworks():
    bl_networks = {}
    tmp_mac = []
    bl_index = 0
    nb_scan = 0

    bl = Bluetooth()
    while nb_scan < NB_SCAN_BL:
        print("scan : %d" %nb_scan)
        bl.start_scan(10) #Duration of scan to be define !!!!
        while bl.isscanning():
            adv = bl.get_adv()
            if adv:
                if adv.mac not in tmp_mac:
                    tmp_mac.append(adv.mac)
                    bl_networks[str(bl_index)] = {"id": str(ubinascii.hexlify(adv.mac, ":"))[2:19],"rssi": str(adv.rssi)}
                    print("NAME = %s -- MAC : %s -- RSSI = %d" %(bl.resolve_adv_data(adv.data, bl.ADV_NAME_CMPL), ubinascii.hexlify(adv.mac, ":"), adv.rssi))
                    bl_index+=1
        nb_scan+=1
        tmp_mac = []
        utime.sleep(BL_DELAY_SCAN_SEC)

    return bl_networks


################################################################################################################
### Function: Send HTTP POST Request to the REST server.
### Params:
###     - url (string): url of the server
###     - login (string): login to conect to the server
###     - pwd (string): password to connect to the server
###     - wifi_payload (dict): dict that contains the wifi networks found (cf getWifiNetworks function)
###     - bl_payload (dict): dict that contains the bluetooth networks found (cf getBluetoothNetworks function)
################################################################################################################
def sendData(url, login, pwd, wifi_payload, bl_payload):
    data = {
        "login": "login",
        "password": pwd,
        "signalsWifi": json.dumps(wifi_payload),
        "signalsBle": json.dumps(bl_payload)
    }

    answer = wc.POSTRequest(url, formData=data)
    print(answer)

    #On the server : send an answer when data have been received correctly so we can check :
    #if answer != "good answer": raise Exception(Failed to send correctly the data to the server)
    if answer == b'Not Authorized':
        raise Exception("ERROR in sendData - Server authentification failed")
    else:
        print("Data sent!")


################################################################################################################
################################################################################################################
if __name__ == "__main__":
    wlan = WLAN(mode=WLAN.STA)
    #connectToWifi("iPhone", WLAN.WPA2, "Chvt2401")
    #wifi_payload = getWifiNetworks()
    #print(wifi_payload)
    #bl_payload = getBluetoothNetworks()
    #sendData(SERVER_URL, SERVER_LOGIN, SERVER_PWD, wifi_payload, bl_payload)

    nets = wlan.scan() #Performs a network scan and returns a list of named tuples with (ssid, bssid, sec, channel, rssi)
    for net in nets:
        print(str(ubinascii.hexlify(net.bssid,":"))[2:19])
        print(net.rssi)

## Test GPS
#    py = Pytrack()
#    l76 = L76GNSS(py, timeout=30)
#    coord = l76.coordinates(debug=True)
#    print(coord)
#    if coord == (None, None):
#        utime.sleep(60)
#        coord = l76.coordinates(debug=True)
#        print(coord)
#        utime.sleep(20)
#        coord = l76.coordinates(debug=True)
#        print(coord)
#222 - 43.57088, 1.466232 | 43.57082, 1.465955 | 43.57094, 1.466248
#Lecture - 43.57084, 1.466268 | 43.57077, 1.466468 | 43.57096, 1.466355 ==> Mean = 43.57086, 1.466364

####################################################################
## TO DO
####################################################################

## Calibrer temps de scan bluetooth

####################################################################
####################################################################
