import utime
import machine
from machine import RTC

rtc = machine.RTC()
rtc.ntp_sync("pool.ntp.org") #Setting time to UTC timezone
utime.sleep_ms(750)
utime.timezone(+3600) #Adjusting time to UTC+1 (for France)
