<?php
    include "/var/www/html/yiren/function_lib/lib.php";
    //获取指数名单

    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname where bc10 is NULL;");
    $yirenname = mysqli_fetch_all($yirenname);
    mysqli_close($yirencon);
    $tvcon = DataWin\getDB("TV");
    $tvname = mysqli_fetch_all(mysqli_query($tvcon,"select name from search_list;"));
    mysqli_close($tvcon);
    $zycon = DataWin\getDB("zhishu");
    $zyname = mysqli_query($zycon,"select name from linshi_word;");
    $zyname = mysqli_fetch_all($zyname);
    mysqli_close($zycon);

    //获取代插入数据库
    $con = DataWin\getDB("yiren");
    $time = DataWin\getDate();

    foreach ($filmname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/index/media/wechat?kwdNamesStr=".urlencode($value[0])."&timePeriodType=MONTH&dataType=MEDIA_WECHAT&queryType=INPUT";
            $result = DataWin\getData($url);
            if(strstr($result,"notFound")){
                continue 2;
            }
            $flag++;
            preg_match_all('/root.SG.data = (.*);\s*/',$result,$data);
        } while (count($data[1])==0 || $flag == 3 );
        $data = json_decode($data[1][0]);
        $wechat = (string)($data->infoList[0]->avgPv);
        $wechat_today = (string)(array_pop($data->pvList[0])->pv);

        mysqli_query($con,"insert into weixin_sogou values('{$value[0]}', '{$time}', '{$wechat}','电影','{$wechat_today}');");
        var_dump($value[0]."****电影");
        sleep(3);
    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/index/media/wechat?kwdNamesStr=".urlencode($value[0])."&timePeriodType=MONTH&dataType=MEDIA_WECHAT&queryType=INPUT";
            $result = DataWin\getData($url);
            if(strstr($result,"notFound")){
                continue 2;
            }
            $flag++;
            preg_match_all('/root.SG.data = (.*);\s*/',$result,$data);
        } while (count($data[1])==0 || $flag == 3 );
        $data = json_decode($data[1][0]);
        $wechat = (string)($data->infoList[0]->avgPv);
        var_dump($value[0]."****电视剧");
        mysqli_query($con,"insert into weixin_sogou values('{$value[0]}', '{$time}', '{$wechat}','电视剧','{$wechat_today}');");
        sleep(3);
    }

    foreach ($yirenname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/index/media/wechat?kwdNamesStr=".urlencode($value[0])."&timePeriodType=MONTH&dataType=MEDIA_WECHAT&queryType=INPUT";
            $result = DataWin\getData($url);
            if(strstr($result,"notFound")){
                continue 2;
            }
            $flag++;
            preg_match_all('/root.SG.data = (.*);\s*/',$result,$data);
        } while (count($data[1])==0 || $flag == 3 );
        $data = json_decode($data[1][0]);
        $wechat = (string)($data->infoList[0]->avgPv);
        var_dump($value[0]."****艺人");
        mysqli_query($con,"insert into weixin_sogou values('{$value[0]}', '{$time}', '{$wechat}','艺人','{$wechat_today}');");
        sleep(3);
    }

    foreach ($zyname as $key => $value) {
        $flag = 0;
        //当连接出错时，页面获取失败，此时重复请求，至多三次
        do {
            $url = "http://zhishu.sogou.com/index/media/wechat?kwdNamesStr=".urlencode($value[0])."&timePeriodType=MONTH&dataType=MEDIA_WECHAT&queryType=INPUT";
            $result = DataWin\getData($url);
            if(strstr($result,"notFound")){
                continue 2;
            }
            $flag++;
            preg_match_all('/root.SG.data = (.*);\s*/',$result,$data);
        } while (count($data[1])==0 || $flag == 3 );
        $data = json_decode($data[1][0]);
        $wechat = (string)($data->infoList[0]->avgPv);
        var_dump($value[0]."****艺人");
        mysqli_query($con,"insert into weixin_sogou values('{$value[0]}', '{$time}', '{$wechat}','综艺','{$wechat_today}');");
        sleep(3);
    }




 ?>
