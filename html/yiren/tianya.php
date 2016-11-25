<?php
/**
 * descript:获取天涯八卦数据
 * @date 2016/4/16
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";

	//固定url
	$conURL="http://bbs.tianya.cn";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	mysql_query("drop table if exists tianya;",$con);
    mysql_query("create table tianya(ttitle varchar(200),tsendtime datetime,tacquitime datetime,primary key(ttitle,tsendtime,tacquitime));",$con);

	$url="http://bbs.tianya.cn/list-funinfo-1.shtml";

	for($j=1;$j<25;$j++)
	{

		$str=file_get_contents($url);

		//用于提取下一页链接
		$arr_url=array();

		//提取下一页的标签
		preg_match_all('/<a href="(.*)" rel="nofollow">下一页<\/a>/', $str, $arr_url);

		$url=$conURL.$arr_url[1][0];

		$arr_content=array();
		$arr_content_tr=array();

		preg_match_all('/<tr[\w\W]*?>([\w\W]*?)<\/tr>/', $str, $arr_content);

		$len=count($arr_content[1]);
		for($i=1;$i<$len;$i++)
		{

			preg_match_all('/<a[\w\W]*?>([\w\W]*?)<\/a>/', $arr_content[1][$i], $arr_content_tr);


			$title=trim($arr_content_tr[1][0]);
			//var_dump($title);

			$year=date("Y-");

			$sendtime=$year.substr(trim($arr_content[1][$i]),-16).":00";

			$acquitime=date("Y-m-d H:i:s");


            $sqlinsert="insert into tianya(ttitle,tsendtime,tacquitime) values('{$title}','{$sendtime}','{$acquitime}');";

            mysql_query($sqlinsert,$con);
			echo $sqlinsert;


		}
	}
	mysql_close();

?>