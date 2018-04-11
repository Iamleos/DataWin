<?php
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="movie";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error());
    $name = $_GET['name'];
    echo "<center><table  style='text-align:center'>
    <tr>
    <th width='400px'>查询结果</th>
    </tr></center></table>";
    $data = array();

    $sql = "select * from maoyan where movie like '%".$name."%'";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_assoc($result)){
        array_push($data,$row);
    }

    $sql = "select * from maoyan";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $tmp = $row["movie"];
        $percent = 0;
        similar_text($tmp,$name,$percent);
        if ($percent > 60 && $percent < 90) {
            array_push($data,$row);
        }
    }

    //输出
    if (count($data) > 0) {
        $data_count = count($data);
        $character_count = count($data[0]);
        $keys = array_keys($data[0]);
        echo "<center><table border='1' style='width:2000px;table-layout:fixed;'>";
        echo "<tr style='background-color:yellow'>";
        foreach ($keys as $key => $value) {
            echo "<td style='width:100%;word-break:keep-all;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'>".$value."</td>";
        }
        echo "</tr>";
        for ($j=0; $j < $data_count; $j++) {
            echo "<tr>";
            foreach ($data[$j] as $key => $value) {
                echo "<td style='width:100%;'>".$value."</td>";
            }
            echo "</tr>";
        }
        echo "</table></center>";
    }

?>
