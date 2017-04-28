<?php

    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
//连接数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    mysqli_query($con, "drop table if exists maoyanpiaofang");
    mysqli_query($con, "create table maoyanpiaofang(mname varchar(30), msumboxoffice varchar(30), mboxoffice varchar(30), mboxofficerate varchar(30), mrowpiecerate varchar(30), mseatrate varchar(30), mpiaofangshijian varchar(30), macquitime varchar(30));");
    mysqli_query($con, "drop table if exists mpiaofangjianbao");
    mysqli_query($con, "create table mpiaofangjianbao(mname varchar(30), msumboxoffice numeric(8,2), mboxoffice numeric(7,2), mboxofficerate numeric(3,1), mrowpiecerate numeric(3,1), mseatrate numeric(3,1), mday int(3), macquitime date);");



//getdata
    $url = "https://box.maoyan.com/promovie/api/box/second.json";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result)->data;
    $sumboxoffice = $data->totalBox;
    $filmData = $data->list;
    foreach ($filmData as $key => $value) {
        $value->sumBoxInfo = yi2wan($value->sumBoxInfo);
        $value->boxRate = del_percent($value->boxRate);
        $value->showRate = del_percent($value->showRate);
        $value->avgSeatView = del_percent($value->avgSeatView);
        $value->releaseInfo = show_days($value->releaseInfo);
        mysqli_query($con,"insert into mpiaofangjianbao values(
        '{$value->movieName}', '{$value->sumBoxInfo}','{$value->boxInfo}','{$value->boxRate}',
        '{$value->showRate}','{$value->avgSeatView}','{$value->releaseInfo}','{$date}'
        );");
        mysqli_query($con,"insert into maoyanpiaofang values(
        '{$value->movieName}', '{$value->sumBoxInfo}','{$value->boxInfo}','{$value->boxRate}',
        '{$value->showRate}','{$value->avgSeatView}','{$data->serverTime}','{$date}'
        );");
        mysqli_query($con,"insert into maoyan_ri_piaofang values(
        '{$value->movieName}','{$value->boxInfo}','{$value->boxRate}',
        '{$value->showRate}','{$value->avgShowView}','{$date}'
        );");
        var_dump($value->movieName);

    }
    mysqli_close($con);

    //数据亿转化万
    function yi2wan($data){
        if (strstr($data,'亿')) {
            return (string)(str_ireplace('亿','',$data)*10000);
        }
        else {
            return str_ireplace('万','',$data);
        }
    }
    //去处%
    function del_percent($data){
        return str_ireplace('%','',$data);
    }
    //上映天数
    function show_days($data){
        switch ($data) {
            case '上映首日':
                return "1";
                break;
            case '零点场':
                return "0";
                break;
            case '点映':
                return "0";
                break;
            case '重映首日':
                return "1";
                break;

            default:
                if(strstr($data,'重映')){
                    $data = str_ireplace('重映','',$data);
                    return str_ireplace('天','',$data);
                }
                else{
                    $data = str_ireplace('上映','',$data);
                    return str_ireplace('天','',$data);
                }
                break;
        }
    }

 ?>
