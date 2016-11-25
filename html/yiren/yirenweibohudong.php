<?php
/**
 * descript:获取艺人微博互动能力
 * @date 2016/4/25
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户无法访问豆瓣八组的问题。
	//ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');

	$url="http://weibo.com/u/1537790411?is_all=1";
	$html=curltest($url);
	echo $html;

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


		//curl_setopt($ch, CURLOPT_COOKIE, "username=test;password=test");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, $header);
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