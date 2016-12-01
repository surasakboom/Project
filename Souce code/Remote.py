#!/usr/bin/env python2.7  -

import RPi.GPIO as GPIO
from socket import *
from time import sleep
import time
from smartcard.System import readers
import binascii

# Thailand ID Smartcard
# define the APDUs used in this script
cid=""
cidold=""
allname=""
nametitleth=""
nameth=""
surnameth=""
nametitleeng=""
nameeng=""
surnameeng=""
Personal_Information = ""
ReadStatus=0
CheckNameStatus=0
NumIP=0
Buttonip1=0
Buttonip2=0
Buttonip3=0
Buttonip4=0


#host = '192.168.51.102' # '127.0.0.1' can also be used
ip = '192.168.51.'
host = ''
port = 52000
DataSend = ""

ChannelChoose = 0

answer = ""


GPIO.setmode(GPIO.BCM)  
  
# GPIO 23 & 17 set up as inputs, pulled up to avoid false detection.  
# Both ports are wired to connect to GND on button press.  
# So we'll be setting up falling edge detection for both  

#GPIO.setup(23, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(19, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(13, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(6, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(5, GPIO.IN, pull_up_down=GPIO.PUD_UP)
#checkname and read
GPIO.setup(11, GPIO.IN, pull_up_down=GPIO.PUD_UP)
#send
GPIO.setup(9, GPIO.IN, pull_up_down=GPIO.PUD_UP)

#lED

#read
GPIO.setup(22, GPIO.OUT)
#ack
GPIO.setup(27, GPIO.OUT)

#abcd
GPIO.setup(12, GPIO.OUT)
GPIO.setup(16, GPIO.OUT)
GPIO.setup(20, GPIO.OUT)
GPIO.setup(21, GPIO.OUT)

GPIO.output(22, False)
GPIO.output(27, False)
GPIO.output(12, False)
GPIO.output(16, False)
GPIO.output(20, False)
GPIO.output(21, False)
# GPIO 24 set up as an input, pulled down, connected to 3V3 on button press  
GPIO.setup(24, GPIO.IN, pull_up_down=GPIO.PUD_UP)
  
# now we'll define two threaded callback functions  
# these will run in another thread when our events are detected

def ReadSmartCard():
    global cid
    global cidold
    global allname
    global nametitleth
    global nameth
    global surnameth
    global nametitleeng
    global nameeng
    global surnameeng
    global Personal_Information
    global ReadStatus
    global CheckNameStatus
    
    state=0
    
    # Reset
    SELECT = [0x00, 0xA4, 0x04, 0x00, 0x08, 0xA0, 0x00, 0x00, 0x00, 0x54, 0x48, 0x00, 0x01]
    
    # CID
    COMMAND1 = [0x80, 0xb0, 0x00, 0x04, 0x02, 0x00, 0x0d]
    COMMAND2 = [0x00, 0xc0, 0x00, 0x00, 0x0d]
    
    # Fullname Thai 
    COMMAND3 = [0x80, 0xb0, 0x00, 0x11, 0x02, 0x00, 0x64]
    COMMAND4 = [0x00, 0xc0, 0x00, 0x00, 0x64]
    
    # Fullname Eng
    COMMAND5 = [0x80, 0xb0, 0x00, 0x75, 0x02, 0x00, 0x64]
    COMMAND6 = [0x00, 0xc0, 0x00, 0x00, 0x64]
    
    # get all the available readers
    r = readers()
    print "Available readers:", r
    
    reader = r[0]
    print "Using:", reader
    
    connection = reader.createConnection()
    connection.connect()
    
    # Reset
    data, sw1, sw2 = connection.transmit(SELECT)
    #print data
    #print "Select Applet: %02X %02X" % (sw1, sw2)
    
    data, sw1, sw2 = connection.transmit(COMMAND1)
    #print data
    #print "Command1: %02X %02X" % (sw1, sw2)
    
    # CID
    data, sw1, sw2 = connection.transmit(COMMAND2)
    #print data
    
    cid=""
    for d in data:
        #print chr(d)
        cid=cid+chr(d)
    print "cid ="+cid
    if(cid!=cidold):
        ReadStatus=0
        CheckNameStatus=0
        
        nametitleth=""
        nameth=""
        surnameth=""
        nametitleeng=""
        nameeng=""
        surnameeng=""
        
            #print
        #print "Command2: %02X %02X" % (sw1, sw2)
        
        # Fullname Thai + Eng + BirthDate + Sex
        data, sw1, sw2 = connection.transmit(COMMAND3)
        #print data
        #print "Command3: %02X %02X" % (sw1, sw2)
        
        data, sw1, sw2 = connection.transmit(COMMAND4)
        #print data
        for d in data:
            #print unicode(chr(d),"tis-620")
            #name=name+unicode(chr(d),"tis-620")
            # 35="#"
            if d==35:
                state=state+1
            else:
                if(state==3):
                    if(d==32):
                        break
                    surnameth=surnameth+unicode(chr(d),"tis-620")
                elif(state==1):
                    nameth=nameth+unicode(chr(d),"tis-620")
                else:
                    nametitleth=nametitleth+unicode(chr(d),"tis-620")
        state=0
        print "nametitleth ="+nametitleth
        print
        print "nameth ="+nameth
        print
        print "surnameth ="+surnameth
        print
        #print "name ="+name
            #print
        #print "Command4: %02X %02X" % (sw1, sw2)
        
        # Address
        data, sw1, sw2 = connection.transmit(COMMAND5)
        #print data
        #print "Command5: %02X %02X" % (sw1, sw2)
        
        data, sw1, sw2 = connection.transmit(COMMAND6)
        #print data
        for d in data:
            #print chr(d)
            #name=name+unicode(chr(d),"tis-620")
            # 35="#"
            if d==35:
                state=state+1
            else:
                if(state==3):
                    if(d==32):
                        break
                    surnameeng=surnameeng+chr(d)
                elif(state==1):
                    nameeng=nameeng+chr(d)
                else:
                    nametitleeng=nametitleeng+chr(d)
        state=0
        print "nametitleeng ="+nametitleeng
        print
        print "nameeng ="+nameeng
        print
        print "surnameeng ="+surnameeng
        print
        Personal_Information=cid+"/"    
        print Personal_Information
        ReadStatus=1
        GPIO.output(22, False)
        sleep(0.3)
        GPIO.output(22, True)
        sleep(0.3)
        GPIO.output(22, False)
        sleep(0.3)
        GPIO.output(22, True)
    cidold=cid
    CheckNameStatus=0
    
def SendToServer(numofchannel):
    print "Pust Button Send"
    global answer
    global ChannelChoose
    global ReadStatus
    global CheckNameStatus
    global NumIP
    global Buttonip1
    global Buttonip2
    global Buttonip3
    global Buttonip4
    global host
    global ip
    
    if ReadStatus==0:
        answer="NoCard"
    else:
        if CheckNameStatus==0:
            answer="CheckName"
            NumIP=0
            if Buttonip1==1:
                NumIP+=1
            if Buttonip2==1:
                NumIP+=2
            if Buttonip3==1:
                NumIP+=4
            if Buttonip4==1:
                NumIP+=8
            host=ip+str(NumIP)
            print host
        else:
            if ChannelChoose==19:
                answer="1"
            elif ChannelChoose==13:
                answer="2"
            elif ChannelChoose==6:
                answer="3"
            elif ChannelChoose==5:
                answer="4"
    
    
    DataSend=Personal_Information+answer
    print "Data send to server ="+DataSend
    
    if answer!="":
        sock = socket()
        #Connecting to socket
        sock.connect((host, port)) #Connect takes tuple of host and port
        sock.send(DataSend)
        #print DataSend
        data = sock.recv(1024)
        # data
        #print type(data)
        #print len(data)
        print data
    
        if(str(data)=="ACK"):
            print "Data from server ="+data
            if CheckNameStatus==0:
                CheckNameStatus=1
            GPIO.output(27, True)
            sleep(1)
            GPIO.output(27, False)
        
    GPIO.output(12, False)
    GPIO.output(16, False)
    GPIO.output(20, False)
    GPIO.output(21, False)
    answer=""
    ChannelChoose=0

#checkname and read
def my_callbackRead(channel):
    print "Pust Button Read"
    ReadSmartCard()
    #global ReadStatus
    

    #if(ReadStatus==0):
    #    ReadSmartCard()
    #    ReadStatus=1
    #else:
    #    print "Readed"
def my_callback1(channel):
    
    #print "Input on pin", channel    
    #SendToServer(channel)
    global ChannelChoose
    global CheckNameStatus
    global NumIP
    global Buttonip1
    global Buttonip2
    global Buttonip3
    global Buttonip4
    
    ChannelChoose=channel
    if CheckNameStatus==0:
        if ChannelChoose==19:
            print "Pust Button "+"1"
            if Buttonip1==0:
                Buttonip1=1
                GPIO.output(12, True)
                print "Buttonip1=1"
            else:
                Buttonip1=0
                print "Buttonip1=0"
                GPIO.output(12, False)
        elif ChannelChoose==13:
            print "Pust Button "+"2"
            if Buttonip2==0:
                Buttonip2=1
                GPIO.output(16, True)
            else:
                Buttonip2=0
                GPIO.output(16, False)
        elif ChannelChoose==6:
            print "Pust Button "+"3"
            if Buttonip3==0:
                Buttonip3=1
                GPIO.output(20, True)
            else:
                Buttonip3=0
                GPIO.output(20, False)
        elif ChannelChoose==5:
            print "Pust Button "+"4"
            if Buttonip4==0:
                Buttonip4=1
                GPIO.output(21, True)
            else:
                Buttonip4=0
                GPIO.output(21, False)

    if CheckNameStatus==1:
        GPIO.output(12, False)
        GPIO.output(16, False)
        GPIO.output(20, False)
        GPIO.output(21, False)
        if ChannelChoose==19:
            GPIO.output(12, True)
            print "Pust Button "+"1"
        elif ChannelChoose==13:
            GPIO.output(16, True)
            print "Pust Button "+"2"
        elif ChannelChoose==6:
            GPIO.output(20, True)
            print "Pust Button "+"3"
        elif ChannelChoose==5:
            GPIO.output(21, True)
            print "Pust Button "+"4"
    
  
def my_callback2(channel):
    print "Input on pin", channel 
    SendToServer(channel)
def my_callback3(channel):
    print "Input on pin", channel
    SendToServer(channel)
def my_callback4(channel):
    print "Input on pin", channel
    SendToServer(channel)   
        
#raw_input("Press Enter when ready\n>")  
   
# when a falling edge is detected on port 17, regardless of whatever   
# else is happening in the program, the function my_callback will be run  
#GPIO.add_event_detect(17, GPIO.FALLING, callback=my_callback, bouncetime=300)  
  
# when a falling edge is detected on port 23, regardless of whatever   
# else is happening in the program, the function my_callback2 will be run  
# 'bouncetime=300' includes the bounce control written into interrupts2a.py  
GPIO.add_event_detect(19, GPIO.FALLING, my_callback1, bouncetime=300)
GPIO.add_event_detect(13, GPIO.FALLING, my_callback1, bouncetime=300)
GPIO.add_event_detect(6, GPIO.FALLING, my_callback1, bouncetime=300)
GPIO.add_event_detect(5, GPIO.FALLING, my_callback1, bouncetime=300)
GPIO.add_event_detect(11, GPIO.FALLING, my_callbackRead, bouncetime=300)
GPIO.add_event_detect(9, GPIO.FALLING, SendToServer, bouncetime=300)  
  
try:
    #ReadSmartCard()
    #print "Waiting for rising edge on port 24"  
    GPIO.wait_for_edge(24, GPIO.RISING)  
    #print "Rising edge detected on port 24. Here endeth the third lesson."
  
except KeyboardInterrupt:  
    GPIO.cleanup()       # clean up GPIO on CTRL+C exit  
GPIO.cleanup()           # clean up GPIO on normal exit  