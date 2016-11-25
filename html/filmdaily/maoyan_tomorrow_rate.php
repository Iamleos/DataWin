<?php
/**
 * descript:获取猫眼明日排片
 * @date 2016/6/23
 * @author  XuJun
 * @version 1.0
 * @package
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

    mysql_query("drop table if exists maoyan_tomorrow;",$con);
    mysql_query("create table maoyan_tomorrow(mname varchar(30),mrate decimal(4,1),macquitime date,primary key(mname,macquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 21:00:00";
    $end="{$day} 22:00:00";

    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=278b18b9-e695-4ea7-b569-48d46f096801&from={$start}&to={$end}"

    );
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $mnames = $Root->getElementsByTagName( "name" );
            $mname = $mnames->item(0)->nodeValue;
            $tomorrow_rates = $Root->getElementsByTagName( "tomorrow_rate" );
            $tomorrow_rate = $tomorrow_rates->item(0)->nodeValue;


           $sqlinsert="insert into maoyan_tomorrow(mname,mrate,macquitime) values('{$mname}','{$tomorrow_rate}','{$day}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);

?>