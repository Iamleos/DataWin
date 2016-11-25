<?php
/**
 * descript:注册用户信息
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
	$id=$_GET['id'];
	$pass=$_GET['password'];
	$nickname = $_GET['nickname'];
	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$arr= array();
	$strsql="insert into userinfo (ID,PassWord,NickName)values('{$id}','{$pass}','{$nickname}')";
	//echo $strsql;
	$result=mysql_query($strsql,$con);
	if($result)
	{
		$arr['result']="ok";
	}else{
		$arr['result']="failed";
	}

	$jsonstr=json_encode($arr);
	echo $jsonstr;
	mysql_close();


?>