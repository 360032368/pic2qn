<?php

require_once('config.inc.php');
require_once('pic2qn.php');

$p2q = new Pic2qn($bucket,$accessKey,$secretKey);

$p2q->remote2local('http://gtms04.alicdn.com/tps/i4/TB1zG5yHXXXXXXdXXXXD.JfRpXX-694-971.jpg');