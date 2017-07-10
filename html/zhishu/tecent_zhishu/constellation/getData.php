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
            exec("python /var/www/html/zhishu/tecent_zhishu/constellation/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $baiyang_value = (string)($data->data->value[0]->lProfilePV);
        $baiyang_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $jinniu_value = (string)($data->data->value[1]->lProfilePV);
        $jinniu_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $shuangzi_value = (string)($data->data->value[2]->lProfilePV);
        $shuangzi_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $juxie_value = (string)($data->data->value[3]->lProfilePV);
        $juxie_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $shizi_value = (string)($data->data->value[4]->lProfilePV);
        $shizi_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $chunv_value = (string)($data->data->value[5]->lProfilePV);
        $chunv_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $tiancheng_value = (string)($data->data->value[6]->lProfilePV);
        $tiancheng_rate = (string)(($data->data->value[6]->dProfileRate)*100);
        $tianxie_value = (string)($data->data->value[7]->lProfilePV);
        $tianxie_rate = (string)(($data->data->value[7]->dProfileRate)*100);
        $sheshou_value = (string)($data->data->value[8]->lProfilePV);
        $sheshou_rate = (string)(($data->data->value[8]->dProfileRate)*100);
        $mojie_value = (string)($data->data->value[9]->lProfilePV);
        $mojie_rate = (string)(($data->data->value[9]->dProfileRate)*100);
        $shuiping_value = (string)($data->data->value[10]->lProfilePV);
        $shuiping_rate = (string)(($data->data->value[10]->dProfileRate)*100);
        $shuangyu_value = (string)($data->data->value[11]->lProfilePV);
        $shuangyu_rate = (string)(($data->data->value[11]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_zy_constellation values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$baiyang_value}','{$baiyang_rate}','{$jinniu_value}','{$jinniu_rate}',
        '{$shuangzi_value}','{$shuangzi_rate}','{$juxie_value}','{$juxie_rate}',
        '{$shizi_value}','{$shizi_rate}','{$chunv_value}','{$chunv_rate}',
        '{$tiancheng_value}','{$tiancheng_rate}','{$tianxie_value}','{$tianxie_rate}',
        '{$sheshou_value}','{$sheshou_rate}','{$mojie_value}','{$mojie_rate}',
        '{$shuiping_value}','{$shuiping_rate}','{$shuangyu_value}','{$shuangyu_rate}'
        );");
        var_dump($value[0]);
	sleep(3);
    }

    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/constellation/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $baiyang_value = (string)($data->data->value[0]->lProfilePV);
        $baiyang_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $jinniu_value = (string)($data->data->value[1]->lProfilePV);
        $jinniu_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $shuangzi_value = (string)($data->data->value[2]->lProfilePV);
        $shuangzi_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $juxie_value = (string)($data->data->value[3]->lProfilePV);
        $juxie_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $shizi_value = (string)($data->data->value[4]->lProfilePV);
        $shizi_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $chunv_value = (string)($data->data->value[5]->lProfilePV);
        $chunv_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $tiancheng_value = (string)($data->data->value[6]->lProfilePV);
        $tiancheng_rate = (string)(($data->data->value[6]->dProfileRate)*100);
        $tianxie_value = (string)($data->data->value[7]->lProfilePV);
        $tianxie_rate = (string)(($data->data->value[7]->dProfileRate)*100);
        $sheshou_value = (string)($data->data->value[8]->lProfilePV);
        $sheshou_rate = (string)(($data->data->value[8]->dProfileRate)*100);
        $mojie_value = (string)($data->data->value[9]->lProfilePV);
        $mojie_rate = (string)(($data->data->value[9]->dProfileRate)*100);
        $shuiping_value = (string)($data->data->value[10]->lProfilePV);
        $shuiping_rate = (string)(($data->data->value[10]->dProfileRate)*100);
        $shuangyu_value = (string)($data->data->value[11]->lProfilePV);
        $shuangyu_rate = (string)(($data->data->value[11]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_yiren_constellation values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$baiyang_value}','{$baiyang_rate}','{$jinniu_value}','{$jinniu_rate}',
        '{$shuangzi_value}','{$shuangzi_rate}','{$juxie_value}','{$juxie_rate}',
        '{$shizi_value}','{$shizi_rate}','{$chunv_value}','{$chunv_rate}',
        '{$tiancheng_value}','{$tiancheng_rate}','{$tianxie_value}','{$tianxie_rate}',
        '{$sheshou_value}','{$sheshou_rate}','{$mojie_value}','{$mojie_rate}',
        '{$shuiping_value}','{$shuiping_rate}','{$shuangyu_value}','{$shuangyu_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/constellation/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $baiyang_value = (string)($data->data->value[0]->lProfilePV);
        $baiyang_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $jinniu_value = (string)($data->data->value[1]->lProfilePV);
        $jinniu_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $shuangzi_value = (string)($data->data->value[2]->lProfilePV);
        $shuangzi_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $juxie_value = (string)($data->data->value[3]->lProfilePV);
        $juxie_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $shizi_value = (string)($data->data->value[4]->lProfilePV);
        $shizi_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $chunv_value = (string)($data->data->value[5]->lProfilePV);
        $chunv_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $tiancheng_value = (string)($data->data->value[6]->lProfilePV);
        $tiancheng_rate = (string)(($data->data->value[6]->dProfileRate)*100);
        $tianxie_value = (string)($data->data->value[7]->lProfilePV);
        $tianxie_rate = (string)(($data->data->value[7]->dProfileRate)*100);
        $sheshou_value = (string)($data->data->value[8]->lProfilePV);
        $sheshou_rate = (string)(($data->data->value[8]->dProfileRate)*100);
        $mojie_value = (string)($data->data->value[9]->lProfilePV);
        $mojie_rate = (string)(($data->data->value[9]->dProfileRate)*100);
        $shuiping_value = (string)($data->data->value[10]->lProfilePV);
        $shuiping_rate = (string)(($data->data->value[10]->dProfileRate)*100);
        $shuangyu_value = (string)($data->data->value[11]->lProfilePV);
        $shuangyu_rate = (string)(($data->data->value[11]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_film_constellation values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$baiyang_value}','{$baiyang_rate}','{$jinniu_value}','{$jinniu_rate}',
        '{$shuangzi_value}','{$shuangzi_rate}','{$juxie_value}','{$juxie_rate}',
        '{$shizi_value}','{$shizi_rate}','{$chunv_value}','{$chunv_rate}',
        '{$tiancheng_value}','{$tiancheng_rate}','{$tianxie_value}','{$tianxie_rate}',
        '{$sheshou_value}','{$sheshou_rate}','{$mojie_value}','{$mojie_rate}',
        '{$shuiping_value}','{$shuiping_rate}','{$shuangyu_value}','{$shuangyu_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/constellation/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $baiyang_value = (string)($data->data->value[0]->lProfilePV);
        $baiyang_rate = (string)(($data->data->value[0]->dProfileRate)*100);
        $jinniu_value = (string)($data->data->value[1]->lProfilePV);
        $jinniu_rate = (string)(($data->data->value[1]->dProfileRate)*100);
        $shuangzi_value = (string)($data->data->value[2]->lProfilePV);
        $shuangzi_rate = (string)(($data->data->value[2]->dProfileRate)*100);
        $juxie_value = (string)($data->data->value[3]->lProfilePV);
        $juxie_rate = (string)(($data->data->value[3]->dProfileRate)*100);
        $shizi_value = (string)($data->data->value[4]->lProfilePV);
        $shizi_rate = (string)(($data->data->value[4]->dProfileRate)*100);
        $chunv_value = (string)($data->data->value[5]->lProfilePV);
        $chunv_rate = (string)(($data->data->value[5]->dProfileRate)*100);
        $tiancheng_value = (string)($data->data->value[6]->lProfilePV);
        $tiancheng_rate = (string)(($data->data->value[6]->dProfileRate)*100);
        $tianxie_value = (string)($data->data->value[7]->lProfilePV);
        $tianxie_rate = (string)(($data->data->value[7]->dProfileRate)*100);
        $sheshou_value = (string)($data->data->value[8]->lProfilePV);
        $sheshou_rate = (string)(($data->data->value[8]->dProfileRate)*100);
        $mojie_value = (string)($data->data->value[9]->lProfilePV);
        $mojie_rate = (string)(($data->data->value[9]->dProfileRate)*100);
        $shuiping_value = (string)($data->data->value[10]->lProfilePV);
        $shuiping_rate = (string)(($data->data->value[10]->dProfileRate)*100);
        $shuangyu_value = (string)($data->data->value[11]->lProfilePV);
        $shuangyu_rate = (string)(($data->data->value[11]->dProfileRate)*100);

        mysqli_query($con ,"insert into tx_tv_constellation values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$baiyang_value}','{$baiyang_rate}','{$jinniu_value}','{$jinniu_rate}',
        '{$shuangzi_value}','{$shuangzi_rate}','{$juxie_value}','{$juxie_rate}',
        '{$shizi_value}','{$shizi_rate}','{$chunv_value}','{$chunv_rate}',
        '{$tiancheng_value}','{$tiancheng_rate}','{$tianxie_value}','{$tianxie_rate}',
        '{$sheshou_value}','{$sheshou_rate}','{$mojie_value}','{$mojie_rate}',
        '{$shuiping_value}','{$shuiping_rate}','{$shuangyu_value}','{$shuangyu_rate}'
        );");
        var_dump($value[0]);

        sleep(3);
    }



 ?>
