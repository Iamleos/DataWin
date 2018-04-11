<?php
    include "/var/www/html/zhishu/tecent_zhishu/function_lib/lib.php";

    //获取采集名单

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

    //获取代插入库
    $con = DataWin\getDB("zhishu");
    $time = DataWin\getDate();
    $info_time = DataWin\getDate("-1");
    $info_date = str_ireplace("-","",$info_time);

    foreach ($zyname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/index/getData.py $value[0] $info_date",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->tagExists == false ||  count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value[0];
        $zonghe = (string)($data->lTagIndex);
        $zimeiti = (string)($data->lWXIndex);
        $chuantongmeiti = (string)($data->lDomainIndex);
        $sousuo = (string)($data->lQVIndex);

        mysqli_query($con ,"insert into tx_zy_index values(
        '{$value[0]}','{$time}','{$info_time}','{$zonghe}','{$zimeiti}','{$chuantongmeiti}','{$sousuo}'
        );");
        var_dump($value[0]);
        sleep(3);
    }


    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/index/getData.py $value[0] $info_date",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->tagExists == false ||  count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value[0];
        $zonghe = (string)($data->lTagIndex);
        $zimeiti = (string)($data->lWXIndex);
        $chuantongmeiti = (string)($data->lDomainIndex);
        $sousuo = (string)($data->lQVIndex);

        mysqli_query($con ,"insert into tx_yiren_index values(
        '{$value[0]}','{$time}','{$info_time}','{$zonghe}','{$zimeiti}','{$chuantongmeiti}','{$sousuo}'
        );");
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/index/getData.py $value[0] $info_date",$json);
            $data = json_decode($json[0]);
            var_dump($data);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->tagExists == false ||  count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);

        $data = $data->data->value[0];
        $zonghe = (string)($data->lTagIndex);
        $zimeiti = (string)($data->lWXIndex);
        $chuantongmeiti = (string)($data->lDomainIndex);
        $sousuo = (string)($data->lQVIndex);

        mysqli_query($con ,"insert into tx_film_index values(
        '{$value[0]}','{$time}','{$info_time}','{$zonghe}','{$zimeiti}','{$chuantongmeiti}','{$sousuo}'
        );");
        var_dump($value[0]);
        sleep(3);

    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/index/getData.py $value[0] $info_date",$json);
            $data = json_decode($json[0]);
            var_dump($data);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->tagExists == false ||  count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value[0];
        $zonghe = (string)($data->lTagIndex);
        $zimeiti = (string)($data->lWXIndex);
        $chuantongmeiti = (string)($data->lDomainIndex);
        $sousuo = (string)($data->lQVIndex);

        mysqli_query($con ,"insert into tx_tv_index values(
        '{$value[0]}','{$time}','{$info_time}','{$zonghe}','{$zimeiti}','{$chuantongmeiti}','{$sousuo}'
        );");
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($othername as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/index/getData.py $value[0] $info_date",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->tagExists == false ||  count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value[0];
        $zonghe = (string)($data->lTagIndex);
        $zimeiti = (string)($data->lWXIndex);
        $chuantongmeiti = (string)($data->lDomainIndex);
        $sousuo = (string)($data->lQVIndex);

        mysqli_query($con ,"insert into tx_other_index values(
        '{$value[0]}','{$time}','{$info_time}','{$zonghe}','{$zimeiti}','{$chuantongmeiti}','{$sousuo}'
        );");
        var_dump($value[0]);
        sleep(3);
    }

 ?>
