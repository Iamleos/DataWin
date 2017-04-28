<?php
include "../maoyan_crack/getWoff.php";
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  date_default_timezone_set("Asia/Shanghai");
  $date = (string)date("Y-m-d",time());
  $flag = 0;
  $filename = array();
  $filmnamefile = fopen("/var/www/html/filmdaily_new/maoyan_want_see/filmname.txt","r");
  while(!feof($filmnamefile)){
    $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
    $flag++;
  }
  array_pop($filmname);
  foreach ($filmname as $key => $value) {
      $url = "http://piaofang.maoyan.com/movie/".$value[1]."?_v_=yes";
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
    preg_match_all('/<style id="js-nuwa">\s*@font-face\{ font-family:"cs";src:url\(data:application\/font-woff;charset=utf-8;base64,(.*)\) format\("woff"\);}/', $result, $ttf);
    if($ttf[1]!=0){
        preg_match_all('/<span class="wish-num ">\s*<i class="cs gsBlur">(.*)<\/i>/' ,$result, $wantSee);
        preg_match_all('/<span class="topic-value">(.*)<\/span>/',$result,$weibo_dis);
        $weibo_dis = $weibo_dis[1][0];
        $wantSee = $wantSee[1];
        if(strstr($weibo_dis,"万")){
            $weibo_dis = str_replace("万","",$weibo_dis);
            $weibo_dis = (string)($weibo_dis*10000);
        }
        getWoff($url,__DIR__);
        sleep(3);
        shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily_new/maoyan_want_see maoyan");
        sleep(3);
        $map = array();
        $mapfile = fopen('/var/www/html/filmdaily_new/maoyan_want_see/key.txt','r');
        for($i=0; $i<10; $i++){
          $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
        }
        $number = array("0","1","2","3","4","5","6","7","8","9");
        $wantSee = (int)(str_ireplace($map, $number, $wantSee)[0]);
    }
    else {
        $wantSee = "fail";
        $weibo_dis = "fail";
    }
    //预售
    $url = "http://piaofang.maoyan.com/movie/".$value[1]."/boxshow";
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
    preg_match_all('/<style id="js-nuwa">\s*@font-face\{ font-family:"cs";src:url\(data:application\/font-woff;charset=utf-8;base64,(.*)\) format\("woff"\);}/', $result, $ttf);
    //判断是否加密
    if($ttf[1]!=0){
        preg_match_all('/<div class="t-col"><i class="cs gsBlur">(.*)<\/i><\/div>/',$result,$presell);
        $presell = $presell[1];
        for($i = 0;$i<count($presell)/8;$i++){
            $temp[$i] = $presell[0+8*$i];
        }
        $presell = $temp;
        preg_match_all('/<div class="t-col"><span(.*)<\/div>/', $result, $dianying);
        $dianying = $dianying[0];
        $index = array();
        foreach ($dianying as $key1 => $value1) {
            if (strstr($value1, '点映')){
                array_push($index, $key1);
            }
            else {
                continue;
            }
        }
        getWoff($url,__DIR__);
        sleep(3);
        shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily_new/maoyan_want_see maoyan");
        sleep(3);
        $map = array();
        $mapfile = fopen('/var/www/html/filmdaily_new/maoyan_want_see/key.txt','r');
        for($i=0; $i<10; $i++){
          $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
        }
        $number = array("0","1","2","3","4","5","6","7","8","9");
        $presell = str_ireplace($map, $number, $presell);
        $sum = 0;
        $dysum = 0;
        foreach ($presell as $key1 => $value1) {
            if($value1 != "--"){
                $sum += $value1;
            }
            else {
                continue;
            }
        }
        foreach ($index as $key1 => $value1) {
            $dysum += $presell[$value1];
        }
        $dianying = $dysum;
        $presell = $sum;
    }
    else {
        $presell = "fail";
    }
    mysqli_query($con, "insert into maoyan_want_see values('{$value[0]}','{$wantSee}','{$date}','{$presell}','{$weibo_dis}','{$dianying}');");






  }
  mysqli_close($con);
 ?>
