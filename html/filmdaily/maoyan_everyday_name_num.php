<?php
/**
 * descript:获取猫眼电影名对应的url已经电影编号
 * @date	2016/7/21
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户的问题。
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";

	//连接数据库
    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	//创建数据库
	mysql_query("create table if not exists maoyan_everyday_name_num(mname varchar(50),mnum int(8),primary key(mname,mnum));");

	//for($k = strtotime('2016-07-29'); $k < strtotime('2016-08-23'); $k += 86400)
	//{
		//$today=date("Y-m-d",$k);
		$today=date("Y-m-d");
		$url="http://piaofang.maoyan.com/?date={$today}";
		$html=file_get_contents($url);
		//echo $html;

		$arr=array();
		$preg='/<ul class="canTouch" data-com="hrefTo,href:([\w\W]*?)">/';
		preg_match_all($preg,$html,$arr);
		//var_dump($arr[1]);

		$arr1=array();
		$preg1='/<li class=\'c1\'>[\w\W]*?<b>([\w\W]*?)<\/b>[\w\W]*?<\/li>/';
		preg_match_all($preg1,$html,$arr1);
		//var_dump($arr1[1]);
		$size=count($arr1[1]);
		for($i=0;$i<$size;$i++)
		{
			$name = $arr1[1][$i];
			$num = findNum($arr[1][$i]);
			if($name!="")
			{
				$strsql="insert into maoyan_everyday_name_num(mname,mnum)values('{$name}','{$num}')";
				echo $strsql."<br/>";
				mysql_query($strsql,$con);
			}


		}
	//}

	function findNum($str=''){
		$str=trim($str);
		if(empty($str)){return '';}
		$reg='/(\d+)/is';//匹配数字的正则表达式
		preg_match_all($reg,$str,$result);
		if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
			return $result[1][0];
		}
		return '';
	}

?>