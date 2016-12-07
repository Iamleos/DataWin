<?php
  date_default_timezone_set("Asia/Shanghai");
  $map = array();
  $file = fopen('/var/www/html/filmdaily/maoyanpiaofangjianbao/result.txt','r');
  $mapfile = fopen('/var/www/html/filmdaily/maoyanpiaofangjianbao/key.txt','r');
  for($i=0; $i<10; $i++){
    $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
  }
  $number = array("0","1","2","3","4","5","6","7","8","9");
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyanpiaofangjianbao/result.txt'));
  $acquittime = date("Y-n-d",time());
  preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
  preg_match_all('/<br>.*>(.*)(<\/em>|<\/i>)/', $result, $piaofangshijian);
  preg_match_all('/<em style=\"margin-left: .1rem\"><i class=\"cs gsBlur\">(.*)<\/i><\/em>/', $result, $sumboxoffice);
  preg_match_all('/<li class=\"c2 \">\s*<b><i class=\"cs gsBlur\">(.*)<\/i><\/b><br\/>/', $result, $boxoffice);
  preg_match_all('/<li class=\"c3 \"><i class=\"cs gsBlur\">(.*)<\/i><\/li>/', $result, $boxofficerate);
  preg_match_all('/<li class=\"c4 \">\s*<i class=\"cs gsBlur\">(.*)<\/i>/',$result, $rowpiecerate);
  preg_match_all('/<li class=\"c5 \">\s*<span style=\"margin-right:-.1rem\">\s*(.*)\s*<\/span>/', $result, $seatrate);
  $filmname = $filmname[1];
  $piaofangshijian = $piaofangshijian[1];
  $mday = array();
  for($key = 0 ; $key<count($piaofangshijian)/2; $key++) {
    $mday[$key] = $piaofangshijian[$key*2+1];
  }
  $CHday = array('上映','天','零点场',"首日",'点映');
  $numday = array('','','0','1','0');
  $mday = str_replace($CHday, $numday, $mday);
  foreach ($mday as $key => $value) {
    $mday[$key] = (int)$value;
  }
  //var_dump($mday);

  $sumboxoffice = $sumboxoffice[1];
  $boxoffice = $boxoffice[1];
  $boxofficerate = $boxofficerate[1];
  $rowpiecerate = $rowpiecerate[1];
  $seatrate = $seatrate[1];
  $seatrate = str_ireplace('<i class="cs gsBlur">','',$seatrate);
  $seatrate = str_ireplace('</i>','',$seatrate);
  $seatrate = str_ireplace('--','0',$seatrate);
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
  mysqli_query($con, "drop table if exists mpiaofangjianbao");
  mysqli_query($con, "create table mpiaofangjianbao(mname varchar(30), msumboxoffice numeric(8,2), mboxoffice numeric(7,2), mboxofficerate numeric(3,1), mrowpiecerate numeric(3,1), mseatrate numeric(3,1), mday int(3), macquitime date);");
  for($i = 0; $i < count($filmname); $i++){
    mysqli_query($con, "insert into mpiaofangjianbao(mname, msumboxoffice, mboxoffice, mboxofficerate, mrowpiecerate, mseatrate, mday, macquitime) values('{$filmname[$i]}', '{$sumboxoffice[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$mday[$i]}', '{$acquittime}')");
  }
  mysqli_close($con);























 ?>
