<?php
/**
 * descript:百度搜索指数入库
 * @date 2016/3/25
 * @author  XuJun
 * @version 1.0
 * @package
 * @notice please use utf-8 to read.请使用utf-8查看源代码
 */
//对于设置编码，打开数据库，这些通用的操作需要写在一个php文件中，方便以后修改，然后 include_once 方便以后维护。
//此代码编写目的是测试入库。

//设置浏览器查看编码格式
header("Content-Type: text/html;charset=utf-8");

//由于数据量很大，所以设置永不超时。
set_time_limit(0);

//计算百度搜索指数的行数
$line = count(file('/var/www/html/uploads/baidu.txt'));

//打开百度搜索指数文件
$baidu=file("/var/www/html/uploads/baidu.txt");

//数据库的基本信息
$host="56a3768226622.sh.cdb.myqcloud.com:4892";
$name="root";
$password="ctfoxno1";
$dbname="filmdaily";

//连接数据库
$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");

//选择数据库
mysql_select_db($dbname,$con);

//设置数据库表格编码
mysql_query("set names utf8");

//用于判断是否插入成功
$flag=0;
//用于显示在哪一行执行失败
$num=0;

//存入百度指数数据时，先删除表格。
mysql_query("drop table if exists baiduzhishu",$con);

//创建数据库表格，由于第一次表格不存在，所以需要先删除，在创建。而不是直接清空表格
mysql_query("create table baiduzhishu(bname varchar(30),bsearch int(7),bmtrate int(3),bacquitime date,primary key(bname,bacquitime));",$con);

echo "Please wait for a moment...";

//读取百度搜索指数中的数据
for($j=0;$j<$line;$j++)
{
	//读取一行中的指定列的数据
    if($j>0)
    {   //先分割一行的字符串
        $arr=explode(",",$baidu[$j]);
	    //获得字符串中电影名称
        $bname=trim(str_replace("\"","",$arr[0]));
		//获取字符串中搜索指数
        $bsearch=intval(substr($arr[1],1,strlen($arr[1])-2));
		//获取字符串中移动指数
        $bmt=intval(substr($arr[3],1,strlen($arr[3])-2));
		//获取字符串中移动占比，这个是通过计算获得的。
        $bmtrate=(int)($bmt*100/($bsearch+0.01));
		//获得数据采集时间，由于原始数据中有一部分没有采集时间，所以我们统一使用上传时间就是采集时间
        $bacquitime=date("Y-m-d");
		//将数据插入到数据库中
        $sqlinsert="insert into baiduzhishu(bname,bsearch,bmtrate,bacquitime) values('{$bname}','{$bsearch}','{$bmtrate}','{$bacquitime}')";
        echo $sqlinsert;
		//用于判断是否插入成功
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
	//用于判断在哪一行执行失败
    echo "excute Failed, check line {$num}";

}else{

    echo "excute OK";
}
mysql_close($con);

?>