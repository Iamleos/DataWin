<?php
    $ch = curl_init("http://www.cbooo.cn/commingnext");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/<h3><a target="_blank" href="http\:\/\/www\.cbooo\.cn\/m\/(.*)" title=""/', $result, $filmnum1);
    preg_match_all('/<h3><a target="_blank" href="http\:\/\/www\.cbooo\.cn\/m\/.*" title="">(.*)<\/a><\/h3>/',$result, $filmname1);
    preg_match_all('/<div class="more-details">\s*([\s|\S])*?\s*<\/div>\s*<\/div>/', $result, $more);
    $filmname1 = str_replace('&#183;', '·',$filmname1[1]);
    $filmnum1 = $filmnum1[1];
    $more = $more[0];
    $temp = NULL;
    foreach ($more as $key1 => $value1) {
        $temp = $temp.$value1;
    }
    preg_match_all('/<a target="_blank" href="http\:\/\/www\.cbooo\.cn\/m\/(.*)" title/', $temp, $filmnum2);
    preg_match_all('/<a target="_blank" href="http\:\/\/www\.cbooo\.cn\/m\/.*" title="(.*)">/', $temp, $filmname2);
    $filmnum2 =  $filmnum2[1];
    $filmname2 = str_replace('&#183;', '·',$filmname2[1]);

    $file = fopen("/var/www/html/filmdaily/marketing_events/filmname_next.txt","w");
    foreach ($filmnum1 as $key => $value) {
        fwrite($file, $filmname1[$key]." ".$value."\n");
    }
    foreach ($filmnum2 as $key => $value) {
        fwrite($file, $filmname2[$key]." ".$value."\n");
    }
    fclose($file);
 ?>
