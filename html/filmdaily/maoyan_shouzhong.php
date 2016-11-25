<?php
/**
 * descript:获取猫眼受众分析
 * @date 2016/7/20
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

	mysql_query("create table if not exists maoyan_shouzhong(name varchar(50),age_11_15 varchar(10),age_16_20 varchar(10),age_21_25 varchar(10),age_26_30 varchar(10),age_31_35 varchar(10),age_36_40 varchar(10),age_41_50 varchar(10),man varchar(10),women varchar(10),primary key(name))");

	$result=mysql_query("select * from maoyan_everyday_name_num");
	while($row=mysql_fetch_row($result))
	{
		$name=$row[0];
		$num=$row[1];
		$exist=mysql_query("select * from maoyan_shouzhong where name='{$name}'");
		if(mysql_num_rows($exist))
		{
			echo "test<br/>";
			continue;

		}
		$url="http://piaofang.maoyan.com/movie/{$num}/aud";
		$html=file_get_contents($url);
		$arr=array();
		$preg='/value[\w\W]*?:([\w\W]*?),[\w\W]*?/';
		preg_match_all($preg,$html,$arr);
		$age_11_15=$arr[1][0];
		$age_16_20=$arr[1][1];
		$age_21_25=$arr[1][2];
		$age_26_30=$arr[1][3];
		$age_31_35=$arr[1][4];
		$age_36_40=$arr[1][5];
		$age_41_50=$arr[1][6];

		$arr1=array();
		$preg1='/[\w\W]*?width:([\w\W]*?)%/';
		preg_match_all($preg1,$html,$arr1);
		$man=$arr1[1][0];
		$women=$arr1[1][1];
		$sqlstr="insert into maoyan_shouzhong(name,age_11_15,age_16_20,age_21_25,age_26_30 ,age_31_35 ,age_36_40 ,age_41_50,man,women)values('{$name}','{$age_11_15}','{$age_16_20}','{$age_21_25}','{$age_26_30}','{$age_31_35}','{$age_36_40}','{$age_41_50}','{$man}','{$women}')";
			echo $sqlstr."<br/>";
			mysql_query($sqlstr,$con);

	}
?>