<?php
/**
 * descript:验证用户登录信息
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
	$id=$_GET['id'];
	$pass=$_GET['password'];

	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$arr= array();
	$strsql="select * from userinfo where ID='{$id}' and PassWord='{$pass}'";
	//echo $strsql;
	$result=mysql_query($strsql,$con);
	if($row=mysql_fetch_row($result))
	{
		$arr['nickname']=$row[2];
		$arr['result']="ok";
	}else{
		$arr['result']="failed";
	}

	$jsonstr=json_encode($arr);
	echo $jsonstr;
	mysql_close();


?>