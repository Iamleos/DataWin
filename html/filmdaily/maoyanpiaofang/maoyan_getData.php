<?php
  date_default_timezone_set("Asia/Shanghai");
  $map = array();
  $file = fopen('/var/www/html/filmdaily/maoyanpiaofang/result.txt','r');
  $mapfile = fopen('/var/www/html/filmdaily/maoyanpiaofang/key.txt','r');
  for($i=0; $i<10; $i++){
    $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
  }
  $number = array("0","1","2","3","4","5","6","7","8","9");
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyanpiaofang/result.txt'));
  $acquittime = date("Y-n-d",time());
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
  foreach($sumboxoffice as $key => $value){
    if(strchr($value, '亿')){
      $sumboxoffice[$key] = str_replace('亿','',$value)*10000;
    }
    elseif(strchr($value, '万')){
      $sumboxoffice[$key] = (float)str_replace('万','',$value);
    }
  }

  $boxofficerate = str_replace('%','',$boxofficerate);
  $rowpiecerate = str_replace('%','',$rowpiecerate);
  $seatrate = str_ireplace('%','',$seatrate);
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  mysqli_query($con, "drop table if exists maoyanpiaofang");
  mysqli_query($con, "create table maoyanpiaofang(mname varchar(30), msumboxoffice numeric(8,2), mboxoffice numeric(7,2), mboxofficerate numeric(3,1), mrowpiecerate numeric(3,1), mseatrate numeric(3,1), mpiaofangshijian varchar(30), macquitime date);");
  var_dump($piaofangshijian[0]);
  for($i = 0; $i < count($filmname); $i++){
    mysqli_query($con, "insert into maoyanpiaofang(mname, msumboxoffice, mboxoffice, mboxofficerate, mrowpiecerate, mseatrate, mpiaofangshijian, macquitime) values('{$filmname[$i]}', '{$sumboxoffice[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$piaofangshijian[0]}', '{$acquittime}')");
  }
  mysqli_close($con);























 ?>
