<?php
namespace DataWin;
//去除亿，万，并且转化
function yi2wan($data){
    if (strstr($data,'亿')) {
        return (string)(str_ireplace('亿','',$data)*10000);
    }
    else {
        return str_ireplace('万','',$data);
    }
}
//数据转化为万
function toWan($data){
    return (string)($data/10000);
}

//去处%
function del_percent($data){
    return str_ireplace('%','',$data);
}

function getData($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getDB($dbname){
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    return $con;

}

//获取指定日期
function getDate(){
    //获取参数
    $arr=func_get_args();
    //获取参数个数
    $arr_num = func_num_args();
    //设置时区
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    switch ($arr_num) {
        case 0:                         //返回当前时间 function getDate()
            return $date;
        case 1:
            # code...
            break;
        case 2:                        //返回指定日期 function getDate($date, $str)
            $date = date_create($arr[0]);
            $date = date_modify($date,$arr[1]." days");
            $date = date_format($date,"Y-m-d");
            return $date;
        default:
            echo "Wrong usage!!";

    }
}











 ?>
