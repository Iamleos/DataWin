<?php
    header("Content-type:text/html; charset=utf-8");
    //获取每部电影的编号
    date_default_timezone_set("Asia/Shanghai");
    $url = "http://piaofang.maoyan.com";
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
    $file = fopen("/var/www/html/filmdaily/maoyan_shouzhong/filmname.txt","w");
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
    $filmnamefile = fopen("/var/www/html/filmdaily/maoyan_shouzhong/filmname.txt","r");
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
      preg_match_all('/<div class="stackcolumn-bar left" style="width:(.*)"><\/div>/',$result, $man);
      preg_match_all('/<div class="stackcolumn-bar right" style="width:(.*)"><\/div>/',$result, $woman);
      if($man[0]!=NULL){
          $man = str_replace('%','',$man[1][0]);
          $woman = str_replace('%','',$woman[1][0]);
          $data = $data[1][0];
          $data = json_decode($data,true);
          $ageData = $data["ageRatesChart"]["series"][0]["points"];
          //数据类型转换
          foreach ($ageData as $key1 => $value1) {
              $ageData[$key1]["yPercent"] = (string)$value1["yPercent"];
          }
          $numberRow = mysqli_query($con, 'select * from maoyan_shouzhong where name="'.$value[0].'";');
          if(!$numberRow->num_rows){
              mysqli_query($con, "insert into maoyan_shouzhong values('{$value[0]}','0','{$ageData[0]['yPercent']}','{$ageData[1]['yPercent']}','{$ageData[2]['yPercent']}',
              '{$ageData[3]['yPercent']}','{$ageData[4]['yPercent']}','{$ageData[5]['yPercent']}','{$man}','{$woman}');");
          }
      }
      else {
          continue;
      }
    }
    mysqli_close($con);


?>
