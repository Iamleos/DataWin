<?php

header("Content-Type: text/html;charset=utf-8");
$host="56a3768226622.sh.cdb.myqcloud.com:4892";
$name="root";
$password="ctfoxno1";
$dbname="book";
$authorname=$_GET['author'];

$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
mysql_select_db($dbname,$con);
$sql="select * from authorinfo where author='{$authorname}'";

$result=mysql_query($sql,$con);
$str=array();

if($row=mysql_fetch_row($result))
{
    $str["author"]=$row[0];
    $str["picture"]=$row[1];
    $str["authorinfo"]=$row[2];
}else{
    $str["author"]="{$authorname}";
    $str["picture"]=0;
    $str["authorinfo"]=0;
}

$res=json_encode($str,JSON_UNESCAPED_UNICODE);
echo urldecode($res);
mysql_close($con);

?>