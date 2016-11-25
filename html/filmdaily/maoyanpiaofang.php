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
   // $sql="drop table if exists maoyanpaipian;create table maoyanpaipian(mname varchar(30),mrowpiecerate decimal(4,2),msession int(5),macquitime date,mtype varchar(16) ,primary key(mname,mtype,macquitime))";
    mysql_query("drop table if exists maoyanpiaofang;",$con);
    mysql_query("create table maoyanpiaofang(mname varchar(30),msumboxoffice int(6),mboxoffice int(5),mboxofficerate decimal(4,2),mrowpiecerate decimal(4,2),mseatrate decimal(4,2),mpiaofangshijian date,macquitime date,primary key(mname,mpiaofangshijian,macquitime));",$con);
    $day=date("Y-m-d");
    //echo $day;
    $start="{$day} 21:00:00";
    $end="{$day} 22:00:00";
   // $url="http://dataapi.skieer.com/SkieerDataAPI/GetData?key=de73db41-a9a3-4ee4-b32a-489b2db363d1&from={$start}&to={$end}";

   // $newdate=date('Y-m-d H:i:s',time()-3300);
    //$enddate=date('Y-m-d H:i:s',time()+300);
    //接口地址
    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=41006ec0-41db-4b5d-812c-b10ffd838e05&from={$start}&to={$end}"
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
            $mnames = $Root->getElementsByTagName( "mname" );
            $mname = $mnames->item(0)->nodeValue;
            $msumboxoffices = $Root->getElementsByTagName( "msumboxoffice" );
            $msumboxoffice = $msumboxoffices->item(0)->nodeValue;
            $mboxoffices = $Root->getElementsByTagName( "mboxoffice" );
            $mboxoffice = $mboxoffices->item(0)->nodeValue;
            $mboxofficerates = $Root->getElementsByTagName( "mboxoofficerate" );
            $mboxofficerate = $mboxofficerates->item(0)->nodeValue;
            $mrowpiecerates = $Root->getElementsByTagName( "mrowpiecerate" );
            $mrowpiecerate = $mrowpiecerates->item(0)->nodeValue;
            $mseatrates = $Root->getElementsByTagName( "mseatrate" );
            $mseatrate = $mseatrates->item(0)->nodeValue;
            $mpiaofangshijians = $Root->getElementsByTagName( "mpiaofangshijian" );
            $mpiaofangshijian = $mpiaofangshijians->item(0)->nodeValue;
            $macquitimes = $Root->getElementsByTagName( "macquitime" );
            $macquitime = $macquitimes->item(0)->nodeValue;
            //$contents = $Root->getElementsByTagName( "content" );
            //$content = $contents->item(0)->nodeValue;

            // 打印数据
           //print_r($name);
           $sqlinsert="insert into maoyanpiaofang(mname,msumboxoffice,mboxoffice,mboxofficerate,mrowpiecerate,mseatrate,mpiaofangshijian,macquitime) values('{$mname}','{$msumboxoffice}','{$mboxoffice}','{$mboxofficerate}','{$mrowpiecerate}','{$mseatrate}','{$mpiaofangshijian}','{$macquitime}')";
           echo $sqlinsert;
           mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);
?>
