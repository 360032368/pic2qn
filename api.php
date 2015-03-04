<?php

require_once('config.inc.php');
require_once('pic2qn.php');

$url = isset($_REQUEST['pic']) ? $_REQUEST['pic'] : '';
$url = urldecode($url);

$p2q = new Pic2qn($bucket,$accessKey,$secretKey);

$result = $p2q->get2send($url);

if(count($result)>0){
	$out = array(
			'success' => true,
			'data' => $result
		);
}else{
	$out = array(
			'success' => false,
			'data' => array()
		);
}

echo json_encode($out);

//$p2q->remove2qn($url);移除远程图片