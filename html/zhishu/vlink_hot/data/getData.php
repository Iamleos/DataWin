<?php
    include "/var/www/html/zhishu/vlink_hot/function_lib/lib.php";
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $con = Datawin\getDB("zhishu");
    exec("python /var/www/html/zhishu/vlink_hot/data/getData_tv.py ",$tv_json);
    exec("python /var/www/html/zhishu/vlink_hot/data/getData_zy.py ",$yiren_json);
    preg_match('/(\{\"date.*)\)\}catch/',$tv_json[0],$tv_data);
    preg_match('/(\{\"date.*)\)\}catch/',$yiren_json[0],$yiren_data);
    $tv_data = json_decode($tv_data[1]);
    $yiren_data = json_decode($yiren_data[1]);
    $tv_data = $tv_data->data;
    $yiren_data = $yiren_data->data;

    foreach ($tv_data as $key => $value) {
        $name = $value->name;
        $rank = $key+1;
        $total_broad = $value->num;
        $trend = $value->trend;
        mysqli_query($con ,"insert into vlink_tv values(
        '{$name}',{$rank},'{$total_broad}','{$trend}','{$date}'
        );");
    }
    foreach ($yiren_data as $key => $value) {
        $name = $value->zh_name;
        $rank = $key+1;
        $score = $value->score;
        $trend = $value->trend;
        $slogan = $value->slogan;
        mysqli_query($con ,"insert into vlink_yiren values(
        '{$name}',{$rank},'{$slogan}','{$score}','{$trend}','{$date}'
        );");
    }

 ?>
