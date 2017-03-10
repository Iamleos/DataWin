<?php
for($i = 0; $i < 50; $i++){
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
//getdata
    $url = "http://baike.baidu.com/starflower/api/starflowerstarlist?rankType=thisWeek&pg=$i";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOSIGNAL, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result);
    //insert into datatbase

    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    foreach ($data->data->thisWeek  as $key => $value) {
        $name = $data->data->thisWeek[$key]->name;
        $flowers = (string)$data->data->thisWeek[$key]->oriScore;
        mysqli_query($con, "insert into baidu_baike_xianhuabang values('{$name}', '{$flowers}', '{$date}');");
    }
}
 ?>
