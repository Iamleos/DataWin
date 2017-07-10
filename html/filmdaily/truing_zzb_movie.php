<?php
/**
 * descript:提取专资办精修数据信息--电影
 * @date 2016/7/10
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
	
	$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$day=date("Y-m-d",strtotime("-1 day"));
	//$day="2017-04-24";
	$html=file_get_contents("http://www.zgdypw.cn/pors/w/webStatisticsDatas/api/{$day}/searchDayBoxOffice");
	$info_str=json_decode($html,true);
	$box_office_sum=$info_str["data"]["dayBoxOffice"]["totalBoxoffice"];
	$session=$info_str["data"]["dayBoxOffice"]["totalSession"]/10000;
	$people=$info_str["data"]["dayBoxOffice"]["totalAudience"]/10000;
	$sql_str="insert into turing_zzb_total(total_piaofang,total_session,total_people,time) values('{$box_office_sum}','{$session}','{$people}','{$day}');";
	mysql_query($sql_str,$con);
	for($i=0;$i<10;$i++)
	{
		$film_name=$info_str["data"]["top10Films"][$i]["filmName"];
		$film_piaofang=$info_str["data"]["top10Films"][$i]["daySales"];
		$film_session=$info_str["data"]["top10Films"][$i]["daySession"];
		$film_people=rand((int)$info_str["data"]["top10Films"][$i]["dayAudience"]/10000,4);
		$film_sum_piaofang=$info_str["data"]["top10Films"][$i]["filmTotalSales"];
		//echo $film_name."-".$film_piaofang."-".$film_session."-".$film_people."-".$film_sum_piaofang."<br/>";
		$sql="insert into turing_zzb_film(film_name,film_piaofang,film_session,film_people,film_sum_piaofang,time) values('{$film_name}','{$film_piaofang}','{$film_session}','{$film_people}','{$film_sum_piaofang}','{$day}');";
		mysql_query($sql,$con);
		echo $sql."<br/>";
	}
	for($i=0;$i<10;$i++)
	{
		$cinema_name=$info_str["data"]["top10Cinemas"][$i]["cinemaName"];
		$cinema_piaofang=$info_str["data"]["top10Cinemas"][$i]["totalSales"];
		$cinema_session=$info_str["data"]["top10Cinemas"][$i]["daySession"];
		$cinema_people=rand((int)$info_str["data"]["top10Cinemas"][$i]["dayAudience"]/10000,4);

		//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
		$sql="insert into turing_zzb_cinema(cinema_name,cinema_piaofang,cinema_session,cinema_people,time) values('{$cinema_name}','{$cinema_piaofang}','{$cinema_session}','{$cinema_people}','{$day}');";
		mysql_query($sql,$con);
		echo $sql."<br/>";
	}
	for($i=0;$i<10;$i++)
	{
		$theatres_name=$info_str["data"]["top10CinemaChains"][$i]["cinemaChainName"];
		$theatres_piaofang=$info_str["data"]["top10CinemaChains"][$i]["totalSales"];
		$theatres_session=$info_str["data"]["top10CinemaChains"][$i]["daySession"];
		$theatres_people=rand((int)$info_str["data"]["top10CinemaChains"][$i]["dayAudience"]/10000,4);

		//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
		$sql="insert into turing_zzb_theatres(theatres_name,theatres_piaofang,theatres_session,theatres_people,time) values('{$theatres_name}','{$theatres_piaofang}','{$theatres_session}','{$theatres_people}','{$day}');";
		mysql_query($sql,$con);
		echo $sql."<br/>";
	}
	for($i=0;$i<10;$i++)
	{
		$city_name=$info_str["data"]["top10Citys"][$i]["cityName"];
		$city_piaofang=$info_str["data"]["top10Citys"][$i]["totalSales"];
		$city_session=$info_str["data"]["top10Citys"][$i]["daySession"];
		$city_people=rand((int)$info_str["data"]["top10Citys"][$i]["dayAudience"]/10000,4);

		//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
		$sql="insert into turing_zzb_city(city_name,city_piaofang,city_session,city_people,time) values('{$city_name}','{$city_piaofang}','{$city_session}','{$city_people}','{$day}');";
		mysql_query($sql,$con);
		echo $sql."<br/>";
	}





?>
