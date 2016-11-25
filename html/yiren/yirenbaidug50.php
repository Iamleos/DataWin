<?php
/**
 * descript: 获得百度女艺人top50
 * @date 2016/4/22
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

	//mysql_query("drop table if exists yirenbaidug50;",$con);
    mysql_query("create table if not exists yirenbaidug50(ynum int(2),yname varchar(30),ysearch int(8),yacquitime date,primary key(yname,yacquitime));",$con);

	$url="http://top.baidu.com/buzz?b=18&fr=topbuzz_b17";
	$html=file_get_contents($url);
	//echo $html;

	$arr1=array();
	$arr2=array();
	$arr3=array();
	$arr4=array();


	$preg1='/<table class="list-table">([\w\W]*?)<\/table>/';  //用于匹配页面的整个table
	//$preg2='/<td class="keyword">([\w\W]*?)<\/td>/';
	//$preg3='/<td class="last">([\w\W]*?)<\/td>/';
	$preg3='/<span class="icon-[\w\W]*?">([\w\W]*?)<\/span>/';      //用于提取table中的搜索指数
	$preg4='/<a class="list-title"[\w\W]*?>([\w\W]*?)<\/a>/';	//用于提取table中的名字
    preg_match_all($preg1,$html,$arr1);
	//preg_match_all($preg2,$arr1[0][0],$arr2);
	preg_match_all($preg3,$arr1[0][0],$arr3);
	preg_match_all($preg4,$arr1[0][0],$arr4);

	//var_dump($arr4);
	for($i=0;$i<50;$i++)
	{
		$num=$i+1;
		$name=$arr4[1][$i];
		$search=$arr3[1][$i];
		$acquitime=date("Y-m-d");
		$name=iconv('GB2312', 'UTF-8', $name);
		$search=iconv('GB2312', 'UTF-8', $search);

		echo $num." ".$name." ".$search."<br/>";

		$sqlstr="insert into yirenbaidug50(ynum,yname,ysearch,yacquitime) values('{$num}','{$name}','{$search}','{$acquitime}');";
		mysql_query($sqlstr,$con);
	}


	mysql_close();
?>