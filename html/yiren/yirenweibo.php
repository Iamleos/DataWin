<?php
/**
 * descript:获取艺人微博信息
 * @date 2016/4/25
 * @author  XuJun
 * @version 1.0
 * @package
 */
 #! /usr/bin/php -q
	header("content-type:text/html;charset=gbk");
	set_time_limit(0);

	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$url="http://s.weibo.com/weibo/%E6%9D%8E%E6%98%8E%E5%90%AF&Refer=STopic_box";
	$html=file_get_contents($url);

	//echo $html;

	$arr1=array();
	$preg1='/<script>STK && STK.pageletM && STK.pageletM.view\(([\w\W]*?)\)<\/script>/';
	//$preg1='/<div class=[\w\W]*?"list_star clearfix[\w\W]*?">([\w\W]*?)<\/div>/';

	preg_match_all($preg1,$html,$arr1);

	$len=count($arr1[1]);
	for($i=0;$i<$len;$i++)
	{
		if(strstr($arr1[1][$i],"\"pid\":\"pl_weibo_directtop\""))
		{
			var_dump (json_decode($arr1[1][$i],true));
			break;
		}
	}



?>