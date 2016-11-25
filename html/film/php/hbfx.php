<?php
/**
 * descript:测试昨日环比数据
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
	$arr['title']=$today."电影营销与昨日环比分析";
	$arr['data']=array();

	$film=mysql_query("select * from dianyingzh order by zboxoffice desc limit 10");
	$i=0;

	while($row=mysql_fetch_row($film,true)){
		$name=$row['mainname'];
		//获取指定日期的前一天
		$yesterday=date("Y-m-d",(strtotime("{$row_time[0]}")-3600*24));
		$film_yesterday=mysql_query("select * from dyhistory where mainname='{$name}' and acquitime='{$yesterday}'");

		$rpf="";
		$gyrc="";
		$pp="";
		$szl="";
		$hgd="";
		$dbkb="";
		$wlslzs="";
		$wltjzs="";
		if($film_row=mysql_fetch_row($film_yesterday,true))
		{
			if($film_row['zboxoffice']!=0)
			{
				$rpf=round((((float)$row['zboxoffice'])-((float)$film_row['zboxoffice']))/((float)$film_row['zboxoffice'])*100,1)."%";
			}else{
				$rpf="";
			}

			if($film_row['zperson']!=0)
			{
				$gyrc=round((((float)$row['zperson'])-((float)$film_row['zperson']))/((float)$film_row['zperson'])*100,1)."%";
			}else{
				$gyrc="";
			}

			$pp=round(((float)$row['jb_mrowpiecerate'])-((float)$film_row['jb_mrowpiecerate']),1)."%";
			$szl=round(((float)$row['jb_mseatrate'])-((float)$film_row['jb_mseatrate']),1)."%";

			//计算今天的好感度
			$dfen_today=$row['dfen']>0?$row['dfen']:"";
			$gfen_today=$row['gfen']>0?$row['gfen']:"";
			$mfen_today=$row['mfen']>0?$row['mfen']:"";
			$wbfen_today=round($row['wbgood_rate']/10.0,1)>0?round($row['wbgood_rate']/10.0,1):"";
			$count_today=4;
			if($dfen_today>0)
			{
				$count_today--;
			}
			if($gfen_today>0)
			{
				$count_today--;
			}

			if($mfen_today>0)
			{
				$count_today--;
			}

			if($wbfen_today>0)
			{
				$count_today--;
			}
			$hgd_today="";
			if($count_today!=4)
			{
				$hgd_today=round(($dfen_today*0.4+$gfen_today*0.2+$mfen_today*0.2+$wbfen_today*0.2)/(1-round($count_today/4.0,3)),3);
			}


			//计算昨天的好感度
			$dfen_yesterday=$film_row['dfen']>0?$film_row['dfen']:"";
			$gfen_yesterday=$film_row['gfen']>0?$film_row['gfen']:"";
			$mfen_yesterday=$film_row['mfen']>0?$film_row['mfen']:"";
			$wbfen_yesterday=round($film_row['wbgood_rate']/10.0,1)>0?round($film_row['wbgood_rate']/10.0,1):"";
			$count_yesterday=4;
			if($dfen_yesterday>0)
			{
				$count_yesterday--;
			}
			if($gfen_yesterday>0)
			{
				$count_yesterday--;
			}

			if($mfen_yesterday>0)
			{
				$count_yesterday--;
			}

			if($wbfen_yesterday>0)
			{
				$count_yesterday--;
			}
			$hgd_yesterday="";
			if($count_yesterday!=4)
			{
				$hgd_yesterday=round(($dfen_yesterday*0.4+$gfen_yesterday*0.2+$mfen_yesterday*0.2+$wbfen_yesterday*0.2)/(1-round($count_yesterday/4.0,3)),3);
			}

			$hgd=round((float)$hgd_today-(float)$hgd_yesterday,2);

			$db_fen1=0;
			$db_fen2=0;
			$db_count=0;
			$dbkb=0;
			$db_fen=mysql_query("select * from dyhistory where mainname='{$name}' order by acquitime");
			while($db_row=mysql_fetch_row($db_fen,true))
			{
				if($db_row['dfen']>0)
				{
					$db_count++;
					if($db_count==1)
					{
						$db_fen1=$db_row['dfen'];
					}
					if($db_count==2)
					{
						$db_fen2=$db_row['dfen'];
						$dbkb=round((float)$db_fen1-(float)$db_fen2,1);
					}
				}

			}

			$wlslzs_today=round((round($row['wzs']/150000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/150000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/1000.0+($row['wread']/10000.0)/150.0)*3,2)>10?9.9:round((round($row['wzs']/150000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/150000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/1000.0+($row['wread']/10000.0)/150.0)*3,2);

			$wlslzs_yesterday=round((round($film_row['wzs']/150000.0,2)+((float)$film_row['bsearch']*0.8+(float)$film_row['qsearch']*0.2)/150000.0+((float)$film_row['bmeiti']+(float)$film_row['qmeiti']*0.2*0.2)/1000.0+($film_row['wread']/10000.0)/150.0)*3,2)>10?9.9:round((round($film_row['wzs']/150000.0,2)+((float)$film_row['bsearch']*0.8+(float)$film_row['qsearch']*0.2)/150000.0+((float)$film_row['bmeiti']+(float)$film_row['qmeiti']*0.2*0.2)/1000.0+($film_row['wread']/10000.0)/150.0)*3,2);

			if(((float)$wlslzs_today-(float)$wlslzs_yesterday)>0)
			{
				$wlslzs=round((float)$wlslzs_today-(float)$wlslzs_yesterday,2);
			}else {
			    $wlslzs="";
			}

			$wltjzs_today=round((float)$hgd_today*(float)$wlslzs_today/8,2);
			$wltjzs_yesterday=round((float)$hgd_yesterday*(float)$wlslzs_yesterday/8,2);

			if(((float)$wltjzs_today-(float)$wltjzs_yesterday)>0)
			{
				$wltjzs=round((float)$wltjzs_today-(float)$wltjzs_yesterday,2);
			}else {
			    $wltjzs="";
			}

		}else {
			    $rpf="";
				$gyrc="";
				$pp="";
				$szl="";
				$hgd="";
				$dbkb="";
				$wlslzs="";
				$wltjzs="";
		}



		$datalist=array("name"=>$name,"rpf"=>$rpf,"gyrc"=>$gyrc,"dbkb"=>$dbkb,"pp"=>$pp,"szl"=>$szl,"hgd"=>$hgd,"wlslzs"=>$wlslzs,"wltjzs"=>$wltjzs);
		$arr['data'][$i]=$datalist;
		$i++;
	}

	echo json_encode($arr);

?>