<?php
    include "/var/www/html/zhishu/tecent_zhishu/function_lib/lib.php";

    //获取采集名单

    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname ;");
    $yirenname = mysqli_fetch_all($yirenname);
    $zyname = mysqli_query($yirencon,"select me from actname where bc10 ='综艺';");
    $zyname = mysqli_fetch_all($zyname);
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
    $info_time = date_create(date("Ym"));
    $now_info_time = date_format($info_time,"Ym");
    $last_info_time = date_modify($info_time,"-1 month");
    $last_info_time = date_format($info_time,"Ym");
    $info_time  = $last_info_time."-".$now_info_time;

    foreach ($zyname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/sex/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $male_value = (string)($data->data->value[0]->lProfilePV);
        $male_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $female_value = (string)($data->data->value[1]->lProfilePV);
        $female_rate = (string)(($data->data->value[1]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_zy_sex values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$male_value}','{$male_rate}','{$female_value}','{$female_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }


    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/sex/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $male_value = (string)($data->data->value[0]->lProfilePV);
        $male_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $female_value = (string)($data->data->value[1]->lProfilePV);
        $female_rate = (string)(($data->data->value[1]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_yiren_sex values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$male_value}','{$male_rate}','{$female_value}','{$female_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/sex/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $male_value = (string)($data->data->value[0]->lProfilePV);
        $male_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $female_value = (string)($data->data->value[1]->lProfilePV);
        $female_rate = (string)(($data->data->value[1]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_film_sex values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$male_value}','{$male_rate}','{$female_value}','{$female_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/sex/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $male_value = (string)($data->data->value[0]->lProfilePV);
        $male_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $female_value = (string)($data->data->value[1]->lProfilePV);
        $female_rate = (string)(($data->data->value[1]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_tv_sex values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$male_value}','{$male_rate}','{$female_value}','{$female_rate}'
        );");

        var_dump($value[0]);

        sleep(3);
    }

    foreach ($othername as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/sex/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $male_value = (string)($data->data->value[0]->lProfilePV);
        $male_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $female_value = (string)($data->data->value[1]->lProfilePV);
        $female_rate = (string)(($data->data->value[1]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_other_sex values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$male_value}','{$male_rate}','{$female_value}','{$female_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }


 ?>
