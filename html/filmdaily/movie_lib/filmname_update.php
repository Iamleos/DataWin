<?php
    include "/var/www/html/filmdaily/lib_function/function.php";
    date_default_timezone_set("Asia/Shanghai");
    $yesterday = date_modify(date_create(date("Y-m-d")),"-1 day");
    $yesterday = date_format($yesterday,"Y-m-d");
    $con = getDB("filmdaily");



    //获取maoyan正在上映电影名字
    $myurl = "http://piaofang.maoyan.com/?ver=normal";
    $result = getResult($myurl);
    preg_match_all('/<li class=\'c1\'>\s*<b>(.*)<\/b>/', $result, $myfilmname);
    $myfilmname = $myfilmname[1];

    //获取gewala正在上映电影名字
    $gwlfilmname = array();
    for ($i=0; $i < 4; $i++) {
        $gwlurl = "https://www.gewara.com/movie/searchMovie.xhtml?pageNo=".$i;
        $result = getResult($gwlurl);
        preg_match_all('/<a href=".*" title=".*" target="_blank" class="color3">(.*)<\/a>/',$result, $name);
        $gwlfilmname = array_merge($gwlfilmname,$name[1]);
    }

    //获取专资办电影名字
    $zzbfilmname = array();
    $zzburl = "http://www.zgdypw.cn/pors/w/webStatisticsDatas/api/"."2017-07-26"."/searchDayBoxOffice";
    $result = getResult($zzburl);
    $result = json_decode($result,true)["data"]["top10Films"];
    foreach ($result as $key => $value) {
        array_push($zzbfilmname,$value["filmName"]);
    }

    //获取douban电影名字
    $dbfilmname = array();
    $dburl = "https://movie.douban.com/cinema/nowplaying/shanghai/";
    $result = getResult($dburl);
    preg_match('/正在上映([\s|\S]*)即将上映/', $result, $tmp);
    preg_match_all('/class="list-item.*\s*data-title="(.*)"/',$tmp[1],$name);
    $dbfilmname = $name[1];

    //获取yien电影名字
    $yefilmname = array();
    $yeurl = "http://www.cbooo.cn/movies";
    $result = getResult($yeurl);
    preg_match_all('/<h5 style="width:158px;" title="(.*)">/',$result,$name);
    foreach ($name[1] as $key => $value) {
        $name[1][$key] = str_ireplace("&#183;","·",$value);
    }
    $yefilmname = $name[1];
    //维护filmname
    $gwlname = array();
    $dbname = array();
    $zzbname = array();
    $yename = array();

    foreach ($myfilmname as $key => $value) {
        $gwlmax = 40;
        $gwlindex = NULL;
        $zzbmax = 50;
        $zzbindex = NULL;
        $dbmax = 40;
        $dbindex = NULL;
        $yemax = 40;
        $yeindex = NULL;
        //选出gwl与maoyan对应名字
        foreach ($gwlfilmname as $key1 => $value1) {
            similar_text($value,$value1,$gwlpercent);
            if ($gwlpercent > $gwlmax) {
                $gwlmax = $gwlpercent;
                $gwlindex = $key1;
            }
        }
        if ($gwlindex !== NULL) {
            array_push($gwlname,$gwlfilmname[$gwlindex]);
            $gwlfilmname[$gwlindex] = "";
        }
        else {
            array_push($gwlname,$value);
        }

        //选出db与maoyan对应名字
        foreach ($dbfilmname as $key1 => $value1) {
            similar_text($value,$value1,$dbpercent);
            if ($dbpercent > $dbmax) {
                $dbmax = $dbpercent;
                $dbindex = $key1;
            }
        }
        if ($dbindex !== NULL) {
            array_push($dbname,$dbfilmname[$dbindex]);
            $dbfilmname[$dbindex] = "";
        }
        else {
            array_push($dbname,$value);
        }

        //选出yien月maoyan对应名字
        foreach ($yefilmname as $key1 => $value1) {
            similar_text($value,$value1,$yepercent);
            if ($yepercent > $yemax) {
                $yemax = $yepercent;
                $yeindex = $key1;
            }
        }
        if ($yeindex !== NULL) {
            array_push($yename,$yefilmname[$yeindex]);
            $yefilmname[$yeindex] = "";
        }
        else {
            array_push($yename,$value);
        }
        //选出zzb与maoyan对应名字
        foreach ($zzbfilmname as $key1 => $value1) {
            similar_text($value,$value1,$zzbpercent);
            if ($zzbpercent > $zzbmax) {
                $zzbmax = $zzbpercent;
                $zzbindex = $key1;
            }
        }
        if ($zzbindex !== NULL) {
            array_push($zzbname,$zzbfilmname[$zzbindex]);
            $zzbfilmname[$zzbindex] = "";
        }
        else {
            array_push($zzbname,$value);
        }

    }

    mysqli_query($con,"update filmname set zzsy = 0;");
    foreach ($myfilmname as $key => $value) {
        $result = mysqli_query($con,"select myname from filmname where myname = '{$value}'");
        if ($result->num_rows != 0) {
            mysqli_query($con,"update filmname set
            mainname = '{$zzbname[$key]}',
            gwlname = '{$gwlname[$key]}',
            dbname = '{$dbname[$key]}',
            zzsy = 1,
            yename = '{$yename[$key]}'
            where myname = '{$value}';");
        }
        else{
            mysqli_query($con,"insert into filmname values(
            '{$zzbname[$key]}','{$value}','{$gwlname[$key]}','{$dbname[$key]}',0,'','',1,'{$yename[$key]}'
            );");
        }

    }










 ?>
