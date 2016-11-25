<?php
/**
 *@调用八爪鱼豆瓣评分api
 *@author: xujun
 *@notice 具体解释看dianyingba.php
 */
 #! /usr/bin/php
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

    mysql_query("drop table if exists doubanfen",$con);
    mysql_query("create table doubanfen(dname varchar(30),dfen decimal(2,1),dshow varchar(10),dacquitime date,primary key(dname,dacquitime));",$con);

    $day=date("Y-m-d");

	$url="https://movie.douban.com/nowplaying/chongqing/";
	$html=file_get_contents($url);
	//echo $html;
	$arr=array();
	//$preg='/<ul class="lists">[\w\W]*?<li data-title="([\w\W]*?)"[\w\W]*?data-score="([\w\W]*?)">[\w\W]*?<\/li>[\w\W]*?<\/ul>/';
	$preg='/<li[\w\W]*?id="[\w\W]*?"[\w\W]*?data-title="([\w\W]*?)"[\w\W]*?data-score="([\w\W]*?)"[\w\W]*?>[\w\W]*?<\/li>/';
	preg_match_all($preg,$html,$arr);

	$size=count($arr[1]);
	for($i=0;$i<$size;$i++)
	{
		$dname=$arr[1][$i];
		$dfen=$arr[2][$i];
		$dshow="True";
		$sqlinsert="insert into doubanfen(dname,dfen,dshow,dacquitime) values('{$dname}','{$dfen}','{$dshow}','{$day}')";
        echo $sqlinsert;
        mysql_query($sqlinsert,$con);



	}

    mysql_close($con);
?>
