<?php
    include "/var/www/html/zhishu/cbooo_hot/function_lib/lib.php";
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $con = Datawin\getDB("zhishu");
    exec("python /var/www/html/zhishu/cbooo_hot/data/getData.py 2",$tv_json);
    exec("python /var/www/html/zhishu/cbooo_hot/data/getData.py 8",$zy_json);
    $tv_data = json_decode($tv_json[0]);
    $zy_data = json_decode($zy_json[0]);
    foreach ($tv_data as $key => $value) {
        $rank = $key+1;
        $name = $value->TvName;
        $youku = $value->Youku;
        $iqiyi = $value->iQiyi;
        $sohu = $value->Sohu;
        $tencent = $value->Tencent;
        $leshi = $value->LeTV;
        $mangguo = $value->HunanTV;
        $pptv = $value->PPTV;
        $total = $value->LeiJi;
        $broad_date = $value->broadsdate;
        $theme = $value->GenresAll;
        $country = $value->Countrys;
        mysqli_query($con ,"insert into cbooo_hot_tv values(
        '{$name}',{$rank},'{$youku}','{$iqiyi}','{$sohu}','{$tencent}',
        '{$leshi}','{$mangguo}','{$pptv}','{$total}','{$broad_date}',
        '{$theme}','{$country}','{$date}'
        );");
    }
    foreach ($zy_data as $key => $value) {
        $rank = $key+1;
        $name = $value->TvName;
        $youku = $value->Youku;
        $iqiyi = $value->iQiyi;
        $sohu = $value->Sohu;
        $tencent = $value->Tencent;
        $leshi = $value->LeTV;
        $mangguo = $value->HunanTV;
        $pptv = $value->PPTV;
        $total = $value->LeiJi;
        $broad_date = $value->broadsdate;
        $theme = $value->GenresAll;
        $country = $value->Countrys;
        mysqli_query($con ,"insert into cbooo_hot_zy values(
        '{$name}',{$rank},'{$youku}','{$iqiyi}','{$sohu}','{$tencent}',
        '{$leshi}','{$mangguo}','{$pptv}','{$total}','{$broad_date}',
        '{$theme}','{$country}','{$date}'
        );");
    }

 ?>
