<?php
/**
 * descript:获取今日和明日的猫眼排片
 * @date	2016/7/26
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户的问题。
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
    $sql="drop table if exists maoyanpaipian;create table maoyanpaipian(mname varchar(30),mrowpiecerate decimal(4,2),msession int(5),macquitime date,mtype varchar(16) ,primary key(mname,mtype,macquitime))";
    mysql_query("drop table if exists maoyanpaipian;",$con);
    mysql_query("create table maoyanpaipian(mname varchar(30),mrowpiecerate decimal(4,2),msession int(5),macquitime date,mtype varchar(16) ,primary key(mname,mtype,macquitime));",$con);

	$today=date("Y-m-d");
	$url="http://piaofang.maoyan.com/show?showDate={$today}&periodType=0&showType=2";

	$html=file_get_contents($url);

	$arr=array();
	$preg='/<div id=\'playPlan_table\' class=\'colorful\'>([\w\W]*?)<\/div>/';
	preg_match_all($preg,$html,$arr);

	//var_dump($arr[1][0]);
	$arr1=array();
	$preg1='/<ul[\w\W]*?data-name="([\w\W]*?)"[\w\W]*?data-rate="([\w\W]*?)%"[\w\W]*?data-number="([\w\W]*?)"[\w\W]*?>[\w\W]*?<\/ul>/';
	preg_match_all($preg1,$arr[1][0],$arr1);
	//echo $html;
	//var_dump($arr1);
	$size=count($arr1[1]);
	for($i=0;$i<$size;$i++)
	{
		$name=$arr1[1][$i];
		$rate=$arr1[2][$i];
		$session=$arr1[3][$i];
		$type=0;
		//echo $name."+".$rate."+".$session."+".$type."<br/>";

		$sqlinsert="insert into maoyanpaipian(mname,mrowpiecerate,msession,macquitime,mtype) values('{$name}','{$rate}','{$session}','{$today}','{$type}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);
	}

	//明日排片

	$tomorrow=date("Y-m-d",strtotime("+1 day"));
	$url_mt="http://piaofang.maoyan.com/show?showDate={$tomorrow}&periodType=0&showType=2";

	$html_mt=file_get_contents($url_mt);

	$arr_mt=array();
	$preg_mt='/<div id=\'playPlan_table\' class=\'colorful\'>([\w\W]*?)<\/div>/';
	preg_match_all($preg_mt,$html_mt,$arr_mt);

	//var_dump($arr[1][0]);
	$arr1_mt=array();
	$preg1_mt='/<ul[\w\W]*?data-name="([\w\W]*?)"[\w\W]*?data-rate="([\w\W]*?)%"[\w\W]*?data-number="([\w\W]*?)"[\w\W]*?>[\w\W]*?<\/ul>/';
	preg_match_all($preg1_mt,$arr_mt[1][0],$arr1_mt);
	//echo $html;
	//var_dump($arr1_mt);
	$size_mt=count($arr1_mt[1]);
	for($i=0;$i<$size_mt;$i++)
	{
		$name=$arr1_mt[1][$i];
		$rate=$arr1_mt[2][$i];
		$session=$arr1_mt[3][$i];
		$type=1;
		//echo $name."+".$rate."+".$session."+".$type."<br/>";
		$sqlinsert="insert into maoyanpaipian(mname,mrowpiecerate,msession,macquitime,mtype) values('{$name}','{$rate}','{$session}','{$today}','{$type}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);
	}
?>