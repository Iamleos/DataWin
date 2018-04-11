<?php
    include __DIR__."/function.php";
    $con = DataWin\getDB("zhishu");
    $result = mysqli_query($con,"select * from other_name where brand is not null;");
    $data = mysqli_fetch_all($result);
    foreach ($data as $key => $value) {
        $result = mysqli_query($con,"select name from other_name where name = '{$value[2]}';");
        if ($result->num_rows == 0) {
            mysqli_query($con, "insert into other_name values(
            '{$value[2]}','{$value[1]}',NULL,'1'
            );");
        }
    }
 ?>
