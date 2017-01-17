<?php
//从文件中读取dianyingzh名字
    date_default_timezone_set("Asia/Shanghai");
    $time = date("Y-m-d", time());
    $dianyingzhFilmName = array();
    $filmnamefile = fopen("/var/www/html/filmdaily/movie_lib/dianyingzh.txt","r");
    $flag = 0;
    while(!feof($filmnamefile)){
      $dianyingzhFilmName[$flag] = explode(" ",str_replace("\r\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($dianyingzhFilmName);
    fclose($filmnamefile);

    //从文件中读取maoyan名字
    $maoyanFilmName = array();
    $filmnamefile = fopen("/var/www/html/filmdaily/movie_lib/maoyan.txt","r");
    $flag = 0;
    while(!feof($filmnamefile)){
      $maoyanFilmName[$flag] = explode(" ",str_replace("\r\n","",fgets($filmnamefile)));
      $flag++;
    }
    array_pop($maoyanFilmName);
    fclose($filmnamefile);

//比对电影名字，没有的，需要加入maoyan
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="movie";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($dianyingzhFilmName as $key => $value) {
        $flag = 0;
        //对比dianyingzh和yien相似度分析
        foreach ($maoyanFilmName as $key1 => $value1) {
            similar_text($value[0],$value1[0],$percent);
            if($percent >= 80){
                $flag = 1;
                break;
            }
        }
        if($flag == 0){
            //搜索电影id
            $ch = curl_init("http://piaofang.maoyan.com/search?key=".urlencode($value[0]));
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<article class="indentInner canTouch" data-url="\/movie\/(.*)">/',$result,$id);
            //获取电影咨询
            $ch = curl_init("http://piaofang.maoyan.com/movie/".$id[1][0]);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<p class="info-category">\s*(.*)<!--\s*-->(.*)\s*<\/p>/',$result, $category);
            preg_match_all('/<p class="info-release">(.*)大陆上映<\/p>/',$result, $release);
            if ($category[0] == NULL) {
                $category = "NULL";
            }else {
                $category = $category[1][0].$category[2][0];
            }
            if ($release[0] == NULL) {
                $release = "NULL";
            }else {
                $release = $release[1][0];
            }
            preg_match_all('/<!-- 影人列表 -->([\s|\S]*)<!-- 技术参数 -->/',$result, $info);
            $info = $info[1][0];
            //查找daoyan，zhuyan，bianji，zhizuo，faxing，jianjie
            $pos['导演'] = strpos($info, "导演</h2>");
            $pos['演员'] = strpos($info, "演员</h2>");
            $pos['编剧'] = strpos($info, "编剧</h2>");
            $pos['制作'] = strpos($info, "制作</h2>");
            $pos['出品'] = strpos($info, "出品</h2>");
            $pos['发行'] = strpos($info, "发行</h2>");
            $pos['影片简介'] = strpos($info, "影片简介");
            $pos['其他'] = strpos($info, "其他</h2>");
            //daoyan
            if($pos['导演'] != false){
                if($pos['演员'] !=false){
                    preg_match_all('/导演([\S|\s]*)演员/',$info,$daoyan);
                    preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$daoyan[1][0],$daoyan);
                    $daoyan = $daoyan[1];
                }
                elseif ($pos['编剧'] !=false) {
                    preg_match_all('/导演([\S|\s]*)编剧/',$info,$daoyan);
                    preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$daoyan[1][0],$daoyan);
                    $daoyan = $daoyan[1];
                }else {
                    preg_match_all('/导演([\S|\s]*)/',$info,$daoyan);
                    preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$daoyan[1][0],$daoyan);
                    $daoyan = $daoyan[1];
                }
            }else {
                $daoyan = NULL;
            }
            //zhuyan
            if ($pos['演员']!= NULL) {
                if ($pos['编剧'] != NULL) {
                    preg_match_all('/演员([\S|\s]*)编剧/',$info,$zhuyan);
                    preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$zhuyan[1][0],$zhuyan);
                    $zhuyan = $zhuyan[1];
                }else {
                    preg_match_all('/演员([\S|\s]*)/',$info,$zhuyan);
                    preg_match_all('/<p class="title ellipsis-1">(.*)<\/p>/',$zhuyan[1][0],$zhuyan);
                    $zhuyan = $zhuyan[1];
                }
            }else {
                $zhuyan = NULL;
            }
            //zhipian
            if ($pos['制作'] != NULL) {
                if ($pos['发行'] != NULL) {
                    preg_match_all('/制作([\S|\s]*)发行/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }elseif ($pos['其他']!= NULL) {
                    preg_match_all('/制作([\S|\s]*)其他/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }else {
                    preg_match_all('/制作([\S|\s]*)/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }
            }elseif ($pos['出品'] != NULL) {
                if ($pos['发行'] != NULL) {
                    preg_match_all('/出品([\S|\s]*)发行/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }elseif ($pos['其他']!= NULL) {
                    preg_match_all('/出品([\S|\s]*)其他/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }else {
                    preg_match_all('/出品([\S|\s]*)/',$info,$zhipian);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$zhipian[1][0],$zhipian);
                    $zhipian = $zhipian[1];
                }
            }else {
                $zhipian = NULL;
            }
            //faxing
            if ($pos['发行'] != NULL) {
                if ($pos['其他']!= NULL) {
                    preg_match_all('/发行([\S|\s]*)其他/',$info,$faxing);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$faxing[1][0],$faxing);
                    $faxing = $faxing[1];
                }else {
                    preg_match_all('/发行([\S|\s]*)/',$info,$faxing);
                    preg_match_all('/<p class="title ellipsis-2">(.*)<\/p>/',$faxing[1][0],$faxing);
                    $faxing = $faxing[1];
                }
            }else {
                $faxing = NULL;
            }
            //jianjie
            if ($pos['影片简介'] != NULL) {
                preg_match_all('/<div class="detail-block-content">(.*)<\/div>/', $info, $jianjie);
                $jianjie = $jianjie[1];
            }else {
                $jianjie = NULL;
            }
            //合并数据
            //daoyan
            if ($daoyan != NULL) {
                $temp = NULL;
                foreach ($daoyan as $key2 => $value2) {
                    $temp .= $value2.';';
                }
                $daoyan = $temp;
            }
            else {
                $daoyan = "NULL";
            }
            //zhuyan
            if ($zhuyan != NULL) {
                $temp = NULL;
                foreach ($zhuyan as $key2 => $value2) {
                    $temp .= $value2.';';
                }
                $zhuyan = $temp;
            }
            else {
                $zhuyan = "NULL";
            }

            //$zhipian
            if ($zhipian != NULL) {
                $temp = NULL;
                foreach ($zhipian as $key2 => $value2) {
                    $temp .= $value2.';';
                }
                $zhipian = $temp;
            }
            else {
                $zhipian = "NULL";
            }

            //$faxing
            if ($faxing != NULL) {
                $temp = NULL;
                foreach ($faxing as $key2 => $value2) {
                    $temp .= $value2.';';
                }
                $faxing = $temp;
            }
            else {
                $faxing = "NULL";
            }

            //$jianjie
            if ($jianjie != NULL) {
                $temp = NULL;
                foreach ($jianjie as $key2 => $value2) {
                    $temp .= $value2.';';
                }
                $jianjie = $temp;
            }
            else {
                $jianjie = "NULL";
            }

            //插入数据库
            mysqli_query($con, "insert into maoyan values('{$value[0]}','{$daoyan}','{$zhuyan}','{$zhipian}','{$faxing}',
            '{$jianjie}','{$id[1][0]}','{$release}','{$category}');");
        }
    }

 ?>
