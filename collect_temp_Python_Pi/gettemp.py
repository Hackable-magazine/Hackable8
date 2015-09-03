#!/usr/bin/env python 
# -*- coding: UTF-8 -*-

# collecter les informations d'un capteur en précisant son numéro

from types import *
import os
import sys
import time
import requests

if len(sys.argv) != 2:
  print "arg = number"
  sys.exit(1)

if not 1 <= int(sys.argv[1]) <=9:
  print "range [1..9]"
  sys.exit(1)

try:
  r = requests.get("http://172.16.16.10"+sys.argv[1]+"/gettemp", timeout=2)
except requests.exceptions.Timeout:
  print time.strftime("%d/%m/%Y  %H:%M:%S")+"  capteur "+sys.argv[1]+" : No response"
except:
  print time.strftime("%d/%m/%Y  %H:%M:%S")+"  capteur "+sys.argv[1]+" : Error"
else:
#  print time.strftime("%d/%m/%Y  %H:%M:%S")+"  capteur "+sys.argv[1]+" ("+os.popen("iw dev wlan0 station get `arp -a 172.16.16.10"+sys.argv[1]+" | awk '{print $4}'` | grep signal: | cut -f 3").read().rstrip()+") : "+r.content.rstrip()+"°C"
  print time.strftime("%d/%m/%Y  %H:%M:%S")\
  +"  capteur "+sys.argv[1]\
  +" ("\
  +os.popen("iw dev wlan0 station get `arp -a 172.16.16.10"\
  +sys.argv[1]\
  +" | awk '{print $4}'` | grep signal: | cut -f 3").read().rstrip()+") : "\
  +r.content.rstrip()+"°C"
