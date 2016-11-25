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
	$arr['title']=$today."电影销售效率";
	$arr['data']=array();

	//echo json_encode($arr);
	$film=mysql_query("select * from dianyingzh where zboxoffice>0 order by zboxoffice desc;");
	$i=0;
	//计算总人数
	$film_people=mysql_query("select zperson from dianyingzh order by zboxoffice desc limit 10");
	$total_people=0;
	while($row=mysql_fetch_row($film_people,true))
	{
		$total_people+=(int)$row['zperson'];
	}


	while($row=mysql_fetch_row($film,true)){
		$name=$row['mainname'];
		$jrpp=round((float)$row['mrowpiecerate'],1)."%";
		$mrpp=round((float)$row['tomorrow_rate'],1)."%";
		$jrhjpp=round((float)$row['m_gold_rowpiecerate_today'],1)."%";
		$mrhjpp=round((float)$row['m_gold_rowpiecerate_tomorrow'],1)."%";
		$pjpj="";
		if((float)$row['zperson']!=0)
		{
			$pjpj=(int)(((float)$row['zboxoffice'])*10000/((float)$row['zperson']));
		}
		$gyxl="";

		if(round((((float)$row['zperson'])/$total_people-(float)$row['jb_mrowpiecerate']/100)*100,1)!=0)
		{
			$gyxl=round((((float)$row['zperson'])/$total_people-(float)$row['jb_mrowpiecerate']/100)*100,1)."%";
		}




		$ppxl=round(((-(float)$row['mrowpiecerate']+(float)$row['jb_mboxofficerate'])*100)/100,1)."%";

		$wxbl=round((1-(float)(((float)$row['zofficerate'])/100.00))*100,3)."%";
		$dbpf=0;
		if($row['dfen']>0)
		{
			$dbpf=$row['dfen'];
		}else{
			$flag=0;
			$dbf=mysql_query("select * from dyhistory where mainname='{$name}' order by acquitime desc");
			while($db_row=mysql_fetch_row($dbf,true))
			{
				if($db_row['dfen']>0)
				{
					$dbpf=$db_row['dfen'];
					$flag=1;
					break;
				}
			}
			if($flag==0)
			{
				$dbpf=0;
			}
		}

		$datalist=array("name"=>$name,"jrpp"=>$jrpp,"mrpp"=>$mrpp,"jrhjpp"=>$jrhjpp,"mrhjpp"=>$mrhjpp,"pjpj"=>$pjpj,"gyxl"=>$gyxl,"ppxl"=>$ppxl,"wxbl"=>$wxbl);
		$arr['data'][$i]=$datalist;
		$i++;
	}

	$j=0;

	//其他电影的预排片
	$arr['ypp']=array();
	$s_film=mysql_query("select mname from specialfilm where type=1 or type=2 union select mname from newfilm where type=3");


	while($row=mysql_fetch_row($s_film,true))
	{

		$name=$row['mname'];
		$data=mysql_query("select * from dianyingzh where mainname='{$name}'");
		while($data_row=mysql_fetch_row($data,true))
		{

			$jrpp=(float)$data_row['jb_mrowpiecerate']>0?$data_row['jb_mrowpiecerate']."%":"";
			$mrpp=(float)$data_row['tomorrow_rate']>0?$data_row['tomorrow_rate']."%":"";
			$jrhjpp=(float)$data_row['m_gold_rowpiecerate_today']>0?$data_row['m_gold_rowpiecerate_today']."%":"";
			$mrhjpp=(float)$data_row['m_gold_rowpiecerate_tomorrow']>0?$data_row['m_gold_rowpiecerate_tomorrow']."%":"";
			$pjpj="";

			$gyxl="";


			$ppxl=round(((-(float)$data_row['mrowpiecerate']+(float)$data_row['jb_mboxofficerate'])*100)/100,1)."%";

			$wxbl=round((1-(float)(((float)$data_row['zofficerate'])/100.00))*100,3)."%";
			$dbpf=0;
			if($data_row['dfen']>0)
			{
				$dbpf=$data_row['dfen'];
			}else{
				$flag=0;
				$dbf=mysql_query("select * from dyhistory where mainname='{$name}' order by acquitime desc");
				while($db_row=mysql_fetch_row($dbf,true))
				{
					if($db_row['dfen']>0)
					{
						$dbpf=$db_row['dfen'];
						$flag=1;
						break;
					}
				}
				if($flag==0)
				{
					$dbpf=0;
				}
			}

			$datalist=array("name"=>$name,"jrpp"=>$jrpp,"mrpp"=>$mrpp,"jrhjpp"=>$jrhjpp,"mrhjpp"=>$mrhjpp,"pjpj"=>$pjpj,"gyxl"=>$gyxl,"ppxl"=>$ppxl,"wxbl"=>"");
			$arr['ypp'][$j]=$datalist;
			$j++;
		}

	}

	echo json_encode($arr);

?>