## Erase file
>BL_RSSI_output.txt 


for i in $(seq 1 24)
do
	system_profiler SPBluetoothDataType | grep RSSI >> BL_RSSI_Output.txt
	system_profiler SPBluetoothDataType | grep RSSI 
	sleep 3;
done



 


## Cas 1 : (D voit H) && (RSSI < -80dB) ==> OK

## Cas 2 : (D ne voit pas H) mais (D & H voient le meme @MAC) ==> ?? (Je pense que dans ce cas si D&H ne se voient pas c'est qu'ils sont trop loin pour permettre un service)
