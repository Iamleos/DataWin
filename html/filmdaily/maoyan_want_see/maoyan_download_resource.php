<?php
    date_default_timezone_set("Asia/Shanghai");
    $today = date("Y-m-d",time());
    $url = "http://piaofang.maoyan.com/store";
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
    preg_match_all('/<div class="title">.*<\/div>\s*([0-9,-]*).*\s*<p class="lineDot">/', $result, $showtime);
    preg_match_all('/<article class=\"indentInner canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\'\">/', $result, $filmnum);
    preg_match_all('/<div class="title">(.*)<\/div>/',$result,$filmname);
    $filmname = $filmname[1];
    $showtime = $showtime[1];
    $filmnum = $filmnum[1];
    $filmnametemp = NULL;
    $filmnumtemp = NULL;
    $showtimetemp = NULL;
    $flag = 0;
    foreach ($showtime as $key => $value) {
        $diff = date_diff(date_create($today),date_create($value));
        if($diff->format("%a") <= 30){
            $filmnametemp[$flag] = $filmname[$key];
            $filmnumtemp[$flag] = $filmnum[$key];
            $showtimetemp[$flag] = $showtime[$key];
            $flag++;
        }
    }
    $filmname = $filmnametemp;
    $filmnum = $filmnumtemp;
    $showtime = $showtimetemp;
    $file = fopen("/var/www/html/filmdaily/maoyan_want_see/filmname.txt","w");
    foreach ($filmnum as $key => $value) {
    fwrite($file, $filmname[$key]." ".$value." ".$showtime[$key]."\n");
    }


 ?>
