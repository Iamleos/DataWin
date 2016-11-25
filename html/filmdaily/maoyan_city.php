<?php
/**
 * descript:获取猫眼城市
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

	mysql_query("create table if not exists maoyan_city(name varchar(50),city varchar(20),piaofang varchar(10),pf_rate varchar(10),pp_rate varchar(10),boxofficesum varchar(10),seat_rate varchar(10),hj_rate varchar(10),people_per varchar(10),people_total varchar(10),session varchar(10),time date,primary key(name,city,time))");


	$result=mysql_query("select * from maoyan_everyday_name_num");
	while($row=mysql_fetch_row($result))
	{
		$name=$row[0];
		$num=$row[1];
		$flag=0;

		$condition=mysql_query("select * from turing_zzb_film where film_name='{$name}'");
		while($myrow=mysql_fetch_row($condition))
		{

			if(strtotime($myrow[5])>strtotime("-3 day"))
			{
				$flag=1;
				continue;
			}
		}
		if($flag==1)
		{
			continue;
		}
		$exist=mysql_query("select * from maoyan_city where name='{$name}'");
		if(mysql_num_rows($exist))
		{
			echo "test<br/>";
			continue;

		}
		$url_date="http://piaofang.maoyan.com/movie/{$num}?_v_=yes";
		$html_date=file_get_contents($url_date);
		//echo $html;
		$arr_date=array();
		$preg_date='/<p>上映日期([\w\W]*?)<\/p>/';
		preg_match_all($preg_date,$html_date,$arr_date);
		$regex="'\d{4}-\d{1,2}-\d{1,2}'is";
		preg_match_all($regex,$arr_date[1][0],$matches);
		$start=$matches[0][0];

		//这里不使用循环，就可以只采集一天
		//for($i=0;$i<7;$i++)
		//{
			//$time=strtotime($start)+$i*3600*24;
			$time=strtotime($start);
			$date=date('Y-m-d',$time);
			$url="http://piaofang.maoyan.com/movie/{$num}/cityBox?date={$date}";

			$html=file_get_contents($url);
			//echo $html;
			$arr2=array();
			$preg2='/<table[\w\W]*?m-table-city[\w\W]*?>([\w\W]*?)<\/table>/';
			preg_match_all($preg2,$html,$arr2);
			//var_dump($arr2[1][0]);
			$arr=array();
			$preg='/<tr>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<td>([\w\W]*?)<\/td>[\w\W]*?<\/tr>/';
			preg_match_all($preg,$arr2[1][0],$arr);
			//var_dump($arr[2]);

			$size=count($arr[2]);
			for($i=0;$i<$size;$i++)
			{
				//城市名
				$city=$arr[1][$i];
				//票房
				$piaofang=$arr[2][$i];
				//票房占比
				$pf_rate=$arr[3][$i];
				//排片占比
				$pp_rate=$arr[4][$i];
				//累计票房
				$boxofficesum=$arr[5][$i];
				//排座占比
				$seat_rate=$arr[6][$i];
				//黄金排片占比
				$hj_rate=$arr[7][$i];
				//场均人次
				$people_per=$arr[8][$i];
				//总人数
				$people_total=$arr[9][$i];
				//场次
				$session=$arr[10][$i];
				//时间

				//echo $name."+".$piaofang."+".$pf_rate."+".$pp_rate."+".$boxofficesum."+".$seat_rate."+".$hj_rate."+".$people_per."+".$people_total."+".$session."+".$date."<br/>";

				$sqlstr="insert into maoyan_city(name,city,piaofang ,pf_rate,pp_rate,boxofficesum ,seat_rate ,hj_rate,people_per,people_total,session,time)values('{$name}','{$city}','{$piaofang}','{$pf_rate}','{$pp_rate}','{$boxofficesum}','{$seat_rate}','{$hj_rate}','{$people_per}','{$people_total}','{$session}','{$date}');";
				//echo $sqlstr;
				mysql_query($sqlstr,$con);

			}
		//}


	}


?>