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
            exec("python /var/www/html/zhishu/tecent_zhishu/education/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $primary_school_value = $data->data->value[0]->lProfilePV;
        $primary_school_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $middle_school_value = $data->data->value[1]->lProfilePV;
        $middle_school_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $high_school_value = $data->data->value[2]->lProfilePV;
        $high_school_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $dazhuan_value = $data->data->value[3]->lProfilePV;
        $dazhuan_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $bachelor_value = $data->data->value[4]->lProfilePV;
        $bachelor_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $master_value = $data->data->value[5]->lProfilePV;
        $master_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $doctor_value = $data->data->value[6]->lProfilePV;
        $doctor_rate = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_zy_education values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$primary_school_value}','{$primary_school_rate}','{$middle_school_value}','{$middle_school_rate}',
        '{$high_school_value}','{$high_school_rate}','{$dazhuan_value}','{$dazhuan_rate}',
        '{$bachelor_value}','{$bachelor_rate}','{$master_value}','{$master_rate}',
        '{$doctor_value}','{$doctor_rate}'
        );");

        var_dump($value[0]);
        sleep(3);
    }



    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/education/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $primary_school_value = $data->data->value[0]->lProfilePV;
        $primary_school_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $middle_school_value = $data->data->value[1]->lProfilePV;
        $middle_school_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $high_school_value = $data->data->value[2]->lProfilePV;
        $high_school_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $dazhuan_value = $data->data->value[3]->lProfilePV;
        $dazhuan_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $bachelor_value = $data->data->value[4]->lProfilePV;
        $bachelor_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $master_value = $data->data->value[5]->lProfilePV;
        $master_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $doctor_value = $data->data->value[6]->lProfilePV;
        $doctor_rate = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_yiren_education values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$primary_school_value}','{$primary_school_rate}','{$middle_school_value}','{$middle_school_rate}',
        '{$high_school_value}','{$high_school_rate}','{$dazhuan_value}','{$dazhuan_rate}',
        '{$bachelor_value}','{$bachelor_rate}','{$master_value}','{$master_rate}',
        '{$doctor_value}','{$doctor_rate}'
        );");

        var_dump($value[0]);
        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/education/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $primary_school_value = $data->data->value[0]->lProfilePV;
        $primary_school_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $middle_school_value = $data->data->value[1]->lProfilePV;
        $middle_school_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $high_school_value = $data->data->value[2]->lProfilePV;
        $high_school_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $dazhuan_value = $data->data->value[3]->lProfilePV;
        $dazhuan_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $bachelor_value = $data->data->value[4]->lProfilePV;
        $bachelor_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $master_value = $data->data->value[5]->lProfilePV;
        $master_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $doctor_value = $data->data->value[6]->lProfilePV;
        $doctor_rate = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_film_education values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$primary_school_value}','{$primary_school_rate}','{$middle_school_value}','{$middle_school_rate}',
        '{$high_school_value}','{$high_school_rate}','{$dazhuan_value}','{$dazhuan_rate}',
        '{$bachelor_value}','{$bachelor_rate}','{$master_value}','{$master_rate}',
        '{$doctor_value}','{$doctor_rate}'
        );");

        var_dump($value[0]);
        sleep(3);

    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/education/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $primary_school_value = $data->data->value[0]->lProfilePV;
        $primary_school_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $middle_school_value = $data->data->value[1]->lProfilePV;
        $middle_school_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $high_school_value = $data->data->value[2]->lProfilePV;
        $high_school_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $dazhuan_value = $data->data->value[3]->lProfilePV;
        $dazhuan_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $bachelor_value = $data->data->value[4]->lProfilePV;
        $bachelor_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $master_value = $data->data->value[5]->lProfilePV;
        $master_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $doctor_value = $data->data->value[6]->lProfilePV;
        $doctor_rate = (string)(($data->data->value[6]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_tv_education values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$primary_school_value}','{$primary_school_rate}','{$middle_school_value}','{$middle_school_rate}',
        '{$high_school_value}','{$high_school_rate}','{$dazhuan_value}','{$dazhuan_rate}',
        '{$bachelor_value}','{$bachelor_rate}','{$master_value}','{$master_rate}',
        '{$doctor_value}','{$doctor_rate}'
        );");

        var_dump($value[0]);
        sleep(3);
    }


 ?>
