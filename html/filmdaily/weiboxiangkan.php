<?php
/**
 * descript:网页抓取微博想看日榜
 * @date 2016/4/14
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	//mysql_query("drop table if exists weiboxiangkan;",$con);
    mysql_query("create table if not exists weiboxiangkan(wbname varchar(30),wbdaysremaining int(3),wbwantfen decimal(4,1),wbwantnum int(8),wbacquitime date ,primary key(wbname,wbacquitime));",$con);

	$url='http://movie.weibo.com/movie/web/ajax_getRankTaobao?date=&data_type=movie_will_day_poll';
	$html=file_get_contents($url);
    $json=json_decode($html,true);


	$len = count($json['content']);

	for($i=0;$i<$len;$i++)
	{
		$name=$json['content'][$i]['name'];
		$days=$json['content'][$i]['shown_days'];
		$score=$json['content'][$i]['trendinfo']['score'];
		$want_num=$json['content'][$i]['want_number'];
		$today=date('Y-m-d');
		$str="insert into weiboxiangkan (wbname,wbdaysremaining,wbwantfen,wbwantnum,wbacquitime) values('{$name}','{$days}','{$score}','{$want_num}','{$today}')";
		mysql_query($str,$con);
		echo $str."<br/>";
	}

	mysql_close();
?>