<?php
/**
 *@调用八爪鱼猫眼评分api
 *@author: xujun
 *@notice 具体解释看dianyingba.php
 */
 #! /usr/bin/php
    header("Content-Type: text/html;charset=utf-8");
	set_time_limit(0);
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   //$host="localhost";
    $name="root";
    $password="ctfoxno1";
   //$password="123456";
    $dbname="filmdaily";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

    mysql_query("drop table if exists maoyanfen",$con);
    mysql_query("create table maoyanfen(mname varchar(30),mfen decimal(2,1),mfennum int(8),mwantnum int(8),macquitime date,primary key(mname,macquitime));",$con);
    $day=date("Y-m-d");
	$url="http://piaofang.maoyan.com/";
	$html=file_get_contents($url);
	$arr=array();
	$preg='/<ul class="canTouch" data-com="hrefTo,href:([\w\W]*?)">/';
	preg_match_all($preg,$html,$arr);
	$size=count($arr[1]);
	for($i=0;$i<$size;$i++)
	{
		$film_url="http://piaofang.maoyan.com".trimall($arr[1][$i]);
		$film_html=file_get_contents($film_url);

		$arr1=array();
		$preg1='/<article class="score[\w\W]*?">[\w\W]*?<i>([\w\W]*?)<\/i>[\w\W]*?<\/article>/';
		preg_match_all($preg1,$film_html,$arr1);

		$mfen=($arr1[1][0]);

		$arr2=array();
		$preg2='/<article class="wish-num">[\w\W]*?<i>([\w\W]*?)<\/i>[\w\W]*?<\/article>/';
		preg_match_all($preg2,$film_html,$arr2);
		$mwantnum=($arr2[1][0]);


		$arr3=array();
		$preg3='/<article class="score[\w\W]*?">[\w\W]*?<span>([\w\W]*?)<\/span>[\w\W]*?<\/article>/';
		preg_match_all($preg3,$film_html,$arr3);
		$mfennum=findNum($arr3[1][0]);


		$arr4=array();
		$preg4='/<title>([\w\W]*?)<\/title>/';
		preg_match_all($preg4,$film_html,$arr4);
		$mname=$arr4[1][0];


		$sqlinsert="insert into maoyanfen(mname,mfen,mfennum,mwantnum,macquitime) values('{$mname}','{$mfen}','{$mfennum}','{$mwantnum}','{$day}')";
        echo $sqlinsert."<br/>";
        mysql_query($sqlinsert,$con);



	}

               //$sqlinsert="insert into maoyanfen(mname,mfen,mfennum,mwantnum,macquitime) values('{$mname}','{$mfen}','{$mfennum}','{$mwantnum}','{$macquitime}')";
               //echo $sqlinsert;
               //mysql_query($sqlinsert,$con);

    mysql_close($con);

	function trimall($str)
	{
		$qian=array(" ","　","'","\n","\r");$hou=array("","","","","");
		return str_replace($qian,$hou,$str);
	}

	function findNum($str=''){
    $str=trim($str);
    if(empty($str)){return '';}
    $temp=array('1','2','3','4','5','6','7','8','9','0');
    $result='';
    for($i=0;$i<strlen($str);$i++){
        if(in_array($str[$i],$temp)){
            $result.=$str[$i];
        }
    }
    return $result;
	}



?>
