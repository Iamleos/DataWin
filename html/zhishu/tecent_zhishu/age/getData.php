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
            exec("python /var/www/html/zhishu/tecent_zhishu/age/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $value_1_10 = $data->data->value[0]->lProfilePV;
        $rate_1_10 = (string)(($data->data->value[0]->dProfileRate)*100);
        $value_11_20 = $data->data->value[1]->lProfilePV;
        $rate_11_20 = (string)(($data->data->value[1]->dProfileRate)*100);
        $value_21_30 = $data->data->value[2]->lProfilePV;
        $rate_21_30 = (string)(($data->data->value[2]->dProfileRate)*100);
        $value_31_40 = $data->data->value[3]->lProfilePV;
        $rate_31_40 = (string)(($data->data->value[3]->dProfileRate)*100);
        $value_41_50 = $data->data->value[4]->lProfilePV;
        $rate_41_50 = (string)(($data->data->value[4]->dProfileRate)*100);
        $value_51_60 = $data->data->value[5]->lProfilePV;
        $rate_51_60 = (string)(($data->data->value[5]->dProfileRate)*100);
        $value_over_60 = $data->data->value[6]->lProfilePV;
        $rate_over_60 = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_zy_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$value_1_10}','{$rate_1_10}','{$value_11_20}','{$rate_11_20}',
        '{$value_21_30}','{$rate_21_30}','{$value_31_40}','{$rate_31_40}',
        '{$value_41_50}','{$rate_41_50}','{$value_51_60}','{$rate_51_60}',
        '{$value_over_60}','{$rate_over_60}'
        );");
        var_dump($value[0]);
        sleep(3);
    }


    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/age/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $value_1_10 = $data->data->value[0]->lProfilePV;
        $rate_1_10 = (string)(($data->data->value[0]->dProfileRate)*100);
        $value_11_20 = $data->data->value[1]->lProfilePV;
        $rate_11_20 = (string)(($data->data->value[1]->dProfileRate)*100);
        $value_21_30 = $data->data->value[2]->lProfilePV;
        $rate_21_30 = (string)(($data->data->value[2]->dProfileRate)*100);
        $value_31_40 = $data->data->value[3]->lProfilePV;
        $rate_31_40 = (string)(($data->data->value[3]->dProfileRate)*100);
        $value_41_50 = $data->data->value[4]->lProfilePV;
        $rate_41_50 = (string)(($data->data->value[4]->dProfileRate)*100);
        $value_51_60 = $data->data->value[5]->lProfilePV;
        $rate_51_60 = (string)(($data->data->value[5]->dProfileRate)*100);
        $value_over_60 = $data->data->value[6]->lProfilePV;
        $rate_over_60 = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_yiren_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$value_1_10}','{$rate_1_10}','{$value_11_20}','{$rate_11_20}',
        '{$value_21_30}','{$rate_21_30}','{$value_31_40}','{$rate_31_40}',
        '{$value_41_50}','{$rate_41_50}','{$value_51_60}','{$rate_51_60}',
        '{$value_over_60}','{$rate_over_60}'
        );");
        var_dump($value[0]);
        sleep(3);
    }
    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/age/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $value_1_10 = $data->data->value[0]->lProfilePV;
        $rate_1_10 = (string)(($data->data->value[0]->dProfileRate)*100);
        $value_11_20 = $data->data->value[1]->lProfilePV;
        $rate_11_20 = (string)(($data->data->value[1]->dProfileRate)*100);
        $value_21_30 = $data->data->value[2]->lProfilePV;
        $rate_21_30 = (string)(($data->data->value[2]->dProfileRate)*100);
        $value_31_40 = $data->data->value[3]->lProfilePV;
        $rate_31_40 = (string)(($data->data->value[3]->dProfileRate)*100);
        $value_41_50 = $data->data->value[4]->lProfilePV;
        $rate_41_50 = (string)(($data->data->value[4]->dProfileRate)*100);
        $value_51_60 = $data->data->value[5]->lProfilePV;
        $rate_51_60 = (string)(($data->data->value[5]->dProfileRate)*100);
        $value_over_60 = $data->data->value[6]->lProfilePV;
        $rate_over_60 = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_film_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$value_1_10}','{$rate_1_10}','{$value_11_20}','{$rate_11_20}',
        '{$value_21_30}','{$rate_21_30}','{$value_31_40}','{$rate_31_40}',
        '{$value_41_50}','{$rate_41_50}','{$value_51_60}','{$rate_51_60}',
        '{$value_over_60}','{$rate_over_60}'
        );");

        var_dump($value[0]);
        sleep(3);

    }
    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/age/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $value_1_10 = $data->data->value[0]->lProfilePV;
        $rate_1_10 = (string)(($data->data->value[0]->dProfileRate)*100);
        $value_11_20 = $data->data->value[1]->lProfilePV;
        $rate_11_20 = (string)(($data->data->value[1]->dProfileRate)*100);
        $value_21_30 = $data->data->value[2]->lProfilePV;
        $rate_21_30 = (string)(($data->data->value[2]->dProfileRate)*100);
        $value_31_40 = $data->data->value[3]->lProfilePV;
        $rate_31_40 = (string)(($data->data->value[3]->dProfileRate)*100);
        $value_41_50 = $data->data->value[4]->lProfilePV;
        $rate_41_50 = (string)(($data->data->value[4]->dProfileRate)*100);
        $value_51_60 = $data->data->value[5]->lProfilePV;
        $rate_51_60 = (string)(($data->data->value[5]->dProfileRate)*100);
        $value_over_60 = $data->data->value[6]->lProfilePV;
        $rate_over_60 = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_tv_age values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$value_1_10}','{$rate_1_10}','{$value_11_20}','{$rate_11_20}',
        '{$value_21_30}','{$rate_21_30}','{$value_31_40}','{$rate_31_40}',
        '{$value_41_50}','{$rate_41_50}','{$value_51_60}','{$rate_51_60}',
        '{$value_over_60}','{$rate_over_60}'
        );");

        var_dump($value[0]);
        sleep(3);
    }


 ?>
