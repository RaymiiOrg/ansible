#!/bin/bash

#define the process name of your webserver
# 
#apache:
# SERVICE='httpd'
#
#lighttpd
# SERVICE='lighttpd'
#
# nginx
# SERVICE='nginx'
#
# NEtwork Interface you want to use
iface="venet0:0"
# network interface for traffic mon
iface2="venet0"

#json start
echo "{"

echo -n "\"Services\": { "
SERVICE=lighttpd
if ps ax | grep -v grep | grep $SERVICE > /dev/null; then echo -n "\"$SERVICE\" : \"running\","; else echo -n "\"$SERVICE\" : \"not running\","; fi
SERVICE=sshd
if ps ax | grep -v grep | grep $SERVICE > /dev/null; then echo -n "\"$SERVICE\" : \"running\","; else echo -n "\"$SERVICE\" : \"not running\","; fi
SERVICE=syslog
if ps ax | grep -v grep | grep $SERVICE > /dev/null; then echo -n "\"$SERVICE\" : \"running\","; else echo -n "\"$SERVICE\" : \"not running\","; fi
SERVICE=php-cgi
if ps ax | grep -v grep | grep $SERVICE > /dev/null; then echo -n "\"$SERVICE\" : \"running\","; else echo -n "\"$SERVICE\" : \"not running\","; fi
#LAST SERVICE HAS TO BE WITHOUT , FOR VALID JSON!!!
SERVICE=munin-node
if ps ax | grep -v grep | grep $SERVICE > /dev/null; then echo -n "\"$SERVICE\" : \"running\""; else echo -n "\"$SERVICE\" : \"not running\""; fi
echo -n " }, "

#disk
echo -n "\"Disk\" : { ";
#df -h -T -xtmpfs -xdevtmpfs -xrootfs | awk '{print "\"device\" : \""$1"\", \"type\" : \""$2"\", \"total\" : \"" $3"\", \"used\" : \""$4"\", \"free\" : \""$5"\", \"percentage\" : \""$6"\", \"mounted on\" : \""$7"\""'}
df -BM --total | awk  ' /total/ { printf "\"total\" : \""$2"\", \"used\" : \""$3"\", \"free\" : \""$4"\", \"percentage\" : \""$5"\" }, " }'

# Load
if $(echo `uptime` | grep -E "min|days" >/dev/null); then echo `uptime` | awk '{ printf "\"Load\" : \""$10"\", "}'; else echo `uptime` | sed s/,//g| awk '{ printf "\"Load\" : \""$8"\", "}'; fi

#Users:
echo -n "\"Users logged on\" : "
if $(echo `uptime` | grep -E "min|days" >/dev/null); then echo `uptime` | awk '{ printf "\""$6"\", "}'; else echo `uptime` | sed s/,//g| awk '{ printf "\""$4"\", "}'; fi


#Uptime 
echo -n "\"Uptime\" : "
if $(echo `uptime` | grep -E "min|days" >/dev/null); then echo `uptime` | awk '{ printf "\""$3 $4"\", "}'; else echo `uptime` | sed s/,//g| awk '{ printf "\""$3"\", "}'; fi

# Memory
echo -n "\"Free RAM\" : "
free -m | grep -v shared | awk '/buffers/ {printf "\""$4"\", "}'
echo -n "\"Total RAM\" : "
free -m | grep -v shared | awk '/Mem/ {printf "\""$2"\", "}'


# local ip
echo -n "\"IPv4\" : "
ip -f inet a | grep "$iface" | awk '/inet/{printf "\""$2"\", " }' 

#hostname
echo -n "\"Hostname\" : "
echo -n "\"`hostname`\", "

# external IP
echo -n "\"External IPv4\" : "
#echo -n "\"" `wget -q -O - checkip.dyndns.org|sed -e 's/.*Current IP Address: //' -e 's/<.*$//'` "\","
echo -n "\"8.9.10.22\","


# network traffic
rxbytes=`/sbin/ifconfig $iface2 | awk '{ gsub(/\:/," ") } ; { print  } ' | awk '/RX\ b/ { print $3 }'`
echo -n "\"rxbytes\" : \""
echo -n $rxbytes
echo -n "\", "

txbytes=`/sbin/ifconfig $iface2 | awk '{ gsub(/\:/," ") } ; { print  } ' | awk '/RX\ b/ { print $8 }'`
echo -n "\"txbytes\" : \""
echo -n $txbytes
echo -n "\", "


# package updates, uncomment for your distro
echo -n "\"updatesavail\" : \""
#debian
echo -n `apt-get -s upgrade | awk '/[0-9]+ upgraded,/ {print $1}'`
#arch
#echo -n `pacman -Sy 1>/dev/null 2>&1; pacman -Qu | wc -l`
#yum (centos/fedora/RHEL)
# echo -n `yum -q check-update | wc -l`
echo -n "\","

#json close
echo -n "\"JSON\" : \"close\""
echo; echo " } "