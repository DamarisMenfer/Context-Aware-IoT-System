import matplotlib.pyplot as plt

rssi = []
dist = [0,0,0,1.2,1.2,1.2,2.4,2.4,2.4,3.6,3.6,3.6,4.8,4.8,4.8,6.0,6.0,6.0,7.2,7.2,7.2,8.4,8.4,8.4]
dist2 = [0,1.2,2.4,3.6,4.8,6.0,7.2,8.4]

def mean_rssi():
    y = 0
    z = 0
    mean_rssi = []
    for x in range(0, 8):
        z += 3
        summ = 0
        
        while (y<z):
            summ += int_rssi[y]
            y += 1
            
        mean_rssi.append(summ/3.0)
    
    return mean_rssi



if __name__ == "__main__":
    f = open("BL_RSSI_output.txt", 'r')
    
    data = f.read(20)
    while (data != ""):
        rssi.append(f.read(3))
        data = f.read(21)
    
    rssi.remove("")
    
    ax = plt.figure().add_subplot(111)
    ax.xaxis.set_label_position('top')
    ax.xaxis.tick_top()
    #plt.gca().invert_yaxis()
    ax.set_xlabel("Distance (m)")
    ax.set_ylabel("RSSI (dB)")
    #plt.title('Bluetoothw RSSI / distance')
    plt.grid(True)
    #ax.plot(dist, rssi)
    #plt.show()

    
    int_rssi = [int(i) for i in rssi]
    mean_rssi = mean_rssi()
    #plt.figure()
    ax.plot(dist2, mean_rssi)
    plt.show()
    
    ##Y-SCALE?????????