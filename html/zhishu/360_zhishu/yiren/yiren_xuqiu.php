<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //提取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $yiren = mysqli_query($con, "select me from actname");
    $yiren = mysqli_fetch_all($yiren);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($yiren as $key => $value) {
        $yirenname = $value[0];
        $flag = 0;
        do {
            $json = NULL;
            $data = NULL;
            exec("python /var/www/html/zhishu/360_zhishu/python/getData_xuqiu.py '{$yirenname}'",$json);
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

        if ($data->data == false) {
            continue;
        }
        $burst = $data->data->burst_query;
        $from = $data->data->from_query;
        $to = $data->data->to_query;
        $list = $data->data->list;
        if (count($burst) < 5) {
            $diff = 5-count($burst);
            $obj = new stdClass();
            $obj->query = NULL;
            $obj->power ="0";
            for ($i=0; $i < $diff; $i++) {
                array_push($burst, $obj);

            }
        }
        if (count($from) < 5) {
            $diff = 5-count($from);
            $obj = new stdClass();
            $obj->query = NULL;
            $obj->relativity ="0";

            for ($i=0; $i < $diff; $i++) {
                array_push($from, $obj);

            }
        }
        if (count($to) < 5) {
            $diff = 5-count($to);
            $obj = new stdClass();
            $obj->query = NULL;
            $obj->relativity ="0";
            for ($i=0; $i < $diff; $i++) {
                array_push($to, $obj);

            }
        }
        if (count($list) < 5) {
            $diff = 5-count($list);
            $obj = new stdClass();
            $obj->query = NULL;
            $obj->power ="0";
            $obj->src = NULL;
            $obj->trend = NULL;
            for ($i=0; $i < $diff; $i++) {
                array_push($list, $obj);

            }
        }
        mysqli_query($con, "insert into 360_yiren_xuqiu values('{$yirenname}',
        '{$list[0]->query}','{$list[0]->power}','{$list[1]->query}','{$list[1]->power}','{$list[2]->query}','{$list[2]->power}','{$list[3]->query}','{$list[3]->power}',
        '{$list[4]->query}','{$list[4]->power}',
        '{$from[0]->query}','{$from[0]->relativity}','{$from[1]->query}','{$from[1]->relativity}','{$from[2]->query}','{$from[2]->relativity}',
        '{$from[3]->query}','{$from[3]->relativity}','{$from[4]->query}','{$from[4]->relativity}',
        '{$to[0]->query}','{$to[0]->relativity}','{$to[1]->query}','{$to[1]->relativity}','{$to[2]->query}','{$to[2]->relativity}','{$to[3]->query}','{$to[3]->relativity}',
        '{$to[4]->query}','{$to[4]->relativity}',
        '{$burst[0]->query}','{$burst[0]->power}','{$burst[1]->query}','{$burst[1]->power}','{$burst[2]->query}','{$burst[2]->power}','{$burst[3]->query}','{$burst[3]->power}',
        '{$burst[4]->query}','{$burst[4]->power}',
        '{$date}');");
        var_dump($yirenname);
        sleep(1);

    }

    mysqli_close($con);
 ?>
