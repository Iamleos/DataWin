<?php
/**
 *@调用八爪鱼猫眼排片api
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
    $sql="drop table if exists maoyanpaipian;create table maoyanpaipian(mname varchar(30),mrowpiecerate decimal(4,2),msession int(5),macquitime date,mtype varchar(16) ,primary key(mname,mtype,macquitime))";
    mysql_query("drop table if exists maoyanpaipian;",$con);
    mysql_query("create table maoyanpaipian(mname varchar(30),mrowpiecerate decimal(4,2),msession int(5),macquitime date,mtype varchar(16) ,primary key(mname,mtype,macquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 21:00:00";
    $end="{$day} 22:00:00";
    $url="http://dataapi.skieer.com/SkieerDataAPI/GetData?key=de73db41-a9a3-4ee4-b32a-489b2db363d1&from={$start}&to={$end}";

   // $newdate=date('Y-m-d H:i:s',time()-3300);
    //$enddate=date('Y-m-d H:i:s',time()+300);
    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=de73db41-a9a3-4ee4-b32a-489b2db363d1&from={$start}&to={$end}"
        //'http://dataapi.skieer.com/SkieerDataAPI/GetData?key={key}&from='.$newdate.'&to='.$enddate,
    );
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $names = $Root->getElementsByTagName( "name" );
            $name = $names->item(0)->nodeValue;
            $rates = $Root->getElementsByTagName( "rate" );
            $rate = $rates->item(0)->nodeValue;
            $timess = $Root->getElementsByTagName( "times" );
            $times = $timess->item(0)->nodeValue;
            $acquitimes = $Root->getElementsByTagName( "acquitime" );
            $acquitime = $acquitimes->item(0)->nodeValue;
            $types = $Root->getElementsByTagName( "type" );
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
           $sqlinsert="insert into maoyanpaipian(mname,mrowpiecerate,msession,macquitime,mtype) values('{$name}','{$rate}','{$times}','{$acquitime}','{$type}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
