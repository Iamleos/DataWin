<?php
/**
 * descript:采集猫眼日票房
 * @date 2016/7/16
 * @author  XuJun
 * @version 1.0
 * @package
 */
	 header("content-type:text/html;charset=utf-8");
	 set_time_limit(0);
		//设置代理，解决用户的问题。
	 ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');

	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";

	//连接数据库
    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	mysql_query("create table if not exists maoyan_ri_piaofang(name varchar(50),piaofang varchar(10),piaofang_rate varchar(10),paipian_rate varchar(10),people_per_session varchar(10),time date,primary key(name,time))");

	$result=mysql_query("select * from maoyan_everyday_name_num");
	while($row=mysql_fetch_row($result))
	{
		$name=$row[0];
		$num=$row[1];
		$exist=mysql_query("select * from maoyan_ri_piaofang where name='{$name}'");
		if(mysql_num_rows($exist))
		{
			echo "test<br/>";
			continue;

		}
		$url="http://piaofang.maoyan.com/movie/{$num}?_v_=yes";
		 $html=file_get_contents($url);
		 //echo $html;
		 $arr=array();
		 $preg='/<div[\w\W]*?id="ticket_tbody">([\w\W]*?)<\/div>/';
		 preg_match_all($preg,$html,$arr);
		 //var_dump($arr[1][0]);
		 $arr1= array();
		 $preg1='/<ul>([\w\W]*?)<\/ul>/';
		 preg_match_all($preg1,$arr[1][0],$arr1);
		 //var_dump($arr1[1]);
		 $size=count($arr1[1]);
		 for($i=0;$i<$size;$i++)
		 {
			//var_dump($arr1[1][$i]);
			$arr2=array();
			$preg2='/[\w\W]*?<li[\w\W]*?>([\w\W]*?)<\/li>/';
			preg_match_all($preg2,$arr1[1][$i],$arr2);
			$arr3 = array();
			$preg3='/<b>([\w\W]*?)<\/b>/';
			preg_match_all($preg3,$arr2[1][0],$arr3);
			$date=$arr3[1][0];
			$today_piaofang=$arr2[1][1];
			$piaofang_rate=$arr2[1][2];
			$paipian_rate=$arr2[1][3];
			$people_per_session=$arr2[1][4];
			//echo $name."+".$date."+".$today_piaofang."+".$piaofang_rate."+".$paipian_rate."+".$people_per_session."<br/>";

			$sqlstr="insert into maoyan_ri_piaofang(name,piaofang ,piaofang_rate,paipian_rate ,people_per_session ,time)values('{$name}','{$today_piaofang}','{$piaofang_rate}','{$paipian_rate}','{$people_per_session}','{$date}')";
			echo $sqlstr."<br/>";
			mysql_query($sqlstr,$con);


		 }
	}

?>