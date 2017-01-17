<?php
    date_default_timezone_set("Asia/Shanghai");
    $acquittime = date("Y-m-d",strtotime("$argv[1]"));
    $file = fopen('/var/www/html/filmdaily/maoyan_ri_piaofang_supplement/result.txt','r');
    $result = fread($file, filesize('/var/www/html/filmdaily/maoyan_ri_piaofang_supplement/result.txt'));
    preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $filmname);
    preg_match_all('/<em style=\"margin-left: .1rem\">(.*)<\/em>/', $result, $sumboxoffice);
    preg_match_all('/<li class=\"c2 \">\s*<b>(.*)<\/b><br\/>/', $result, $boxoffice);
    preg_match_all('/<li class=\"c3 \">(.*)<\/li>/', $result, $boxofficerate);
    preg_match_all('/<li class=\"c4 \">\s*(.*)\s*<\/li>/',$result, $rowpiecerate);
    preg_match_all('/<li class=\"c5 \">\s*<span style=\"margin-right:-.1rem\">\s*(.*)\s*<\/span>/', $result, $seatrate);
    $filmname = $filmname[1];
    $sumboxoffice = $sumboxoffice[1];
    $boxoffice = $boxoffice[1];
    $boxofficerate = $boxofficerate[1];
    $rowpiecerate = $rowpiecerate[1];
    $seatrate = $seatrate[1];
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
    var_dump($sumboxoffice);
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    for($i = 0; $i < count($filmname); $i++){
        mysqli_query($con, "insert into maoyan_ri_piaofang(name, piaofang, piaofang_rate, paipian_rate, people_per_session, time) values('{$filmname[$i]}', '{$boxoffice[$i]}', '{$boxofficerate[$i]}', '{$rowpiecerate[$i]}', '{$seatrate[$i]}', '{$acquittime}')");
        var_dump($filmname[$i]);
        var_dump($boxoffice[$i]);
    }
    mysqli_close($con);

 ?>
