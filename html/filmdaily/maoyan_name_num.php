<?php
/**
 * descript:获取猫眼电影名对应的url已经电影编号
 * @date	2016/7/21
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

	//创建数据库
	mysql_query("create table if not exists maoyan_name_num(mname varchar(50),mnum int(8),primary key(mname,mnum));");
	$result=mysql_query("select * from filmname where zzsy=0");
	while($row=mysql_fetch_row($result))
	{
		$mainname=$row[0];
		$myname=$row[1];
		$url="http://piaofang.maoyan.com/search?key=".urlencode("{$mainname}");
		$html=file_get_contents($url);
		$arr=array();
		$preg='/<article class="indentInner canTouch" data-url="([\w\W]*?)">/';
		preg_match_all($preg,$html,$arr);
		//var_dump($arr[1][0]);
		if (preg_match('|(\d+)|',$arr[1][0],$r))
		{
			$num=$r[1];
			$strsql="insert into maoyan_name_num(mname,mnum)values('{$mainname}','{$num}')";
			echo $strsql."<br/>";
			mysql_query($strsql);
		}

	}


?>