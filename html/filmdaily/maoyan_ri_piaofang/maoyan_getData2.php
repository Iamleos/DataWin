<?php
  $file = fopen('/home/leos/work/maoyan_ri_piaofang/result.txt','r');
  $result = fread($file, filesize('/home/leos/work/maoyan_ri_piaofang/result.txt'));
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
  for($i = 0; $i < count($filmname); $i++){
    mysqli_query($con, "insert into maoyan_ri_piaofang(name, piaofang, piaofang_rate, paipian_rate, people_per_session, time,) values('{$filmname[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$acquittime}')");
  }
  mysqli_close($con);























 ?>
