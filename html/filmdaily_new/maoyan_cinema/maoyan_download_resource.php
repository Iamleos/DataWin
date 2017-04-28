<?php
    include "../maoyan_crack/getWoff.php";
//http://piaofang.maoyan.com/company/cinema?date=2016-12-21&webCityId=0&cityTier=1&page=1&cityName=%E4%B8%80%E7%BA%BF%E5%9F%8E%E5%B8%82
$field = array(
    "全国"=>array(
        "webCityId"=>'0',
        "cityTier"=>'1',
        "page"=>'1',
        "cityName"=>'',
    ),
    "一线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'1',
        "page"=>'1',
        "cityName"=>'',
    ),
    "二线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'2',
        "page"=>'1',
        "cityName"=>'',
    ),
    "三线城市"=>array(
        "webCityId"=>'0',
        "cityTier"=>'3',
        "page"=>'1',
        "cityName"=>'',
    ),
    "北京"=>array(
        "webCityId"=>'1',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "上海"=>array(
        "webCityId"=>'10',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "广州"=>array(
        "webCityId"=>'20',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "深圳"=>array(
        "webCityId"=>'30',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "重庆"=>array(
        "webCityId"=>'45',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "杭州"=>array(
        "webCityId"=>'50',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "南京"=>array(
        "webCityId"=>'55',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "武汉"=>array(
        "webCityId"=>'57',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "成都"=>array(
        "webCityId"=>'59',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
    "苏州"=>array(
        "webCityId"=>'80',
        "cityTier"=>'0',
        "page"=>'1',
        "cityName"=>'',
    ),
);

foreach ($field as $key => $value) {
    if($key == "全国"){
        for($i=0; $i<4; $i++){
            date_default_timezone_set("Asia/Shanghai");
            $date = date_create(date("Y-m-d"));
            $date = date_sub($date,date_interval_create_from_date_string("1 days"));
            $date = date_format($date, "Y-m-d");
            $page = $i+1;
            $url = "http://piaofang.maoyan.com/company/cinema?date=".$date."&webCityId={$value['webCityId']}&cityTier={$value['cityTier']}&page={$page}&cityName=".urlencode($key);
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
            $file = fopen('/var/www/html/filmdaily_new/maoyan_cinema/result.txt','w');
            fwrite($file, $result);
            preg_match_all('/<style id="js-nuwa">\s*@font-face\{ font-family:"cs";src:url\(data:application\/font-woff;charset=utf-8;base64,(.*)\) format\("woff"\);}/', $result, $ttf);
            if(count($ttf[1])==0){
                shell_exec("php /var/www/html/filmdaily_new/maoyan_cinema/maoyan_getData1.php $i");
                var_dump("1");
            }
            else {
                getWoff($url,__DIR__);
                sleep(3);
                shell_exec("/home/jdk.bin/java -classpath /var/www/html/filmdaily_new/maoyan_cinema maoyan");
                sleep(3);
                shell_exec("php /var/www/html/filmdaily_new/maoyan_cinema/maoyan_getData.php $i");
                var_dump("2");

            }
        }
    }
    else {
    }
}

 ?>
