<?php
  date_default_timezone_set("Asia/Shanghai");
  for($i = 0; $i < 44; $i++){
      $date = date_create("2016-11-01");
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
      preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
      preg_match_all('/<ul class=\"canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\?_v_=yes\'\">/', $result, $filmnum);
      $filmname = $filmname[1];
      $filmnum = $filmnum[1];
      $file = fopen("/var/www/html/filmdaily/maoyan_city_supplement/filmname.txt","w");
      foreach ($filmnum as $key => $value) {
        fwrite($file, $filmname[$key]." ".$value."\n");
      }

      shell_exec("php /var/www/html/filmdaily/maoyan_city_supplement/maoyan_getData.php $date");
  }


 ?>
