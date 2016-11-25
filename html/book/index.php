<?php

   header("Content-Type: text/html;charset=utf-8");
   $host="56a3768226622.sh.cdb.myqcloud.com:4892";
   $name="root";
   $password="ctfoxno1";
   $dbname="book";

   $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
   mysql_select_db($dbname,$con);

   $sql="select * from paihang order by total desc limit 50";

   $sqlbaidu30a="select sum(baidu30a) from paihang";
   $baidu30a=mysql_query($sqlbaidu30a,$con);
   $sum_baidu30a=mysql_fetch_row($baidu30a);
   $int_sum_baidu30a=intval($sum_baidu30a[0]);
  // print($int_sum_baidu30a);

   $sqlbaidu30data="select sum(baidu30data) from paihang";
   $baidu30data=mysql_query($sqlbaidu30data,$con);
   $sum_baidu30data=mysql_fetch_row($baidu30data);
   $int_sum_baidu30data=intval($sum_baidu30data[0]);
  // print($int_sum_baidu30data);

   $sql36030search="select sum(36030search) from paihang";
   $a36030search=mysql_query($sql36030search,$con);
   $sum_36030search=mysql_fetch_row($a36030search);
   $int_sum_36030search=intval($sum_36030search[0]);
  //print($int_sum_36030search);

   $sql36030mt="select sum(36030mt) from paihang";
   $a36030mt=mysql_query($sql36030mt,$con);
   $sum_36030mt=mysql_fetch_row($a36030mt);
   $int_sum_36030mt=intval($sum_36030mt[0]);
  // print($int_sum_36030mt);

   $sqlweizs30zt="select sum(weizs30zt) from paihang";
   $weizs30zt=mysql_query($sqlweizs30zt,$con);
   $sum_weizs30zt=mysql_fetch_row($weizs30zt);
   $int_sum_weizs30zt=intval($sum_weizs30zt[0]);
   //print("-----------------");
  // print($int_sum_weizs30zt);
  // print("-----------------");

   $sqlttheme="select sum(ttheme) from paihang";
   $ttheme=mysql_query($sqlttheme,$con);
   $sum_ttheme=mysql_fetch_row($ttheme);
   $int_sum_ttheme=intval($sum_ttheme[0]);
   //print($int_sum_ttheme);

   $sqltpaper="select sum(tpaper) from paihang";
   $tpaper=mysql_query($sqltpaper,$con);
   $sum_tpaper=mysql_fetch_row($tpaper);
   $int_sum_tpaper=intval($sum_tpaper[0]);
   //print($int_sum_tpaper);

   $sqltlove="select sum(tlove) from paihang";
   $tlove=mysql_query($sqltlove,$con);
   $sum_tlove=mysql_fetch_row($tlove);
   $int_sum_tlove=intval($sum_tlove[0]);
   //print($int_sum_tlove);

   $sqlwpaper="select sum(wpaper) from paihang";
   $wpaper=mysql_query($sqlwpaper,$con);
   $sum_wpaper=mysql_fetch_row($wpaper);
   $int_sum_wpaper=intval($sum_wpaper[0]);
   //print($int_sum_wpaper);

   $sqlwreader="select sum(wreader) from paihang";
   $wreader=mysql_query($sqlwreader,$con);
   $sum_wreader=mysql_fetch_row($wreader);
   $int_sum_wreader=intval($sum_wreader[0]);
   //print($int_sum_wreader);



   mysql_query("set names utf8");
   $result=mysql_query($sql,$con);
   $reslist=array();
   $i=0;
   while($row=mysql_fetch_row($result)){
       $reslist[$i]["no"]=$i+1;
       $reslist[$i]["author"]=$row[0];
       //$reslist[$i]["baidu30a"]=$row[1];
       //$reslist[$i]["baidu30data"]=$row[2];
       //$reslist[$i]["36030search"]=$row[3];
       //$reslist[$i]["36030mt"]=$row[4];
       //$reslist[$i]["weizs30zt"]=$row[5];
       // $reslist[$i]["ttheme"]=$row[6];
       //$reslist[$i]["tpaper"]=$row[7];
       //$reslist[$i]["tlove"]=$row[8];
       //$reslist[$i]["wpaper"]=$row[9];
       //$reslist[$i]["wreader"]=$row[10];
       $reslist[$i]["rate"]=doubleval($row[11])*100;
       $reslist[$i]["ci"]=intval(doubleval($row[12])*100000);
       $reslist[$i]["weixin"]=intval(10000*0.075*(doubleval($row[9])/$int_sum_wpaper+doubleval($row[10])/$int_sum_wreader));
       $reslist[$i]["weizs"]=intval(10000000*0.1*(doubleval($row[5])/$int_sum_weizs30zt));
       $reslist[$i]["tieba"]=intval(1000000*0.05/3*(doubleval($row[6])/$int_sum_ttheme+doubleval($row[7])/$int_sum_tpaper+doubleval($row[8])/$int_sum_tlove));
       $reslist[$i]["search"]=intval(500000*0.15*(doubleval($row[1])/$int_sum_baidu30a+doubleval($row[3])/$int_sum_36030search));
       $reslist[$i]["meiti"]=intval(10000000*0.15*(0.7*doubleval($row[2])/$int_sum_baidu30a+0.3*doubleval($row[4])/$int_sum_36030search));
       $i++;
   }
   $str=json_encode($reslist,JSON_UNESCAPED_UNICODE);
   echo urldecode($str);
   mysql_close($con);
?>