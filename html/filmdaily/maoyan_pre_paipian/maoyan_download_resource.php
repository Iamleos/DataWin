<?php
date_default_timezone_set("Asia/Shanghai");
    for($i = 1 ; $i < 8 ; $i++){
        //非黄金拍片
        $date = date_create();
        $date = date_modify($date,"+$i days");
        $date = date_format($date,"Y-m-d");
        $url = "http://piaofang.maoyan.com/show?showDate=".$date."&periodType=0&showType=2";
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
        $file = fopen('/var/www/html/filmdaily/maoyan_pre_paipian/result.txt','w');
        fwrite($file, $result);
        preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
        if(count($ttf[1])==0){
            echo "no key";
        }
        else {
            preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
            $com = "wget -P /var/www/html/filmdaily/maoyan_pre_paipian ".$ttf[1][0];
            shell_exec($com);
            sleep(3);
            shell_exec('rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyan_pre_paipian/'.$filename[1][0]);
            sleep(3);
            shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyan_pre_paipian maoyan");
            sleep(3);
            shell_exec("php /var/www/html/filmdaily/maoyan_pre_paipian/maoyan_getData0.php $i");
            var_dump("key");
        }
        //黄金拍片
        $date = date_create();
        $date = date_modify($date,"+$i days");
        $date = date_format($date,"Y-m-d");
        $url = "http://piaofang.maoyan.com/show?showDate=".$date."&periodType=1&showType=2";
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
        $file = fopen('/var/www/html/filmdaily/maoyan_pre_paipian/result.txt','w');
        fwrite($file, $result);
        preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
        if(count($ttf[1])==0){
            echo "no key";
        }
        else {
            preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
            $com = "wget -P /var/www/html/filmdaily/maoyan_pre_paipian ".$ttf[1][0];
            shell_exec($com);
            sleep(3);
            shell_exec('rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyan_pre_paipian/'.$filename[1][0]);
            sleep(3);
            shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyan_pre_paipian maoyan");
            sleep(3);
            shell_exec("php /var/www/html/filmdaily/maoyan_pre_paipian/maoyan_getData1.php $i");
            var_dump("key");
        }
    }
?>
