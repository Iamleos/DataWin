<?php
//获取tv名字
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");

    $data = mysqli_query($con,"select name from search_list;");
    $data = mysqli_fetch_all($data);
    mysqli_close($con);

//连接yingxiang数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="yingxiang";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($data as $key => $value) {
        $name = urlencode($value[0]);
        $url = "http://s.weibo.com/impress?key=".$name."&cate=realtime&isswitch=1&refer=tag&cuid=2883234484";
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
        preg_match_all('/<div class="impress_label impress_label_b">([\s\S]*)<!--<div class="impress_hint">/',$result, $temp);
        if(count($temp[1]) == 0){
            continue;
        }
        preg_match_all('/<a href=".*" class=".*"><span class="size(.*)">(.*)<\/span><\/a>/',$temp[1][0], $data);
        $size = $data[1];
        $tag = $data[2];
        foreach ($tag as $key1 => $value1) {
            if ($tag[$key1] == "") {
                continue;
            }
            $tag[$key1] = str_ireplace("<br>","",$tag[$key1]);
            mysqli_query($con, "insert into yingxiang_tv_everyday values('{$value[0]}','{$tag[$key1]}','{$size[$key1]}','{$date}');");
        }
        var_dump($value[0]);
    }
    mysqli_close($con);
 ?>
