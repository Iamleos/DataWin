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
    $filmnamefile = fopen("/var/www/html/filmdaily/baidu_movie_fans/filmname.txt","r");
    while(!feof($filmnamefile)){
      $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($filmname);

    foreach ($filmname as $key => $value) {
        $url = "https://mdianying.baidu.com/fan/movieActorRank?movieId=".$value[1]."&orderId=&ticketNum=0";
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
        preg_match_all('/<span class="actor-name actor-name.*">(.*)<\/span>/', $result, $actorname);
        preg_match_all('/<span class="all-ticket">共(.*)张<\/span>/', $result, $totle);
        preg_match_all('/<span class="today-ticket">今日(.*)张<\/span>/', $result, $today);
        $actorname = $actorname[1];
        $totle = $totle[1];
        $today = $today[1];
        foreach ($actorname as $key1 => $value1) {
            //var_dump("insert into baidu_movie_fans values('{$value[0]}', '{$value1}', '{$totle[$key1]}','{$today[$key1]}','{$date}');");
            mysqli_query($con, "insert into baidu_movie_fans values('{$value[0]}', '{$value1}', '{$totle[$key1]}','{$today[$key1]}','{$date}');");
        }
    }

?>
