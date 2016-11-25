<?php
/**
 * descript:采集友谊走到尽头贴吧
 * @date 2016/4/18
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");

	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";

    $name="root";
    $password="ctfoxno1";

    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

    mysql_query("drop table if exists youyiba;",$con);
    mysql_query("create table youyiba(ytitle varchar(150),ysendtime datetime,yacquitime datetime,primary key(ytitle,ysendtime,yacquitime));",$con);
	$url="http://tieba.baidu.com/f?kw=%E5%8F%8B%E8%B0%8A%E5%B7%B2%E8%B5%B0%E5%88%B0%E5%B0%BD%E5%A4%B4&ie=utf-8";
	//echo $html;
    for($j=1;$j<5;$j++)
	{
	    $pn=$j*50;
		$html=file_get_contents($url);
        $url="http://tieba.baidu.com/f?kw=%E5%8F%8B%E8%B0%8A%E5%B7%B2%E8%B5%B0%E5%88%B0%E5%B0%BD%E5%A4%B4&ie=utf-8&pn={$pn}";

		for($i=3;$i<45;$i++)
		{
			$arr1=array();
			$arr2=array();
			$arr3=array();
			$preg1 = '/<div[\w\W]*?class="t_con cleafix"[\w\W]*?>([\w\W]*?)<\/div>/';
			$preg2='/<a href="[\w\W]*?" title="[\w\W]*?" target="_blank" class="j_th_tit ">([\w\W]*?)<\/a>/';
			$preg3='/<span[\w\W]*?class="threadlist_reply_date pull_right j_reply_data"[\w\W]*?title="最后回复时间"[\w\W]*?>([\w\W]*?)<\/span>/';
			preg_match_all($preg1, $html, $arr1);
			preg_match_all($preg2, $arr1[0][$i], $arr2);
			preg_match_all($preg3, $arr1[0][$i],$arr3);
			$title=trim($arr2[1][0]);
			$sendtime=trim($arr3[1][0]);
			$acquitime=date("Y-m-d H:i:s");

            $day=date("Y-m-d");
			 $second=":00";
           if(strstr($sendtime,":"))
           {
               $sqlinsert="insert into youyiba(ytitle,ysendtime,yacquitime) values('{$title}','{$day} {$sendtime}{$second}','{$acquitime}')";
               echo $sqlinsert."<br/>";
               mysql_query($sqlinsert,$con);
           }

		}
	}
	mysql_close();

?>