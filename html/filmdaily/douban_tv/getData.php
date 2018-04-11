<?php
/*
此脚本每周做一次，爬去前20页douban，更新douba_tv表中数据，主要是上映时间的更新
*/
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    for($i = 0 ; $i <10 ; $i++){
        $url = "https://movie.douban.com/j/search_subjects?type=tv&tag=%E5%9B%BD%E4%BA%A7%E5%89%A7&sort=time&page_limit=20&page_start=".$i*20;
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

        $result = json_decode($result);
        $tv = $result->subjects;
        foreach ($tv as $key => $value) {
            $hasTv = mysqli_query($con, "select name from douban_tv where name=\"{$value->title}\";");
            $rate = $value->rate;
            if(mysqli_fetch_array($hasTv) == NULL){
                $url = $value->url;
                //$url = "https://movie.douban.com/subject/26745143/?tag=%E5%9B%BD%E4%BA%A7%E5%89%A7&from=gaia_video";
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
                //提取数据
                preg_match_all('/rel="v:directedBy">(.{0,10})<\/a>/', $result,$daoyan);
                preg_match_all('/<span ><span class=\'pl\'>编剧<\/span>: <span class=\'attrs\'>(.*)<\/span><\/span><br\/>/', $result, $bianju);
                if ($bianju[1] == NULL){
                    $bianju[1] == NULL;
                }
                else {
                    preg_match_all('/<a href=".*?">(.{0,10})<\/a>/', $bianju[1][0], $bianju);
                }
                preg_match_all('/rel="v:starring">(.{0,10})<\/a>/', $result, $zhuyan);
                preg_match_all('/<span property="v:genre">(.{0,10})<\/span>/', $result, $type);
                preg_match_all('/<span class="pl">制片国家\/地区:<\/span>(.*)<br\/>/', $result, $region);
                preg_match_all('/<span class="pl">语言:<\/span>(.*)<br\/>/', $result, $language);
                preg_match_all('/<span class="pl">首播:<\/span> <span property="v:initialReleaseDate" content=".*">(.*)<\/span><br\/>/',$result, $firstShow);
                preg_match_all('/<span class="pl">集数:<\/span>(.*)<br\/>/', $result, $jishu);
                preg_match_all('/<span class="pl">单集片长:<\/span>(.*)<br\/>/', $result, $danjipianchang);
                preg_match_all('/<span class="pl">又名:<\/span>(.*)<br\/>/', $result, $youming);
                preg_match_all('/<a class="playBtn" data-cn=".*" data-source=".*"  href="javascript: void 0;">\s*(.*)\s*<\/a>/', $result, $playBtn);
                preg_match_all('/<span class="buylink-price"><span>\s*(.*)\s*<\/span><\/span>/', $result, $buylink);
                preg_match_all('/<a href="\/tag\/.*" class="">(.*)<\/a>/', $result, $tag);
                preg_match_all('/<div id="mainpic" class="">[\s\S]*?<img src="(.*?)" title/', $result, $image);

                //Imag
                if (count($image[1]) != 1){
                    $image = "NULL";
                }
                else {
                    $image = $image[1][0];
                }
                //整合数据
                //daoyan
                if (count($daoyan[1]) != 1) {
                    $temp = NULL;
                    foreach ($daoyan[1] as $key1 => $value1) {
                        $temp .= $value1.";";
                    }
                    $daoyan = $temp;
                }
                elseif (count($daoyan[1]) == 1) {
                    $daoyan = $daoyan[1][0];
                }
                else {
                    $daoyan = NULL;
                }
                //bianju
                if (count($bianju[1]) != 1) {
                    $temp = NULL;
                    foreach ($bianju[1] as $key1 => $value1) {
                        $temp .= $value1.";";
                    }
                    $bianju = $temp;
                }
                elseif (count($bianju[1]) == 1) {
                    $bianju = $bianju[1][0];
                }
                else {
                    $bianju = NULL;
                }
                //zhuyan
                if (count($zhuyan[1]) != 1) {
                    $temp = NULL;
                    foreach ($zhuyan[1] as $key1 => $value1) {
                        $temp .= $value1.";";
                    }
                    $zhuyan = $temp;
                }
                elseif (count($zhuyan[1]) == 1) {
                    $zhuyan = $zhuyan[1][0];
                }
                else {
                    $zhuyan = NULL;
                }
                //type
                if ($type[1] == NULL){
                    $type = "NULL";
                }
                else {
                    $type = $type[1][0];
                }
                //$region
                if ($region == NULL){
                    $region = "NULL";
                }
                else {
                    $region = $region[1][0];
                }
                //language
                if ($language[1] == NULL){
                    $language = "NULL";
                }
                else {
                    $language = $language[1][0];
                }
                //firstShow
                if ($firstShow[1] == NULL){
                    $firstShow = "NULL";
                    $zzsy = "0";
                }
                else {
                    $firstShow = $firstShow[1][0];
                    preg_match('/(.{0,10})/',$firstShow,$firstShowEx);
                    $diff = date_diff(date_create($date),date_create($firstShowEx[1]));
                    if ($diff->format("%a") <= 90) {
                        $zzsy = "1";
                    }
                    else {
                        $zzsy = "0";
                    }

                }
                //jishu
                if ($jishu[1] == NULL){
                    $jishu = "NULL";
                }
                else {
                    $jishu = $jishu[1][0];
                }
                //danjipianchang
                if ($danjipianchang[1] == NULL){
                    $danjipianchang = "NULL";
                }
                else {
                    $danjipianchang = $danjipianchang[1][0];
                }
                //youming
                if ($youming[1] == NULL){
                    $youming = "NULL";
                }
                else {
                    $youming = $youming[1][0];
                }

                //playBtn
                if (count($playBtn[1]) != 1) {
                    $temp = NULL;
                    foreach ($playBtn[1] as $key1 => $value1) {
                        $temp .= $value1."/".$buylink[1][$key1].";";
                    }
                    $playBtn = $temp;
                }
                elseif (count($playBtn[1]) == 1) {
                    $playBtn = $playBtn[1][0].$buylink[1][0];
                }
                else {
                    $playBtn = NULL;
                }

                //tag
                if (count($tag[1]) != 1) {
                    $temp = NULL;
                    foreach ($tag[1] as $key1 => $value1) {
                        $temp .= $value1.";";
                    }
                    $tag = $temp;
                }
                elseif (count($tag[1]) == 1) {
                    $tag = $tag[1][0];
                }
                else {
                    $tag = NULL;
                }
		mysqli_query($con, "insert into tv_list values(\"{$value->title}\",\"{$image}\",\"1\");");
                mysqli_query($con, "insert into douban_tv values(\"{$value->title}\", \"{$rate}\", \"国产剧\", \"NULL\",\"{$daoyan}\", \"{$bianju}\", \"{$zhuyan}\", \"{$type}\"
                                                                        , \"{$region}\", \"{$language}\", \"{$firstShow}\", \"{$jishu}\", \"{$danjipianchang}\"
                                                                        , \"{$youming}\", \"{$playBtn}\", \"{$tag}\",\"{$zzsy}\",\"1\",\"{$url}\");");
            }
            else {
                $url = $value->url;
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
                preg_match_all('/<span class="pl">首播:<\/span> <span property="v:initialReleaseDate" content=".*">(.*)<\/span><br\/>/',$result, $firstShow);
                if ($firstShow[1] == NULL){
                    $firstShow = "NULL";
                    $zzsy = "0";
                }
                else {
                    $firstShow = $firstShow[1][0];
                    preg_match('/(.{0,10})/',$firstShow,$firstShowEx);
                    $diff = date_diff(date_create($date),date_create($firstShowEx[1]));
                    if ($diff->format("%a") <= 90) {
                        $zzsy = "1";
                    }
                    else {
                        $zzsy = "0";
                    }

                }
                mysqli_query($con, "update douban_tv set zzsy=\"{$zzsy}\" where name=\"{$value->title}\";");


            }
            var_dump($value->title);
	        sleep(1);
        }

    }

 ?>
