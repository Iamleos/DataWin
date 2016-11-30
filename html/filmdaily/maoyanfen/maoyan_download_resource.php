<?php
  date_default_timezone_set("Asia/Shanghai");
  $url = "http://piaofang.maoyan.com";
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
  preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
  preg_match_all('/<ul class=\"canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\?_v_=yes\'\">/', $result, $filmnum);
  $filmname = $filmname[1];
  $filmnum = $filmnum[1];
  $file = fopen("/var/www/html/filmdaily/maoyanfen/filmname.txt","w");
  foreach ($filmnum as $key => $value) {
    fwrite($file, $filmname[$key]." ".$value."\n");
  }


 ?>
