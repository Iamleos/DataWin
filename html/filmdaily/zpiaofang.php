<?php
/**
 *@调用八爪鱼专资办票房api
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

    mysql_query("drop table if exists zpiaofang;",$con);
    mysql_query("create table zpiaofang(zname varchar(30),zboxofficesum int(6),zboxoffice int(5),zsession int(7),zperson int(8),zofficesale int(6),zofficerate decimal(4,2),zinternetsale int(6),zinternetrate decimal(4,2),zrealtimeboxoffice int(8),zestimatedboxoffice int(8),zacquitime date,primary key(zname,zacquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 22:00:00";
    $end="{$day} 23:00:00";

    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=2ae221d8-ea46-4385-85b2-42aa6e65a0a2&from={$start}&to={$end}"

    );
    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $znames = $Root->getElementsByTagName( "zname" );
            $zname = $znames->item(0)->nodeValue;
            $zboxofficesums = $Root->getElementsByTagName( "zboxofficesum" );
            $zboxofficesum = $zboxofficesums->item(0)->nodeValue;
            $zboxoffices = $Root->getElementsByTagName( "zboxofficedaily" );
            $zboxoffice = $zboxoffices->item(0)->nodeValue;
            $zsessions = $Root->getElementsByTagName( "zsession" );
            $zsession = $zsessions->item(0)->nodeValue;
            $zpersons = $Root->getElementsByTagName( "zperson" );
            $zperson = $zpersons->item(0)->nodeValue;
            $zofficesales = $Root->getElementsByTagName( "zofficesale" );
            $zofficesale = $zofficesales->item(0)->nodeValue;
			$zofficerates = $Root->getElementsByTagName( "zofficerate" );
            $zofficerate = $zofficerates->item(0)->nodeValue;
			$zinternetsales = $Root->getElementsByTagName( "zinternetsale" );
            $zinternetsale = $zinternetsales->item(0)->nodeValue;
			$zinternetrates = $Root->getElementsByTagName( "zinternetrate" );
            $zinternetrate = $zinternetrates->item(0)->nodeValue;
			$zrealtimeboxoffices = $Root->getElementsByTagName( "zrealtimeboxoffice" );
            $zrealtimeboxoffice = $zrealtimeboxoffices->item(0)->nodeValue;
			$zestimatedboxoffices = $Root->getElementsByTagName( "zestimatedboxoffice" );
            $zestimatedboxoffice = $zestimatedboxoffices->item(0)->nodeValue;
            $zacquitimes = $Root->getElementsByTagName( "zacqutime" );
            $zacquitime = $zacquitimes->item(0)->nodeValue;


           $sqlinsert="insert into zpiaofang(zname,zboxofficesum,zboxoffice,zsession,zperson,zofficesale,zofficerate,zinternetsale,zinternetrate,zrealtimeboxoffice,zestimatedboxoffice,zacquitime) values('{$zname}','{$zboxofficesum}','{$zboxoffice}','{$zsession}','{$zperson}','{$zofficesale}','{$zofficerate}','{$zinternetsale}','{$zinternetrate}','{$zrealtimeboxoffice}','{$zestimatedboxoffice}','{$zacquitime}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
