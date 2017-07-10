<?php
/**
 * descript:手工采集专资办票房
 * @date 2016/6/23
 * @author  XuJun
 * @version 1.0
 * @package
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
	mysql_query("drop table if exists zzbpiaofang;",$con);
    mysql_query("create table zzbpiaofang(zname varchar(30),zboxofficesum int(6),zboxoffice int(5),zsession int(7),zperson int(8),zofficesale int(6),zofficerate decimal(4,2),zinternetsale int(6),zinternetrate decimal(4,2),zrealtimeboxoffice int(8),zestimatedboxoffice int(8),zacquitime date,primary key(zname,zacquitime));",$con);
	
	$day=date("Y-m-d");
	$url="http://weixin.gjdyzjb.cn/pors/w/webChatRealTimeDatas/api/{$day}/searchFilmTop10";
	$html=file_get_contents($url);
	$info_str=json_decode($html,true);
	$zrealtimeboxoffice=$info_str["data"]["dayBoxOffice"]["totalBoxoffice"];
	$zestimatedboxoffice=$info_str["data"]["dayBoxOffice"]["forecastTotalBoxoffice"];
	$time=$info_str["data"]["businessDay"];
	
	for($i=0;$i<10;$i++)
	{
		$zname=$info_str["data"]["top10Films"][$i]["filmName"];
		$zboxoffice=$info_str["data"]["top10Films"][$i]["daySales"];
		$zboxofficesum=$info_str["data"]["top10Films"][$i]["filmTotalSales"];
		$zsession=$info_str["data"]["top10Films"][$i]["daySession"];
		$zperson=rand((int)$info_str["data"]["top10Films"][$i]["dayAudience"]/10000,4);
		$zofficesale=$info_str["data"]["top10Films"][$i]["localDaySales"];
		$zofficerate=$info_str["data"]["top10Films"][$i]["localDaySalesPer"];
		$zinternetsale=$info_str["data"]["top10Films"][$i]["onlineDaySales"];
		$zinternetrate=$info_str["data"]["top10Films"][$i]["onlineDaySalesPer"];


		$sqlinsert="insert into zzbpiaofang(zname,zboxofficesum,zboxoffice,zsession,zperson,zofficesale,zofficerate,zinternetsale,zinternetrate,zrealtimeboxoffice,zestimatedboxoffice,zacquitime) values('{$zname}','{$zboxofficesum}','{$zboxoffice}','{$zsession}','{$zperson}','{$zofficesale}','{$zofficerate}','{$zinternetsale}','{$zinternetrate}','{$zrealtimeboxoffice}','{$zestimatedboxoffice}','{$time}')";
        echo $sqlinsert;
        mysql_query($sqlinsert,$con);


	}


?>