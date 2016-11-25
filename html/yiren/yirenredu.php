<?php
/**
 * descript:计算艺人论坛热度
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

	mysql_query("create table yirenredu as select * from actname;");

	//添加艺人的贴吧关注数，主题数，文章数
	mysql_query("alter table yirenredu add column ytheme int(10);");
	mysql_query("alter table yirenredu add column ypaper int(10);");
	mysql_query("alter table yirenredu add column ylove int(10);");

	//添加艺人的贴吧关注数，主题数，文章数的变化情况
	mysql_query("alter table yirenredu add column incretheme int(10);");
	mysql_query("alter table yirenredu add column increpaper int(10);");
	mysql_query("alter table yirenredu add column increlove int(10);");

	//添加微信中的文章数，阅读数，点赞数
	mysql_query("alter table yirenredu add column wpaper int(10);");
	mysql_query("alter table yirenredu add column wread int(10);");
	mysql_query("alter table yirenredu add column wlove int(10);");

	//添加娱乐圈、豆瓣八组、友谊吧、天涯、兔区的提及数
	mysql_query("alter table yirenredu add column yulequannum int(10);");
	mysql_query("alter table yirenredu add column doubanbazunum int(10);");
	mysql_query("alter table yirenredu add column youyinum int(10);");
	mysql_query("alter table yirenredu add column tianyanum int(10);");
	mysql_query("alter table yirenredu add column tuqunum int(10);");

	//出入采集日期
	mysql_query("alter table yirenredu add column acquitime date");

	$day=date("Y-m-d");

	mysql_query("update yirenredu set acquitime='{$day}'");

	//插入艺人百度贴吧主题数，文章数，关注数
	mysql_query("update yirenredu set ytheme=(select yirenba.ytheme from yirenba where yirenba.yname=yirenredu.me  and yirenba.yacquitime='{$day}');");
	mysql_query("update yirenredu set ypaper=(select yirenba.ypaper from yirenba where yirenba.yname=yirenredu.me  and yirenba.yacquitime='{$day}');");
	mysql_query("update yirenredu set ylove=(select yirenba.ylove from yirenba where yirenba.yname=yirenredu.me  and yirenba.yacquitime='{$day}');");

	//插入艺人百度贴吧主题变化数，文章变化数，关注变化数
	mysql_query("update yirenredu set incretheme=(select yirenba.incretheme from yirenba where yirenba.yname=yirenredu.me and yirenba.yacquitime='{$day}');");
	mysql_query("update yirenredu set increpaper=(select yirenba.increpaper from yirenba where yirenba.yname=yirenredu.me and yirenba.yacquitime='{$day}');");
	mysql_query("update yirenredu set increlove=(select yirenba.increlove from yirenba where yirenba.yname=yirenredu.me and yirenba.yacquitime='{$day}');");

	//插入艺人微信文章数，阅读数，点赞数
	mysql_query("update yirenredu set wpaper=(select yirenweixin.ypaper from yirenweixin where yirenweixin.yname=yirenredu.me and yirenweixin.yacquitime='{$day}');");
	mysql_query("update yirenredu set wread=(select yirenweixin.yread from yirenweixin where yirenweixin.yname=yirenredu.me and yirenweixin.yacquitime='{$day}');");
	mysql_query("update yirenredu set wlove=(select yirenweixin.ylove from yirenweixin where yirenweixin.yname=yirenredu.me and yirenweixin.yacquitime='{$day}');");

	$yulequanstr="";
	$doubanbazustr="";
	$youyibastr="";
	$tianyastr="";
	$tuqustr="";

	//将娱乐圈中的标题提取出来并串联起来
	$resyule=mysql_query("select ytitle from yuleba;",$con);
	while($row=mysql_fetch_row($resyule))
	{
		$yulequanstr=$row[0]."".$yulequanstr;
	}

	echo $yulequanstr."<br>";

	//根据艺人的不同的别名提取提及数
	$yulename=mysql_query("select * from actname");
	while($row=mysql_fetch_row($yulename)){

		$yulenum=0;
		if($row[0]!=""){

			$yulenum+=substr_count($yulequanstr,$row[0]);
		}
		if($row[1]!=""){

			$yulenum+=substr_count($yulequanstr,$row[1]);
		}
		if($row[2]!=""){

        $yulenum+=substr_count($yulequanstr,$row[2]);

		}if($row[3]!=""){

			$yulenum+=substr_count($yulequanstr,$row[3]);

		}if($row[4]!=""){

        $yulenum+=substr_count($yulequanstr,$row[4]);

		}if($row[5]!=""){

        $yulenum+=substr_count($yulequanstr,$row[5]);

		}if($row[6]!=""){

        $yulenum+=substr_count($yulequanstr,$row[6]);

		}if($row[7]!=""){

			$yulenum+=substr_count($yulequanstr,$row[7]);

		}if($row[8]!=""){

		   $yulenum+=substr_count($yulequanstr,$row[8]);

		}if($row[9]!=""){

			$yulenum+=substr_count($yulequanstr,$row[9]);

		}if($row[10]!=""){

			$yulenum+=substr_count($yulequanstr,$row[10]);
		}
    echo $row[0]."-".$yulenum."<br/>";

    echo "update yirenredu set yulequannum={$yulenum} where yirenredu.me='{$row[0]}';";

    mysql_query("update yirenredu set yulequannum={$yulenum} where yirenredu.me='{$row[0]}';");

	}

	//同上，提取豆瓣八组的提及数
	$resdouban=mysql_query("select dtitle from doubanbazu;",$con);
	while($row=mysql_fetch_row($resdouban))
	{
		$doubanbazustr=$row[0]."".$doubanbazustr;
	}

	echo $doubanbazustr."<br>";

	$doubanname=mysql_query("select * from actname");

	while($row=mysql_fetch_row($doubanname)){

		$doubannum=0;

		if($row[0]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[0]);
		}
		if($row[1]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[1]);
		}
	    if($row[2]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[2]);

		}if($row[3]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[3]);

		}if($row[4]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[4]);

		}if($row[5]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[5]);

		}if($row[6]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[6]);

		}if($row[7]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[7]);

		}if($row[8]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[8]);

		}if($row[9]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[9]);

		}if($row[10]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[10]);
		}

    echo $row[0]."-".$doubannum."<br/>";
    echo "update yirenredu set doubanbazunum={$doubannum} where yirenredu.me='{$row[0]}';";
    mysql_query("update yirenredu set doubanbazunum={$doubannum} where yirenredu.me='{$row[0]}';");

	}

   //提取友谊吧的提及数
	$resyouyi=mysql_query("select ytitle from youyiba;",$con);
	while($row=mysql_fetch_row($resyouyi))
	{
		$youyibastr=$row[0]."".$youyibastr;
	}

	echo $youyibastr."<br>";

	$youyiname=mysql_query("select * from actname");

	while($row=mysql_fetch_row($youyiname)){
		$youyinum=0;
		if($row[0]!=""){

			$youyinum+=substr_count($youyibastr,$row[0]);
		}
		if($row[1]!=""){

			$youyinum+=substr_count($youyibastr,$row[1]);

		}
		if($row[2]!=""){

			$youyinum+=substr_count($youyibastr,$row[2]);

		}if($row[3]!=""){

		    $youyinum+=substr_count($youyibastr,$row[3]);

		}if($row[4]!=""){

			$youyinum+=substr_count($youyibastr,$row[4]);

		}if($row[5]!=""){

			$youyinum+=substr_count($youyibastr,$row[5]);

		}if($row[6]!=""){

			$youyinum+=substr_count($youyibastr,$row[6]);

		}if($row[7]!=""){

			$youyinum+=substr_count($youyibastr,$row[7]);

		}if($row[8]!=""){

			$youyinum+=substr_count($youyibastr,$row[8]);

		}if($row[9]!=""){

			$youyinum+=substr_count($youyibastr,$row[9]);

		}if($row[10]!=""){

			$youyinum+=substr_count($youyibastr,$row[10]);
		}

    echo $row[0]."-".$youyinum."<br/>";
    echo "update yirenredu set youyinum={$youyinum} where yirenredu.me='{$row[0]}';";
    mysql_query("update yirenredu set youyinum={$youyinum} where yirenredu.me='{$row[0]}';");

	}


	//天涯提及数
	$restianya=mysql_query("select ttitle from tianya;",$con);
	while($row=mysql_fetch_row($restianya))
	{
		$tianyastr=$row[0]."".$tianyastr;
	}

	echo $tianyastr."<br>";

	$tianyaname=mysql_query("select * from actname");

	while($row=mysql_fetch_row($tianyaname)){
		$tianyanum=0;
		if($row[0]!=""){

			$tianyanum+=substr_count($tianyastr,$row[0]);
		}
		if($row[1]!=""){

			$tianyanum+=substr_count($tianyastr,$row[1]);
		}
		if($row[2]!=""){

			$tianyanum+=substr_count($tianyastr,$row[2]);

		}if($row[3]!=""){

        $tianyanum+=substr_count($tianyastr,$row[3]);

		}if($row[4]!=""){

			$tianyanum+=substr_count($tianyastr,$row[4]);

		}if($row[5]!=""){

        $tianyanum+=substr_count($tianyastr,$row[5]);

		}if($row[6]!=""){

			$tianyanum+=substr_count($tianyastr,$row[6]);

		}if($row[7]!=""){

        $tianyanum+=substr_count($tianyastr,$row[7]);

		}if($row[8]!=""){

			$tianyanum+=substr_count($tianyastr,$row[8]);

		}if($row[9]!=""){

			$tianyanum+=substr_count($tianyastr,$row[9]);

		}if($row[10]!=""){

			$tianyanum+=substr_count($tianyastr,$row[10]);
		}

    echo $row[0]."-".$tianyanum."<br/>";
    echo "update yirenredu set tianyanum={$tianyanum} where yirenredu.me='{$row[0]}';";
    mysql_query("update yirenredu set tianyanum={$tianyanum} where yirenredu.me='{$row[0]}';");

	}

	//兔区提及数
	$restuqu=mysql_query("select jtitle from tuqu;",$con);

	while($row=mysql_fetch_row($restuqu))
	{
		$tuqustr=$row[0]."".$tuqustr;
	}

	echo $tuqustr."<br>";

	$tuquname=mysql_query("select * from actname");

	while($row=mysql_fetch_row($tuquname)){

		$tuqunum=0;
		if($row[0]!=""){

			$tuqunum+=substr_count($tuqustr,$row[0]);
		}
		if($row[1]!=""){

			$tuqunum+=substr_count($tuqustr,$row[1]);
		}
		if($row[2]!=""){

			$tuqunum+=substr_count($tuqustr,$row[2]);

		}if($row[3]!=""){

			$tuqunum+=substr_count($tuqustr,$row[3]);

		}if($row[4]!=""){

			$tuqunum+=substr_count($tuqustr,$row[4]);

		}if($row[5]!=""){

			$tuqunum+=substr_count($tuqustr,$row[5]);

		}if($row[6]!=""){

			$tuqunum+=substr_count($tianyastr,$row[6]);

		}if($row[7]!=""){

			$tuqunum+=substr_count($tuqustr,$row[7]);

		}if($row[8]!=""){

			$tuqunum+=substr_count($tuqustr,$row[8]);

		}if($row[9]!=""){

			$tuqunum+=substr_count($tuqustr,$row[9]);

		}if($row[10]!=""){

			$tuqunum+=substr_count($tuqustr,$row[10]);
		}

    //echo $row[0]."-".$tuqunum."<br/>";
    //echo "update yirenredu set tuqunum={$tuqunum} where yirenredu.me='{$row[0]}';";
    mysql_query("update yirenredu set tuqunum={$tuqunum} where yirenredu.me='{$row[0]}';");
    mysql_query("create table if not exists hyyirenredu like yirenredu;");
	mysql_query("insert into hyyirenredu select * from yirenredu");
	echo "insert into hyyirenredu select * from yirenredu";
	mysql_close();
	}

?>