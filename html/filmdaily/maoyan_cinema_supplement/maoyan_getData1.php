<?php
//http://piaofang.maoyan.com/company/cinema?typeId=0&date=2016-12-22&webCityId=0&cityTier=0&page=2&noSum=1&cityName=%25E5%2585%25A8%25E5%259B%25BD
//http://piaofang.maoyan.com/company/cinema?typeId=0&date=2016-12-22&webCityId=0&cityTier=0&page=3&noSum=1&cityName=%25E5%2585%25A8%25E5%259B%25BD
//http://piaofang.maoyan.com/company/cinema?typeId=0&date=2016-12-22&webCityId=0&cityTier=0&page=4&noSum=1&cityName=%25E5%2585%25A8%25E5%259B%25BD
  $no = $argv[1]*50;
  date_default_timezone_set("Asia/Shanghai");
  $date=date("Y-m-d",strtotime("$argv[2]"));
  $file = fopen('/var/www/html/filmdaily/maoyan_cinema_supplement_supplement/result.txt','r');
  $result = fread($file, filesize('/var/www/html/filmdaily/maoyan_cinema_supplement_supplement/result.txt'));
  preg_match_all("/<td>(.*)<\/td>/", $result, $data);
  $data = $data[1];
  for($i = 0;$i<51;$i++){
	if(strstr($data[$i*6+2],'万')){
		$data[$i*6+2] = str_replace('万','',$data[$i*6+2]);
	}
	elseif(strstr($data[$i*6+2],'亿')){
		$data[$i*6+2] = (string)(((float)str_replace('亿','',$data[$i*6+2]))*10000);
	}
	else{
		$data[$i*6+2] = (string)(((float)$data[$i*6+2])/10000);
	}
	if(strstr($data[$i*6+3],'万')){
                $data[$i*6+3] = (string)(((float)str_replace('万','',$data[$i*6+3]))*10000);
        }
  }

  var_dump($data);
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  for($i = 0; $i < 51; $i++){
      if(($no>=50) && ($data[$i]==NULL)){
          continue;
      }
      else {
          mysqli_query($con, "insert into maoyan_cinema(cinema_name, no, piaofang, total_people, per_people, single_boxoffice, time) values('{$data[$i*6+1]}', '{$no}', '{$data[$i*6+2]}', '{$data[$i*6+3]}', '{$data[$i*6+4]}', '{$data[$i*6+5]}', '{$date}')");
          $no++;
      }
  }
  mysqli_close($con);
 ?>
