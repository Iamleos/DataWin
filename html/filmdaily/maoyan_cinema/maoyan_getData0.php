<?php
  date_default_timezone_set("Asia/Shanghai");
  $date = date("Y-m-d");
  $map = array();
  $file = fopen('/var/www/html/filmdaily/maoyan_cinema/result.txt','r');
  $mapfile = fopen('/var/www/html/filmdaily/maoyan_cinema/key.txt','r');
  for($i=0; $i<10; $i++){
    $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
  }
  $number = array("0","1","2","3","4","5","6","7","8","9");
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyan_cinema/result.txt'));
  $acquittime = date("Y-m-d",time());
  preg_match_all("/<td>\d{0,2}<\/td>\s*<td>(.*)<\/td>/", $result, $cinema_name);
  preg_match_all("/<td><i class=\"cs gsBlur\">(.*)<\/i><\/td>/", $result, $data);
  $cinema_name = $cinema_name[1];
  $data = $data[1];
  $data = str_ireplace($map, $number, $data);
  for($i = 0;$i<count($cinema_name);$i++){
	if(strstr($data[$i*4],'万')){
		$data[$i*4] = str_replace('万','',$data[$i*4]);
	}
	elseif(strstr($data[$i*4],'亿')){
		$data[$i*4] = (string)(((float)str_replace('亿','',$data[$i*4]))*10000);
	}
	else{
		$data[$i*4] = (string)(((float)$data[$i*4])/10000);
	}
	if(strstr($data[$i*4+1],'万')){
                $data[$i*4+1] = (string)(((float)str_replace('万','',$data[$i*4+1]))*10000);
        }
  }

  var_dump($data);
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
    mysqli_query($con, "insert into maoyan_cinema(cinema_name, no, piaofang, total_people, per_people, single_boxoffice, time) values('{$cinema_name[0]}', '0', '{$data[0]}', '{$data[1]}', '{$data[2]}', '{$data[3]}', '{$date}')");
  mysqli_close($con);
 ?>
