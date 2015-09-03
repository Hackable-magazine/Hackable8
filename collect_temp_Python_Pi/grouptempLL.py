#!/usr/bin/env python 
# -*- coding: UTF-8 -*-

# collecter les informations d'une série de capteur
# version avec un peu minimum d'informations (touuuuuut petit écran)

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

for num in sys.argv[1:]:
  try:
    r = requests.get("http://172.16.16.10"+num+"/gettemp", timeout=2)
  except requests.exceptions.Timeout:
    print " #"+num+" : (-)"
  except:
    print " #"+num+" : (E)"
  else:
    print " #"+num\
    +": "\
    +r.content.rstrip()+"°C"
#    somme += float(r.content.rstrip())

#print "Moyenne: %.2f°C" % (somme/(len(sys.argv)-1))

