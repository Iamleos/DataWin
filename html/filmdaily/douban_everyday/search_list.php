<?php
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    mysqli_query($con, "drop table if exists search_list");
    mysqli_query($con,"CREATE TABLE search_list (`name` varchar(10) NOT NULL, PRIMARY key(name)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $douban_tv_name = mysqli_query($con,"select name from douban_tv where zzsy ='1';");
    $tv_name_name = mysqli_query($con,"select name from tv_name;");
    $douban_tv_name = mysqli_fetch_all($douban_tv_name);
    $tv_name_name = mysqli_fetch_all($tv_name_name);
    foreach ($douban_tv_name as $key1 => $value1) {
        mysqli_query($con, "insert into search_list values('{$value1[0]}');");
    }
    foreach ($tv_name_name as $key2 => $value2) {
        mysqli_query($con, "insert into search_list values('{$value2[0]}');");
    }

 ?>
