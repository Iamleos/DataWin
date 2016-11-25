<?php
/**
 * descript:测试电影网络声量
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
	$arr['title']=$today."电影网络声量";
	$arr['data']=array();


	$film=mysql_query("select * from dianyingzh order by zboxoffice desc limit 10");
	$i=0;
	while($row=mysql_fetch_row($film,true)){
		$name=$row['mainname'];
		$wzs="";
		if($row['wzs']>0)
		{
			$wzs=$row['wzs'];
		}

		$sszs=(int)((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2);
		$mtzs=(int)((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2);
		$wxydl=round($row['wread']/10000);
		$wbslgsl="";
		if(round(((float)$row['wzs']/(float)$row['zboxoffice']),2)>0)
		{
			$wbslgsl=round(((float)$row['wzs']/(float)$row['zboxoffice']),2);
		}
		$ssslgsl=round((float)$sszs/(float)$row['zboxoffice']);
		$mtslgsl=round((float)$mtzs/(float)$row['zboxoffice'],1);
		$wxslgsl=round((float)$wxydl/(float)$row['zboxoffice'],2);


		$datalist=array("name"=>$name,"wzs"=>$wzs,"sszs"=>$sszs,"mtzs"=>$mtzs,"wxydl"=>$wxydl,"wbslgsl"=>$wbslgsl,"ssslgsl"=>$ssslgsl,"mtslgsl"=>$mtslgsl,"wxslgsl"=>$wxslgsl);
		$arr['data'][$i]=$datalist;
		$i++;
	}

	echo json_encode($arr);

?>