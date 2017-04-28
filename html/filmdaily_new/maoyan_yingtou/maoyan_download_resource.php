<?php
    include "../maoyan_crack/getWoff.php";
  date_default_timezone_set("Asia/Shanghai");
  $date = date("Y-m-d", strtotime("-1 day"));
  $url = "piaofang.maoyan.com/company/invest";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
  curl_setopt($ch, CURLOPT_TIMEOUT, 2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_NOSIGNAL, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $result = curl_exec($ch);
  $file = fopen('/var/www/html/filmdaily_new/maoyan_yingtou/result.txt','w');
  fwrite($file, $result);
  getWoff($url,__DIR__);
  sleep(3);
  shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily_new/maoyan_yingtou maoyan");
  sleep(3);
  shell_exec("php /var/www/html/filmdaily_new/maoyan_yingtou/maoyan_getData.php");

 ?>
