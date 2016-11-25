<?php
/**
 *@调用八爪鱼百度电影舆情api
 *@author: xujun
 *@date 2016/3/30
 */
 #! /usr/bin/php
	//设置浏览器编码和数据库的基本信息
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="website";

	//连接数据库
    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	//由于昨天的数据不需要，所以先删除数据再建立表格，如果是先清空数据也可以

    mysql_query("create table if not exists  chendu(title varchar(200),time date,paperurl varchar(300),imgurl varchar(300),primary key(title,time));",$con);

	//记录当天的时间，根据八爪鱼的采集时间，确定收集数据时间
    $day=date("Y-m-d");
    $start="{$day} 15:30:00";
    $end="{$day} 16:30:00";

    //调用八爪鱼的api
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=4a9593be-2071-4f2a-b45b-9fd4da09bcab&from={$start}&to={$end}"
    );

    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $titles = $Root->getElementsByTagName( "title" );
            $title= $titles->item(0)->nodeValue;
            $times = $Root->getElementsByTagName( "time" );
            $time = $times->item(0)->nodeValue;
            $imgurls=$Root->getElementsByTagName( "imgurl" );
            $imgurl=$imgurls->item(0)->nodeValue;
            $paperurls=$Root->getElementsByTagName( "paperurl" );
            $paperurl=$paperurls->item(0)->nodeValue;


			//将数据插入到数据表
            $sqlinsert="insert into chendu(title,time,paperurl,imgurl) values('{$title}','{$time}','{$paperurl}','{$imgurl}')";
            echo $sqlinsert;
            mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
