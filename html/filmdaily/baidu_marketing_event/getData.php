<?php
    //从baidufilmID中获取需要采集的电影ID
    date_default_timezone_set("Asia/Shanghai");
    $date = date('Y-m-d');
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $film = mysqli_query($con, "select name,ID from baidufilmID where status=1;");
    $film = mysqli_fetch_all($film);
//获取数据
    foreach ($film as $key => $value) {
        //value[0] filmname; value[1] filmID
        //用户画像
        $url = "https://mdianying.baidu.com/api/rank/profile?sfrom=wise_shoubai&sub_channel=&c=315&cc=&lat=&lng=&kehuduan=&device=1_1904_&movieId=".$value[1];
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
        //data
        $male = (string)$data->data->result[0]->sexInfo->male;
        $female = (string)$data->data->result[0]->sexInfo->female;
        $region = $data->data->result[0]->region;
        foreach ($region as $key1 => $value1) {
            switch ($value1->province) {
                case '北京':
                    $beijing = (string)$value1->proportion;
                    break;
                case '上海':
                    $shanghai = (string)$value1->proportion;
                    break;
                case '天津':
                    $tianjing = (string)$value1->proportion;
                    break;
                case '重庆':
                    $chongqing = (string)$value1->proportion;
                    break;
                case '山东':
                    $shandong = (string)$value1->proportion;
                    break;
                case '浙江':
                    $zhejiang = (string)$value1->proportion;
                    break;
                case '江苏':
                    $jiangsu = (string)$value1->proportion;
                    break;
                case '福建':
                    $fujian = (string)$value1->proportion;
                    break;
                case '湖南':
                    $hunan = (string)$value1->proportion;
                    break;
                case '湖北':
                    $hubei = (string)$value1->proportion;
                    break;
                case '河南':
                    $henan = (string)$value1->proportion;
                    break;
                default:
                    break;
            }
        }

        sleep(1);
        //营销指数
        $url = "https://mdianying.baidu.com/api/rank/sale?sfrom=wise_shoubai&sub_channel=&c=315&cc=&lat=&lng=&kehuduan=&device=1_1904_&movieId=".$value[1];
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
        //data
        $search = $data[0]->search->all;
        $search = $search[count($search)-1]->volume;
        $trailer = $data[0]->trailer;
        $trailer = $trailer[count($trailer)-1]->volume;
        sleep(1);
        //口碑预期
        $url = "https://mdianying.baidu.com/api/rank/reputation?sfrom=wise_shoubai&sub_channel=&c=315&cc=&lat=&lng=&kehuduan=&device=1_1904_&movieId=".$value[1];
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
        //data
        if(count($data->result) == 0){
            var_dump($value[0]);
            mysqli_query($con, "insert into baidu_marketing_event values('{$value[0]}', '{$male}', '{$female}', '{$beijing}', '{$shanghai}', '{$tianjing}', '{$chongqing}'
                                                                       , '{$shandong}', '{$zhejiang}', '{$jiangsu}', '{$fujian}', '{$hunan}', '{$hubei}', '{$henan}'
                                                                       , '{$search}', '{$trailer}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '{$date}');");
        }
        else {
            $keywords = $data->result[0]->keywords;
            $keyword0 = $keywords[0]->keyword;
            $cnt0 = (string)$keywords[0]->cnt;
            $keyword1 = $keywords[1]->keyword;
            $cnt1 = (string)$keywords[1]->cnt;
            $keyword2 = $keywords[2]->keyword;
            $cnt2 = (string)$keywords[2]->cnt;
            $keyword3 = $keywords[3]->keyword;
            $cnt3 = (string)$keywords[3]->cnt;
            $keyword4 = $keywords[4]->keyword;
            $cnt4 = (string)$keywords[4]->cnt;
            $good = 0;
            $middle = 0;
            $bad = 0;
            foreach ($keywords as $key2 => $value2) {
                switch ($value2->type) {
                    case 1:
                        $good +=$value2->cnt;
                        break;
                    case 2:
                        $middle +=$value2->cnt;
                        break;
                    case 3:
                        $bad +=$value2->cnt;
                        break;
                }
            }
            var_dump($value[0]);
            mysqli_query($con, "insert into baidu_marketing_event values('{$value[0]}', '{$male}', '{$female}', '{$beijing}', '{$shanghai}', '{$tianjing}', '{$chongqing}'
                                                                       , '{$shandong}', '{$zhejiang}', '{$jiangsu}', '{$fujian}', '{$hunan}', '{$hubei}', '{$henan}'
                                                                       , '{$search}', '{$trailer}', '{$keyword0}', '{$cnt0}', '{$keyword1}', '{$cnt1}', '{$keyword2}'
                                                                       , '{$cnt2}', '{$keyword3}', '{$cnt3}', '{$keyword4}', '{$cnt4}', '{$good}', '{$middle}', '{$bad}'
                                                                       , '{$date}');");
        }
        sleep(1);
    }

    mysqli_close($con);



 ?>
