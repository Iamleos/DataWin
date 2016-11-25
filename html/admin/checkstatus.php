<?php
/**
 * descript:用于检测数据库的状态
 * @date 2016/4/11
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("Content-Type: text/html;charset=utf-8");

    //由于数据量很大，所以设置永不超时。
    set_time_limit(0);

	$result=array();



	//百度媒体指数查询
//	$baidumeiti=mysql_query("select * from baidumeiti",$con);
//	while($row=mysql_fetch_row($baidumeiti))
//	{
//		$baidumeit_arr=array();
//		$today=date("Y-m-d");
//		if($row[2]==$today)
//		{
//			$baidumeit_arr['name']="百度媒体指数";
//			$baidumeit_arr['time']=$today;
//			$baidumeit_arr['status']="入库成功";
//			$baidumeit_arr['systime']="23:00:00";
//			$result['baidumeiti']=$baidumeit_arr;
//			break;
//		}else{
//			$baidumeit_arr['name']="百度媒体指数";
//			$baidumeit_arr['time']=$today;
//			$baidumeit_arr['status']="尚未入库";
//			$baidumeit_arr['systime']="23:00:00";
//			$result['baidumeiti']=$baidumeit_arr;
//			break;
//		}
//
//	}


	//调用检测入库的状态
	//checkStatus("baidumeiti","百度媒体指数","23:00:00",2);
	//checkStatus("baiduzhishu","百度搜索指数","23:00:00",3);
	//checkStatus("qihu","360指数","23:00:00",3);
	//checkStatus("weizhishuzhengti","微指数整体","23:00:00",2);
	//checkStatus("weizhishuyidong","微指数移动","23:00:00",3);
	checkStatus("yirenba","艺人贴吧","19:00:00",4,"filmdaily");
	checkStatus("dianyingba","电影吧","19:00:00",4,"filmdaily");
	checkStatus("doubanbazu","豆瓣八组","20:00:00",2,"filmdaily");
	checkStatus("doubanfen","豆瓣分","21:00:00",3,"filmdaily");
    checkStatus("gewalafen","格瓦拉分","21:00:00",3,"filmdaily");
	checkStatus("mpiaofangjianbao","猫眼票房简报","21:30:00",7,"filmdaily");
	checkStatus("maoyanhuangjinpaipian","猫眼黄金排片","21:30:00",3,"filmdaily");
	checkStatus("maoyanpaipian","猫眼排片","21:30:00",3,"filmdaily");
	checkStatus("maoyanpiaofang","猫眼票房粗报","21:30:00",7,"filmdaily");
	checkStatus("maoyanfen","猫眼评分","21:00:00",4,"filmdaily");
	checkStatus("tianya","天涯社区","20:00:00",2,"yiren");
	checkStatus("tuqu","晋江兔区","20:00:00",2,"yiren");
	checkStatus("enyipiaofang","艺恩票房","21:30:00",5,"filmdaily");
	checkStatus("zzbpiaofang","专资办票房","21:35:00",11,"filmdaily");
	checkStatus("yuleba","娱乐圈吧","19:00:00",2,"filmdaily");
	checkStatus("youyiba","友谊吧","19:00:00",2,"filmdaily");



	/**
	 * descript: 检测入库的状态
	 * @param $tabname:数据库表名 $strname:数据库的中文名  $systime:系统指定的入库时间 $i:表示采集时间是在第几列
	 * @return
	 * @date 2016/4/11
	 */
	function checkStatus($tabname,$strname,$systime,$i,$dbname)
	{
		$host="56a3768226622.sh.cdb.myqcloud.com:4892";
		$name="root";
		$password="ctfoxno1";
		//$dbname="filmdaily";
		global $result;

		//连接数据库
		$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");

		//选择数据库
		mysql_select_db($dbname,$con);

		//设置数据库表格编码


		mysql_query("set names utf8");

	    $sqlresult=mysql_query("select * from {$tabname}",$con);
		$arr=array();
		$today=date("Y-m-d");
		if($row=mysql_fetch_row($sqlresult))
		{

			if(strtotime($row[$i])>=strtotime("{$today} 00:00:00"))
			{
				$arr['name']=$strname;
				$arr['time']=$today;
				$arr['status']="<font color='#6B8E23'>入库成功</font>";
				$arr['systime']=$systime;
				$result["{$tabname}"]=$arr;

			}else{
				$arr['name']=$strname;
				$arr['time']=$today;
				$arr['status']="<font color='#CD0000'>尚未入库</font>";
				$arr['systime']=$systime;
				$result["{$tabname}"]=$arr;

			}

		}else{

				$arr['name']=$strname;
				$arr['time']=$today;
				$arr['status']="<font color='#CD0000'>尚未入库</font>";
				$arr['systime']=$systime;
				$result["{$tabname}"]=$arr;

		}
		mysql_close();
	} // end func
	echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
