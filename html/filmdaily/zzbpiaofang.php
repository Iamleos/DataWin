<?php
/**
 * descript:手工采集专资办票房
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
	mysql_query("drop table if exists zzbpiaofang;",$con);
    mysql_query("create table zzbpiaofang(zname varchar(30),zboxofficesum int(6),zboxoffice int(5),zsession int(7),zperson int(8),zofficesale int(6),zofficerate decimal(4,2),zinternetsale int(6),zinternetrate decimal(4,2),zrealtimeboxoffice int(8),zestimatedboxoffice int(8),zacquitime date,primary key(zname,zacquitime));",$con);

	$url="http://111.205.151.7/movies/0";
	$html=file_get_contents($url);
	//echo $html;
	$arr1= array();
	$arr2= array();
	$arr3= array();
	$preg1='/<span class="num">([\w\W]*?)<\/span>/';
	$preg2='/<div class="open_time">([\w\W]*?)<\/div>/';
	$preg3='/<tr>([\w\W]*?)<\/tr>/';
	preg_match_all($preg1, $html, $arr1);
	preg_match_all($preg2, $html, $arr2);
	preg_match_all($preg3, $html, $arr3);
	//var_dump($arr3[1]);


	$zrealtimeboxoffice=findNum($arr1[1][0]);
	$zestimatedboxoffice=findNum($arr1[1][1]);
	//var_dump($arr2[1][0]);
	$regex="'\d{4}-\d{1,2}-\d{1,2}'is";
	preg_match_all($regex,$arr2[1][0],$matches);
	//var_dump($matches);
	$time=$matches[0][0];
	//echo $time;
	//echo $zrealtimeboxoffice;
	//echo $zestimatedboxoffice;

	for($i=1;$i<count($arr3[1]);$i++)
	{
		//var_dump($arr3[1][$i]);
		$arr=array();
		$preg='/<td>([\w\W]*?)<\/td>/';
		preg_match_all($preg, $arr3[1][$i], $arr);
		$zname=$arr[1][0];
		$zboff_preg='/([\w\W]*?)<i>([\w\W]*?)<\/i>/';
		preg_match_all($zboff_preg, $arr[1][1], $zboff_arr);
		$zboxoffice=findNum($zboff_arr[1][0]);
		$zboxofficesum=findNum($zboff_arr[2][0]);
		preg_match_all($zboff_preg, $arr[1][2], $zsp_arr);
		$zsession=findNum($zsp_arr[1][0]);
		$zperson=findNum($zsp_arr[2][0]);
		preg_match_all($zboff_preg, $arr[1][3], $zoff_arr);

		$zofficesale=findNum($zoff_arr[1][0]);
		$zofficerate=intval(((int)findNum($zoff_arr[2][0]))/100);
		echo $zofficerate."<br/>";
		preg_match_all($zboff_preg, $arr[1][4], $zint_arr);
		$zinternetsale=findNum($zint_arr[1][0]);
		$zinternetrate=intval(((int)findNum($zint_arr[2][0]))/100);

	        if (strstr($zname,"&middot;")) {
        	    $zname = str_replace("&middot;","·",$zname);
	        }


		$sqlinsert="insert into zzbpiaofang(zname,zboxofficesum,zboxoffice,zsession,zperson,zofficesale,zofficerate,zinternetsale,zinternetrate,zrealtimeboxoffice,zestimatedboxoffice,zacquitime) values('{$zname}','{$zboxofficesum}','{$zboxoffice}','{$zsession}','{$zperson}','{$zofficesale}','{$zofficerate}','{$zinternetsale}','{$zinternetrate}','{$zrealtimeboxoffice}','{$zestimatedboxoffice}','{$time}')";
        //echo $sqlinsert;
        mysql_query($sqlinsert,$con);


	}

	//提取字符串中的数字
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
