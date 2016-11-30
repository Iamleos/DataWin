<?php
  date_default_timezone_set("Asia/Shanghai");
  $date = date_create(date("Y-m-d"));
  $date = date_sub($date,date_interval_create_from_date_string("1 days"));
  $date = date_format($date, "Y-m-d");
  var_dump($date);
  $url = "http://piaofang.maoyan.com/company/cinema?date=".$date."&webCityId=0&cityTier=0&page=1&cityName=%E5%85%A8%E5%9B%BD";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
  curl_setopt($ch, CURLOPT_TIMEOUT, 2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_NOSIGNAL, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $result = curl_exec($ch);
  preg_match_all('/url\(\/\/(.*)\)\sformat/', $result, $ttf);
//http://p1.meituan.net/colorstone/78f19117454922cfb8d94c374aa5e2f53000.ttf
  preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
  var_dump($ttf);
  $file = fopen('/var/www/html/filmdaily/maoyan_cinema/result.txt','w');
  fwrite($file, $result);
  $com = "wget -P /var/www/html/filmdaily/maoyan_cinema ".$ttf[1][0];
  shell_exec($com);
  sleep(3);
  shell_exec('rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyan_cinema/'.$filename[1][0]);
  sleep(3);
  shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyan_cinema maoyan");
  sleep(3);
  shell_exec("php /var/www/html/filmdaily/maoyan_cinema/maoyan_getData.php");

 ?>
