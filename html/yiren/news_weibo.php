<?php
/**
 * descript:采集360指数中艺人的微博和新闻
 * @date 2016/6/7
 * @author  XuJun
 * @version 1.0
 * @package
 */

	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	//设置代理，解决用户无法访问豆瓣八组的问题。
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	$resultname=mysql_query("select name from acnametop50",$con);
    while($row=mysql_fetch_row($resultname))
	{
		$name=$row[0];
		//$name ='杨幂';
		//新闻地址 http://index.so.com/index.php?a=relNewsJson&q=%E6%9D%A8%E5%B9%82
		//微博地址 http://index.so.com/index.php?a=relWeiboJson&q=%E6%9D%A8%E5%B9%82
		$newsurl = "http://index.so.com/index.php?a=relNewsJson&q={$name}";
		$weibourl = "http://index.so.com/index.php?a=relWeiboJson&q={$name}";

		$news = file_get_contents($newsurl);
		$newsjson = json_decode($news,true);

		$weibo = file_get_contents($weibourl);
		$weibojson = json_decode($weibo,true);

		$totalnewstitle="";
		$totalweibotitle="";

		$newslen=count($newsjson['data']['list']);
		for($i=0;$i<$newslen;$i++)
		{
			$title = strip_tags($newsjson['data']['list'][$i]['title']);
			$totalnewstitle=$totalnewstitle."__".$title;
			//echo $title."<br/>";
		}

		//var_dump($weibojson['data']['list'][1]);
		$weibolen = count($weibojson['data']['list']);
		for($i=0;$i<$weibolen;$i++)
		{
			$title = strip_tags($weibojson['data']['list'][$i]['text']);
			$forwardsum = strip_tags($weibojson['data']['list'][$i]['forwardsnum']);
			$commentsum = strip_tags($weibojson['data']['list'][$i]['commentsnum']);
			$totalweibotitle = $totalweibotitle."__".$title."-".$forwardsum."-".$commentsum;
			//echo $title." ".$forwardsum." ".$commentsum."<br/>";

			//可以用于扩展计算每条微博的转发数和评论数
		}
		$today=date("Y-m-d");
		if(mysql_num_rows(mysql_query("select * from hyhittitle where yname='{$name}' and yacquitime = {$today}"))<1)
		{

			mysql_query("insert into hyhittitle (yname,news,weibo,yacquitime) values ('{$name}','{$totalnewstitle}','{$totalweibotitle}','{$today}')");
			echo "insert into hyhittitle (yname,news,weibo,yacquitime) values ('{$name}','{$totalnewstitle}','{$totalweibotitle}','{$today}')";
		}else {
		    mysql_query("update hyhittitle set news = '{$totalnewstitle}' where yname='{$name}'");
			mysql_query("update hyhittitle set weibo = '{$totalweibotitle}' where yname='{$name}'");
			echo "update hyhittitle set weibo = '{$totalweibotitle}' where yname='{$name}'";
		}
	}

    mysql_close($con);

?>