<?php
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $douban_tv_name = mysqli_query($con, "select name, first_show from douban_tv where zzsy='1';");
    $douban_tv_name = mysqli_fetch_all($douban_tv_name);
    foreach ($douban_tv_name as $key => $value) {
        preg_match('/(.{0,10})/',$value[1],$firstShowEx);
        $diff = date_diff(date_create($date),date_create($firstShowEx[1]));
        if ($diff->format("%a") >90) {
            mysqli_query($con,"update douban_tv set zzsy='0' where name = \"{$value[0]}\";");
            var_dump($value[0]);
        }
    }

 ?>
