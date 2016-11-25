<?php
/**
 * descript:提取百度艺人贴吧数据
 * @date 2016/4/15
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
	mysql_query("create table if not exists yirenba(yname varchar(30),ytheme int(8),ypaper int(10),ylove int(10),yacquitime date,incretheme int(8),increpaper int(10),increlove int(10),primary key(yname,yacquitime));",$con);

	$result=mysql_query("select me from actname",$con);

	while($row=mysql_fetch_row($result))
	{

		$yname=$row[0];
		//$yname='泉水';
		$url="http://tieba.baidu.com/f?ie=utf-8&kw={$yname}&fr=search";

		$html=file_get_contents($url);

		//用于提取网页
		$arr=array();

		//提取包含class="th_footer_bright"的标签
		$preg = '/<div[\w\W]*?class="th_footer_bright">([\w\W]*?)<\/div>/';
		preg_match_all($preg, $html, $arr);
		echo $arr[1][0];

		//提取字符串中的数字

		$ytheme=0;
		$ypaper=0;
		$ylove=0;
		$yacquitime=date("Y-m-d");
		$incretheme=0;
		$increpaper=0;
		$increlove=0;
		$pattern = "/\d+/";
		if(preg_match_all($pattern, $arr[1][0], $match)){

			var_dump($match);
			$len=count($match[0]);
			$ytheme=$match[0][0];
			$ypaper=$match[0][1];
			$ylove=$match[0][$len-1];


		}

		$yinfo=mysql_query("select ytheme,ypaper,ylove from yirenba where yname='{$yname}'",$con);

        while($row=mysql_fetch_row($yinfo))
        {
		    if($row[0]!=0&&$ytheme!=0)
			{
				$incretheme=$ytheme-$row[0];
			}
			if($row[1]!=0&&$ypaper!=0)
			{
				$increpaper=$ypaper-$row[1];
			}
			if($row[2]!=0&&$ylove!=0)
			{
				$increlove=$ylove-$row[2];
			}

        }


       // mysql_query("delete  from yirenba where yname='{$yname}'");

		if($yname!="")
		{
			$sqlinsert="insert into yirenba(yname,ytheme,ypaper,ylove,yacquitime,incretheme,increpaper,increlove) values('{$yname}','{$ytheme}','{$ypaper}','{$ylove}','{$yacquitime}','{$incretheme}','{$increpaper}','{$increlove}')";
			echo $sqlinsert;
			mysql_query($sqlinsert,$con);
		}

	}
	//$day=date("Y-m-d");
	//mysql_query("update xingqubuluo set yacquitime={$day}");

	mysql_close($con);


?>