<?php
    include "/var/www/html/zhishu/tecent_zhishu/function_lib/lib.php";
    $province = simplexml_load_file("/var/www/html/zhishu/tecent_zhishu/province/region.xml");
    $province = json_decode(json_encode($province),true);

    //获取采集名单

    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname;");
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
            exec("python /var/www/html/zhishu/tecent_zhishu/province/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $index = array();
        foreach ($data as $key1 => $value1) {
            $index[$key1] = $province["value"][array_search($data[$key1]->sProfileValueID,$province["key"])];
            $rate[$key1] = (string)(($data[$key1]->dProfileRate)*100);
        }

        mysqli_query($con ,"insert into tx_zy_province(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]},{$index[31]},
        {$index[32]},{$index[33]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$rate[0]}','{$rate[1]}','{$rate[2]}','{$rate[3]}','{$rate[4]}','{$rate[5]}','{$rate[6]}','{$rate[7]}',
        '{$rate[8]}','{$rate[9]}','{$rate[10]}','{$rate[11]}','{$rate[12]}','{$rate[13]}','{$rate[14]}','{$rate[15]}',
        '{$rate[16]}','{$rate[17]}','{$rate[18]}','{$rate[19]}','{$rate[20]}','{$rate[21]}','{$rate[22]}','{$rate[23]}',
        '{$rate[24]}','{$rate[25]}','{$rate[26]}','{$rate[27]}','{$rate[28]}','{$rate[29]}','{$rate[30]}','{$rate[31]}',
        '{$rate[32]}','{$rate[33]}'
        );");
        var_dump($value[0]);
        sleep(3);
    }


    foreach ($yirenname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/province/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $index = array();
        foreach ($data as $key1 => $value1) {
            $index[$key1] = $province["value"][array_search($data[$key1]->sProfileValueID,$province["key"])];
            $rate[$key1] = (string)(($data[$key1]->dProfileRate)*100);
        }

        mysqli_query($con ,"insert into tx_yiren_province(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]},{$index[31]},
        {$index[32]},{$index[33]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$rate[0]}','{$rate[1]}','{$rate[2]}','{$rate[3]}','{$rate[4]}','{$rate[5]}','{$rate[6]}','{$rate[7]}',
        '{$rate[8]}','{$rate[9]}','{$rate[10]}','{$rate[11]}','{$rate[12]}','{$rate[13]}','{$rate[14]}','{$rate[15]}',
        '{$rate[16]}','{$rate[17]}','{$rate[18]}','{$rate[19]}','{$rate[20]}','{$rate[21]}','{$rate[22]}','{$rate[23]}',
        '{$rate[24]}','{$rate[25]}','{$rate[26]}','{$rate[27]}','{$rate[28]}','{$rate[29]}','{$rate[30]}','{$rate[31]}',
        '{$rate[32]}','{$rate[33]}'
        );");
        var_dump($value[0]);
        sleep(3);
    }

    foreach ($filmname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/province/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $index = array();
        foreach ($data as $key1 => $value1) {
            $index[$key1] = $province["value"][array_search($data[$key1]->sProfileValueID,$province["key"])];
            $rate[$key1] = (string)(($data[$key1]->dProfileRate)*100);
        }

        mysqli_query($con ,"insert into tx_film_province(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]},{$index[31]},
        {$index[32]},{$index[33]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$rate[0]}','{$rate[1]}','{$rate[2]}','{$rate[3]}','{$rate[4]}','{$rate[5]}','{$rate[6]}','{$rate[7]}',
        '{$rate[8]}','{$rate[9]}','{$rate[10]}','{$rate[11]}','{$rate[12]}','{$rate[13]}','{$rate[14]}','{$rate[15]}',
        '{$rate[16]}','{$rate[17]}','{$rate[18]}','{$rate[19]}','{$rate[20]}','{$rate[21]}','{$rate[22]}','{$rate[23]}',
        '{$rate[24]}','{$rate[25]}','{$rate[26]}','{$rate[27]}','{$rate[28]}','{$rate[29]}','{$rate[30]}','{$rate[31]}',
        '{$rate[32]}','{$rate[33]}'
        );");
        var_dump($value[0]);

        sleep(3);

    }

    foreach ($tvname as $key => $value) {
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/tecent_zhishu/province/getData.py $value[0] $last_info_time $now_info_time",$json);
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
        $index = array();
        foreach ($data as $key1 => $value1) {
            $index[$key1] = $province["value"][array_search($data[$key1]->sProfileValueID,$province["key"])];
            $rate[$key1] = (string)(($data[$key1]->dProfileRate)*100);
        }

        mysqli_query($con ,"insert into tx_tv_province(name, time, acquitime,
        {$index[0]},{$index[1]},{$index[2]},{$index[3]},{$index[4]},{$index[5]},{$index[6]},{$index[7]},
        {$index[8]},{$index[9]},{$index[10]},{$index[11]},{$index[12]},{$index[13]},{$index[14]},{$index[15]},
        {$index[16]},{$index[17]},{$index[18]},{$index[19]},{$index[20]},{$index[21]},{$index[22]},{$index[23]},
        {$index[24]},{$index[25]},{$index[26]},{$index[27]},{$index[28]},{$index[29]},{$index[30]},{$index[31]},
        {$index[32]},{$index[33]}
        ) values(
        '{$value[0]}','{$time}','{$info_time}',
        '{$rate[0]}','{$rate[1]}','{$rate[2]}','{$rate[3]}','{$rate[4]}','{$rate[5]}','{$rate[6]}','{$rate[7]}',
        '{$rate[8]}','{$rate[9]}','{$rate[10]}','{$rate[11]}','{$rate[12]}','{$rate[13]}','{$rate[14]}','{$rate[15]}',
        '{$rate[16]}','{$rate[17]}','{$rate[18]}','{$rate[19]}','{$rate[20]}','{$rate[21]}','{$rate[22]}','{$rate[23]}',
        '{$rate[24]}','{$rate[25]}','{$rate[26]}','{$rate[27]}','{$rate[28]}','{$rate[29]}','{$rate[30]}','{$rate[31]}',
        '{$rate[32]}','{$rate[33]}'
        );");
        var_dump($value[0]);

        sleep(3);
    }


 ?>
