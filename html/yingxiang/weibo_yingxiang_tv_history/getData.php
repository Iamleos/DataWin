<?php
    date_default_timezone_set("Asia/Shanghai");
    //获取需要采集的明星名字
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $tvname = mysqli_query($con, "select name from search_list;");
    $tvname = mysqli_fetch_all($tvname);
    mysqli_close($con);

    //连接yingxiang数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="yingxiang";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($tvname as $key => $value) {
        $time = NULL;
        $size = NULL;
        $tag = NULL;
        for ($i=0; $i < 3; $i++) {
            $url = "http://s.weibo.com/impress?cate=ajax&key=".urlencode($value[0])."&page=".($i*5-1)."&refer=tag&cuid=2883234484";
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
            if (json_decode($result)->state == 0) {
                continue 2;
            }
            $data = json_decode($result)->html;
            $data = explode("</section></article></div>", $data);
            array_pop($data);
            foreach ($data as $key1 => $value1) {
                $date = date_create();
                $date = date_date_set($date, 2017, 02, 01);
                $month = $i*5+$key1;
                $date = date_modify($date, "-$month month");
                $date = date_format($date, "Y-m-d");
                preg_match_all('/<a href=".*" class=".*"><span class="size(.*)">(.*)<\/span><\/a>/',$data[$key1], $temp);
                $size[$i*5+$key1] = $temp[1];
                $tag[$i*5+$key1] = $temp[2];
                $time[$i*5+$key1] = $date;
            }
        }
        foreach ($tag as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                if ($value3 == "") {
                    continue;
                }
                $value3 = str_ireplace("<br>","",$value3);
                mysqli_query($con, "insert into yingxiang_tv_history values('{$value[0]}', '{$value3}', '{$size[$key2][$key3]}','{$time[$key2]}');");
            }
        }
        var_dump($value[0]);
        sleep(1);
    }
    mysqli_close($con);
 ?>
