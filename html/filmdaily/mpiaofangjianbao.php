<?php
/**
 *@调用八爪鱼猫眼排片当日简报api
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

    mysql_query("drop table if exists mpiaofangjianbao;",$con);
    mysql_query("create table mpiaofangjianbao(mname varchar(30),msumboxoffice int(6),mboxoffice int(5),mboxofficerate decimal(4,2),mrowpiecerate decimal(4,2),mseatrate decimal(4,2),mday int(3),macquitime date,primary key(mname,macquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 21:00:00";
    $end="{$day} 22:00:00";

    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=d996bbf3-af09-41d7-b2c3-37f361379bc9&from={$start}&to={$end}"

    );
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $mnames = $Root->getElementsByTagName( "mname" );
            $mname = $mnames->item(0)->nodeValue;
            $msumboxoffices = $Root->getElementsByTagName( "mboxofficesum" );
            $msumboxoffice = $msumboxoffices->item(0)->nodeValue;
            $mboxoffices = $Root->getElementsByTagName( "mboxofficedaily" );
            $mboxoffice = $mboxoffices->item(0)->nodeValue;
            $mboxofficerates = $Root->getElementsByTagName( "mboxofficerate" );
            $mboxofficerate = $mboxofficerates->item(0)->nodeValue;
            $mrowpiecerates = $Root->getElementsByTagName( "mrowpiecerate" );
            $mrowpiecerate = $mrowpiecerates->item(0)->nodeValue;
            $mseatrates = $Root->getElementsByTagName( "mseatrate" );
            $mseatrate = $mseatrates->item(0)->nodeValue;
            $mdays = $Root->getElementsByTagName( "mday" );
            $mday = $mdays->item(0)->nodeValue;
            $macquitimes = $Root->getElementsByTagName( "macquitime" );
            $macquitime = $macquitimes->item(0)->nodeValue;


           $sqlinsert="insert into mpiaofangjianbao(mname,msumboxoffice,mboxoffice,mboxofficerate,mrowpiecerate,mseatrate,mday,macquitime) values('{$mname}','{$msumboxoffice}','{$mboxoffice}','{$mboxofficerate}','{$mrowpiecerate}','{$mseatrate}','{$mday}','{$macquitime}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
