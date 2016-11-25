<?php
/**
 *@调用八爪鱼猫眼评分api
 *@author: xujun
 *@notice 具体解释看dianyingba.php
 */
 #! /usr/bin/php
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("drop table if exists newfilm",$con);
    mysql_query("create table newfilm(mname varchar(30),type int(8),macquitime date,primary key(mname,macquitime));",$con);

    $day=date("Y-m-d",strtotime("+1day"));
	$url="http://piaofang.maoyan.com/?date={$day}";
	$html=file_get_contents($url);
	$arr=array();
	$preg='/<li class="solid">[\w\W]*?<b>([\w\W]*?)<\/b>[\w\W]*?<br>([\w\W]*?)<\/li>/';
	preg_match_all($preg,$html,$arr);
	//var_dump($arr);

	//点映的type为1，零点场的type为2
	$size=count($arr[1]);
	for($i=0;$i<$size;$i++)
	{
		$name=$arr[1][$i];
		$type=0;
		//var_dump(strip_tags($arr[2][$i]));
		if(trim(strip_tags($arr[2][$i]))=="点映")
		{
			$type=1;
		}else if(trim(strip_tags($arr[2][$i]))=="零点场"){
			$type=2;
		}else if(trim(strip_tags($arr[2][$i]))=="上映首日"){
		    $type=3;
		}else {
		    $type=0;
		}

		$sqlinsert="insert into newfilm(mname,type,macquitime) values('{$name}','{$type}','{$day}')";
        echo $sqlinsert."<br/>";
        mysql_query($sqlinsert,$con);

	}


    mysql_close($con);


?>
