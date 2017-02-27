<?php
    $ch = curl_init("https://mdianying.baidu.com/");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/\/movie\/detail\?movieId=(.*)" data-log/', $result, $filmnum);
    preg_match_all('/<p class="movie-name">(.*)<\/p>/', $result, $filmname);
    $filmnum = $filmnum[1];
    $filmname = $filmname[1];
    $file = fopen("/var/www/html/filmdaily/baidu_movie_fans/filmname.txt","w");
    foreach ($filmnum as $key => $value) {
        fwrite($file, $filmname[$key]." ".$value."\n");
    }


?>
