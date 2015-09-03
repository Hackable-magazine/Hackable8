#!/bin/bash
# A. Apvrille - November 16, 2014

# Gpio 17 = chaudiere on/off
#
# Gpio 27 = 0 off = chauffage on (par defaut)
#           1 on  = chauffage off
#
# Gpio 23 : off = thermostat manuel
#           on = programme

. /lib/lsb/init-functions
NAME="CHAUDIERE"

function usage() {
    echo "./chaudiere.sh <status|chauffage_status|chaudiere_status"
    echo "                |chaudiere_on|chaudiere_off|"
    echo "                |chauffage_off|"
    echo "                |chauffage_manuel|chauffage_auto valeur>";
    exit
}

# Retourne l'etat de la chaudiere
function status_chaudiere() {
    local status=`cat /sys/class/gpio/gpio17/value`
    if [ $status = "0" ]; then
	chaudiere="Off"
    else
	chaudiere="On"
    fi
}

# Retourne l'etat actuel du chauffage
function status_chauffage() {
    local status27=`cat /sys/class/gpio/gpio27/value`
    local status23=`cat /sys/class/gpio/gpio23/value`

    if [ $status23 = "1" ]; then
	chauffage="Auto"
	# auto va reguler le chauffage en coupant ou fermant gpio27
    else
	if [ $status27 = "0" ]; then
	    chauffage='Manuel'
	else
	    chauffage='Off'
	fi
    fi
}

# couper le *chauffage*
function turn_off_chauffage() {
    echo "0" > /sys/class/gpio/gpio23/value
    echo "1" > /sys/class/gpio/gpio27/value
    log_daemon_msg "Arret du chauffage" "$NAME"
}

# passer le chauffage en mode manuel
function set_manuel() {
    echo "0" > /sys/class/gpio/gpio23/value
    echo "0" > /sys/class/gpio/gpio27/value
    log_daemon_msg "Passage en chauffage manuel" "$NAME"
}

# passer le chauffage en mode automatique
function set_auto() {
    echo "TODO: $1"
    #echo "1" > /sys/class/gpio/gpio23/value
    # 
    # Ensuite, il faut reguler sur GPIO 27 ouvert/ferme en fonction de la programmation
    # Il faut recuperer la temperature actuelle
    # echo "0" > /sys/class/gpio/gpio27/value
    
    #En mode 23, mode "thermostat automatique", il faut pouvoir positionner une temprature, par exemple 20 degrs. Appelons la "t". Soit t_current la temprature intrieure obtenue avec la station meteo.
    #Tant que mode automatique :
    #Si (t < t_current - 1) alors, mettre relais 23 a 1.
    #Si (t >= t_current) alors mettre relais 23 a 0
    #attendre 5 mn.
}

# couper la chaudiere (et tout au passage)
function turn_off_chaudiere() {
    echo "0" > /sys/class/gpio/gpio23/value
    echo "0" > /sys/class/gpio/gpio27/value
    echo "0" > /sys/class/gpio/gpio17/value
    log_daemon_msg "Arret total de la chaudiere" "$NAME"
}

# mettre en route la chaudiere
function turn_on_chaudiere() {
    echo "1" > /sys/class/gpio/gpio17/value
    log_daemon_msg "Demarrage de la chaudiere" "$NAME"
}

# --------------------------------------------

# verification du nombre d'arguments
if [ $# -lt 1 ]; then
    usage
    exit
fi

if [ $# -gt 2 ]; then
    usage
    exit
fi

# traitement des commandes
if [ "$1" == "chaudiere_status" ]; then
    status_chaudiere
    echo $chaudiere
    exit 1
fi

if [ "$1" == "chauffage_status" ]; then
    status_chaudiere
    if [ "$chaudiere" == "Off" ]; then
	echo "Chaudiere desactivee"
    else
	status_chauffage
	echo $chauffage
    fi
    exit 1
fi

if [ "$1" == "status" ]; then
    status_chaudiere
    status_chauffage
    echo "Chaudiere: $chaudiere"
    echo "Chauffage: $chauffage"
fi

if [ "$1" == "chaudiere_off" ]; then
    turn_off_chaudiere
    status_chaudiere
    echo $chaudiere
    exit 1
fi

if [ "$1" == "chaudiere_on" ]; then
    turn_on_chaudiere
    status_chaudiere
    echo $chaudiere
    exit 1
fi

if [ "$1" == "chauffage_off" ]; then
    status_chaudiere
    if [ "$chaudiere" == "On" ]; then
	turn_off_chauffage
    fi 
    status_chauffage
    echo $chauffage
    exit 1
fi

if [ "$1" == "chauffage_manuel" ]; then
    status_chaudiere
    if [ "$chaudiere" == "On" ]; then
	set_manuel
    fi 
    status_chauffage
    echo $chauffage
    exit 1
fi

if [ "$1" == "chauffage_auto" ]; then
    status_chaudiere
    if [ "$chaudiere" == "On" ]; then
	set_auto "$2"
    fi 
    status_chauffage
    echo $chauffage
    exit 1
fi


exit 1

