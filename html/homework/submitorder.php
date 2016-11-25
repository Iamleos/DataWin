<?php
/**
 * descript:提交订单
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */

	$mycarnum=$_GET['mycarnum'];
	$address=$_GET['address'];
	$begintime=$_GET['begintime'];
	$endtime=$_GET['endtime'];
	$money=$_GET['money'];
	$score=$_GET['score'];
	$userid=$_GET['userid'];
	$orderid = time();
	//homework/submitorder.php?mycarnum=渝AA12BA&address=重庆大学虎溪花园%20A36&begintime=2016年5月13日%2013:20&endtime=2016年5月13日%2017:20&money=12&score=5&userid=15696081572
	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$arr= array();
	$strsql="insert into orderinfo(orderid,mycarnum,address,begintime,money,score,endtime,userid)values('{$orderid}','{$mycarnum}','{$address}','{$begintime}','{$money}','{$score}','{$endtime}','{$userid}');";
	//echo $strsql;
	$result=mysql_query($strsql,$con);
	if($result)
	{
		$arr['result']="ok";
	}else {

		$arr['result']="failed";
	}
	$jsonstr=json_encode($arr);
	echo $jsonstr;
	mysql_close();


?>