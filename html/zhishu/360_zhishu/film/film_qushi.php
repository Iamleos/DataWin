<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $data_date = date_create($date);
    date_modify($data_date,"-1 days");
    $data_date = date_format($data_date,"Y-m-d");
    //提取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $film = mysqli_query($con, "select mainname from filmname where zzsy=1");
    $film = mysqli_fetch_all($film);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($film as $key => $value) {
        $filmname = $value[0];
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/360_zhishu/python/getData_qushi.py '{$filmname}'",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->msg == "no result") {
                continue 2;
            }
        } while (0);
        $indexdata = $data->data[0];
        //如果没有数据，跳过该关键词
        if($indexdata == NULL){
            continue;
        }
        $monthindex = $indexdata->data->month_index;
        $week_index = $indexdata->data->week_index;
        $month_year_ratio = $indexdata->data->month_year_ratio; //同比
        $month_chain_ratio = $indexdata->data->month_chain_ratio; //环比
        //搜索指数趋势
        /*
        $url = "http://index.haosou.com/index/soIndexJson?area=%E5%85%A8%E5%9B%BD&q=".$url_name;
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

        $indexdata = json_decode($result)->data->index;
        $indexdata = json_decode(json_encode($indexdata),true);
        $indexdata = array_values($indexdata)[0];
        $indexdata = explode("|",$indexdata);
        $search_lastday = array_pop($indexdata); //搜索趋势——昨天

        //媒体关注度
        $url = "http://index.haosou.com/index/soMediaJson?q=".$url_name;
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

        $indexdata = json_decode($result)->data->media;
        $indexdata = json_decode(json_encode($indexdata),true);
        $indexdata = array_values($indexdata)[0];
        $indexdata = explode("|",$indexdata);
        $media_lastday = array_pop($indexdata); //媒体关注度——昨天
        */
        //入库
        mysqli_query($con, "insert into 360_film_qushi values('{$filmname}', '{$monthindex}','{$week_index}','{$month_year_ratio}','{$month_chain_ratio}','NULL',
                                                                'NULL','{$data_date}','{$date}');");
        var_dump($filmname);
        sleep(1);
    }
    mysqli_close($con);

 ?>
