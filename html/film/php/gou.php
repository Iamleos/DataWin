<?php
/**
 * descript:得到狗语言
 * @date 2016/8/17
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
	$weekarray=array("日","一","二","三","四","五","六"); //先定义一个数组
	$num=(int)date("w",strtotime($row_time[0]));
	$week="星期".$weekarray[$num];

	$new_film=0;

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

	$tomorrow_film=mysql_query("select * from newfilm where type=3");

	$new_film=mysql_num_rows($tomorrow_film);



	$gou=$today.$week."，截止21:30大盘{$jtsspf}万，预计全日大盘{$jtyjpf}万，环比{$hb}万，同比{$tb}万。";
	if($new_film>0)
	{
		$gou.="有{$new_film}部新片上映。<br/>";
	}else {

		$gou.="<br/>";
	}



	$s_film=mysql_query("select * from dianyingzh where m_gold_rowpiecerate_today>0 or m_gold_rowpiecerate_tomorrow>0 or jb_mrowpiecerate>0 or tomorrow_rate>0 order by zboxoffice desc");


	while($row=mysql_fetch_row($s_film,true))
	{

		$str = "";
		//$m_b=mysql_query("select * from dianyingzh where mainname='{$row['mainname']}'");
		//echo "select zboxoffice from dianyingzh where mainname='{$row['mainname']}'++++";
		//$m_boxoffice=mysql_fetch_row($m_b,true);

		$type=mysql_query("select type from specialfilm where mname='{$row['mainname']}'");
		$mtype=mysql_fetch_row($type,true);

		if((int)$mtype['type']==0||(int)$mtype['type']==3)
		{
			$syts=1;
			if($row['eday']==""&&$row['jb_mday']=="")
			{
				$syts=1;
			}else if($row['eday']==""){
				$syts=$row['jb_mday'];
			}else{
				$syts=$row['eday'];
			}

			$pf=0;

			if((float)$row['zrealtimeboxoffice']>0)
			{
				$pf=(int)(((float)$row['zboxoffice'])*((float)$row['zestimatedboxoffice'])/((float)$row['zrealtimeboxoffice']));
			}else {
				$pf=0;

			}
			if((int)$pf>700)
			{
				if($syts<=7)
				{
					if((int)$mtype['type']==3)
					{
						$str.=$row['mainname']."  上映首日，预计全日票房{$pf}万";
					}else {
					    $str.=$row['mainname']."  上映{$syts}天，预计全日票房{$pf}万";
					}


				}else {
				   $str.=$row['mainname']."，预计全日票房{$pf}万";
				}

				if((int)$pf>1500)
				{
					$yesterday=date("Y-m-d",(strtotime("{$row_time[0]}")-3600*24));
					$film_yesterday=mysql_query("select * from dyhistory where mainname='{$row['mainname']}' and acquitime='{$yesterday}'");
					$film_row=mysql_fetch_row($film_yesterday,true);

					$pf_hb="";
					if($film_row['zboxoffice']!=0)
					{
						$pf_hb=round((((float)$row['zboxoffice'])-((float)$film_row['zboxoffice']))/((float)$film_row['zboxoffice'])*100,1);

						if(abs($pf_hb)>0)
						{
							$str.="，票房环比{$pf_hb}%";
						}

					}else{
						$pf_hb="";
					}

				}
			}else if((int)$pf>100&&$syts<8){
				if((int)$mtype['type']==3)
					{
						$str.=$row['mainname']."  上映首日，预计全日票房{$pf}万";
					}else {
					    $str.=$row['mainname']."  上映{$syts}天，预计全日票房{$pf}万";
					}
			}

			if((float)round($row['jb_mseatrate'],1)>40)
			{
				$sz=round($row['jb_mseatrate'],1)."%";
				if($str!="")
				{
					$str.="，上座率高达{$sz}";
				}

			}



		}else if((int)$mtype['type']==1){
			$dy=mysql_query("select * from mpiaofangjianbao where mname='{$row['mainname']}'");
			$jb=mysql_fetch_row($dy,true);
			if($jb['mboxoffice']>0)
			{
				$str.=$row['mainname']."  点映，预计全日票房{$jb['mboxoffice']}万";
			}else {
			    $str.=$row['mainname']."  点映，";
			}

			$sz=round($row['jb_mseatrate'],1)."%";
			if(round($row['jb_mseatrate'],1)>0)
			{
				if($str!="")
				{
					$str.="，上座率为{$sz}";
				}
			}


		}else if((int)$mtype['type']==2){
			$dy=mysql_query("select * from mpiaofangjianbao where mname='{$row['mainname']}'");
			$jb=mysql_fetch_row($dy,true);
			if($jb['mboxoffice']>0)
			{
				$str.=$row['mainname']."  零点场，预计全日票房{$jb['mboxoffice']}万";
			}else {
			    $str.=$row['mainname']."  零点场，";
			}
			$sz=round($row['jb_mseatrate'],1)."%";
			if(round($row['jb_mseatrate'],1)>0)
			{
				if($str!="")
				{
					$str.="，上座率为{$sz}";
				}else {
					$str.="{$row['mainname']}，上座率为{$sz}";
				}
			}


		}



		if(floor(($row['zboxofficesum']-$row['zboxoffice'])/10000)<floor(($row['zboxofficesum'])/10000))
		{
			$yi=floor(($row['zboxofficesum'])/10000);
			if($yi>0)
			{
				if($str!="")
				{
					$str.="，累计破{$yi}亿";
				}else {
					$str.="{$row['mainname']}，累计破{$yi}亿";
				}

			}



		}

		if((float)$row['zsession']>0)
		{
			$cj=(int)(((float)$row['zperson'])/((float)$row['zsession']));
			if($cj>40)
			{
				if($str!="")
				{
					$str.="，场均高达{$cj}人";
				}
			}
		}

		if(round((1-(float)(((float)$row['zofficerate'])/100.00))*100,3)<50)
		{
			$wxbl=round((1-(float)(((float)$row['zofficerate'])/100.00))*100,3);
			if($str!="")
			{
				$str.="，网销占比仅{$wxbl}%";
			}
		}

		$gyxl="";

		$film_people=mysql_query("select zperson from dianyingzh order by zboxoffice desc limit 10");
		$total_people=0;
		while($row_people=mysql_fetch_row($film_people,true))
		{
			$total_people+=(int)$row_people['zperson'];
		}

		if(round((((float)$row['zperson'])/$total_people-(float)$row['jb_mrowpiecerate']/100)*100,1)!=0)
		{
			$gyxl=round((((float)$row['zperson'])/$total_people-(float)$row['jb_mrowpiecerate']/100)*100,1);
		}

		$ppxl=round(((-(float)$row['mrowpiecerate']+(float)$row['jb_mboxofficerate'])*100)/100,1);

		if((float)$gyxl+(float)$ppxl>7)
		{
			if($str!="")
			{
				$str.="，排片效率高";
			}
		}


		$db_fen1=0;
		$db_fen2=0;
		$db_count=0;
		$dbkb=0;
		$db_fen=mysql_query("select * from dyhistory where mainname='{$row['mainname']}' order by acquitime desc");

		while($db_row=mysql_fetch_row($db_fen,true))
		{
			if($db_row['dfen']>0)
			{
				$db_count++;
				if($db_count==1)
				{
					$db_fen1=round($db_row['dfen'],1);
				}
				if($db_count==2)
				{
					$db_fen2=$db_row['dfen'];
					$dbkb=round((float)$db_fen1-(float)$db_fen2,1);
				}
			}

		}

		if(($dbkb>0.2&&($row['zboxoffice']>700))||($dbkb>0.2&&($row['zboxoffice']>100)&&($row['jb_mday']<8)))
		{
			if($str!="")
			{
				$str.="，豆瓣分上升到{$db_fen1}";
			}

		}else if(($dbkb<-0.2&&($row['zboxoffice']>700))||($dbkb<-0.2&&($row['zboxoffice']>100)&&($row['jb_mday']<8))){

			if($str!="")
			{
				$str.="，豆瓣分下降到{$db_fen1}";
			}
		}

		$wlslzs_today=round((round($row['wzs']/80000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/200000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/100.0+($row['wread']/10000.0)/100.0)*3,2)>10?9.9:round((round($row['wzs']/80000.0,2)+((float)$row['bsearch']*0.8+(float)$row['qsearch']*0.2)/200000.0+((float)$row['bmeiti']+(float)$row['qmeiti']*0.2*0.2)/100.0+($row['wread']/10000.0)/100.0)*3,2);

		if((float)$wlslzs_today>7)
		{
			if($str!="")
			{
				$str.="，网络声量高";
			}
		}

		if((float)$wlslzs_today>3&&(float)$gyxl+(float)$ppxl>7)
		{
			$syts=1;
			if($row['eday']==""&&$row['jb_mday']=="")
			{
				$syts=1;
			}else if($row['eday']==""){
				$syts=$row['jb_mday'];
			}else{
				$syts=$row['eday'];
			}
			if($syts<7)
			{
				if($str!="")
				{
					$str.="，票房价值凸显";
				}
			}else{

				if($str!="")
				{
					$str.="，具备票房续航能力";
				}
			}

		}

		$ppc=round((float)$row['tomorrow_rate'],2)-round((float)$row['jb_mrowpiecerate'],2);

		if($ppc<-0.2)
		{

			if($row['tomorrow_rate']>0&&$new_film>0)
			{
				if($str!="")
				{
					$str.="，受新片影响，明日排片下降到{$row['tomorrow_rate']}%";
				}
			}else if($row['tomorrow_rate']>0&&$new_film==0){

				if($str!="")
				{
					$str.="，明日排片下降到{$row['tomorrow_rate']}%";
				}
			}else if($row['tomorrow_rate']==0&&$new_film>0){

				if($str!="")
				{
					$str.="，受新片影响，明日近乎没有排片";
				}
			}else if($row['tomorrow_rate']==0&&$new_film==0){

				if($str!="")
				{
					$str.="，明日近乎没有排片";
				}
			}

		}else if($row['tomorrow_rate']>0){
		    if($str!="")
				{
					$str.="，明日排片{$row['tomorrow_rate']}%";
				}
		}

		if($str!="")
		{
		    $gou.=$str."<br/>";
		}



	}



	$sy_film=mysql_query("select mname from newfilm where type=3");
	while($sy_row=mysql_fetch_row($sy_film,true))
	{
		$sy_name=$sy_row['mname'];

		$sy_result=mysql_query("select * from maoyanpaipian where mtype=1 and mname='{$sy_name}'");
		if (mysql_num_rows($sy_result) >0) {

			$gou.=$sy_name."  明日首映，明日排片".$sy_result['mrowpiecerate']."%<br/>";
		}


	}

	echo $gou;




?>