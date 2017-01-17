<?php
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $result = mysqli_query($con,"select mainname from dianyingzh;");
    $file = fopen("/var/www/html/filmdaily/movie_lib/dianyingzh.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }

    mysqli_select_db($con,"movie");
    $result = mysqli_query($con,"select movie from yien;");
    $file = fopen("/var/www/html/filmdaily/movie_lib/yien.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }

    $result = mysqli_query($con,"select movie from maoyan;");
    $file = fopen("/var/www/html/filmdaily/movie_lib/maoyan.txt","w");
    while($row = mysqli_fetch_row($result)){
        fwrite($file, $row[0]."\r\n");
    }



?>
