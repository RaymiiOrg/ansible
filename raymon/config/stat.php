<?php
/*
#Copyright (c) 2012 Remy van Elst
#Permission is hereby granted, free of charge, to any person obtaining a copy
#of this software and associated documentation files (the "Software"), to deal
#in the Software without restriction, including without limitation the rights
#to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#copies of the Software, and to permit persons to whom the Software is
#furnished to do so, subject to the following conditions:
#
#The above copyright notice and this permission notice shall be included in
#all copies or substantial portions of the Software.
#
#THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#THE SOFTWARE.
*/

?>
<html>
<head>
<title>Stats</title>
<!-- bar via http://www.joshuawinn.com/quick-and-simple-css-percentage-bar-using-php/ -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="git/js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="git/js/kickstart.js"></script>                                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="git/css/kickstart.css" media="all" />                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="git/style.css" media="all" />                          <!-- CUSTOM STYLES -->
<style type="text/css">
.percentbar { background:#CCCCCC; border:1px solid #666666; height:10px; }
.percentbar div { background: #28B8C0; height: 10px; }
</style>
<meta http-equiv="refresh" content="300">
</head>
<body><a id="top-of-page"></a><div id="wrap" class="clearfix">
<?php
function shortstat($bestand,$host) {
        if ($file = file_get_contents($bestand)) {
                if ($json_a = json_decode($file, true)) {
                        $closed=0;
                        $havestat = 0;
                        if(is_array($json_a)) {
                        ?><table><tr><td>
                        <?php echo "Host: ". $json_a['Hostname'] . ", <br /> "; 
                        echo "Uptime: " . $json_a['Uptime'] . "";
                        ?>
                        </td><td>
                        <?php
                                foreach ($json_a['Services'] as $service => $status) {
                                        if($status == "running") {
                                                echo '<font color="green">' . $service . '</font><span class="icon small green" data-icon="q"></span> ';
                                        } elseif ($status == "not running") {
                                                echo '<font color="red">' . $service . '</font><span class="icon small red" data-icon="r"></span> ';
                                        }
                                }
                        ?></td><td><?php

                        echo "Updates: " .$json_a['updatesavail'] . ". <br />";
                        echo "HDD:  " . $json_a['Disk']['total'] . ", "; 
                        echo "used: " . $json_a['Disk']['used'] , ", ";
                        echo "free: " . $json_a['Disk']['free'] , ", ";
                        $percent = str_replace("%", "", $json_a['Disk']['percentage']); 
                        ?><div class="percentbar" style="width: 100px;"><div style="width:<?php echo round($percent); ?>px;"></div></div>
                        </td></tr><tr><td><?php
                        echo "RAM: " . $json_a['Total RAM'] . "MB total,<br /> ". $json_a['Free RAM'] . "MB free. ";
                        $used_ram = $json_a['Total RAM'] - $json_a['Free RAM'];
                        $value = $used_ram;
                        $max = $json_a['Total RAM'];
                        $scale = 1.0;
                        if ( !empty($max) ) { $percent = ($value * 100) / $max; } 
                        else { $percent = 0; }
                        if ( $percent > 100 ) { $percent = 100; } 
                        ?>
                        <div class="percentbar" style="width:<?php echo round(100 * $scale); ?>px;">
                                <div style="width:<?php echo round($percent * $scale); ?>px;"></div>
                        </div>
                        <?php
                        ?></td><td><?php
                        echo "Int IPv4: " . $json_a['IPv4'] . "<br />";
                        $rxmb=round((($json_a['rxbytes'] / 1024) / 1024));
                        $txmb=round((($json_a['txbytes'] / 1024) / 1024));
                        echo "Received " . $rxmb . " MB, Transferred: ". $txmb ." MB. <br />";
                        ?></td><td>
                        <?php echo  "Users: " . $json_a['Users logged on']; 
                        echo "<br />Load: " . $json_a['Load'] . " ";
                        ?>
                        </td></tr></table><?php
                        }
                } else {
                        echo "Error decoding JSON stat file for host $host";
                }
        } else  {
                echo "Error while getting stats for host $host";
        }
}


function ping($host, $port, $timeout) { 
  $tB = microtime(true); 
  $fP = fSockOpen($host, $port, $errno, $errstr, $timeout); 
  if (!$fP) {  return '<font color="red">' . $host . ' down from here. </font><span class="icon small red" data-icon="r"></span> '; } 
  $tA = microtime(true); 
  return '<font color="green">' . $host . ' ' . round((($tA - $tB) * 1000), 0).' ms </font><span class="icon small green" data-icon="q"></span> ';
}


?> 

<div class="col_12">

<ul class="tabs left">
<li><a href="#tabc1">Overview</a></li>
<li><a href="#tabc2">Details & History</a></li>
</ul>

<div id="tabc1" class="tab-content">
<?php 
echo "<i>Ping monitor:</i>";

echo ping("google.com", 80, 5);
echo " - ";

echo ping("raymii.org", 80, 5);
echo " - ";

echo ping("www.erasmusmc.nl", 80, 5);
echo " - ";

echo ping("raymii.nl", 80, 5);
echo " - ";


echo ping("lowendtalk.com", 80, 5);



?>

<h4>Host 1</h4> <?php shortstat("host1.json","host1.org"); ?>
<hr class="alt1" />
<h4>Host 2</h4> <?php shortstat("host2.json","host2.nl"); ?>
<hr class="alt1" />
<h4>Other servers</h4>
<?php shortstat("host3.spcs.json","host3.nl"); ?>
<hr class="alt1" />
<?php shortstat("host4.spcs.json","host3.nl"); ?>
<hr class="alt1" />



<div id="tabc2" class="tab-content"> 
<h3>This feature is coming soon.</h3>

</div>

</div>
</body>
</html>