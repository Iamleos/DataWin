<?php
/**
 * descript:
 * @date 2016/4/3
 * @author  XuJun
 * @version 2.0
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

	//建立电影总表
	mysql_query("drop table if exists dianyingzh",$con);
	mysql_query("create table dianyingzh as select * from filmname where zzsy='1';");

	//添加猫眼排片数据
	mysql_query("alter table dianyingzh add column mrowpiecerate decimal(5,2);");
	mysql_query("alter table dianyingzh add column msession int(8);");

	//添加猫眼排片黄金排片数据
	mysql_query("alter table dianyingzh add column m_gold_rowpiecerate_today decimal(5,2)");
	mysql_query("alter table dianyingzh add column m_gold_session_today int(8)");
	mysql_query("alter table dianyingzh add column m_gold_rowpiecerate_tomorrow decimal(5,2)");
	mysql_query("alter table dianyingzh add column m_gold_session_tomorrow int(8)");

	//添加猫眼票房数据
	mysql_query("alter table dianyingzh add column msumboxoffice int(8);");
	mysql_query("alter table dianyingzh add column mboxoffice int(8);");
	mysql_query("alter table dianyingzh add column mboxofficerate decimal(5,2);");
	mysql_query("alter table dianyingzh add column mseatrate decimal(5,2);");
	mysql_query("alter table dianyingzh add column mpiaofangshijian date;");

	//添加猫眼明日排片占比数据
	mysql_query("alter table dianyingzh add column tomorrow_rate decimal(4,1);");

	//添加猫眼票房简报数据
	mysql_query("alter table dianyingzh add column jb_msumboxoffice int(8);");
	mysql_query("alter table dainyingzh add column jb_mboxoffice int(8);");
	mysql_query("alter table dianyingzh add column jb_mboxofficerate decimal(5,2);");
	mysql_query("alter table dianyingzh add column jb_mrowpiecerate decimal(5,2);");
	mysql_query("alter table dianyingzh add column jb_mseatrate decimal(5,2);");
	mysql_query("alter table dianyingzh add column jb_mday int(3);");

	//添加艺恩票房数据
	mysql_query("alter table dianyingzh add column eboxofficesum int(6)");
	mysql_query("alter table dianyingzh add column eboxofficedaily int(6)");
	mysql_query("alter table dianyingzh add column eboxofficerate decimal(4,2)");
	mysql_query("alter table dianyingzh add column eday int(3)");

	//添加专资办票房数据
	mysql_query("alter table dianyingzh add column zboxofficesum int(6);");
	mysql_query("alter table dianyingzh add column zboxoffice int(5);");
	mysql_query("alter table dianyingzh add column zsession int(7);");
	mysql_query("alter table dianyingzh add column zperson int(8);");
	mysql_query("alter table dianyingzh add column zofficesale int(6);");
	mysql_query("alter table dianyingzh add column zofficerate decimal(4,2);");
	mysql_query("alter table dianyingzh add column zinternetsale int(6);");
	mysql_query("alter table dianyingzh add column zinternetrate decimal(4,2;");
	mysql_query("alter table dianyingzh add column zrealtimeboxoffice int(6);");
	mysql_query("alter table dianyingzh add column zestimatedboxoffice int(6);");

	//添加电影吧的数据列
	mysql_query("alter table dianyingzh add column dtheme int(10);");
	mysql_query("alter table dianyingzh add column dpaper int(10);");
	mysql_query("alter table dianyingzh add column dlove int(10);");

	//添加豆瓣分，格瓦拉分，格瓦拉关注数
	mysql_query("alter table dianyingzh add column dfen decimal(5,2);");
	mysql_query("alter table dianyingzh add column gfen decimal(5,2);");
	mysql_query("alter table dianyingzh add column glove int(10);");

	//添加猫眼评分
	mysql_query("alter table dianyingzh add column mfen decimal(4,2)");
	mysql_query("alter table dianyingzh add column mfennum int(8)");
	mysql_query("alter table dianyingzh add column mwantnum int(8)");

	//添加微博热议榜
	mysql_query("alter table dianyingzh add column wbscore decimal(4,1)");
	mysql_query("alter table dianyingzh add column wbfennum int(8)");
	mysql_query("alter table dianyingzh add column wbgood_rate decimal(4,2)");
	mysql_query("alter table dianyingzh add column wbbad_rate decimal(4,2)" );

	//添加百度指数，百度媒体指数，百度指数移动占比
	mysql_query("alter table dianyingzh add column bsearch int(10);");
	mysql_query("alter table dianyingzh add column bmtrate int(4);");
	mysql_query("alter table dianyingzh add column bmeiti int(5);");

	//添加360指数
	mysql_query("alter table dianyingzh add column qsearch int(10);");
	mysql_query("alter table dianyingzh add column qmeiti int(8);");

	//添加微指数
	mysql_query("alter table dianyingzh add column wzs int(10);");
	mysql_query("alter table dianyingzh add column wzsmt int(10);");

	//添加微信指数
	mysql_query("alter table dianyingzh add column wpaper int(10)");
	mysql_query("alter table dianyingzh add column wread int(10)");
	mysql_query("alter table dianyingzh add column wlove int(10)");

	//添加微信指数数据
	mysql_query("update dianyingzh set wpaper=(select dianyingweixin.dpaper from dianyingweixin where dianyingzh.mainname=dianyingweixin.dname or dianyingzh.bc1=dianyingweixin.dname or dianyingzh.bc2=dianyingweixin.dname );");
	mysql_query("update dianyingzh set wread=(select dianyingweixin.dread from dianyingweixin where dianyingzh.mainname=dianyingweixin.dname or dianyingzh.bc1=dianyingweixin.dname or dianyingzh.bc2=dianyingweixin.dname );");
	mysql_query("update dianyingzh set wlove=(select dianyingweixin.dlove from dianyingweixin where dianyingzh.mainname=dianyingweixin.dname or dianyingzh.bc1=dianyingweixin.dname or dianyingzh.bc2=dianyingweixin.dname );");

	//添加猫眼票房的数据
	mysql_query("update dianyingzh set mrowpiecerate=(select maoyanpaipian.mrowpiecerate from maoyanpaipian where maoyanpaipian.mtype='0' and (dianyingzh.mainname=maoyanpaipian.mname or dianyingzh.myname=maoyanpaipian.mname ));");
	mysql_query("update dianyingzh set msession=(select maoyanpaipian.msession from maoyanpaipian where (dianyingzh.mainname=maoyanpaipian.mname or dianyingzh.myname=maoyanpaipian.mname ) and maoyanpaipian.mtype='0');");


	//添加艺恩票房数据
	mysql_query("update dianyingzh set eboxofficesum=(select enyipiaofang.eboxofficesum from enyipiaofang where dianyingzh.mainname=enyipiaofang.ename);");
	mysql_query("update dianyingzh set eboxofficedaily=(select enyipiaofang.eboxofficedaily from enyipiaofang where dianyingzh.mainname=enyipiaofang.ename);");
	mysql_query("update dianyingzh set eboxofficerate=(select enyipiaofang.eboxofficerate from enyipiaofang where dianyingzh.mainname=enyipiaofang.ename);");
	mysql_query("update dianyingzh set eday=(select enyipiaofang.eday from enyipiaofang where dianyingzh.mainname=enyipiaofang.ename);");

	//名字相似比较
	$zzb=mysql_query("select * from zzbpiaofang");
	while($row=mysql_fetch_row($zzb))
	{
		$zzbname=trimall(filter_mark($row[0]));
		$film =mysql_query("select * from dianyingzh where zzsy=1");
		while($film_row = mysql_fetch_row($film))
		{
			 $dyzhname=trimall(filter_mark($film_row[0]));
			 similar_text($zzbname,$dyzhname,$percent);
			 if($percent>75)
			{
				echo $zzbname."+".$dyzhname;
				foreach($row as $key => $value){
					if($value==NULL){
						$row[$key] = 0;
					}
				}
				mysql_query("update dianyingzh set zboxofficesum='{$row[1]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zboxoffice='{$row[2]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zsession='{$row[3]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zperson='{$row[4]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zofficesale='{$row[5]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zofficerate='{$row[6]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zinternetsale='{$row[7]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zinternetrate='{$row[8]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zrealtimeboxoffice='{$row[9]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				mysql_query("update dianyingzh set zestimatedboxoffice='{$row[10]}' where dianyingzh.mainname ='{$film_row[0]}' ;");
				break;
			}
		}
		echo "<br/>";

	}

	function filter_mark($text){
		 if(trim($text)=='')return '';
		 $text=preg_replace("/[[:punct:]\s]/",' ',$text);
		 $text=urlencode($text);
		 $text=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88|[a-z])+/",' ',$text);
		 $text=urldecode($text);
		 return trim($text);
	}
	function trimall($str)//删除空格
	{
		$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
		return str_replace($qian,$hou,$str);
	}
	//添加专资办数据
	//mysql_query("update dianyingzh set zboxofficesum=(select zzbpiaofang.zboxofficesum from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zboxoffice=(select zzbpiaofang.zboxoffice from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zsession=(select zzbpiaofang.zsession from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zperson=(select zzbpiaofang.zperson from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zofficesale=(select zzbpiaofang.zofficesale from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zofficerate=(select zzbpiaofang.zofficerate from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zinternetsale=(select zzbpiaofang.zinternetsale from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zinternetrate=(select zzbpiaofang.zinternetrate from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zrealtimeboxoffice=(select zzbpiaofang.zrealtimeboxoffice from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");
	//mysql_query("update dianyingzh set zestimatedboxoffice=(select zzbpiaofang.zestimatedboxoffice from zzbpiaofang where zzbpiaofang.zname=dianyingzh.mainname or zzbpiaofang.zname=dianyingzh.bc1 or zzbpiaofang.zname=dianyingzh.bc2);");

	//添加猫眼票房黄金时间数据
	mysql_query("update dianyingzh set m_gold_rowpiecerate_today=(select maoyanhuangjinpaipian.m_gold_rowpiecerate from maoyanhuangjinpaipian where maoyanhuangjinpaipian.m_gold_type=0 and (dianyingzh.mainname=maoyanhuangjinpaipian.m_gold_name or dianyingzh.myname=maoyanhuangjinpaipian.m_gold_name));");

	mysql_query("update dianyingzh set m_gold_session_today=(select maoyanhuangjinpaipian.m_gold_session from maoyanhuangjinpaipian where maoyanhuangjinpaipian.m_gold_type=0 and (dianyingzh.mainname=maoyanhuangjinpaipian.m_gold_name or dianyingzh.myname=maoyanhuangjinpaipian.m_gold_name));");

	mysql_query("update dianyingzh set m_gold_session_tomorrow=(select maoyanhuangjinpaipian.m_gold_session from maoyanhuangjinpaipian where maoyanhuangjinpaipian.m_gold_type=1 and (dianyingzh.mainname=maoyanhuangjinpaipian.m_gold_name or dianyingzh.myname=maoyanhuangjinpaipian.m_gold_name));");

	mysql_query("update dianyingzh set m_gold_rowpiecerate_tomorrow=(select maoyanhuangjinpaipian.m_gold_rowpiecerate from maoyanhuangjinpaipian where maoyanhuangjinpaipian.m_gold_type=1 and (dianyingzh.mainname=maoyanhuangjinpaipian.m_gold_name or dianyingzh.myname=maoyanhuangjinpaipian.m_gold_name));");


	//添加猫眼票房数据
	mysql_query("update dianyingzh set msumboxoffice=(select maoyanpiaofang.msumboxoffice from maoyanpiaofang where (dianyingzh.mainname=maoyanpiaofang.mname or dianyingzh.myname=maoyanpiaofang.mname));");
	mysql_query("update dianyingzh set mboxoffice=(select maoyanpiaofang.mboxoffice from maoyanpiaofang where (dianyingzh.mainname=maoyanpiaofang.mname or dianyingzh.myname=maoyanpiaofang.mname));");
	mysql_query("update dianyingzh set mboxofficerate=(select maoyanpiaofang.mboxofficerate from maoyanpiaofang where (dianyingzh.mainname=maoyanpiaofang.mname or dianyingzh.myname=maoyanpiaofang.mname));");
	mysql_query("update dianyingzh set mseatrate=(select maoyanpiaofang.mseatrate from maoyanpiaofang where (dianyingzh.mainname=maoyanpiaofang.mname or dianyingzh.myname=maoyanpiaofang.mname));");
	mysql_query("update dianyingzh set mpiaofangshijian=(select maoyanpiaofang.mpiaofangshijian from maoyanpiaofang where (dianyingzh.mainname=maoyanpiaofang.mname or dianyingzh.myname=maoyanpiaofang.mname));");

	//添加明日排片占比数据
	mysql_query("update dianyingzh set tomorrow_rate=(select maoyanpaipian.mrowpiecerate from maoyanpaipian where maoyanpaipian.mtype='1' and (dianyingzh.mainname=maoyanpaipian.mname or dianyingzh.myname=maoyanpaipian.mname ));");


	//添加猫眼票房简报数据
	mysql_query("update dianyingzh set jb_msumboxoffice=(select mpiaofangjianbao.msumboxoffice from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");
	mysql_query("update dianyingzh set jb_mboxoffice=(select mpiaofangjianbao.mboxoffice from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");
	mysql_query("update dianyingzh set jb_mboxofficerate=(select mpiaofangjianbao.mboxofficerate from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");
	mysql_query("update dianyingzh set jb_mrowpiecerate=(select mpiaofangjianbao.mrowpiecerate from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");
	mysql_query("update dianyingzh set jb_mseatrate=(select mpiaofangjianbao.mseatrate from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");
	mysql_query("update dianyingzh set jb_mday=(select mpiaofangjianbao.mday from mpiaofangjianbao where dianyingzh.mainname=mpiaofangjianbao.mname or dianyingzh.myname=mpiaofangjianbao.mname);");

	//添加电影吧的数据
	mysql_query("update dianyingzh set dtheme=(select dianyingba.dtheme from dianyingba where dianyingzh.mainname=dianyingba.dname or dianyingzh.bc1=dianyingba.dname or dianyingzh.bc2=dianyingba.dname );");
	mysql_query("update dianyingzh set dpaper=(select dianyingba.dpaper from dianyingba where dianyingzh.mainname=dianyingba.dname or dianyingzh.bc1=dianyingba.dname or dianyingzh.bc2=dianyingba.dname );");
	mysql_query("update dianyingzh set dlove=(select dianyingba.dlove from dianyingba where dianyingzh.mainname=dianyingba.dname or dianyingzh.bc1=dianyingba.dname or dianyingzh.bc2=dianyingba.dname );");

	//添加豆瓣分、格瓦拉分数据
	mysql_query("update dianyingzh set dfen=(select doubanfen.dfen from doubanfen where dianyingzh.mainname=doubanfen.dname or dianyingzh.dbname=doubanfen.dname or dianyingzh.bc2=doubanfen.dname );");
	mysql_query("update dianyingzh set gfen=(select gewalafen.gfen from gewalafen where dianyingzh.mainname=gewalafen.gname or dianyingzh.bc1=gewalafen.gname or dianyingzh.bc2=gewalafen.gname );");
	mysql_query("update dianyingzh set glove=(select gewalafen.glove from gewalafen where dianyingzh.mainname=gewalafen.gname or dianyingzh.bc1=gewalafen.gname or dianyingzh.bc2=gewalafen.gname );");

	//添加猫眼评分的数据
	mysql_query("update dianyingzh set mfen=(select maoyanfen.mfen from maoyanfen where dianyingzh.mainname=maoyanfen.mname or dianyingzh.myname=maoyanfen.mname or dianyingzh.bc2=maoyanfen.mname)");
	mysql_query("update dianyingzh set mfennum=(select maoyanfen.mfennum from maoyanfen where dianyingzh.mainname=maoyanfen.mname or dianyingzh.myname=maoyanfen.mname or dianyingzh.bc2=maoyanfen.mname)");
	mysql_query("update dianyingzh set mwantnum=(select maoyanfen.mwantnum from maoyanfen where dianyingzh.mainname=maoyanfen.mname or dianyingzh.myname=maoyanfen.mname or dianyingzh.bc2=maoyanfen.mname)");

	//添加微博电影热议数据
	mysql_query("update dianyingzh set wbscore=(select weiboreyi.wbscore from weiboreyi where weiboreyi.wbname=dianyingzh.mainname or weiboreyi.wbname=dianyingzh.bc1 or weiboreyi.wbname=dianyingzh.bc2 )");
	mysql_query("update dianyingzh set wbfennum=(select weiboreyi.wbfennum from weiboreyi where weiboreyi.wbname=dianyingzh.mainname or weiboreyi.wbname=dianyingzh.bc1 or weiboreyi.wbname=dianyingzh.bc2 )");
	mysql_query("update dianyingzh set wbgood_rate=(select weiboreyi.wbgood_rate from weiboreyi where weiboreyi.wbname=dianyingzh.mainname or weiboreyi.wbname=dianyingzh.bc1 or weiboreyi.wbname=dianyingzh.bc2 )");
	mysql_query("update dianyingzh set wbbad_rate=(select weiboreyi.wbbad_rate from weiboreyi where weiboreyi.wbname=dianyingzh.mainname or weiboreyi.wbname=dianyingzh.bc1 or weiboreyi.wbname=dianyingzh.bc2 )");


	//添加百度指数数据
	mysql_query("update dianyingzh set bsearch=(select baiduzhishu.bsearch from baiduzhishu where dianyingzh.mainname=baiduzhishu.bname or dianyingzh.bc1=baiduzhishu.bname or dianyingzh.bc2=baiduzhishu.bname );");
	mysql_query("update dianyingzh set bmtrate=(select baiduzhishu.bmtrate from baiduzhishu where dianyingzh.mainname=baiduzhishu.bname or dianyingzh.bc1=baiduzhishu.bname or dianyingzh.bc2=baiduzhishu.bname );");
	mysql_query("update dianyingzh set bmeiti=(select baidumeiti.bmeiti from baidumeiti where dianyingzh.mainname=baidumeiti.bname or dianyingzh.bc1=baidumeiti.bname or dianyingzh.bc2=baidumeiti.bname );");

	//添加360数据
	mysql_query("update dianyingzh set qsearch=(select qihu.qsearch from qihu where dianyingzh.mainname=qihu.qname or dianyingzh.bc1=qihu.qname or dianyingzh.bc2=qihu.qname );");
	mysql_query("update dianyingzh set qmeiti=(select qihu.qmeiti from qihu where dianyingzh.mainname=qihu.qname or dianyingzh.bc1=qihu.qname or dianyingzh.bc2=qihu.qname );");

	//添加微指数
	mysql_query("update dianyingzh set wzs=(select weizhishuzhengti.wzs from weizhishuzhengti where dianyingzh.mainname=weizhishuzhengti.wname or dianyingzh.bc1=weizhishuzhengti.wname or dianyingzh.bc2=weizhishuzhengti.wname limit 1);");
	mysql_query("update dianyingzh set wzsmt=(select weizhishuyidong.wzsmt from weizhishuyidong where dianyingzh.mainname=weizhishuyidong.wname or dianyingzh.bc1=weizhishuyidong.wname or dianyingzh.bc2=weizhishuyidong.wname  limit 1);");
	mysql_query("update dianyingzh set wzsmt=(select wzsmt/(wzs+0.1) limit 1);");


	//添加娱乐圈、豆瓣八组、友谊吧、天涯、兔区的列
	mysql_query("alter table dianyingzh add column yulequannum int(10);");
	mysql_query("alter table dianyingzh add column doubanbazunum int(10);");
	mysql_query("alter table dianyingzh add column youyinum int(10);");
	mysql_query("alter table dianyingzh add column tianyanum int(10);");
	mysql_query("alter table dianyingzh add column tuqunum int(10);");
	mysql_query("alter table dianyingzh add column acquitime date;");

	$today=date("Y-m-d");

	mysql_query("update dianyingzh set acquitime='{$today}'");

	//用于提取五大娱乐论坛的提及数

	$yulequanstr="";
	$doubanbazustr="";
	$youyibastr="";
	$tianyastr="";
	$tuqustr="";

	//提取娱乐圈的提及数
	$resyule=mysql_query("select ytitle from yuleba;",$con);
	while($row=mysql_fetch_row($resyule))
	{
		$yulequanstr=$row[0]."".$yulequanstr;
	}

	echo $yulequanstr."<br>";

	$yulename=mysql_query("select * from filmname");

	while($row=mysql_fetch_row($yulename)){

		$yulenum=0;
		if($row[0]!=""){

			$yulenum+=substr_count($yulequanstr,$row[0]);

		}
		if($row[5]!=""){

			$yulenum+=substr_count($yulequanstr,$row[5]);

		}if($row[6]!=""){

			$yulenum+=substr_count($yulequanstr,$row[6]);
		}

    echo $row[0]."-".$yulenum."<br/>";
    echo "update dianyingzh set yulequannum={$yulenum} where dianyingzh.mainname='{$row[0]}';";
    mysql_query("update dianyingzh set yulequannum={$yulenum} where dianyingzh.mainname='{$row[0]}';");

	}

	//提取豆瓣八组的提及数

	$resdouban=mysql_query("select dtitle from doubanbazu;",$con);
	while($row=mysql_fetch_row($resdouban))
	{
		$doubanbazustr=$row[0]."".$doubanbazustr;
	}

	echo $doubanbazustr."<br>";

	$doubanname=mysql_query("select * from filmname");
	while($row=mysql_fetch_row($doubanname)){
		$doubannum=0;

		if($row[0]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[0]);
		}
		if($row[5]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[5]);

		}if($row[6]!=""){

			$doubannum+=substr_count($doubanbazustr,$row[6]);
		}

    echo $row[0]."-".$doubannum."<br/>";
    echo "update dianyingzh set doubanbazunum={$doubannum} where dianyingzh.mainname='{$row[0]}';";
    mysql_query("update dianyingzh set doubanbazunum={$doubannum} where dianyingzh.mainname='{$row[0]}';");

	}

	//提取友谊吧提及数
	$resyouyi=mysql_query("select ytitle from youyiba;",$con);
	while($row=mysql_fetch_row($resyouyi))
	{
		$youyibastr=$row[0]."".$youyibastr;
	}

	echo $youyibastr."<br>";

	$youyiname=mysql_query("select * from filmname");
	while($row=mysql_fetch_row($youyiname)){

		$youyinum=0;

		if($row[0]!=""){

			$youyinum+=substr_count($youyibastr,$row[0]);
		}
		if($row[5]!=""){

			$youyinum+=substr_count($youyibastr,$row[5]);

		}if($row[6]!=""){

			$youyinum+=substr_count($youyibastr,$row[6]);
		}
    echo $row[0]."-".$youyinum."<br/>";
    echo "update dianyingzh set youyinum={$youyinum} where dianyingzh.mainname='{$row[0]}';";
    mysql_query("update dianyingzh set youyinum={$youyinum} where dianyingzh.mainname='{$row[0]}';");

	}

	//提取友谊吧数据
	$restianya=mysql_query("select ttitle from tianya;",$con);
	while($row=mysql_fetch_row($restianya))
	{
		$tianyastr=$row[0]."".$tianyastr;
	}
	echo $tianyastr."<br>";

	$tianyaname=mysql_query("select * from filmname");
	while($row=mysql_fetch_row($tianyaname)){

		$tianyanum=0;

		if($row[0]!=""){

			$tianyanum+=substr_count($tianyastr,$row[0]);
		}
		if($row[5]!=""){

        $tianyanum+=substr_count($tianyastr,$row[5]);

		}if($row[6]!=""){

        $tianyanum+=substr_count($tianyastr,$row[6]);
    }
    echo $row[0]."-".$tianyanum."<br/>";
    echo "update dianyingzh set tianyanum={$tianyanum} where dianyingzh.mainname='{$row[0]}';";
    mysql_query("update dianyingzh set tianyanum={$tianyanum} where dianyingzh.mainname='{$row[0]}';");

	}

	//提取晋江兔区的提及数
	$restuqu=mysql_query("select jtitle from jinjiangtuqu;",$con);
	while($row=mysql_fetch_row($restuqu))
	{
		$tuqustr=$row[0]."".$tuqustr;
	}

	echo $tuqustr."<br>";

	$tuquname=mysql_query("select * from filmname");
	while($row=mysql_fetch_row($tuquname)){
		$tuqunum=0;
		if($row[0]!=""){

			$tuqunum+=substr_count($tuqustr,$row[0]);
		}
		if($row[5]!=""){

			$tuqunum+=substr_count($tuqustr,$row[5]);

		}if($row[6]!=""){

			$tuqunum+=substr_count($tianyastr,$row[6]);
		}
    echo $row[0]."-".$tuqunum."<br/>";
    echo "update dianyingzh set tuqunum={$tuqunum} where dianyingzh.mainname='{$row[0]}';";
    mysql_query("update dianyingzh set tuqunum={$tuqunum} where dianyingzh.mainname='{$row[0]}';");

	}

	//建立电影综合记录的历史数据
mysql_query("CREATE TABLE  IF NOT EXISTS `dyhistory` (
  `mainname` varchar(45) NOT NULL,
  `myname` varchar(45) DEFAULT NULL,
  `gwlname` varchar(45) DEFAULT NULL,
  `dbname` varchar(45) DEFAULT NULL,
  `filmnamecol` int(11) NOT NULL,
  `bc1` varchar(45) DEFAULT NULL,
  `bc2` varchar(45) NOT NULL DEFAULT '',
  `zzsy` bit(1) NOT NULL DEFAULT b'0' COMMENT '正在上映',
  `mrowpiecerate` decimal(5,2) DEFAULT NULL,
  `msession` int(8) DEFAULT NULL,
  `m_gold_rowpiecerate_today` decimal(5,2) DEFAULT NULL,
  `m_gold_session_today` int(8) DEFAULT NULL,
  `m_gold_rowpiecerate_tomorrow` decimal(5,2) DEFAULT NULL,
  `m_gold_session_tomorrow` int(8) DEFAULT NULL,
  `msumboxoffice` int(8) DEFAULT NULL,
  `mboxoffice` int(8) DEFAULT NULL,
  `mboxofficerate` decimal(5,2) DEFAULT NULL,
  `mseatrate` decimal(5,2) DEFAULT NULL,
  `mpiaofangshijian` date DEFAULT NULL,
  `tomorrow_rate` decimal(4,1) DEFAULT NULL,
  `jb_msumboxoffice` int(8) DEFAULT NULL,
  `jb_mboxofficerate` decimal(5,2) DEFAULT NULL,
  `jb_mrowpiecerate` decimal(5,2) DEFAULT NULL,
  `jb_mseatrate` decimal(5,2) DEFAULT NULL,
  `jb_mday` int(3) DEFAULT NULL,
  `eboxofficesum` int(6) DEFAULT NULL,
  `eboxofficedaily` int(6) DEFAULT NULL,
  `eboxofficerate` decimal(4,2) DEFAULT NULL,
  `eday` int(3) DEFAULT NULL,
  `zboxofficesum` int(6) DEFAULT NULL,
  `zboxoffice` int(5) DEFAULT NULL,
  `zsession` int(7) DEFAULT NULL,
  `zperson` int(8) DEFAULT NULL,
  `zofficesale` int(6) DEFAULT NULL,
  `zofficerate` decimal(4,2) DEFAULT NULL,
  `zinternetsale` int(6) DEFAULT NULL,
  `zrealtimeboxoffice` int(6) DEFAULT NULL,
  `zestimatedboxoffice` int(6) DEFAULT NULL,
  `dtheme` int(10) DEFAULT NULL,
  `dpaper` int(10) DEFAULT NULL,
  `dlove` int(10) DEFAULT NULL,
  `dfen` decimal(5,2) DEFAULT NULL,
  `gfen` decimal(5,2) DEFAULT NULL,
  `glove` int(10) DEFAULT NULL,
  `mfen` decimal(4,2) DEFAULT NULL,
  `mfennum` int(8) DEFAULT NULL,
  `mwantnum` int(8) DEFAULT NULL,
  `wbscore` decimal(4,1) DEFAULT NULL,
  `wbfennum` int(8) DEFAULT NULL,
  `wbgood_rate` decimal(4,2) DEFAULT NULL,
  `wbbad_rate` decimal(4,2) DEFAULT NULL,
  `bsearch` int(10) DEFAULT NULL,
  `bmtrate` int(4) DEFAULT NULL,
  `bmeiti` int(5) DEFAULT NULL,
  `qsearch` int(10) DEFAULT NULL,
  `qmeiti` int(8) DEFAULT NULL,
  `wzs` int(10) DEFAULT NULL,
  `wzsmt` int(10) DEFAULT NULL,
  `wpaper` int(10) DEFAULT NULL,
  `wread` int(10) DEFAULT NULL,
  `wlove` int(10) DEFAULT NULL,
  `yulequannum` int(10) DEFAULT NULL,
  `doubanbazunum` int(10) DEFAULT NULL,
  `youyinum` int(10) DEFAULT NULL,
  `tianyanum` int(10) DEFAULT NULL,
  `tuqunum` int(10) DEFAULT NULL,
  `acquitime` date DEFAULT NULL,
  primary key(mainname,acquitime)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

mysql_query("insert IGNORE into dyhistory select * from dianyingzh ");


?>
