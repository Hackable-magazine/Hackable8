#!/usr/bin/env python 
# -*- coding: UTF-8 -*-

# collecter les informations d'une série de capteur
# version avec un peu moins d'informations (petit écran)

from types import *
import os
import sys
import time
import requests

if len(sys.argv) < 2:
  print "arg = number number number number..."
  sys.exit(1)

for num in sys.argv[1:]:
  if not 1 <= int(num) <=9:
    print "range [1..9]"
    sys.exit(1)

somme = 0
enerreur = 0
for num in sys.argv[1:]:
  try:
    r = requests.get("http://172.16.16.10"+num+"/gettemp", timeout=2)
  except requests.exceptions.Timeout:
    print " #"+num+" : ---"
    enerreur+=1
  except:
    print " #"+num+" : EEE"
    enerreur+=1
  else:
    print " #"+num\
    +"("\
    +os.popen("iw dev wlan0 station get `arp -a 172.16.16.10"\
    +num\
    +" | awk '{print $4}'` | grep signal: | cut -f 3").read().rstrip()+"): "\
    +r.content.rstrip()+"°C"
    somme += float(r.content.rstrip())

print "Moyenne: %.2f°C" % (somme/(len(sys.argv)-1-enerreur))

