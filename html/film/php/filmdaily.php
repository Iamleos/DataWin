<?php
/**
 * descript:测试电影电影票房、排片占比、票房占比
 * @date
 * @author  XuJun
 * @version 1.0
 * @package
 */
 #! /usr/bin/php
	//设置浏览器编码
	header("Content-Type:text/html;charset=utf-8");
	//由于数据量非常大，所以设置永不超时
	set_time_limit(0);
	//数据库基本信息
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
	$name="root";
	$password="ctfoxno1";
	$dbname="filmdaily";
	//连接数据库
	$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
	mysql_select_db($dbname,$con);
	mysql_query("set names utf8");

	$time=mysql_query("select acquitime from dianyingzh");
	$row_time=mysql_fetch_row($time);
	$today=date("Y年n月j日",strtotime($row_time[0]));

	$arr=array();
	$arr['title']=$today."电影票房粗报(万元)";


	$film_today=mysql_query("select * from dianyingzh order by zboxoffice desc limit 10");
	$jtyjpf=0;
	$jtsspf=0;
	while($film_row_today=mysql_fetch_row($film_today,true))
	{
		if($film_row_today['zestimatedboxoffice']>0)
		{
			$jtyjpf=$film_row_today['zestimatedboxoffice'];
			$jtsspf=$film_row_today['zrealtimeboxoffice'];
			break;
		}


	}

	$yesterday=date("Y-m-d",(strtotime("{$row_time[0]}")-3600*24));
	$film_yesterday=mysql_query("select * from dyhistory where acquitime='{$yesterday}' order by zboxoffice desc limit 10");
	$ztyjpf=0;
	if($film_row=mysql_fetch_row($film_yesterday,true))
	{
		$ztyjpf=$film_row['zestimatedboxoffice'];
	}


	$sz=date("Y-m-d",(strtotime("{$row_time[0]}")-3600*24*7));
	$film_sz=mysql_query("select * from dyhistory where acquitime='{$sz}' order by zboxoffice desc limit 10");
	$szyjpf=0;
	if($film_row_sz=mysql_fetch_row($film_sz,true))
	{
		$szpf=$film_row_sz['zestimatedboxoffice'];

	}

	$tb="";
	if(round((float)$jtyjpf-(float)$szpf)>=0)
	{
		$tb="涨".round((float)$jtyjpf-(float)$szpf);
	}else {
	    $tb="跌".abs(round((float)$jtyjpf-(float)$szpf));
	}

	$hb="";
	if(round((float)$jtyjpf-(float)$ztyjpf)>=0)
	{
		$hb="涨".round((float)$jtyjpf-(float)$ztyjpf);
	}else {
	    $hb="跌".abs(round((float)$jtyjpf-(float)$ztyjpf));
	}

	$sub_title="到21:30票房{$jtsspf}万，预计全日{$jtyjpf}万，环比{$hb}万，同比{$tb}万";

	$arr['sub_title']=$sub_title;

	$film=mysql_query("select * from dianyingzh order by zboxoffice desc limit 10");

	//票房数据
	$i=0;
	$pf_sum=0;
	$arr['data_pf']=array();
	$arr['data_pfzb']=array();
	$arr['data_ppzb']=array();
	$ppzb_sum=0;

	while($row=mysql_fetch_row($film,true)){
		$name=$row['mainname'];
		$dypf=$row['zboxoffice'];
		$pf_sum+=(float)$dypf;
		$datalist=array("name"=>$name,"dypf"=>$dypf);
		$arr['data_pf'][$i]=$datalist;

		$pfzb=round($dypf/$jtsspf*100,2);
		$datalist1=array("name"=>$name,"pfzb"=>$pfzb);
		$arr['data_pfzb'][$i]=$datalist1;

		$ppzb=$row['jb_mrowpiecerate'];
		$ppzb_sum+=$ppzb;
		$datalist2=array("name"=>$name,"ppzb"=>$ppzb);
		$arr['data_ppzb'][$i]=$datalist2;

		$i++;
	}
	$arr['data_pf'][10]=array("name"=>"其他","dypf"=>round($jtsspf-$pf_sum));
	$arr['data_pfzb'][10]=array("name"=>"其他","pfzb"=>round((float)($jtsspf-$pf_sum)/(float)$jtsspf*100.0,2));
	$arr['data_ppzb'][10]=array("name"=>"其他","ppzb"=>round(100-$ppzb_sum,1));

	echo json_encode($arr);

?>
