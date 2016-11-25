<?php
/**
 * descript:获取数据库中的前10条数据，按照时间排序
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
	$pn=$_GET['pn'];
	$province = $_GET['province'];
	$params = $_GET['params'];
	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	$number=((int)$pn)*10;
	$i=1;
	$j=0;

	$arr=array();
    $result = "";
	if($params=="默认排序")
	{
		global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' order by sendtime desc limit {$number}");
	}else if($params=="价格最低"){

		global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' and price>0 order by price asc limit {$number}");

	}else if($params=="查看最多"){

		global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' order by look desc limit {$number}");

	}else if($params=="300以下"){

		global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' and price<300 and price>0 order by price asc limit {$number}");
	}else if($params=="300-600"){

	    global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' and price>=300 and price<=600 order by sendtime desc limit {$number}");
	}else if($params=="600-900"){

	    global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' and price>=600 and price<=900 order by sendtime desc limit {$number}");
	}else if($params=="900以上"){

	    global $result;
		$result=mysql_query("select * from parklot where province = '{$province}' and price>=900 order by sendtime desc limit {$number}");
	}

	while($row=mysql_fetch_row($result))
	{
		if($i<($number-9))
		{
			$i++;
		}else{

			$arr[$j]['title']=$row[0];
			$arr[$j]['sendtime']=$row[1];
			$arr[$j]['look']=$row[2];
			$arr[$j]['province']=$row[3];
			$arr[$j]['city']=$row[4];
			$arr[$j]['contacts']=$row[5];
			$arr[$j]['price']=$row[6];
			$arr[$j]['cellphone']=$row[7];
			$arr[$j]['qq']=$row[8];
			$j++;
		}

	}
	sort($arr);
	//var_dump($arr);
	$jsonstr=json_encode($arr);
	echo $jsonstr;

	mysql_close();


?>