<?php
/**
 * descript:抓取网页新闻数据
 * @date 2016/4/19
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');

	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
    mysql_query("create table if not exists xinwen(title varchar(150),who varchar(30),time datetime,redu int (3),imgurl varchar(150), link varchar(150),primary key(title));",$con);




	for($i=1;$i<=50;$i++)
	{
		$url="http://news.so.com/ns?q=%E8%BD%A6%E4%BD%8D%E5%87%BA%E7%A7%9F&pn={$i}&tn=news&rank=rank&j=0";
		$html=file_get_contents($url);

		//echo $html;

		$arr1=array();
		$arr2=array();
		$arr3=array();
		$arr4=array();
		$arr5=array();
		$arr6=array();

		$preg1='/<li class="res-list hasimg"[\w\W]*?>([\w\W]*?)<\/li>/';
		$preg2='/<a class="news_title" href="([\w\W]*?)"[\w\W]*?>([\w\W]*?)<\/a>/';
		$preg3='/<span class="sitename"[\w\W]*?>([\w\W]*?)<\/span>/';
		$preg4='/<span class="posttime" title="([\w\W]*?)"[\w\W]*?>[\w\W]*?<\/span>/';
		$preg5='/<a target="_blank" href="[\w\W]*?" class="same"[\w\W]*?>([\w\W]*?)<\/a>/';
		$preg6='/<img src="([\w\W]*?)"[\w\W]*?>/';

		preg_match_all($preg1, $html, $arr1);
		for($j=0;$j<count($arr1[0]);$j++)
		{

			preg_match_all($preg2, $arr1[0][$j], $arr2);
			preg_match_all($preg3, $arr1[0][$j], $arr3);
			preg_match_all($preg4, $arr1[0][$j], $arr4);
			preg_match_all($preg5, $arr1[0][$j], $arr5);
			preg_match_all($preg6, $arr1[0][$j], $arr6);

			//var_dump($arr5[1][0]);     //相关文章

			//var_dump($arr4[1][0]);	   //发布时间
			//var_dump($arr3[1][0]);	   //发布网站
			//var_dump($arr1);   //整个条目
			//var_dump(trim($arr6[1][0]," \t\r\n"));
			//var_dump($arr2[1][0]);    //文章url

			$title=str_replace(array("/","<",">","em"),"",$arr2[2][0]); //标题
			$link=$arr2[1][0];                //文章链接
			$who=$arr3[1][0];                  //发布网站
			$time=$arr4[1][0];  //发布时间
			$redu=0;
			if(@preg_match('/\d+/',$arr5[1][0],$arr)){
			  $redu=$arr[0];                 //热度
			}
			$imgurl=$arr6[1][0];           //照片url
			//echo $title." ".$link." ".$who." ".$time." ".$redu." ".$imgurl."<br/>";
			//echo $redu."<br/>";
			$sqlstr="insert into   xinwen(title,who,time,redu,imgurl,link)values('{$title}','{$who}','{$time}','{$redu}','{$imgurl}','{$link}');";
			echo $sqlstr."<br/>";
            $result=mysql_query($sqlstr,$con);

			if($result){
				echo "成功";
			}else{
				echo "失败";
			}






		}




	}

	mysql_close();
?>
