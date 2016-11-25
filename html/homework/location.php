<?php
	header("content-type:text/html;charset=utf-8");
	$latitude=$_GET['latitude'];
	$longitude=$_GET['longitude'];
	$url="http://api.map.baidu.com/geocoder/v2/?ak=kGMiPAHHPjhxaeIlqGWSCfVnmFh87sqn&location={$latitude},{$longitude}&output=json&pois=1";
	$str=file_get_contents($url);
    $json=json_decode($str,true);
	$result=$json['result'];
	echo $result['addressComponent']['city'];
