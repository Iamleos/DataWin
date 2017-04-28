<?php
    header("Content-type:text/html; charset=utf-8");
    //获取每部电影的编号
    date_default_timezone_set("Asia/Shanghai");
    $time = date("Y-m-d",time());
    $url = "http://piaofang.maoyan.com/?ver=normal";
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
    $file = fopen("/var/www/html/filmdaily_new/maoyan_shouzhong/filmname.txt","w");
    foreach ($filmnum as $key => $value) {
      fwrite($file, $filmname[$key]." ".$value."\n");
    }

    //受众信息插入数据库
    //////////////////////
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());
    $flag = 0;
    $filename = array();
    $filmnamefile = fopen("/var/www/html/filmdaily_new/maoyan_shouzhong/filmname.txt","r");
    while(!feof($filmnamefile)){
      $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
      $ch = curl_init("http://piaofang.maoyan.com/movie/".$value[1]."/wantindex");
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_NOSIGNAL, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $result = curl_exec($ch);
      curl_close($ch);
      preg_match_all('/<script id="pageData" type="application\/json">\s*(.*)\s*<\/script>/',$result,$data);
      preg_match_all('/<div class="stackcolumn-bar left" style="width:(.*)"><\/div>/',$result, $left);
      preg_match_all('/<div class="stackcolumn-bar right" style="width:(.*)"><\/div>/',$result, $right);
      preg_match_all('/<div class="linebars-desc">(.*)<\/div>\s*<div class="linebars-value">(.*)<\/div>/',$result, $city);
      $city_data = array("","","","");
      foreach($city[1] as $key2 => $value2){
	  switch ($value2){
		case "一线城市":
			$city_data[0] = str_replace('%','',$city[2][$key2]);
			break;
		case "二线城市":
                        $city_data[1] = str_replace('%','',$city[2][$key2]);
                        break;
		case "三线城市":
                        $city_data[2] = str_replace('%','',$city[2][$key2]);
                        break;
		case "四线城市":
                        $city_data[3] = str_replace('%','',$city[2][$key2]);
                        break;
	  }

      }
      if($left[0]!=NULL){
          $man = str_replace('%','',$left[1][0]);
          $woman = str_replace('%','',$right[1][0]);
	  $bachelor_or_above = str_replace('%','',$left[1][1]);
	  $bachelor_below = str_replace('%','',$right[1][1]);
          $data = $data[1][0];
          $data = json_decode($data,true);
          $ageData = $data["ageRatesChart"]["series"][0]["points"];
          $occupation = $data["occupChart"]["series"][0]["points"];
          $interest = $data["interestChart"]["series"][0]["points"];
          //数据类型转换
          foreach ($ageData as $key1 => $value1) {
              $ageData[$key1]["yPercent"] = (string)$value1["yPercent"];
          }
          foreach ($occupation as $key1 => $value1) {
              $occupation[$key1]["yPercent"] = (string)$value1["yPercent"];
          }
          foreach ($interest as $key1 => $value1) {
              $interest[$key1]["yPercent"] = (string)$value1["yPercent"];
          }
          var_dump($interest);
              mysqli_query($con, "insert into maoyan_shouzhong values('{$value[0]}','0','{$ageData[0]['yPercent']}','{$ageData[1]['yPercent']}','{$ageData[2]['yPercent']}',
             '{$ageData[3]['yPercent']}','{$ageData[4]['yPercent']}','{$ageData[5]['yPercent']}','{$man}','{$woman}','{$date}','{$bachelor_or_above}','{$bachelor_below}','{$city_data[0]}',
                '{$city_data[1]}','{$city_data[2]}','{$city_data[3]}','{$occupation[2]['yPercent']}','{$occupation[1]['yPercent']}','{$occupation[0]['yPercent']}','{$interest[3]['yPercent']}',
                '{$interest[0]['yPercent']}','{$interest[1]['yPercent']}','{$interest[2]['yPercent']}','{$interest[7]['yPercent']}','{$interest[4]['yPercent']}','{$interest[8]['yPercent']}',
                '{$interest[10]['yPercent']}','{$interest[11]['yPercent']}','{$interest[5]['yPercent']}','{$interest[6]['yPercent']}','{$interest[9]['yPercent']}');");

      }
      else {
          continue;
      }
    }
    mysqli_close($con);


?>
