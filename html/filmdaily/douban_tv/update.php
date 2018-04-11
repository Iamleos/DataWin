<?php
/*
	此脚本用于更新tv_name 里面的href
*/
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $shortName = mysqli_query($con, "select name from tv_name where href is NULL;");
    $shortName = mysqli_fetch_all($shortName);
    foreach ($shortName as $key => $value) {
        $href_array = array();
        //下面的循化是为了补充采集失败请求的，最多请求三次，
        for($i = 0; $i< 3; $i++){
            $url = "https://movie.douban.com/subject_search?search_text=".urlencode($value[0])."&cat=1002";
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
            if ($result == false || $result == NULL ||$result == "") {
                 continue;
            }
            preg_match_all('/<table width="100%" class="">([\s\S]*?)<\/table>/', $result,$element);
            foreach ($element[1] as $key1 => $value1) {
                //如果是电影，pass
                if (strpos($value1,"电影") !=false) {
                    continue;
                }
                //如果上映时间过久，pass
                preg_match_all('/<a href="(.*)" onclick=&#34;moreurl/',$value1,$href);
                $url = $href[1][0];
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
                if (count($firstShow[1]) == 0) {
                    array_push($href_array,$href[1][0]);
                    break 2;
                }
                preg_match('/(.{0,10})/',$firstShow[1][0],$firstShowEx);
                if (strlen($firstShowEx[1]) >= 10 && strpos($firstShowEx[1],'(') ==false) {
                    $diff = date_diff(date_create($date),date_create($firstShowEx[1]));
                    if ($diff->format("%a")>360) {
                        continue;
                    }
                    else {
                        array_push($href_array,$href[1][0]);
                        break 2;
                    }
                }
                else {
                    array_push($href_array,$href[1][0]);
                    break 2;
                }
            }
        }
        if(count($href_array) == 0){
            mysqli_query($con,"delete from tv_name  where name=\"{$value[0]}\";");
        }
        mysqli_query($con,"update tv_name set href= \"{$href[1][0]}\" where name=\"{$value[0]}\";");
        var_dump($value[0]);
        sleep(1);
    }

 ?>
