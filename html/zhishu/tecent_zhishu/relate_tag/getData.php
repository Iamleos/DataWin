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
    $info_time = date_create(date("Ymd"));
    $now_info_time = date_modify($info_time,"-1 day");
    $now_info_time = date_format($now_info_time,"Ymd");
    $last_info_time = date_modify($info_time,"-29 days");
    $last_info_time = date_format($info_time,"Ymd");
    $info_time  = $last_info_time."-".$now_info_time;

    foreach ($zyname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/relate_tag/getData.py $value[0] $last_info_time $now_info_time",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if (count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value;
        for ($i=0; $i < min(count($data),15); $i++) {
            mysqli_query($con, "insert into tx_zy_relate_tag values(
            '{$value[0]}', '{$time}','{$data[$i]->sTagName}',{$data[$i]->dRelateIndex},'{$data[$i]->iTagDelta}'
            );");

        }
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/relate_tag/getData.py $value[0] $last_info_time $now_info_time",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if (count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value;
        for ($i=0; $i < min(count($data),15); $i++) {
            mysqli_query($con, "insert into tx_film_relate_tag values(
            '{$value[0]}', '{$time}','{$data[$i]->sTagName}',{$data[$i]->dRelateIndex},'{$data[$i]->iTagDelta}'
            );");

        }
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/relate_tag/getData.py $value[0] $last_info_time $now_info_time",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if (count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value;
        for ($i=0; $i < min(count($data),15); $i++) {
            mysqli_query($con, "insert into tx_tv_relate_tag values(
            '{$value[0]}', '{$time}','{$data[$i]->sTagName}',{$data[$i]->dRelateIndex},'{$data[$i]->iTagDelta}'
            );");

        }
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/relate_tag/getData.py $value[0] $last_info_time $now_info_time",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if (count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value;
        for ($i=0; $i < min(count($data),15); $i++) {
            mysqli_query($con, "insert into tx_yiren_relate_tag values(
            '{$value[0]}', '{$time}','{$data[$i]->sTagName}',{$data[$i]->dRelateIndex},'{$data[$i]->iTagDelta}'
            );");

        }
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($othername as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/relate_tag/getData.py $value[0] $last_info_time $now_info_time",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if (count($data->data->value) == 0) {
                continue 2;
            }
        } while (0);
        $data = $data->data->value;
        for ($i=0; $i < min(count($data),15); $i++) {
            mysqli_query($con, "insert into tx_other_relate_tag values(
            '{$value[0]}', '{$time}','{$data[$i]->sTagName}',{$data[$i]->dRelateIndex},'{$data[$i]->iTagDelta}'
            );");

        }
        var_dump($value[0]);
        sleep(3);
    }


 ?>
