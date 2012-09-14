#!/bin/bash
wget -O {{ scriptdir }}/host1.json http://host1.org/stat.json 
chmod 777 {{ scriptdir }}/host1.json
wget -O {{ scriptdir }}/host2.json http://host2.nl/stat.json
chmod 777 {{ scriptdir }}/host2.json
wget -O {{ scriptdir }}/host3.json http://host3.nl/stat.json
chmod 777 {{ scriptdir }}/host3.json
wget -O {{ scriptdir }}/host4.json http://host4.nl/stat.json
chmod 777 {{ scriptdir }}/host4.json


