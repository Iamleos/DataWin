<?php
    include "../function_lib/lib.php";
    $time = DataWin\getDate(DataWin\getDate(),"-1");    //使用自定义命名空间My的函数
    $con = DataWin\getDB("filmdaily");
    mysqli_query($con, "delete from yien_cinema_today;");
    for ($i=0; $i < 5; $i++) {
        $url = "http://www.cbooo.cn/BoxOffice/getCBD?pIndex=".($i+1)."&dt=".$time;
        $result = json_decode(DataWin\getData($url));
        $data = $result->data1;
        foreach ($data as $key => $value) {
            $no = $value->RowNum;
            $cinema_name = $value->CinemaName;
            $piaofang = (string)($value->TodayBox/10000);
            $per_number = $value->TodayShowCount;
            $per_people = $value->AvgPeople;
            $per_price = $value->price;
            $seat_rate =$value->Attendance;
            mysqli_query($con,"insert into yien_cinema_today values(
            '{$cinema_name}','{$no}','{$piaofang}','{$per_number}','{$per_people}','{$per_price}','{$seat_rate}','{$time}'
            );");
            mysqli_query($con,"insert into yien_cinema_history values(
            '{$cinema_name}','{$no}','{$piaofang}','{$per_number}','{$per_people}','{$per_price}','{$seat_rate}','{$time}'
            );");
        }
    }


 ?>
