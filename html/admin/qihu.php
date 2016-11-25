<?php
/**
 * descript:360指数入库
 * @date 2016/3/25
 * @author  XuJun
 * @version 1.0
 * @package
 * @notice please use utf-8 to read.请使用utf-8查看源代码
 * 具体解释看baidu.php
 */


header("Content-Type: text/html;charset=utf-8");
set_time_limit(0);
$line = count(file('/var/www/html/uploads/qihu.txt'));
//echo $line;
$qihu=file("/var/www/html/uploads/qihu.txt");
$host="56a3768226622.sh.cdb.myqcloud.com:4892";
//$host="localhost";
$name="root";
$password="ctfoxno1";
//$password="123456";
$dbname="filmdaily";

$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
mysql_select_db($dbname,$con);
mysql_query("set names utf8");
//$sql="create table as select * from yirenba";
$flag=0;
$num=0;
mysql_query("drop table if exists qihu",$con);
mysql_query("create table qihu(qname varchar(30),qsearch int(7),qmeiti int(7),qacquitime date,primary key(qname,qacquitime));",$con);
echo "Please wait for a moment...";
for($j=0;$j<$line;$j++)
{
    if($j>0)
    {
        $arr=explode(",",$qihu[$j]);
        $qname=trim(str_replace("\"","",$arr[0]));
        $qsearch=intval(trim(str_replace("\"","",$arr[2])));
        $qmeiti=intval(substr($arr[(count($arr)-5)/2+5],1,strlen($arr[(count($arr)-5)/2+5]))) ;
        //$bmtrate=(int)($bmt*100/($bsearch+0.01));
        $qacquitime=date("Y-m-d");
        //echo $qname."-";
        $sqlinsert="insert into qihu(qname,qsearch,qmeiti,qacquitime) values('{$qname}','{$qsearch}','{$qmeiti}','{$qacquitime}')";
        echo $sqlinsert;
        $result=mysql_query($sqlinsert,$con);
        if(!$result)
        {
            $flag=1;
            $num=$j;
            break;
        }


    }

}

if($flag==1)
{
    echo "excute Failed, check line {$num}";
}else{
    echo "excute OK";
}
mysql_close($con);
?>
