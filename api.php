<?php

require_once('config.inc.php');
require_once('pic2qn.php');

$p2q = new Pic2qn($bucket,$accessKey,$secretKey);

print_r($p2q->get2send('http://gtms04.alicdn.com/tps/i4/TB1zG5yHXXXXXXdXXXXD.JfRpXX-694-971.jpg'));