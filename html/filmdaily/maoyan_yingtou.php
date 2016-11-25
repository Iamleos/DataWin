<?php
/**
 * descript:获取猫眼的影投信息
 * @date 2016/7/15
 * @author  XuJun
 * @version 1.0
 * @package
 */

	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户的问题。
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";

	//连接数据库
    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("create table maoyan_yingtou(cinema_name varchar(50),no int(3),piaofang varchar(15),total_people varchar(15),per_people varchar(15),single_boxoffice varchar(15),time date,primary key(cinema_name,time));",$con);
	$yesterday=date("Y-m-d",strtotime("-1 day"));
	$url="http://piaofang.maoyan.com/company/invest";
	$html=file_get_contents($url);
	//echo $html;
	$arr=array();
	$arr1=array();
	$preg='/<div id="ticket_content">([\w\W]*?)<\/div>/';
	$preg1='/<tr[\w\W]*?>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<\/tr>/';
	preg_match_all($preg,$html,$arr);
	//var_dump($arr[1][0]);
	preg_match_all($preg1,$arr[1][0],$arr1);
	//var_dump($arr1);
	$arr_size=count($arr1[2]);
	for($j=0;$j<$arr_size;$j++)
	{
		$no=$arr1[1][$j];
		$cinema_name=$arr1[2][$j];
		$piaofang=$arr1[3][$j];
		$total_people=$arr1[4][$j];
		$per_people=$arr1[5][$j];
		$single_boxoffice=$arr1[6][$j];
		//echo $no."+".$cinema_name."+".$piaofang."+".$total_people."+".$per_people."+".$single_boxoffice."<br/>";
		$sqlinsert="insert into maoyan_yingtou(cinema_name,no,piaofang,total_people,per_people,single_boxoffice,time) values('{$cinema_name}','{$no}','{$piaofang}','{$total_people}','{$per_people}','{$single_boxoffice}','{$yesterday}')";
		echo $sqlinsert;
		mysql_query($sqlinsert,$con);

	}

?>