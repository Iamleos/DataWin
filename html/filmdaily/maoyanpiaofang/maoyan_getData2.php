<?php
  $file = fopen('/var/www/html/filmdaily/maoyanpiaofang/result.txt','r');
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyanpiaofang/result.txt'));
  $acquittime = date("Y-n-d H:i:s",time());
  preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>\s*/', $result, $filmname);
  preg_match_all('/每30分钟更新一次，上次更新北京时间(.*)<\/div>/', $result, $piaofangshijian);
  preg_match_all('/<em style=\"margin-left: .1rem\">(.*)<\/em>/', $result, $sumboxoffice);
  preg_match_all('/<li class=\"c2 \">\s*<b>(.*)<\/b><br\/>/', $result, $boxoffice);
  preg_match_all('/<li class=\"c3 \">(.*)<\/li>/', $result, $boxofficerate);
  preg_match_all('/<li class=\"c4 \">\s*(.*)\s*<\/li>/',$result, $rowpiecerate);
  preg_match_all('/<li class=\"c5 \">\s*<span style=\"margin-right:-.1rem\">\s*(.*)\s*<\/span>/', $result, $seatrate);
  $filmname = $filmname[1];
  $piaofangshijian = $piaofangshijian[1];
  $sumboxoffice = $sumboxoffice[1];
  $boxoffice = $boxoffice[1];
  $boxofficerate = $boxofficerate[1];
  $rowpiecerate = $rowpiecerate[1];
  $seatrate = $seatrate[1];
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  mysqli_query($con, "drop table if exists maoyanpiaofang");
  mysqli_query($con, "create table maoyanpiaofang(mname varchar(30), msumboxoffice varchar(30), mboxoffice varchar(30), mboxofficerate varchar(30), mrowpiecerate varchar(30), mseatrate varchar(30), mpiaofangshijian varchar(30), macquitime varchar(30));");
  for($i = 0; $i < count($filmname); $i++){
    mysqli_query($con, "insert into maoyanpiaofang(mname, msumboxoffice, mboxoffice, mboxofficerate, mrowpiecerate, mseatrate, mpiaofangshijian, macquitime) values('{$filmname[$i]}', '{$sumboxoffice[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$piaofangshijian[0]}', '{$acquittime}')");
  }
  mysqli_close($con);























 ?>
