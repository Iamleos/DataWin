<?php
/**
 * descript:百度媒体指数入库
 * @date  2016/3/25
 * @author  XuJun
 * @version 1.0
 * @package
 * @notice please use utf-8 to read.请使用utf-8查看源代码
 * 具体解释看baidu.php
 */
//对于设置编码，打开数据库，这些通用的操作需要写在一个php文件中，方便以后修改，然后 include_once 方便以后维护。
//此代码编写目的是测试入库。

//设置浏览器查看编码格式
header("Content-Type: text/html;charset=utf-8");

//由于数据量很大，所以设置永不超时。
set_time_limit(0);

//计算百度媒体指数的行数
$line = count(file('/var/www/html/uploads/baidumeiti.txt'));
$baidumeiti=file("/var/www/html/uploads/baidumeiti.txt");

//数据库的基本信息
$host="56a3768226622.sh.cdb.myqcloud.com:4892";
$name="root";
$password="ctfoxno1";
$dbname="filmdaily";

$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
mysql_select_db($dbname,$con);
mysql_query("set names utf8");
$flag=0;
$num=0;

mysql_query("drop table if exists baidumeiti",$con);
mysql_query("create table baidumeiti(bname varchar(30),bmeiti int(7),bacquitime date,primary key(bname,bacquitime));",$con);
echo "Please wait for a moment...";
$j=0;
while($j<(int)($line/30))
{
        $arr=explode(",",$baidumeiti[30*$j+1]);
        $bname=trim(str_replace("\"","",$arr[0]));

        $bmeiti=(int)(substr($arr[3],1,strlen($arr[3])-4));
	//$bmeiti = (int)$arr[3];
        $bacquitime=date("Y-m-d");
	echo $bmeiti;
        $sqlinsert="insert into baidumeiti(bname,bmeiti,bacquitime) values('{$bname}','{$bmeiti}','{$bacquitime}')";
        echo $sqlinsert;
        $result=mysql_query($sqlinsert,$con);
        if(!$result)
        {
            $flag=1;
            $num=30*$j+1;
            break;
        }
    $j++;

}

if($flag==1)
{
    echo "excute Failed, check line {$num}";
}else{
    echo "excute OK";
}
mysql_close($con);
?>
