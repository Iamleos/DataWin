<?php
/**
 * descript:豆瓣八组数据采集
 * @date 2016/4/18
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户无法访问豆瓣八组的问题。
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("drop table if exists doubanbazu;",$con);
    mysql_query("create table doubanbazu(dtitle varchar(150),dsendtime datetime,dacquitime datetime,primary key(dtitle,dsendtime,dacquitime));",$con);

	$url="https://www.douban.com/group/blabla/discussion";

	//echo $html;
	for($i=2;$i<60;$i++)
	{
		$html=file_get_contents($url);
		$start=$i*25;
		$url="https://www.douban.com/group/blabla/discussion?start={$start}";
		for($j=1;$j<20;$j++)
		{
			$arr1=array();
			$arr2=array();
			$arr3=array();
			$preg1='/<tr class="">([\w\W]*?)<\/tr>/';
			$preg2='/<a href="[\w\W]*?" title="[\w\W]*?" class="">([\w\W]*?)<\/a>/';
			$preg3='/<td nowrap="nowrap" class="time">([\w\W]*?)<\/td>/';

			preg_match_all($preg1, $html, $arr1);
			preg_match_all($preg2, $arr1[0][$j], $arr2);
			preg_match_all($preg3, $arr1[0][$j], $arr3);

			$sendtime=date("Y-").$arr3[1][0].":00";


			//var_dump($arr1[0][$j]);

			$title=$arr2[1][0];

			//$sendtime=date("Y-").trim(substr($arr1[0][2],-80));

			$acquitime=date("Y-m-d H:i:s");

			$sqlinsert="insert into doubanbazu(dtitle,dsendtime,dacquitime) values('{$title}','{$sendtime}','{$acquitime}')";
            echo $sqlinsert."<br/>";
            mysql_query($sqlinsert,$con);

		}

	}
	mysql_close();
?>