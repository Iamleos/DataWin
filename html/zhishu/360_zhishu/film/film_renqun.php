<?php
    //读取地区编号配置文件
    $region = simplexml_load_file("/var/www/html/zhishu/360_zhishu/region.xml");
    $region = json_decode(json_encode($region),true);
#    $characters = simplexml_load_file("../characters.xml");
#    $characters = json_decode(json_encode($characters),true);
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //提取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $film = mysqli_query($con, "select mainname from filmname where zzsy=1");
    $film = mysqli_fetch_all($film);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    //爬取数据
    foreach ($film as $key => $value) {
        $filmname = $value[0];
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/360_zhishu/python/getData_renqun.py '{$filmname}'",$json);
            $data = json_decode($json[0]);
            if ($flag > 2) {
                break;
            }
            elseif ($data == NULL) {
                $flag++;
                continue;
            }
            if ($data->msg == "no result") {
                continue 2;
            }
        } while (0);
        $data = $data->data;
        if($data == false){
            continue;
        }
        $age = $data->age;
        $interest = $data->interest;
        $province = $data->province;
        $sex = $data->sex;
        $sex = json_decode(json_encode($sex),true);
        $age = json_decode(json_encode($age),true);
        foreach ($age as $key1 => $value1) {
            $age_cp[$value1["entity"]]["percent"] = (string)($value1["percent"]/100);
            $age_cp[$value1["entity"]]["sex"]["male"] = (string)($value1["sex"]["male"]/100);
            $age_cp[$value1["entity"]]["sex"]["female"] = (string)($value1["sex"]["female"]/100);

        }
        //有的关键词的搜索出的age信息只有3个或者更少的年龄段的划分，需要补全
        $age_total_cp = array("01","02","03","04","05");
        $age_current_cp = array_keys($age_cp);
        $age_cp_diff = array_diff($age_total_cp,$age_current_cp);
        foreach ($age_cp_diff as $key6 => $value6) {
            $age_cp["$value6"]["percent"] = "0";
            $age_cp["$value6"]["sex"]["male"] = "0";
            $age_cp["$value6"]["sex"]["female"] = "0";
        }

        foreach ($sex as $key2 => $value2) {
            $sex_cp[$value2["entity"]] = (string)($value2["percent"]/100);
        }
        foreach ($interest as $key3 => $value3) {
            $interest[$key3]->percent = (string)($value3->percent/100);
        }
        if (count($province) < 34) {
            $province_id = array();
            foreach ($province as $key4 => $value4) {
                array_push($province_id,(string)$value4->entity);
            }
            $diff = array_diff($region["value"],$province_id);
            foreach ($diff as $key5 => $value5) {
                $obj = new stdClass();
                $obj->percent = "0";
                $obj->entity = $value5;
                array_push($province, $obj);
            }
        }
        if (count($interest) < 8) {
            $diff = 8-count($interest);
            $obj = new stdClass();
            $obj->entity = "60";
            $obj->percent = "0";

            for ($i=0; $i < $diff; $i++) {
                array_push($interest, $obj);

            }
        }

        mysqli_query($con,"insert into 360_film_region(name,
        {$region["key"][(int)($province[0]->entity)-1]},{$region["key"][(int)($province[1]->entity)-1]},{$region["key"][(int)($province[2]->entity)-1]},{$region["key"][(int)($province[3]->entity)-1]},
        {$region["key"][(int)($province[4]->entity)-1]},{$region["key"][(int)($province[5]->entity)-1]},{$region["key"][(int)($province[6]->entity)-1]},{$region["key"][(int)($province[7]->entity)-1]},
        {$region["key"][(int)($province[8]->entity)-1]},{$region["key"][(int)($province[9]->entity)-1]},{$region["key"][(int)($province[10]->entity)-1]},{$region["key"][(int)($province[11]->entity)-1]},
        {$region["key"][(int)($province[12]->entity)-1]},{$region["key"][(int)($province[13]->entity)-1]},{$region["key"][(int)($province[14]->entity)-1]},{$region["key"][(int)($province[15]->entity)-1]},
        {$region["key"][(int)($province[16]->entity)-1]},{$region["key"][(int)($province[17]->entity)-1]},{$region["key"][(int)($province[18]->entity)-1]},{$region["key"][(int)($province[19]->entity)-1]},
        {$region["key"][(int)($province[20]->entity)-1]},{$region["key"][(int)($province[21]->entity)-1]},{$region["key"][(int)($province[22]->entity)-1]},{$region["key"][(int)($province[23]->entity)-1]},
        {$region["key"][(int)($province[24]->entity)-1]},{$region["key"][(int)($province[25]->entity)-1]},{$region["key"][(int)($province[26]->entity)-1]},{$region["key"][(int)($province[27]->entity)-1]},
        {$region["key"][(int)($province[28]->entity)-1]},{$region["key"][(int)($province[29]->entity)-1]},{$region["key"][(int)($province[30]->entity)-1]},{$region["key"][(int)($province[31]->entity)-1]},
        {$region["key"][(int)($province[32]->entity)-1]},{$region["key"][(int)($province[33]->entity)-1]},
        acquitime)
        values('{$filmname}',
        '{$province[0]->percent}','{$province[1]->percent}','{$province[2]->percent}','{$province[3]->percent}','{$province[4]->percent}','{$province[5]->percent}',
        '{$province[6]->percent}','{$province[7]->percent}','{$province[8]->percent}','{$province[9]->percent}','{$province[10]->percent}','{$province[11]->percent}',
        '{$province[12]->percent}','{$province[13]->percent}','{$province[14]->percent}','{$province[15]->percent}','{$province[16]->percent}','{$province[17]->percent}',
        '{$province[18]->percent}','{$province[19]->percent}','{$province[20]->percent}','{$province[21]->percent}','{$province[22]->percent}','{$province[23]->percent}',
        '{$province[24]->percent}','{$province[25]->percent}','{$province[26]->percent}','{$province[27]->percent}','{$province[28]->percent}','{$province[29]->percent}',
        '{$province[30]->percent}','{$province[31]->percent}','{$province[32]->percent}','{$province[33]->percent}',
        '{$date}');");

        mysqli_query($con, "insert into 360_film_renqun values('{$filmname}',
        '{$age_cp["01"]["percent"]}','{$age_cp["01"]["sex"]["male"]}','{$age_cp["01"]["sex"]["female"]}',
        '{$age_cp["02"]["percent"]}','{$age_cp["02"]["sex"]["male"]}','{$age_cp["02"]["sex"]["female"]}',
        '{$age_cp["03"]["percent"]}','{$age_cp["03"]["sex"]["male"]}','{$age_cp["03"]["sex"]["female"]}',
        '{$age_cp["04"]["percent"]}','{$age_cp["04"]["sex"]["male"]}','{$age_cp["04"]["sex"]["female"]}',
        '{$age_cp["05"]["percent"]}','{$age_cp["05"]["sex"]["male"]}','{$age_cp["05"]["sex"]["female"]}',
        '{$sex_cp["01"]}','{$sex_cp["02"]}',
        '{$date}');");

        mysqli_query($con, "insert into 360_film_attribute values('{$filmname}',
        '{$interest[0]->entity}','{$interest[0]->percent}','{$interest[1]->entity}','{$interest[1]->percent}',
        '{$interest[2]->entity}','{$interest[2]->percent}','{$interest[3]->entity}','{$interest[3]->percent}',
        '{$interest[4]->entity}','{$interest[4]->percent}','{$interest[5]->entity}','{$interest[5]->percent}',
        '{$interest[6]->entity}','{$interest[6]->percent}','{$interest[7]->entity}','{$interest[7]->percent}',
        '{$date}');");
        var_dump("insert into 360_film_attribute values('{$filmname}',
        '{$interest[0]->entity}','{$interest[0]->percent}','{$interest[1]->entity}','{$interest[1]->percent}',
        '{$interest[2]->entity}','{$interest[2]->percent}','{$interest[3]->entity}','{$interest[3]->percent}',
        '{$interest[4]->entity}','{$interest[4]->percent}','{$interest[5]->entity}','{$interest[5]->percent}',
        '{$interest[6]->entity}','{$interest[6]->percent}','{$interest[7]->entity}','{$interest[7]->percent}',
        '{$date}');");
        var_dump($filmname);
        sleep(1);

    }
    mysqli_close($con);













 ?>
