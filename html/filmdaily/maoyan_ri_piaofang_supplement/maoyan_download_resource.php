<?php
    date_default_timezone_set("Asia/Shanghai");
    for($i = 0; $i < 194; $i++){
        $date = date_create("2016-06-01");
        $date = date_modify($date,"+$i days");
        $date = date_format($date,"Y-m-d");
        $url = "http://piaofang.maoyan.com/?date=".$date;
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
        $file = fopen('/var/www/html/filmdaily/maoyan_ri_piaofang_supplement/result.txt','w');
        fwrite($file, $result);
        preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
        if(count($ttf[1])==0){
            var_dump("1");
            shell_exec("php /var/www/html/filmdaily/maoyan_ri_piaofang_supplement/maoyan_getData2.php $date");
        }
        else{
            var_dump("2");

            preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
            $com = " wget -P /var/www/html/filmdaily/maoyan_ri_piaofang_supplement ".$ttf[1][0];
            shell_exec($com);
            sleep(3);
            shell_exec('rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyan_ri_piaofang_supplement/'.$filename[1][0]);
            sleep(3);
            exec("/home/jdk//bin/java -classpath /var/www/html/filmdaily/maoyan_ri_piaofang_supplement/ maoyan");
            sleep(3);
            shell_exec("php /var/www/html/filmdaily/maoyan_ri_piaofang_supplement/maoyan_getData.php $date");
	    sleep(1);
        }
    }
 ?>
