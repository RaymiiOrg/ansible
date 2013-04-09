#!/bin/bash
## Firewall all the things!
{{ iptables_path }} -F
## But allow what is needed.
{{ iptables_path }} -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

## Host specific rules

{% for host in groups['collectd-clients'] %}
{% if  hostvars[host]['ansible_default_ipv4']['address'] %}
## Host {{ hostvars[host]['inventory_hostname'] }}
{{ iptables_path }} -A INPUT -p udp -s {{ hostvars[host]['ansible_default_ipv4']['address'] }} --dport {{ collectd_port }} -j ACCEPT
{% endif %}
{% if  hostvars[host]['ansible_default_ipv6']['address'] %}
## Host {{ hostvars[host]['inventory_hostname'] }}
{{ ip6tables_path }} -A INPUT -p udp -s {{ hostvars[host]['ansible_default_ipv6']['address'] }} --dport {{ collectd_port }} -j ACCEPT
{% endif %}
{% endfor %}
 

{{ ip6tables_path }} -A INPUT -p udp -m udp --dport {{ collectd_port }} -m limit --limit 5/sec --limit-burst 8 -j LOG --log-prefix "collectd_port "
{{ iptables_path }} -A INPUT -p udp -m udp --dport {{ collectd_port }} -m limit --limit 5/sec --limit-burst 8 -j LOG --log-prefix "collectd_port "
## Final deny rule
{{ iptables_path }} -A INPUT -p udp --dport {{ collectd_port }} -j DROP
{{ ip6tables_path }} -A INPUT -p udp --dport {{ collectd_port }} -j DROP