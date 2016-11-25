<?php
/**
 *@调用八爪鱼猫眼黄金排片api
 *@author: xujun
 *@notice 具体解释看dianyingba.php
 */
 #! /usr/bin/php
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
    mysql_query("drop table if exists maoyanhuangjinpaipian;",$con);
    mysql_query("create table maoyanhuangjinpaipian(m_gold_name varchar(30),m_gold_rowpiecerate decimal(4,2),m_gold_session int(5),m_gold_acquitime date,m_gold_type int(2) ,primary key(m_gold_name,m_gold_type,m_gold_acquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 21:00:00";
    $end="{$day} 22:00:00";

    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=456fe6f3-9fce-408f-8a06-f7afc6855469&from={$start}&to={$end}"

        //'http://dataapi.skieer.com/SkieerDataAPI/GetData?key={key}&from='.$newdate.'&to='.$enddate,
    );
	echo "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=456fe6f3-9fce-408f-8a06-f7afc6855469&from={$start}&to={$end}";
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $names = $Root->getElementsByTagName( "m_gold_name" );
            $name = $names->item(0)->nodeValue;
            $rates = $Root->getElementsByTagName( "m_gold_rate" );
            $rate = $rates->item(0)->nodeValue;
            $timess = $Root->getElementsByTagName( "m_gold_times" );
            $times = $timess->item(0)->nodeValue;
            $acquitimes = $Root->getElementsByTagName( "m_gold_acquitime" );
            $acquitime = $acquitimes->item(0)->nodeValue;
            $types = $Root->getElementsByTagName( "m_gold_type" );
            $type = $types->item(0)->nodeValue;
            //$contents = $Root->getElementsByTagName( "content" );
            //$content = $contents->item(0)->nodeValue;
            $data=array(
                'name'=>$name,//获取的title
                'rate'=>$rate,
                'times'=>$times,
                'acquitime'=>$acquitime,
                'type'=>$type
                //'content'=>$content,//获取的content
                // 'time'=>time(),//当前时间戳
            );
            // 打印数据
           //print_r($name);
           $sqlinsert="insert into maoyanhuangjinpaipian(m_gold_name,m_gold_rowpiecerate,m_gold_session,m_gold_acquitime,m_gold_type) values('{$name}','{$rate}','{$times}','{$acquitime}','{$type}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
