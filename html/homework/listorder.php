<?php
/**
 * descript:获取数据库中的前10条数据，按照时间排序
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
	$userid=$_GET['userid'];

	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");


	$arr=array();

	$result=mysql_query("select * from orderinfo where userid = '{$userid}' order by orderid desc");
	$j=0;
	while($row=mysql_fetch_row($result))
	{


		$arr[$j]['orderid']=$row[0];
		$arr[$j]['mycarnum']=$row[1];
		$arr[$j]['address']=$row[2];
		$arr[$j]['begintime']=$row[3];
		$arr[$j]['money']=$row[4];
		$arr[$j]['score']=$row[5];
		$arr[$j]['endtime']=$row[6];
		$j++;
	}

	$jsonstr=json_encode($arr);
	echo $jsonstr;
	mysql_close();


?>