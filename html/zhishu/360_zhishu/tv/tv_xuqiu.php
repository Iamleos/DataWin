<?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //提取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $tv = mysqli_query($con, "select name from douban_tv where zzsy='1'");
    $tv = mysqli_fetch_all($tv);
    mysqli_close($con);
    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    foreach ($tv as $key => $value) {
        $tvname = $value[0];
        $url_name = urlencode($tvname);
        $url = "http://index.haosou.com/index/radarJson?t=30&q=".$url_name;
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

        $data = json_decode($result);
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


        mysqli_query($con, "insert into 360_tv_xuqiu values('{$tvname}',
        '{$list[0]->query}','{$list[0]->power}','{$list[1]->query}','{$list[1]->power}','{$list[2]->query}','{$list[2]->power}','{$list[3]->query}','{$list[3]->power}',
        '{$list[4]->query}','{$list[4]->power}',
        '{$from[0]->query}','{$from[0]->relativity}','{$from[1]->query}','{$from[1]->relativity}','{$from[2]->query}','{$from[2]->relativity}',
        '{$from[3]->query}','{$from[3]->relativity}','{$from[4]->query}','{$from[4]->relativity}',
        '{$to[0]->query}','{$to[0]->relativity}','{$to[1]->query}','{$to[1]->relativity}','{$to[2]->query}','{$to[2]->relativity}','{$to[3]->query}','{$to[3]->relativity}',
        '{$to[4]->query}','{$to[4]->relativity}',
        '{$burst[0]->query}','{$burst[0]->power}','{$burst[1]->query}','{$burst[1]->power}','{$burst[2]->query}','{$burst[2]->power}','{$burst[3]->query}','{$burst[3]->power}',
        '{$burst[4]->query}','{$burst[4]->power}',
        '{$date}');");

        var_dump($tvname);
        sleep(1);

    }

    mysqli_close($con);
 ?>
