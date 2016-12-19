<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-n-d",time());
    $ch = curl_init("http://www.cbooo.cn/BoxOffice/GetHourBoxOffice");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0");
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    //var_dump($result);
    //preg_match_all('/<td style='width:150px'>/');
    $data = json_decode($result,true);
    $data = $data["data2"];
    //connect to db
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    mysqli_query($con, "drop table if exists enyipiaofang");
    mysqli_query($con,"create table enyipiaofang(ename varchar(30),eboxofficesum int(6),eboxofficedaily int(5),eboxofficerate decimal(4,2),eday int(3),eacquitime date,primary key(ename,eacquitime));");
    //exchange data
    foreach ($data as $key => $value) {
        $data[$key]["BoxOffice"] = (int)$value["BoxOffice"];
        $data[$key]["sumBoxOffice"] = (int)$value["sumBoxOffice"];
        $data[$key]["movieDay"] = (int)$value["movieDay"];
        $data[$key]["boxPer"] = (float)$value["boxPer"];
    }
    foreach ($data as $key => $value) {
        mysqli_query($con,"insert into enyipiaofang(ename,eboxofficesum,eboxofficedaily,eboxofficerate,eday,eacquitime) values('{$data[$key]["MovieName"]}','{$data[$key]["sumBoxOffice"]}','{$data[$key]["BoxOffice"]}','{$data[$key]["boxPer"]}','{$data[$key]["movieDay"]}','{$date}');");
    }
?>
