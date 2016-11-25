<?php
/**
 * descript:网页抓取微博热议日榜
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
    mysql_query("drop table if exists weiboreyi;",$con);
    mysql_query("create table weiboreyi(wbname varchar(30),wbday int(3),wbscore decimal(4,1),wbfennum int(8),wbgood_rate decimal(4,2),wbbad_rate decimal(4,2),wbacquitime date ,primary key(wbname,wbacquitime));",$con);

	$url='http://movie.weibo.com/movie/web/ajax_getRankTaobao?date=&data_type=movie_hot_day_poll';
	$html=file_get_contents($url);
    $json=json_decode($html,true);
	$len = count($json['content']);
	for($i=0;$i<$len;$i++)
	{
		$name=$json['content'][$i]['name'];
		$days=$json['content'][$i]['shown_days'];
		$score=$json['content'][$i]['trendinfo']['score'];
		$score_count=$json['content'][$i]['markinfo']['score_count'];
		$good_rate=number_format($json['content'][$i]['markinfo']['good_rate']*100,2);
		$bad_rate=number_format($json['content'][$i]['markinfo']['bad_rate']*100,2);
		$today=date('Y-m-d');
		$str="insert into weiboreyi (wbname,wbday,wbscore,wbfennum,wbgood_rate,wbbad_rate,wbacquitime) values('{$name}','{$days}','{$score}','{$score_count}','{$good_rate}','{$bad_rate}','{$today}')";
		mysql_query($str,$con);
		echo $str."<br/>";
	}
	mysql_close();
?>