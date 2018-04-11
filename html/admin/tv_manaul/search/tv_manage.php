<?php
    header("Content-type:text/html;charset=utf-8");
    $json = $_POST["json"];
    $option = $_POST["opt"];
    $json = json_decode($json,true);
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error());

/*返回值
error1: 未提供name
success_delete: 删除数据成功
success_insert: 插入数据成功
success_modify: 修改数据成功
*/

    if ($option == "delete") {
        $name = $json["name"];
        if ($name == "") {
            echo json_encode(array("result"=>"error1"));
        }else {
            $name = str_replace("'","",$name);
            $sql = "delete from douban_tv where name='{$name}'";
            $result = mysqli_query($con,$sql);
            echo json_encode(array("result"=>"success_delete"));
        }
    }
    elseif ($option == "insert") {
        $name = $json["name"];
        if ($name == "") {
            echo json_encode(array("result"=>"error1"));
        }else {
            foreach ($json as $key => $value) {
                //去除非法字符
                $json[$key] = str_replace("'","",$value);
            }
            $sql = "insert into douban_tv values(
            '{$name}','{$json["pingfen"]}','{$json["juxing"]}','{$json["gongshi"]}','{$json["daoyan"]}','{$json["bianju"]}',
            '{$json["zhuyan"]}','{$json["type"]}','{$json["region"]}','{$json["language"]}','{$json["first_show"]}','{$json["jishu"]}'
            ,'{$json["danjipianchang"]}','{$json["youming"]}','{$json["where_to_see"]}','{$json["tag"]}','{$json["zzsy"]}','{$json["need_update"]}','{$json["href"]}'
            )";
            mysqli_query($con,$sql);
            echo json_encode(array("result"=>"success_insert"));
        }
    }
    elseif ($option == "modify") {
        $name = $json["name"];
        if ($name == "") {
            echo json_encode(array("result"=>"error1"));
        }else {
            $str = "";
            foreach ($json as $key => $value) {
                //echo $key;
                if ($key == "id") {
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
                $sql = "update douban_tv set ".$str." where name='{$name}'";
                mysqli_query($con,$sql);
                echo json_encode(array("result"=>"success_modify"));
            }
        }
    }
    else {
        echo json_encode(array("result"=>"error"));
    }

?>
