<?php
/**
 * descript:获取猫眼想看指数
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

	mysql_query("create table if not exists maoyan_want_see(name varchar(50),wish_num int(8),time varchar(20))");

	$result=mysql_query("select * from maoyan_name_num");
	while($row=mysql_fetch_row($result))
	{
		$name=$row[0];
		$num=$row[1];
		$exist=mysql_query("select * from maoyan_want_see where name='{$name}'");
		if(mysql_num_rows($exist))
		{
			echo "test<br/>";
			continue;

		}

		$url="http://piaofang.maoyan.com/movie/{$num}?_v_=yes";
		$html=file_get_contents($url);
		//echo $html;
		$arr=array();
		$preg='/wish:{"label":[\w\W]*?"data":([\w\W]*?),"date":/';
		preg_match_all($preg,$html,$arr);
		$str=str_replace(array("[","]"),"",$arr[1]);
		$wish_array=explode(',',$str[0]);
		//var_dump($wish_array);

		$arr1=array();
		$preg1='/,"date":([\w\W]*?)},/';
		preg_match_all($preg1,$html,$arr1);
		$time=str_replace(array("[","]"),"",$arr1[1][1]);
		$array=explode(',',$time);
		$date_array=str_replace('"',"",$array);
		$len=count($date_array);
		for($i=0;$i<$len;$i++)
		{
			//echo $wish_array[$i]."+".$date_array[$i]."<br/>";
			$sqlstr="insert into maoyan_want_see(name,wish_num,time)values('{$name}','{$wish_array[$i]}','{$date_array[$i]}');";
			echo $sqlstr;
			mysql_query($sqlstr,$con);
		}
	}

?>