<?php
/**
 * descript: 获取晋江兔区数据
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

	$conURL="http://bbs.jjwxc.net/board.php?board=2&type=&page=";
	$url="http://bbs.jjwxc.net/board.php?board=2&type=&page=2";


	$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("drop table if exists tuqu;",$con);
    $result=mysql_query("create table tuqu(jtitle varchar(240),jsendtime datetime,jacquitime datetime,primary key(jtitle,jsendtime,jacquitime));",$con);

	for($i=2;$i<50;$i++)
	{
		$str=file_get_contents($url);
		$url=$conURL."{$i}";


		for($j=1;$j<25;$j++)
		{
			//提取表格元素
			$arr_tab=array();
			//提取a标签
			$arr_a=array();
			preg_match_all('/<tr[\w\W]*?bgcolor="#FFE7F7"[\w\W]*?valign="middle"[\w\W]*?>([\w\W]*?)<\/tr>/', $str, $arr_tab);

			preg_match_all('/<a[\w\W]*?title="([\w\W]*?)"[\w\W]*?>([\w\W]*?)<\/a>/', $arr_tab[0][$j], $arr_a);
			$sendtime=trim(substr($arr_a[1][0],-19));
			$title=iconv('GB2312', 'UTF-8', trim($arr_a[2][0]));
			$acquitime=date("Y-m-d H:i:s");

			//$today=date("Y-m-d 00:00:00");
           if($title!='')
           {
               $sqlinsert="insert into tuqu(jtitle,jsendtime,jacquitime) values('{$title}','{$sendtime}','{$acquitime}')";
               echo $sqlinsert."<br/>";
               mysql_query($sqlinsert,$con);
           }


		}


	}


	mysql_close();
?>


