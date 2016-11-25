<?php
/**
 * descript:获取艺人最近的资讯
 * @date 2016/4/22
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");

	set_time_limit(0);
	ini_set('user_agent','Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0)');
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";

    $name="root";
    $password="ctfoxno1";

    $dbname="yiren";

	$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
	mysql_query("set names utf8");

	$result=mysql_query("select me from actname order by me limit 10",$con);

	while($row=mysql_fetch_row($result,true))
	{
		$yname=$row['me'];
		$url="http://v.sogou.com/v?query={$yname}";
		var_dump(curltest($url,"GET"));
		echo "<br/>";
	}

	function curltest($url,$method="POST",$data=array(),$header=array(),$head=0,$body=0,$timeout = 30)
	{

		$ip = "115.28.".rand(1, 255).".".rand(1, 255);
		$headers = array("X-FORWARDED-FOR:$ip");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if (strpos($url, "https") !== false ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}
		}
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		switch ($method) {
		case 'POST':
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			break;
		case 'GET':
			break;
		case 'PUT':
			curl_setopt($ch, CURLOPT_PUT, 1);
			curl_setopt($ch, CURLOPT_INFILE, '');
			curl_setopt($ch, CURLOPT_INFILESIZE, 10);
			break;
		case 'DELETE':
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
		}


		curl_setopt($ch, CURLOPT_COOKIE, "username=test;password=test");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, $headers);
		curl_setopt($ch, CURLOPT_NOBODY, $body);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$rtn = curl_exec($ch); //获得返回
		if (curl_errno($ch)) {
			echo 'Errno'.curl_error($ch);//捕抓异常
		}
		curl_close($ch);
		return $rtn;
	}
?>