<?php
    $day = $argv[1];
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $ppdate = date_create();
    $ppdate = date_modify($ppdate,"+$day days");
    $ppdate = date_format($ppdate,"Y-m-d");
    $map = array();
    $file = fopen('/var/www/html/filmdaily/maoyan_pre_paipian/result.txt','r');
    $mapfile = fopen('/var/www/html/filmdaily/maoyan_pre_paipian/key.txt','r');
    for($i=0; $i<10; $i++){
      $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
    }
    $number = array("0","1","2","3","4","5","6","7","8","9");
    $result = fread($file, filesize('/var/www/html/filmdaily/maoyan_pre_paipian/result.txt'));
    preg_match_all('/<li class=\'c1 lineDot\'>(.*)<\/li>/', $result, $filmname);
    preg_match_all('/<li class="c2 red"><i class="cs gsBlur">(.*)%<\/i><\/li>/', $result, $percent);
    preg_match_all('/<li class="c3 gray"><i class="cs gsBlur">(.*)场<\/i><\/li>/',$result, $changshu);
    $filmname = $filmname[1];
    $percent = $percent[1];
    $changshu = $changshu[1];
    $percent = str_ireplace($map, $number, $percent);
    //解码
    $changshu = str_ireplace($map, $number, $changshu);

    //去逗号&空格
    $changshu = str_ireplace(",", "", $changshu);
    $filmname = str_ireplace(" ", "", $filmname);

    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($filmname as $key => $value) {
        $hasFilm = mysqli_query($con, "select name from maoyan_pre_paipian where name='{$value}' and pptime='{$ppdate}' and time='{$date}';");
        if(mysqli_fetch_array($hasFilm) !=NULL){
            mysqli_query($con,"update maoyan_pre_paipian set goldern_percent='{$percent[$key]}', goldern_number='{$changshu[$key]}' where name='{$value}' and pptime='{$ppdate}' and time='{$date}';");
        }
        else {
            mysqli_query($con, "insert into maoyan_pre_paipian values('{$value}', '0', '0', '{$percent[$key]}', '{$changshu[$key]}', '{$ppdate}', '{$date}');");
        }
    }

    mysqli_close($con);

 ?>
