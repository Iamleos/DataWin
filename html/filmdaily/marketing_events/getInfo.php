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
    $flag = 0;
    $filename = array();
    $filmnamefile = fopen("/var/www/html/filmdaily/marketing_events/filmname.txt","r");
    while(!feof($filmnamefile)){
      $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
        var_dump($filmname);
        //爬取电影基本信息
        $url = "http://www.cbooo.cn/m/".$value[1];
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
        preg_match_all('/<p>类型：(.*)<\/p>/', $result,$category);
        preg_match_all('/<p>\s*上映时间：(.*)（中国）\s*<\/p>/', $result, $showtime);
        $category = $category[1][0];
        $showtime = $showtime[1][0];
        //爬取营销信息
        $url = "http://www.cbooo.cn/Mdata/getMovieEventAll?movieid=".$value[1];
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
        $result = str_replace('</h5>', "</h5>\n", $result);

        preg_match_all('/<h5>(.*)<\/h5>/', $result, $marketing_type);
        preg_match_all('/<h4>(.*)<\/h4>/', $result, $title);
        preg_match_all('/<var>(.*)<\/var>/', $result, $source_time);
        preg_match_all('/<a href=\'(.*)\' style=\'color/', $result, $URL);
        preg_match_all('/<p>\s*([\s|\S]*?)<a href/', $result, $detail);
        $marketing_type = $marketing_type[1];
        $title = $title[1];
        $source_time = $source_time[1];
        $URL = $URL[1];
        $detail = $detail[1];
        foreach ($source_time as $key1 => $value1) {
            $temp = explode(' ', $value1);
            $source[$key1] = $temp[0];
            $time[$key1] = $temp[1];
        }
        foreach ($marketing_type as $key1 => $value1) {
            mysqli_query($con, "insert into marketing_events values('{$value[0]}', '{$category}', '{$value1}', '{$source[$key1]}',
            '{$time[$key1]}', '{$detail[$key1]}', '{$URL[$key1]}', '{$showtime}', '{$title[$key1]}' );");
        }

    }
 ?>
