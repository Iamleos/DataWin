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
    //$host="localhost";
    $name="root";
    $password="ctfoxno1";
    //$password="123456";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("create table if not exists turing_zzb_total (total_piaofang decimal(7,2),total_session decimal(4,2),total_people decimal(5,2),time date ,primary key(time));");
	mysql_query("create table if not exists turing_zzb_film (film_name varchar(50),film_piaofang decimal(7,2),film_session int(6),film_people decimal(5,2),film_sum_piaofang decimal(8,2),time date,primary key(film_name,time));");
	mysql_query("create table if not exists turing_zzb_cinema (cinema_name varchar(50),cinema_piaofang decimal(6,2),cinema_session int(6),cinema_people decimal(5,2),time date ,primary key(cinema_name,time));");
	mysql_query("create table if not exists turing_zzb_theatres (theatres_name varchar(50),theatres_piaofang decimal(6,2),theatres_session int(6),theatres_people decimal(5,2),time date,primary key(theatres_name,time)) ;");
	mysql_query("create table if not exists turing_zzb_city (city_name varchar(50),city_piaofang decimal(6,2),city_session int(6),city_people decimal(5,2),time date,primary key(city_name,time)) ;");

	function send_post($url, $post_data) {

	  $postdata = http_build_query($post_data);
	  $options = array(
		'http' => array(
		  'method' => 'POST',
		  'header' => 'Content-type:application/x-www-form-urlencoded',
		  'content' => $postdata,
		  'timeout' => 15 * 60 // 超时时间（单位:s）
		)
	  );
	  $context = stream_context_create($options);
	  $result = file_get_contents($url, false, $context);

	  return $result;
	}

	//$day="20150930";
	//while(strtotime($day)<strtotime("20160727"))
	//{
		if(count($argv)==2){
			$day=date("Ymd",strtotime("$argv[1]"));
		}
		else{
			$day=date("Ymd",strtotime("-1 day"));
		}
		$post_data = array(
		  'time' => "{$day}"
		);
var_dump($post_data);
		$json_str=send_post('http://www.zgdypw.cn/ashx/TEST.ashx?type=getdate', $post_data);
		$info_str=json_decode($json_str);
		$box_office_sum = str_replace(",","",$info_str->sales);
		$session=str_replace(",","",$info_str->sessionCount);
		$people=str_replace(",","",$info_str->salesCount);
		$sql_str="insert into turing_zzb_total(total_piaofang,total_session,total_people,time) values('{$box_office_sum}','{$session}','{$people}','{$day}');";
		mysql_query($sql_str,$con);
		echo $sql_str."<br/>";

		//电影票房数据库
		$listData1=$info_str->listData1;
		//昨天做到这里

		//var_dump($listData1);
		$arr1=array();
		$preg1="/<tr><td>[\w\W]*?<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><\/tr>/";
		preg_match_all($preg1, $listData1, $arr1);
		$size1=count($arr1[2]);

		for($i=0;$i<$size1;$i++)
		{
			$film_name=$arr1[1][$i];
			$film_piaofang=str_replace(",","",$arr1[2][$i]);
			$film_session=str_replace(",","",$arr1[3][$i]);
			$film_people=str_replace(",","",$arr1[4][$i]);
			$film_sum_piaofang=str_replace(",","",$arr1[5][$i]);
			//echo $film_name."-".$film_piaofang."-".$film_session."-".$film_people."-".$film_sum_piaofang."<br/>";
			$sql="insert into turing_zzb_film(film_name,film_piaofang,film_session,film_people,film_sum_piaofang,time) values('{$film_name}','{$film_piaofang}','{$film_session}','{$film_people}','{$film_sum_piaofang}','{$day}');";
			mysql_query($sql,$con);
			echo $sql."<br/>";
		}

		$listData2=$info_str->listData2;
		//昨天做到这里

		//var_dump($listData1);
		$arr2=array();
		$preg2="/<tr><td>[\w\W]*?<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><\/tr>/";
		preg_match_all($preg2, $listData2, $arr2);
		$size2=count($arr2[2]);

		for($i=0;$i<$size2;$i++)
		{
			$cinema_name=$arr2[1][$i];
			$cinema_piaofang=str_replace(",","",$arr2[2][$i]);
			$cinema_session=str_replace(",","",$arr2[3][$i]);
			$cinema_people=str_replace(",","",$arr2[4][$i]);
var_dump($cinema_piaofang);
			//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
			$sql="insert into turing_zzb_cinema(cinema_name,cinema_piaofang,cinema_session,cinema_people,time) values('{$cinema_name}','{$cinema_piaofang}','{$cinema_session}','{$cinema_people}','{$day}');";
			mysql_query($sql,$con);
		}


		$listData3=$info_str->listData3;
		//昨天做到这里

		//var_dump($listData1);
		$arr3=array();
		$preg3="/<tr><td>[\w\W]*?<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><\/tr>/";
		preg_match_all($preg3, $listData3, $arr3);
		$size3=count($arr3[2]);

		for($i=0;$i<$size3;$i++)
		{
			$theatres_name=$arr3[1][$i];
			$theatres_piaofang=str_replace(",","",$arr3[2][$i]);
			$theatres_session=str_replace(",","",$arr3[3][$i]);
			$theatres_people=str_replace(",","",$arr3[4][$i]);

			//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
			$sql="insert into turing_zzb_theatres(theatres_name,theatres_piaofang,theatres_session,theatres_people,time) values('{$theatres_name}','{$theatres_piaofang}','{$theatres_session}','{$theatres_people}','{$day}');";
			mysql_query($sql,$con);
			echo $sql."<br/>";
		}

		$listData4=$info_str->listData4;
		//昨天做到这里

		//var_dump($listData1);
		$arr4=array();
		$preg4="/<tr><td>[\w\W]*?<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><td>([\w\W]*?)<\/td><\/tr>/";
		preg_match_all($preg4, $listData4, $arr4);
		$size4=count($arr4[2]);

		for($i=0;$i<$size4;$i++)
		{
			$city_name=$arr4[1][$i];
			$city_piaofang=str_replace(",","",$arr4[2][$i]);
			$city_session=str_replace(",","",$arr4[3][$i]);
			$city_people=str_replace(",","",$arr4[4][$i]);

			//echo $cinema_name."-".$cinema_piaofang."-".$cinema_session."-".$cinema_people."<br/>";
			$sql="insert into turing_zzb_city(city_name,city_piaofang,city_session,city_people,time) values('{$city_name}','{$city_piaofang}','{$city_session}','{$city_people}','{$day}');";
			mysql_query($sql,$con);
			echo $sql."<br/>";
		}

		$next_time = strtotime($day) + 3600*24;
		$day = date('Ymd',$next_time);
		echo $day."<br/>";
	//}



?>
