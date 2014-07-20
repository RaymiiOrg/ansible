Ansible
=======

My ansible playbooks. 

* lighttpd-nodes is used for installing and configuring lighttpd.
* raymon is used to deploy my little status monitoring applicaton server: [Ray-Mon](https://raymii.org/cms/p_Bash_PHP_Server_Status_Monitor)  
* start is for the app by Bas ten Feld: [start](https://github.com/develup/start)  
* munin-client is used to install munin client, it has the [hostedmunin.com](http://hostedmunin.com) servers by default, but you can essily define your own.
* vnstat is used to install and setup vnstat on debian, including initializing and config file setup.
* oh-my-zsh is to st up zsh and the [oh-my-zsh by robbyrussel](https://github.com/robbyrussell/oh-my-zsh) config.
* collectd is to set up collectd servers and collectd clients, using the `collectd-servers` and `collectd-clients` groups, [see tutorial on Raymii.org](https://raymii.org/s/tutorials/Collectd_server_setup_tutorial_with_web_frontend.html)
* sudo is to set up sudo as I like it, with an admin group and such.
* vpn is used to set ip an IPSEC/L2TP VPN server with local user (PAM/UNIX) authentication [as described here](https://raymii.org/s/tutorials/IPSEC_L2TP_vpn_with_Ubuntu_12.04.html)
* tor is used to set up a tor relay node
* openstack-example  is used for the following tutorial: []()

Playbooks are here merely for example for others and reference. 

**Note: Playbooks may be outdated. Pull requests welcome**


# License

Unless otherwise stated:

    Copyright (C) 2014 Remy van Elst

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
