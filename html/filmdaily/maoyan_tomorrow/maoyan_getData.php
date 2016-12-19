<?php
  date_default_timezone_set("Asia/Shanghai");
  $map = array();
  $file = fopen('/var/www/html/filmdaily/maoyan_tomorrow/result.txt','r');
  $mapfile = fopen('/var/www/html/filmdaily/maoyan_tomorrow/key.txt','r');
  for($i=0; $i<10; $i++){
    $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
  }
  $number = array("0","1","2","3","4","5","6","7","8","9");
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyan_tomorrow/result.txt'));
  $acquitime = date("Y-n-d",time());
  preg_match_all("/<li class='c1 lineDot'>(.*)<\/li>/", $result, $filmname);
  preg_match_all('/<li class="c2 red"><i class="cs gsBlur">(.*)<\/i><\/li>/', $result, $rate);
  $filmname = $filmname[1];
  $rate = $rate[1];
  $rate = str_ireplace($map, $number, $rate);
  $rate = str_replace('%', '', $rate);
  foreach ($rate as $key => $value) {
      $rate[$key] = (float)$value;
  }
  var_dump($filmname);
  var_dump($rate);
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  mysqli_query($con, "drop table if exists maoyanpiaofang");
  mysqli_query($con, "create table maoyan_tomorrow(mname varchar(30), mrate decimal(4,1), macquitime date);");
  for($i = 0; $i < count($filmname); $i++){
      mysqli_query($con, "insert into maoyan_tomorrow(mname, mrate,macquitime) values('{$filmname[$i]}','{$rate[$i]}','{$acquitime}');");
  }
  mysqli_close($con);























 ?>
