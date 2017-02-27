<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d",time());

//获取数据库连接
//filmdaily数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $filmdaily=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($filmdaily,"set names utf8");
//movie数据库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="movie";
    $movie = mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($movie,"set names utf8");



//获取即将上映的电影
    date_default_timezone_set("Asia/Shanghai");
    $today = date("Y-m-d",time());
    $url = "http://piaofang.maoyan.com/store";
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
    preg_match_all('/<div class="title">.*<\/div>\s*([0-9,-]*).*\s*<p class="lineDot">/', $result, $showtime);
    preg_match_all('/<article class=\"indentInner canTouch\" data-com=\"hrefTo,href:\'\/movie\/(.*)\'\">/', $result, $filmnum);
    preg_match_all('/<div class="title">(.*)<\/div>/',$result,$filmname);
    $filmname = $filmname[1];
    $showtime = $showtime[1];
    $filmnum = $filmnum[1];
    $filmnametemp = NULL;
    $filmnumtemp = NULL;
    $flag = 0;
    foreach ($showtime as $key => $value) {
        $diff = date_diff(date_create($today),date_create($value));
        if($diff->format("%a") <= 30){
            $filmnametemp[$flag] = $filmname[$key];
            $filmnumtemp[$flag] = $filmnum[$key];
            $flag++;
        }
    }
    $filmname = $filmnametemp;
    $filmnum = $filmnumtemp;

//
foreach ($filmnum as $key => $value) {
        //获取电影咨询
    $ch = curl_init("http://piaofang.maoyan.com/movie/".$value);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/<p class="info-release">(.*)大陆上映<\/p>/',$result, $release);
    $release = $release[1][0];
    $former = mysqli_query($movie, "select time from maoyan where movie = '{$filmname[$key]}';");
    $former = mysqli_fetch_all($former, MYSQLI_ASSOC)[0]["time"];
    if ($release == $former) {
        var_dump("1");
        continue;
    }else {
        mysqli_query($movie, "update maoyan set time = '{$release}' where movie = '{$filmname[$key]}';");
        mysqli_query($filmdaily, "insert into film_change values('{$filmname[$key]}', '{$former}', '{$release}', '{$date}');");
        var_dump("2");
    }
}
?>
