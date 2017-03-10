<?php

    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    //所有电影状态置0   （0：为上映或者下映          1：正在上映）
    mysqli_query($con, "update baidufilmID set status=0;");
//获取百度电影ID
    $url = "https://mdianying.baidu.com/";
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
    preg_match_all('/<p class="movie-name">(.*)<\/p>/', $result, $filmname);
    preg_match_all('/<a class="item" href="\/movie\/detail\?movieId=(.*)" data-log=/', $result, $ID);
    $filmname = $filmname[1];
    $ID = $ID[1];

//加入百度电影资料库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($filmname as $key => $value) {
        $hasFilme = mysqli_query($con, "select name from baidufilmID where name='{$value}';");
        if(mysqli_fetch_array($hasFilme)!=NULL){
            mysqli_query($con, "update baidufilmID set status=1 where name='{$value}';");
        }
        else {
            mysqli_query($con, "insert into baidufilmID values('{$value}', '{$ID[$key]}', 1);");
        }
    }
?>
