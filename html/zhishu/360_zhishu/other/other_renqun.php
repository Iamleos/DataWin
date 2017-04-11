<?php
    //读取地区编号配置文件
    $region = simplexml_load_file("../region.xml");
    $region = json_decode(json_encode($region),true);
    $characters = simplexml_load_file("../characters.xml");
    $characters = json_decode(json_encode($characters),true);
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //提取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $other = mysqli_query($con, "select name from linshi_word");
    $other = mysqli_fetch_all($other);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    //爬取数据
    foreach ($other as $key => $value) {
        $othername = $value[0];
        $url_name = urlencode($othername);
        $url = "http://index.haosou.com/index/indexquerygraph?t=30&area=%E5%85%A8%E5%9B%BD&q=".$url_name;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result)->data;
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

        mysqli_query($con,"insert into 360_other_region(name,
        {$region["key"][(int)($province[0]->entity-1)]},{$region["key"][(int)($province[1]->entity-1)]},{$region["key"][(int)($province[2]->entity-1)]},{$region["key"][(int)($province[3]->entity-1)]},
        {$region["key"][(int)($province[4]->entity-1)]},{$region["key"][(int)($province[5]->entity-1)]},{$region["key"][(int)($province[6]->entity-1)]},{$region["key"][(int)($province[7]->entity-1)]},
        {$region["key"][(int)($province[8]->entity-1)]},{$region["key"][(int)($province[9]->entity-1)]},{$region["key"][(int)($province[10]->entity-1)]},{$region["key"][(int)($province[11]->entity-1)]},
        {$region["key"][(int)($province[12]->entity-1)]},{$region["key"][(int)($province[13]->entity-1)]},{$region["key"][(int)($province[14]->entity-1)]},{$region["key"][(int)($province[15]->entity-1)]},
        {$region["key"][(int)($province[16]->entity-1)]},{$region["key"][(int)($province[17]->entity-1)]},{$region["key"][(int)($province[18]->entity-1)]},{$region["key"][(int)($province[19]->entity-1)]},
        {$region["key"][(int)($province[20]->entity-1)]},{$region["key"][(int)($province[21]->entity-1)]},{$region["key"][(int)($province[22]->entity-1)]},{$region["key"][(int)($province[23]->entity-1)]},
        {$region["key"][(int)($province[24]->entity-1)]},{$region["key"][(int)($province[25]->entity-1)]},{$region["key"][(int)($province[26]->entity-1)]},{$region["key"][(int)($province[27]->entity-1)]},
        {$region["key"][(int)($province[28]->entity-1)]},{$region["key"][(int)($province[29]->entity-1)]},{$region["key"][(int)($province[30]->entity-1)]},{$region["key"][(int)($province[31]->entity-1)]},
        {$region["key"][(int)($province[32]->entity-1)]},{$region["key"][(int)($province[33]->entity-1)]},
        acquitime)
        values('{$othername}',
        '{$province[0]->percent}','{$province[1]->percent}','{$province[2]->percent}','{$province[3]->percent}','{$province[4]->percent}','{$province[5]->percent}',
        '{$province[6]->percent}','{$province[7]->percent}','{$province[8]->percent}','{$province[9]->percent}','{$province[10]->percent}','{$province[11]->percent}',
        '{$province[12]->percent}','{$province[13]->percent}','{$province[14]->percent}','{$province[15]->percent}','{$province[16]->percent}','{$province[17]->percent}',
        '{$province[18]->percent}','{$province[19]->percent}','{$province[20]->percent}','{$province[21]->percent}','{$province[22]->percent}','{$province[23]->percent}',
        '{$province[24]->percent}','{$province[25]->percent}','{$province[26]->percent}','{$province[27]->percent}','{$province[28]->percent}','{$province[29]->percent}',
        '{$province[30]->percent}','{$province[31]->percent}','{$province[32]->percent}','{$province[33]->percent}',
        '{$date}');");

        mysqli_query($con, "insert into 360_other_renqun values('{$othername}',
        '{$age_cp["01"]["percent"]}','{$age_cp["01"]["sex"]["male"]}','{$age_cp["01"]["sex"]["female"]}',
        '{$age_cp["02"]["percent"]}','{$age_cp["02"]["sex"]["male"]}','{$age_cp["02"]["sex"]["female"]}',
        '{$age_cp["03"]["percent"]}','{$age_cp["03"]["sex"]["male"]}','{$age_cp["03"]["sex"]["female"]}',
        '{$age_cp["04"]["percent"]}','{$age_cp["04"]["sex"]["male"]}','{$age_cp["04"]["sex"]["female"]}',
        '{$age_cp["05"]["percent"]}','{$age_cp["05"]["sex"]["male"]}','{$age_cp["05"]["sex"]["female"]}',
        '{$sex_cp["01"]}','{$sex_cp["02"]}',
        '{$date}');");

        mysqli_query($con, "insert into 360_other_attribute values('{$othername}',
        '{$characters["key"][(int)$interest[0]->entity-1]}','{$interest[0]->percent}','{$characters["key"][(int)$interest[1]->entity-1]}','{$interest[1]->percent}',
        '{$characters["key"][(int)$interest[2]->entity-1]}','{$interest[2]->percent}','{$characters["key"][(int)$interest[3]->entity-1]}','{$interest[3]->percent}',
        '{$characters["key"][(int)$interest[4]->entity-1]}','{$interest[4]->percent}','{$characters["key"][(int)$interest[5]->entity-1]}','{$interest[5]->percent}',
        '{$characters["key"][(int)$interest[6]->entity-1]}','{$interest[6]->percent}','{$characters["key"][(int)$interest[7]->entity-1]}','{$interest[7]->percent}',
        '{$date}');");
        var_dump($othername);
        sleep(1);

    }
    mysqli_close($con);













 ?>
