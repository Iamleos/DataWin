<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //获取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $other = mysqli_query($con, "select name from other_name");
    $other = mysqli_fetch_all($other);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    //用python爬取数据
    foreach ($other as $key => $value) {

        $othername = $value[0];
        exec("python /var/www/html/zhishu/weibo_zhishu/other_reyidu/getId.py $othername", $id);
        sleep(1);
        if ($id[0] == 'error') {
            $id = NULL;
            continue;
        }
        exec("python /var/www/html/zhishu/weibo_zhishu/other_reyidu/getData.py $id[0]", $json);
        sleep(1);
        $data = json_decode($json[0]);
        $region_reyidu = json_decode( json_encode( $data->zone),true);
        $user_reyidu = json_decode( json_encode( $data->user),true);
        if($region_reyidu["beijing"]["index"] == 0 &&$region_reyidu["shanghai"]["index"] == 0 && $region_reyidu["guangdong"]["index"] == 0 && $region_reyidu["jiangsu"]["index"] == 0 && $user_reyidu["beijing"]["index"] == 0 &&$user_reyidu["shanghai"]["index"] == 0 && $user_reyidu["guangdong"]["index"] == 0 && $user_reyidu["jiangsu"]["index"] == 0){
                $json = NULL;
	        exec("python /var/www/html/zhishu/weibo_zhishu/other_reyidu/getData.py $id[0]", $json);
	        sleep(1);
	        $data = json_decode($json[0]);
	        $region_reyidu = json_decode( json_encode( $data->zone),true);
	        $user_reyidu = json_decode( json_encode( $data->user),true);
        }

        //value去百分号
        foreach ($region_reyidu as $key => $value) {
            $region_reyidu[$key]["value"] = str_ireplace("%","",$value["value"]);
        }
        foreach ($user_reyidu as $key => $value) {
            $user_reyidu[$key]["value"] = str_ireplace("%","",$value["value"]);
        }
        //入库
        mysqli_query($con,"insert into wzs_other_reyidu_region(name,ningxia,jiangxi,beijing,zhejiang,henan,guangdong,shanghai,jiangsu,sichuan,shandong,hubei,fujian,hunan,hebei,liaoning,chongqing,anhui,shanxi,
        hongkong,guangxi,yunnan,heilongjiang,shaanxi,taiwan,jilin,hainan,guizhou,tianjin,gansu,neimongol,xinjiang,qinghai,macau,xizang,time) values('{$othername}','{$region_reyidu["ningxia"]["ct"]}','{$region_reyidu["jiangxi"]["ct"]}','{$region_reyidu["beijing"]["ct"]}',
        '{$region_reyidu["zhejiang"]["ct"]}','{$region_reyidu["henan"]["ct"]}','{$region_reyidu["guangdong"]["ct"]}','{$region_reyidu["shanghai"]["ct"]}','{$region_reyidu["jiangsu"]["ct"]}',
        '{$region_reyidu["sichuan"]["ct"]}','{$region_reyidu["shandong"]["ct"]}','{$region_reyidu["hubei"]["ct"]}','{$region_reyidu["fujian"]["ct"]}','{$region_reyidu["hunan"]["ct"]}',
        '{$region_reyidu["hebei"]["ct"]}','{$region_reyidu["liaoning"]["ct"]}','{$region_reyidu["chongqing"]["ct"]}','{$region_reyidu["anhui"]["ct"]}','{$region_reyidu["shanxi"]["ct"]}',
        '{$region_reyidu["hongkong"]["ct"]}','{$region_reyidu["guangxi"]["ct"]}','{$region_reyidu["yunnan"]["ct"]}','{$region_reyidu["heilongjiang"]["ct"]}','{$region_reyidu["shaanxi"]["ct"]}',
        '{$region_reyidu["taiwan"]["ct"]}','{$region_reyidu["jilin"]["ct"]}','{$region_reyidu["hainan"]["ct"]}','{$region_reyidu["guizhou"]["ct"]}','{$region_reyidu["tianjin"]["ct"]}',
        '{$region_reyidu["gansu"]["ct"]}','{$region_reyidu["neimongol"]["ct"]}','{$region_reyidu["xinjiang"]["ct"]}','{$region_reyidu["qinghai"]["ct"]}','{$region_reyidu["macau"]["ct"]}',
        '{$region_reyidu["xizang"]["ct"]}','{$date}');");

        mysqli_query($con,"insert into wzs_other_reyidu_user(name,ningxia,jiangxi,beijing,zhejiang,henan,guangdong,shanghai,jiangsu,sichuan,shandong,hubei,fujian,hunan,hebei,liaoning,chongqing,anhui,shanxi,
        hongkong,guangxi,yunnan,heilongjiang,shaanxi,taiwan,jilin,hainan,guizhou,tianjin,gansu,neimongol,xinjiang,qinghai,macau,xizang,time) values('{$othername}','{$user_reyidu["ningxia"]["num"]}','{$user_reyidu["jiangxi"]["num"]}','{$user_reyidu["beijing"]["num"]}',
        '{$user_reyidu["zhejiang"]["num"]}','{$user_reyidu["henan"]["num"]}','{$user_reyidu["guangdong"]["num"]}','{$user_reyidu["shanghai"]["num"]}','{$user_reyidu["jiangsu"]["num"]}',
        '{$user_reyidu["sichuan"]["num"]}','{$user_reyidu["shandong"]["num"]}','{$user_reyidu["hubei"]["num"]}','{$user_reyidu["fujian"]["num"]}','{$user_reyidu["hunan"]["num"]}',
        '{$user_reyidu["hebei"]["num"]}','{$user_reyidu["liaoning"]["num"]}','{$user_reyidu["chongqing"]["num"]}','{$user_reyidu["anhui"]["num"]}','{$user_reyidu["shanxi"]["num"]}',
        '{$user_reyidu["hongkong"]["num"]}','{$user_reyidu["guangxi"]["num"]}','{$user_reyidu["yunnan"]["num"]}','{$user_reyidu["heilongjiang"]["num"]}','{$user_reyidu["shaanxi"]["num"]}',
        '{$user_reyidu["taiwan"]["num"]}','{$user_reyidu["jilin"]["num"]}','{$user_reyidu["hainan"]["num"]}','{$user_reyidu["guizhou"]["num"]}','{$user_reyidu["tianjin"]["num"]}',
        '{$user_reyidu["gansu"]["num"]}','{$user_reyidu["neimongol"]["num"]}','{$user_reyidu["xinjiang"]["num"]}','{$user_reyidu["qinghai"]["num"]}','{$user_reyidu["macau"]["num"]}',
        '{$user_reyidu["xizang"]["num"]}','{$date}');");
        $id = NULL;
        $json = NULL;
        var_dump($othername);
    }
    mysqli_close($con);


 ?>
