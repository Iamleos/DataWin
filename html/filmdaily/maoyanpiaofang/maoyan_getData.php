<?php
  $map = array();
  $file = fopen('/var/www/html/filmdaily/maoyanpiaofang/result.txt','r');
  $mapfile = fopen('/var/www/html/filmdaily/maoyanpiaofang/key.txt','r');
  for($i=0; $i<10; $i++){
    $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
  }
  $number = array("0","1","2","3","4","5","6","7","8","9");
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyanpiaofang/result.txt'));
  $acquittime = date("Y-n-d H:i:s",time());
  preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
  preg_match_all('/每30分钟更新一次，上次更新北京时间(.*)<\/div>/', $result, $piaofangshijian);
  preg_match_all('/<em style=\"margin-left: .1rem\"><i class=\"cs gsBlur\">(.*)<\/i><\/em>/', $result, $sumboxoffice);
  preg_match_all('/<li class=\"c2 \">\s*<b><i class=\"cs gsBlur\">(.*)<\/i><\/b><br\/>/', $result, $boxoffice);
  preg_match_all('/<li class=\"c3 \"><i class=\"cs gsBlur\">(.*)<\/i><\/li>/', $result, $boxofficerate);
  preg_match_all('/<li class=\"c4 \">\s*<i class=\"cs gsBlur\">(.*)<\/i>/',$result, $rowpiecerate);
  preg_match_all('/<li class=\"c5 \">\s*<span style=\"margin-right:-.1rem\">\s*<i class=\"cs gsBlur\">(.*)<\/i>/', $result, $seatrate);
  $filmname = $filmname[1];
  $piaofangshijian = $piaofangshijian[1];
  $sumboxoffice = $sumboxoffice[1];
  $boxoffice = $boxoffice[1];
  $boxofficerate = $boxofficerate[1];
  $rowpiecerate = $rowpiecerate[1];
  $seatrate = $seatrate[1];
  $sumboxoffice = str_ireplace($map, $number, $sumboxoffice);
  $boxoffice = str_ireplace($map, $number, $boxoffice);
  $boxofficerate = str_ireplace($map, $number, $boxofficerate);
  $rowpiecerate = str_ireplace($map, $number, $rowpiecerate);
  $seatrate = str_ireplace($map, $number, $seatrate);
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  mysqli_query($con, "drop table if exists maoyanpiaofang");
  mysqli_query($con, "create table maoyanpiaofang(mname varchar(30), msumboxoffice varchar(30), mboxoffice varchar(30), mboxofficerate varchar(30), mrowpiecerate varchar(30), mseatrate varchar(30), mpiaofangshijian varchar(30), macquitime varchar(30));");
  var_dump($piaofangshijian[0]);
  for($i = 0; $i < count($filmname); $i++){
    mysqli_query($con, "insert into maoyanpiaofang(mname, msumboxoffice, mboxoffice, mboxofficerate, mrowpiecerate, mseatrate, mpiaofangshijian, macquitime) values('{$filmname[$i]}', '{$sumboxoffice[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$piaofangshijian[0]}', '{$acquittime}')");
  }
  mysqli_close($con);























 ?>
