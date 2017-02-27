<?php
/**
 * descript:测试电影销售效率
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
	$arr['title']=$today."电影口碑力量";
	$arr['data']=array();

	//echo json_encode($arr);
	$film=mysql_query("select * from dianyingzh order by zboxoffice desc limit 10");
	$i=0;

	while($row=mysql_fetch_row($film,true)){
		$name=$row['mainname'];
		$dfen=$row['dfen']>0?$row['dfen']:"";
		$gfen=$row['gfen']>0?$row['gfen']:"";
		$mfen=$row['mfen']>0?$row['mfen']:"";
		$wbfen=round($row['wbgood_rate']/10.0,1)>0?round($row['wbgood_rate']/10.0,1):"";
		$count=4;
		if($dfen>0)
		{
			$count--;
		}
		if($gfen>0)
		{
			$count--;
		}

		if($mfen>0)
		{
			$count--;
		}

		if($wbfen>0)
		{
			$count--;
		}

		$hgd="";
		if($count!=4)
		{
			$hgd=round(($dfen*0.4+$gfen*0.2+$mfen*0.2+$wbfen*0.2)/(1-round($count/4.0,2)),2)>9.99?9.99:round(($dfen*0.4+$gfen*0.2+$mfen*0.2+$wbfen*0.2)/(1-round($count/4.0,2)),2);
		}

		$wlslzs=round((round($row['wzs']/150000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/150000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/1000.0+($row['wread']/10000.0)/150.0)*3,2)>10?9.9:round((round($row['wzs']/150000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/150000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/1000.0+($row['wread']/10000.0)/150.0)*3,2);

		$wltjzs=round((float)$hgd*(float)$wlslzs/8,2);


		$datalist=array("name"=>$name,"dfen"=>$dfen,"gfen"=>$gfen,"mfen"=>$mfen,"wbfen"=>$wbfen,"hgd"=>$hgd,"wlslzs"=>$wlslzs,"wltjzs"=>$wltjzs);
		$arr['data'][$i]=$datalist;
		$i++;
	}

	echo json_encode($arr);

?>