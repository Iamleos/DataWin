<?php
    header("Content-type:text/html;charset=utf-8");
    $json = $_POST["json"];
    $option = $_POST["opt"];
    $json = json_decode($json,true);
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="movie";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error());

/*返回值
error0: 删除和修改时未提供ｉｄ
error1: 插入数据未提供name
error2: 插入数据时不能提供ｉｄ
success_delete: 删除数据成功
success_insert: 插入数据成功
success_modify: 修改数据成功
*/

    if ($option == "delete") {
        $id = $json["id"];
        if ($id == "") {
            echo json_encode(array("result"=>"error0"));
        }else {
            $sql = "delete from maoyan where id={$id}";
            $result = mysqli_query($con,$sql);
            echo json_encode(array("result"=>"success_delete"));
        }
    }
    elseif ($option == "insert") {
        $id = $json["id"];
        if ($id != "") {
            echo json_encode(array("result"=>"error2"));
        }else {
            $movie = $json["movie"];
            if ($movie == "") {
                echo json_encode(array("result"=>"error1"));
            }else {
                foreach ($json as $key => $value) {
                    //去除非法字符
                    $json[$key] = str_replace("'","",$value);
                }
                $sql = "insert into maoyan values(
                '{$movie}','{$json["daoyan"]}','{$json["zhuyan"]}','{$json["zhipian"]}','{$json["faxing"]}','{$json["jianjie"]}',
                null,'{$json["time"]}','{$json["category"]}','{$json["region"]}'
                )";
                mysqli_query($con,$sql);
                echo json_encode(array("result"=>"success_insert"));
            }
        }
    }
    elseif ($option == "modify") {
        $id = $json["id"];
        if ($id == "") {
            echo json_encode(array("result"=>"error0"));
        }else {
            $str = "";
            foreach ($json as $key => $value) {
                //echo $key;
                if ($key == "id") {
                    continue;
                }
                if ($key == "movie") {
                    continue;
                }
                if ($value != "") {
                        //去除非法字符
                    $value = str_replace("'","",$value);
                    $str .= $key."="."'$value',";
                }
            }
            if ($str == "") {
                echo json_encode(array("result"=>"error"));
            }
            else{
                $str = substr($str,0,strlen($str)-1);
                $sql = "update maoyan set ".$str."where id={$id}";
                mysqli_query($con,$sql);
                echo json_encode(array("result"=>"success_modify"));
            }
        }
    }
    else {
        echo json_encode(array("result"=>"error"));
    }

?>
