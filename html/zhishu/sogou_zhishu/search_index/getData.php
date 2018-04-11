<?php
    include "/var/www/html/zhishu/sogou_zhishu/function_lib/lib.php";
    //获取指数名单
    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname;");
    $yirenname = mysqli_fetch_all($yirenname);
    mysqli_close($yirencon);
    $tvcon = DataWin\getDB("TV");
    $tvname = mysqli_fetch_all(mysqli_query($tvcon,"select name from search_list;"));
    mysqli_close($tvcon);
    $zycon = DataWin\getDB("zhishu");
    $zyname = mysqli_query($zycon,"select name from linshi_word;");
    $zyname = mysqli_fetch_all($zyname);
    mysqli_close($zycon);
    $othercon = DataWin\getDB("zhishu");
    $othername = mysqli_query($othercon,"select name from other_name;");
    $othername = mysqli_fetch_all($othername);
    mysqli_close($othercon);
    //获取代插入数据库
    $con = DataWin\getDB("zhishu");
    $date = DataWin\getDate();
    $yesterday = date_format(date_modify(date_create(date("Y-m-d")),'-1 day'),'Y-m-d');
    foreach ($filmname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_ALL&queryType=INPUT";
            $result = DataWin\getData($url);
            $total_data = json_decode($result);
            $flag++;
        } while (($total_data->success == false) && ($flag <= 3) );
        if (!$total_data->success) {
            continue;
        }

        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_PC&queryType=INPUT";
            $result = DataWin\getData($url);
            $pc_data = json_decode($result);
            $flag++;
        } while (($pc_data->success == false) && ($flag <= 3) );
        if (!$pc_data->success) {
            continue;
        }
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_WAP&queryType=INPUT";
            $result = DataWin\getData($url);
            $wap_data = json_decode($result);
            $flag++;
        } while (($wap_data->success == false) && ($flag <= 3) );
        if (!$wap_data->success) {
            continue;
        }

        $total_data = array_pop($total_data->data->pvList[0])->pv;
        $pc_data = array_pop($pc_data->data->pvList[0])->pv;
        $wap_data = array_pop($wap_data->data->pvList[0])->pv;
        mysqli_query($con, "insert into sogou_film_search values(
        '{$value[0]}','{$yesterday}','{$date}','{$total_data}','{$pc_data}','{$wap_data}'
        );");
        var_dump($value[0]."****电影");
        sleep(3);
    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_ALL&queryType=INPUT";
            $result = DataWin\getData($url);
            $total_data = json_decode($result);
            $flag++;
        } while (($total_data->success == false) && ($flag <= 3) );
        if (!$total_data->success) {
            continue;
        }

        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_PC&queryType=INPUT";
            $result = DataWin\getData($url);
            $pc_data = json_decode($result);
            $flag++;
        } while (($pc_data->success == false) && ($flag <= 3) );
        if (!$pc_data->success) {
            continue;
        }
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_WAP&queryType=INPUT";
            $result = DataWin\getData($url);
            $wap_data = json_decode($result);
            $flag++;
        } while (($wap_data->success == false) && ($flag <= 3) );
        if (!$wap_data->success) {
            continue;
        }

        $total_data = array_pop($total_data->data->pvList[0])->pv;
        $pc_data = array_pop($pc_data->data->pvList[0])->pv;
        $wap_data = array_pop($wap_data->data->pvList[0])->pv;
        mysqli_query($con, "insert into sogou_tv_search values(
        '{$value[0]}','{$yesterday}','{$date}','{$total_data}','{$pc_data}','{$wap_data}'
        );");
        var_dump($value[0]."****tv");
        sleep(3);
    }
    foreach ($yirenname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_ALL&queryType=INPUT";
            $result = DataWin\getData($url);
            $total_data = json_decode($result);
            $flag++;
        } while (($total_data->success == false) && ($flag <= 3) );
        if (!$total_data->success) {
            continue;
        }

        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_PC&queryType=INPUT";
            $result = DataWin\getData($url);
            $pc_data = json_decode($result);
            $flag++;
        } while (($pc_data->success == false) && ($flag <= 3) );
        if (!$pc_data->success) {
            continue;
        }
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_WAP&queryType=INPUT";
            $result = DataWin\getData($url);
            $wap_data = json_decode($result);
            $flag++;
        } while (($wap_data->success == false) && ($flag <= 3) );
        if (!$wap_data->success) {
            continue;
        }

        $total_data = array_pop($total_data->data->pvList[0])->pv;
        $pc_data = array_pop($pc_data->data->pvList[0])->pv;
        $wap_data = array_pop($wap_data->data->pvList[0])->pv;
        mysqli_query($con, "insert into sogou_yiren_search values(
        '{$value[0]}','{$yesterday}','{$date}','{$total_data}','{$pc_data}','{$wap_data}'
        );");
        var_dump($value[0]."****yiren");
        sleep(3);
    }

    foreach ($zyname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_ALL&queryType=INPUT";
            $result = DataWin\getData($url);
            $total_data = json_decode($result);
            $flag++;
        } while (($total_data->success == false) && ($flag <= 3) );
        if (!$total_data->success) {
            continue;
        }

        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_PC&queryType=INPUT";
            $result = DataWin\getData($url);
            $pc_data = json_decode($result);
            $flag++;
        } while (($pc_data->success == false) && ($flag <= 3) );
        if (!$pc_data->success) {
            continue;
        }
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_WAP&queryType=INPUT";
            $result = DataWin\getData($url);
            $wap_data = json_decode($result);
            $flag++;
        } while (($wap_data->success == false) && ($flag <= 3) );
        if (!$wap_data->success) {
            continue;
        }

        $total_data = array_pop($total_data->data->pvList[0])->pv;
        $pc_data = array_pop($pc_data->data->pvList[0])->pv;
        $wap_data = array_pop($wap_data->data->pvList[0])->pv;
        mysqli_query($con, "insert into sogou_zy_search values(
        '{$value[0]}','{$yesterday}','{$date}','{$total_data}','{$pc_data}','{$wap_data}'
        );");
        var_dump($value[0]."****zy");
        sleep(3);
    }

    foreach ($othername as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_ALL&queryType=INPUT";
            $result = DataWin\getData($url);
            $total_data = json_decode($result);
            $flag++;
        } while (($total_data->success == false) && ($flag <= 3) );
        if (!$total_data->success) {
            continue;
        }

        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_PC&queryType=INPUT";
            $result = DataWin\getData($url);
            $pc_data = json_decode($result);
            $flag++;
        } while (($pc_data->success == false) && ($flag <= 3) );
        if (!$pc_data->success) {
            continue;
        }
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/getRenderData?kwdNamesStr=".urlencode("$value[0]")."&timePeriodType=MONTH&dataType=SEARCH_WAP&queryType=INPUT";
            $result = DataWin\getData($url);
            $wap_data = json_decode($result);
            $flag++;
        } while (($wap_data->success == false) && ($flag <= 3) );
        if (!$wap_data->success) {
            continue;
        }

        $total_data = array_pop($total_data->data->pvList[0])->pv;
        $pc_data = array_pop($pc_data->data->pvList[0])->pv;
        $wap_data = array_pop($wap_data->data->pvList[0])->pv;
        mysqli_query($con, "insert into sogou_other_search values(
        '{$value[0]}','{$yesterday}','{$date}','{$total_data}','{$pc_data}','{$wap_data}'
        );");
        var_dump($value[0]."****other");
        sleep(3);
    }



 ?>
