<?php
    date_default_timezone_set("Asia/Shanghai");
    header("Content-type:text/html; charset=utf-8");
    $date = date("Y-m-d",strtotime("tomorrow"));
    $ch = curl_init("http://piaofang.maoyan.com/show?showDate=".$date."&periodType=0&showType=2");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $file = fopen('/var/www/html/filmdaily/maoyan_tomorrow/result.txt','w');
    fwrite($file, $result);
    preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
    if(count($ttf[1])!=0){
        preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
        $com = " wget -P /var/www/html/filmdaily/maoyan_tomorrow ".$ttf[1][0];
        shell_exec($com);
        sleep(3);
        shell_exec('rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyan_tomorrow/'.$filename[1][0]);
        sleep(3);
        exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyan_tomorrow/ maoyan");
        sleep(3);
        shell_exec("php /var/www/html/filmdaily/maoyan_tomorrow/maoyan_getData.php");
    }
?>
