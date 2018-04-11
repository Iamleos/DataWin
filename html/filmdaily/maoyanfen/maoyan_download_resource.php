<?php
  date_default_timezone_set("Asia/Shanghai");
  $url = "http://piaofang.maoyan.com/?ver=normal";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0");
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_NOSIGNAL, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $result = curl_exec($ch);
  preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
  preg_match_all('/<ul class=\"canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\?_v_=yes\'\">/', $result, $filmnum);
  $filmname = $filmname[1];
  $filmnum = $filmnum[1];
var_dump($filmname);
die();
  $file = fopen(__DIR__."/filmname.txt","w");
  foreach ($filmnum as $key => $value) {
    fwrite($file, $filmname[$key]." ".$value."\n");
  }
  curl_close($ch);

 ?>
