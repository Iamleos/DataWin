<?php
//连接数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

//
    date_default_timezone_set("Asia/Shanghai");
    $date = (string)date("Y-m-d",time());

    $ch = curl_init("http://top.baidu.com/buzz?b=26&c=1&fr=topcategory_c1");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/href_top=".*\">(.*)<\/a>/', $result, $filmname);
    preg_match_all('/<span class="icon-.*">(.*)<\/span>/', $result, $index);
    $index = $index[1];
    $filmname = $filmname[1];
    foreach ($filmname as $key => $value) {
        $filmname[$key] = iconv("gbk", "UTF-8", $value);
    }
    var_dump($filmname);
    foreach ($filmname as $key => $value) {
        mysqli_query($con, "insert into baidu_fyb value('{$value}', '{$index[$key]}', '{$date}');");
    }



 ?>
