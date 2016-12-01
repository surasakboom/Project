import sys, os, socket,requests
from thread import *
from time import sleep
import json


#Receive from Remote 
HostRemote = '192.168.51.10'   # Symbolic name meaning all available interfaces
PortRemote = 52000 # Arbitrary non-privileged port

#Send to Server insert database
PortServer = 10000
HostServer = '192.168.73.35'

#Receive from PHP 
HostPhp = ""
PortPhp = 12345

urlA = 'http://192.168.51.50/clicker/production/php/send-answer.php'
urlB = 'http://192.168.51.50/clicker/production/php/send-check-student.php'

#variable Receive from Remote
Result =[]
#variable Receive from PHP
QuestionN0=0
Secnum=""
CidChecked=[]
Cidcheckstatus=0
#variable Socket
backlog = 5 # Number of clients on wait.
buf_size = 1024

try:
        socket.setdefaulttimeout(0.5) # raise a socket.timeout error after a half second
        listening_sockets = []
        #Remote
        listening_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        listening_socket.setsockopt(socket.SOL_SOCKET,socket.SO_REUSEADDR,1) 
        listening_socket.bind((HostRemote, PortRemote)) 
        listening_socket.listen(backlog)
        listening_sockets.append(listening_socket)
        print 'Socket now Remote'
        #PHP
        listening_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        listening_socket.setsockopt(socket.SOL_SOCKET,socket.SO_REUSEADDR,1) 
        listening_socket.bind((HostPhp, PortPhp)) 
        listening_socket.listen(backlog)
        listening_sockets.append(listening_socket)
        #print 'Socket now PHP'
except socket.error, (value, message):
        if listening_socket:
                listening_socket.close()
        print 'Could not open socket: ' + message
        sys.exit(1)
def SendResultToServer():
        #socketserver   = socket()
        #socketserver.connect((HostServer, PortServer))
        sock = socket()
        #Connecting to socket
        sock.connect((host, port)) #Connect takes tuple of host and port
        js =json.dumps(Result)
        print "SendResultToServer= "+js
        #socketserver.send(js)
        sock.send(js)
        #data = socketserver.recv(1024)
        data = sock.recv(1024)
        if data==ACK:
                #socketserver.close()
                sock.close()
                print data
        else:
                SendResultToServer()
                
def clientthread(conn):
    HostReceived,PortReceived=conn.getsockname()
    #print PortReceived
    global Secnum
    global CidChecked
    global Cidcheckstatus
    data = conn.recv(1024) # 1024 stands for bytes of data to be received
    print "received data = "+data
    Sectionid=0
    if(PortReceived==PortRemote):
        dic={}
        
        #cid,nametitleeng,nameeng,surnameeng,answer=data.split("/")
        cid,answer=data.split("/")
        #dic["questionno"]=QuestionNo
        dic["cid"]=cid
        #dic["nametitleeng"]=nametitleeng
        #dic["nameeng"]=nameeng
        #dic["surnameeng"]=surnameeng
        dic["answer"]=answer
        #if dic not in Result:
                #Result.append(dic)
                #answer=1
                #cid=2016
        if answer!="CheckName":
                if len(answer) >0:
                        #url="http://172.19.201.255/clicker/production/php/send-answer.php?answer="+answer+"&id="+cid
                        #r= requests.get(url)
                        
                        payload = {'answer': answer , 'id': cid , 'sid': Secnum}
                        # POST with form-encoded data
                        r = requests.post(urlA, data=payload)
                        
                        print "Send Answer To Server"              
                #SendResultToServer()
        else:
                Cidcheckstatus=0
                if len(answer) >0:
                        for i in CidChecked:
                            if cid==i :
                               Cidcheckstatus=1
                        print CidChecked
                        print Cidcheckstatus
                        if Cidcheckstatus==0:
                                #url="http://172.19.201.255/clicker/production/php/send-check-student.php?student_id="+cid
                                #r= requests.get(url)
                                payload = {'student_id': cid , 'section_id': Secnum}
                                # POST with form-encoded data
                                print urlB
                                print payload
                                r = requests.post(urlB, data=payload)
                                
                                print "Send CheckName To Server"
                                CidChecked.append(cid)   
        print ""
    elif(PortReceived==PortPhp):        
        #QuestionNo=int(data)
        print data
        Sectext,Secnum=data.split("/")
        #Sectionid=int(SecNum)
    conn.send('ACK')
    
    conn.close()
while True:
        for sock in listening_sockets:
            try:
                conn, addr = sock.accept()
                
                print 'Connected with ' + addr[0] + ':' + str(addr[1])
                 
                #start new thread takes 1st argument as a function name to be run, second is the tuple of arguments to the function.
                start_new_thread(clientthread ,(conn,))
                #accepted_socket, adress = sock.accept()
                #print 'Socket accepted'
                #data = accepted_socket.recv(buf_size)
                #print data
                #if data:
                        #accepted_socket.send('Hello, and goodbye.')
                #accepted_socket.close()
            except socket.timeout:
                pass