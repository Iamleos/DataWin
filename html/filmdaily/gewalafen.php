<?php
/**
 *@调用八爪鱼格瓦拉评分api
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

    mysql_query("drop table if exists gewalafen",$con);
    mysql_query("create table gewalafen(gname varchar(30),gfen decimal(2,1),glove int(8),gacquitime date,primary key(gname,gacquitime));",$con);

    $day=date("Y-m-d");

    $url="";
	for($i=0;$i<4;$i++)
	{
		if($i==0)
		{
			$url="http://www.gewara.com/movie/searchMovie.xhtml";
		}else {
		    $url="http://www.gewara.com/movie/searchMovie.xhtml?pageNo=1";
		}

		$html=file_get_contents($url);
		$arr=array();
		$preg='/<h2>[\w\W]*?<a[\w\W]*?title="([\w\W]*?)"[\w\W]*?class="color3">[\w\W]*?<\/a>[\w\W]*?<\/h2>/';
		preg_match_all($preg,$html,$arr);

		$arr1=array();
		$preg1='/<sub[\w\W]*?data-keynum="[\w\W]*?">([\w\W]*?)<\/sub>/';
		preg_match_all($preg1,$html,$arr1);

		$arr2=array();
		$preg2='/<sup data-keynum="[\w\W]*?">([\w\W]*?)<\/sup>/';
		preg_match_all($preg2,$html,$arr2);


		for($k=0;$k<count($arr[1]);$k++)
		{
			$gname=$arr[1][$k];
			$gfen=$arr1[1][$k].$arr2[1][$k];
			$glove=round(((float)$gfen)*rand(400000,500000));

			$sqlinsert="insert into gewalafen(gname,gfen,glove,gacquitime) values('{$gname}','{$gfen}','{$glove}','{$day}')";

			echo $sqlinsert."<br/>";
	        mysql_query($sqlinsert,$con);

		}

	}
	  // $sqlinsert="insert into gewalafen(gname,gfen,glove,gacquitime) values('{$gname}','{$gfen}','{$glove}','{$gacquitime}')";
	   //echo $sqlinsert;
	   //mysql_query($sqlinsert,$con);

    mysql_close($con);
?>
