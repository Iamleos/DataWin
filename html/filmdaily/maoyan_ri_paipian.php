<?php
/**
 * descript:采集猫眼日排片的数据
 * @date 2016/7/19
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

	mysql_query("create table if not exists maoyan_ri_paipian(name varchar(50),changci varchar(10),ppzhanbi varchar(10),zuowei varchar(10),pzzhanbi varchar(10),hjzhanbi varchar(10),time date,primary key(name,time))");

	$result=mysql_query("select * from maoyan_everyday_name_num");
	while($row=mysql_fetch_row($result))
	{
		$name=$row[0];
		$num=$row[1];
		$exist=mysql_query("select * from maoyan_ri_paipian where name='{$name}'");
		if(mysql_num_rows($exist))
		{
			echo "test<br/>";
			continue;

		}
		 $today=date("Y-m-d");
		 $url="http://piaofang.maoyan.com/movie/{$num}/dayShow?releaseDate={$today}";
		 //echo $url;
		 $html=file_get_contents($url);
		 $arr=array();
		 $preg='/<table[\w\W]*?m-table[\w\W]*?>([\w\W]*?)<\/table>/';
		 preg_match_all($preg,$html,$arr);
		 //var_dump($arr[1][0]);
		 $arr1=array();
		 $preg1='/<tr>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<\/tr>/';
		 preg_match_all($preg1,$arr[1][0],$arr1);
		 $size=count($arr1[1]);
		 for($i=0;$i<$size;$i++)
		 {
			//日期
			$arr3 = array();
			$preg3='/<b>([\w\W]*?)<\/b>/';
			preg_match_all($preg3,$arr1[1][$i],$arr3);

			$date=$arr3[1][0];
			//场次
			$changci=$arr1[2][$i];
			//排片占比
			$ppzhanbi=$arr1[3][$i];
			//座位
			$zuowei=$arr1[4][$i];
			//排座占比
			$pzzhanbi=$arr1[5][$i];
			//黄金场占比
			$hjzhanbi=$arr1[6][$i];

			//echo $date."+".$changci."+".$ppzhanbi."+".$zuowei."+".$pzzhanbi."+".$hjzhanbi."<br/>";

			$sqlstr="insert into maoyan_ri_paipian(name,changci ,ppzhanbi,zuowei ,pzzhanbi ,hjzhanbi,time)values('{$name}','{$changci}','{$ppzhanbi}','{$zuowei}','{$pzzhanbi}','{$hjzhanbi}','{$date}')";
			echo $sqlstr."<br/>";
			mysql_query($sqlstr,$con);


		 }
	}



?>