<?php
echo exec('whoami');
  $ch = curl_init("http://piaofang.maoyan.com");
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_NOSIGNAL, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $result = curl_exec($ch);
  var_dump($result);
  //curl_close($ch);
  $file = fopen('/var/www/html/filmdaily/maoyanpiaofang/result.txt','w');
  fwrite($file, $result);
  preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
  curl_close($ch);
if(count($ttf[1])==0){
  shell_exec("php /var/www/html/filmdaily/maoyanpiaofang/maoyan_getData2.php");
  echo "we have get in";
}
else{
  preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$filename);
  $com = "sudo wget -P /var/www/html/filmdaily/maoyanpiaofang ".$ttf[1][0];
  shell_exec($com);
  sleep(3);
  shell_exec('sudo rename '.$filename[1][0].' map.ttf /var/www/html/filmdaily/maoyanpiaofang/'.$filename[1][0]);
  sleep(3);
  exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyanpiaofang/ maoyan");
  sleep(3);
  shell_exec("sudo php /var/www/html/filmdaily/maoyanpiaofang/maoyan_getData.php");
}
 ?>
