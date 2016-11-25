<?php
/**
 * descript:获得明星势力榜
 * @date 2016/4/22
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
    //$sql="create table as select * from yirenba";
    //mysql_query("drop table if exists yirenshilibang",$con);
    mysql_query("create table if not exists yirenshilibang(no int(2),yname varchar(30),yscore decimal(4,2),tiji int(8),tijino int(6),hudong int(8),hudongno int(6),sousuo int(8),sousuono int(6),aimu int(8),aimuno int(6),acquitime date ,primary key(yname,acquitime));",$con);

    $day=date("Y-m-d");

    $start="{$day} 19:00:00";
    $end="{$day} 20:00:00";

    $apiUrl=array(
        "http://dataapi.skieer.com/SkieerDataAPI/GetData?key=fcd6e294-0392-4823-9b56-695859cc8209&from={$start}&to={$end}"

    );

    $doc = new DomDocument();
    //循环接口地址
    foreach($apiUrl as $v){
        $doc->load($v);
        $books = $doc->getElementsByTagName( "Root" );
        foreach( $books as $Root )
        {
            // 获取XML内容
            $NOs = $Root->getElementsByTagName( "NO" );
            $NO= $NOs->item(0)->nodeValue;
            $names = $Root->getElementsByTagName( "name" );
            $name = $names->item(0)->nodeValue;
            $scores=$Root->getElementsByTagName( "score" );
            $score=$scores->item(0)->nodeValue;
            $tijis=$Root->getElementsByTagName( "tiji" );
            $tiji=$tijis->item(0)->nodeValue;
			$tijinos=$Root->getElementsByTagName( "tijino" );
            $tijino=$tijinos->item(0)->nodeValue;
			$hudongs=$Root->getElementsByTagName( "hudong" );
            $hudong=$hudongs->item(0)->nodeValue;
			$hudongnos=$Root->getElementsByTagName( "hudongno" );
            $hudongno=$hudongnos->item(0)->nodeValue;
			$sousuos=$Root->getElementsByTagName( "sousuo" );
            $sousuo=$sousuos->item(0)->nodeValue;

			$sousuonos=$Root->getElementsByTagName( "sousuono" );
            $sousuono=$sousuonos->item(0)->nodeValue;

			$aimunos=$Root->getElementsByTagName( "aimuno" );
            $aimuno=$aimunos->item(0)->nodeValue;
			$aimus=$Root->getElementsByTagName( "aimu" );
            $aimu=$aimus->item(0)->nodeValue;

			$acquitime=date("Y-m-d");

		   $sqlinsert="insert into yirenshilibang(no,yname,yscore,tiji,tijino,hudong,hudongno,sousuo ,sousuono ,aimu ,aimuno,acquitime)values('{$NO}','{$name}','{$score}','{$tiji}','{$tijino}','{$hudong}','{$hudongno}','{$sousuo}','{$sousuono}','{$aimu}','{$aimuno}','{$acquitime}')";
		   echo $sqlinsert;
		   mysql_query($sqlinsert,$con);

        }
    }
    mysql_close($con);

?>