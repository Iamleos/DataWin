<?php

    include "/var/www/html/zhishu/badiu_zhishu/function_lib/lib.php";

    //获取采集名单
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    $filmcon = DataWin\getDB("filmdaily");
    $filmname = mysqli_query($filmcon,"select mainname from filmname where zzsy=1;");
    $filmname = mysqli_fetch_all($filmname);
    mysqli_close($filmcon);
    $yirencon = DataWin\getDB("yiren");
    $yirenname = mysqli_query($yirencon,"select me from actname;");
    $yirenname = mysqli_fetch_all($yirenname);
    $zycon = DataWin\getDB("zhishu");
    $zyname = mysqli_query($zycon,"select name from linshi_word;");
    $zyname = mysqli_fetch_all($zyname);
    mysqli_close($yirencon);
    $tvcon = DataWin\getDB("TV");
    $tvname = mysqli_fetch_all(mysqli_query($tvcon,"select name from search_list;"));
    mysqli_close($tvcon);
    $con = DataWin\getDB("zhishu");
    $othercon = DataWin\getDB("zhishu");
    $othername = mysqli_query($othercon,"select name from other_name;");
    $othername = mysqli_fetch_all($othername);
    mysqli_close($othercon);

    foreach ($zyname as $key => $value) {
        exec("python /var/www/html/zhishu/badiu_zhishu/xuqiu/getData.py '{$value[0]}'",$json);

        $data = json_decode($json[0],true);
        $data = array_pop($data["data"]);
        if ($data == NULL) {
            $json = NULL;
            $data = NULL;
            continue;
        }
        $data2 = $data;
        $data3 = $data;
        $data4 = $data;
        $keyword3 = array();
        $keyword2 = array();
        $keyword4 = array();
        //搜索指数

        foreach ($data3 as $key1 => $value1) {
            $data3[$key1] = explode("\t", $value1);
        }
        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data3 as $key2 => $value2) {
                preg_match('/3=(.*?)\&/', $value2[1], $temp);
                if (((int)$temp[1]) > ((int)$flag_value)) {
                    $flag_value = $temp[1];
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);
                }
            }
            $keyword3[$i][0] = $flag_key;
            $keyword3[$i][1] = $flag_value;
            $data3[$flag_index][1] = "3=0&";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_zy_xuqiu_searchIndex values('{$value[0]}','{$keyword3[$i][0]}','{$keyword3[$i][1]}','{$date}')");
        }
        //来源检索词
        foreach ($data2 as $key1 => $value1) {
            $data2[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data2 as $key2 => $value2) {
                if (strstr($value2[1],"2=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"2=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("2=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword2[$i][0] = $flag_key;
            $keyword2[$i][1] = $flag_value;
            $data2[$flag_index][1] = "2=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_zy_xuqiu_from values('{$value[0]}','{$keyword2[$i][0]}','{$keyword2[$i][1]}','{$date}')");
        }

        //需求图谱
        foreach ($data4 as $key1 => $value1) {
            $data4[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data4 as $key2 => $value2) {
                if (strstr($value2[1],"0=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"0=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("0=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword4[$i][0] = $flag_key;
            $keyword4[$i][1] = $flag_value;
            $data4[$flag_index][1] = "0=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_zy_xuqiu_demand values('{$value[0]}','{$keyword4[$i][0]}','{$keyword4[$i][1]}','{$date}')");
        }
        sleep(1);
        $json = NULL;
        $data = NULL;


    }


    foreach ($filmname as $key => $value) {
        exec("python /var/www/html/zhishu/badiu_zhishu/xuqiu/getData.py '{$value[0]}'",$json);
        $data = json_decode($json[0],true);
        $data = array_pop($data["data"]);
        var_dump($value[0]);
        if ($data == NULL) {
            $json = NULL;
            $data = NULL;
            continue;
        }
        $data2 = $data;
        $data3 = $data;
        $data4 = $data;
        $keyword3 = array();
        $keyword2 = array();
        $keyword4 = array();
        //搜索指数

        foreach ($data3 as $key1 => $value1) {
            $data3[$key1] = explode("\t", $value1);
        }
        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data3 as $key2 => $value2) {
                preg_match('/3=(.*?)\&/', $value2[1], $temp);
                if (((int)$temp[1]) > ((int)$flag_value)) {
                    $flag_value = $temp[1];
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);
                }
            }
            $keyword3[$i][0] = $flag_key;
            $keyword3[$i][1] = $flag_value;
            $data3[$flag_index][1] = "3=0&";
        }

        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_film_xuqiu_searchIndex values('{$value[0]}','{$keyword3[$i][0]}','{$keyword3[$i][1]}','{$date}')");
        }
        //来源检索词
        foreach ($data2 as $key1 => $value1) {
            $data2[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data2 as $key2 => $value2) {
                if (strstr($value2[1],"2=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"2=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("2=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword2[$i][0] = $flag_key;
            $keyword2[$i][1] = $flag_value;
            $data2[$flag_index][1] = "2=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_film_xuqiu_from values('{$value[0]}','{$keyword2[$i][0]}','{$keyword2[$i][1]}','{$date}')");
        }

        //需求图谱
        foreach ($data4 as $key1 => $value1) {
            $data4[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data4 as $key2 => $value2) {
                if (strstr($value2[1],"0=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"0=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("0=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword4[$i][0] = $flag_key;
            $keyword4[$i][1] = $flag_value;
            $data4[$flag_index][1] = "0=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_film_xuqiu_demand values('{$value[0]}','{$keyword4[$i][0]}','{$keyword4[$i][1]}','{$date}')");
        }
        sleep(1);
        $json = NULL;
        $data = NULL;
    }


    foreach ($tvname as $key => $value) {
        exec("python /var/www/html/zhishu/badiu_zhishu/xuqiu/getData.py '{$value[0]}'",$json);
        $data = json_decode($json[0],true);
        $data = array_pop($data["data"]);
        var_dump($value[0]);
        if ($data == NULL) {
            $json = NULL;
            $data = NULL;
            continue;
        }
        $data2 = $data;
        $data3 = $data;
        $data4 = $data;
        $keyword3 = array();
        $keyword2 = array();
        $keyword4 = array();
        //搜索指数

        foreach ($data3 as $key1 => $value1) {
            $data3[$key1] = explode("\t", $value1);
        }
        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data3 as $key2 => $value2) {
                preg_match('/3=(.*?)\&/', $value2[1], $temp);
                if (((int)$temp[1]) > ((int)$flag_value)) {
                    $flag_value = $temp[1];
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);
                }
            }
            $keyword3[$i][0] = $flag_key;
            $keyword3[$i][1] = $flag_value;
            $data3[$flag_index][1] = "3=0&";
        }

        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_tv_xuqiu_searchIndex values('{$value[0]}','{$keyword3[$i][0]}','{$keyword3[$i][1]}','{$date}')");
        }
        //来源检索词
        foreach ($data2 as $key1 => $value1) {
            $data2[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data2 as $key2 => $value2) {
                if (strstr($value2[1],"2=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"2=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("2=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword2[$i][0] = $flag_key;
            $keyword2[$i][1] = $flag_value;
            $data2[$flag_index][1] = "2=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_tv_xuqiu_from values('{$value[0]}','{$keyword2[$i][0]}','{$keyword2[$i][1]}','{$date}')");
        }

        //需求图谱
        foreach ($data4 as $key1 => $value1) {
            $data4[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data4 as $key2 => $value2) {
                if (strstr($value2[1],"0=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"0=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("0=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword4[$i][0] = $flag_key;
            $keyword4[$i][1] = $flag_value;
            $data4[$flag_index][1] = "0=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_tv_xuqiu_demand values('{$value[0]}','{$keyword4[$i][0]}','{$keyword4[$i][1]}','{$date}')");
        }
        sleep(1);
        $json = NULL;
        $data = NULL;
    }

    foreach ($yirenname as $key => $value) {
        exec("python /var/www/html/zhishu/badiu_zhishu/xuqiu/getData.py $value[0]",$json);
        $data = json_decode($json[0],true);
        $data = array_pop($data["data"]);
        if ($data == NULL) {
            $json = NULL;
            $data = NULL;
            continue;
        }

        $data2 = $data;
        $data3 = $data;
        $data4 = $data;
        $keyword3 = array();
        $keyword2 = array();
        $keyword4 = array();

        //搜索指数

        foreach ($data3 as $key1 => $value1) {
            $data3[$key1] = explode("\t", $value1);
        }
        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data3 as $key2 => $value2) {
                preg_match('/3=(.*?)\&/', $value2[1], $temp);
                if (((int)$temp[1]) > ((int)$flag_value)) {
                    $flag_value = $temp[1];
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);
                }
            }
            $keyword3[$i][0] = $flag_key;
            $keyword3[$i][1] = $flag_value;
            $data3[$flag_index][1] = "3=0&";
        }

        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_yiren_xuqiu_searchIndex values('{$value[0]}','{$keyword3[$i][0]}','{$keyword3[$i][1]}','{$date}')");
        }
        //来源检索词
        foreach ($data2 as $key1 => $value1) {
            $data2[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data2 as $key2 => $value2) {
                if (strstr($value2[1],"2=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"2=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("2=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword2[$i][0] = $flag_key;
            $keyword2[$i][1] = $flag_value;
            $data2[$flag_index][1] = "2=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_yiren_xuqiu_from values('{$value[0]}','{$keyword2[$i][0]}','{$keyword2[$i][1]}','{$date}')");
        }

        //需求图谱
        foreach ($data4 as $key1 => $value1) {
            $data4[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data4 as $key2 => $value2) {
                if (strstr($value2[1],"0=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"0=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("0=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword4[$i][0] = $flag_key;
            $keyword4[$i][1] = $flag_value;
            $data4[$flag_index][1] = "0=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_yiren_xuqiu_demand values('{$value[0]}','{$keyword4[$i][0]}','{$keyword4[$i][1]}','{$date}')");
        }
        sleep(1);
        $json = NULL;
        $data = NULL;
    }

    foreach ($othername as $key => $value) {
        exec("python /var/www/html/zhishu/badiu_zhishu/xuqiu/getData.py '{$value[0]}'",$json);

        $data = json_decode($json[0],true);
        $data = array_pop($data["data"]);
        if ($data == NULL) {
            $json = NULL;
            $data = NULL;
            continue;
        }
        $data2 = $data;
        $data3 = $data;
        $data4 = $data;
        $keyword3 = array();
        $keyword2 = array();
        $keyword4 = array();
        //搜索指数

        foreach ($data3 as $key1 => $value1) {
            $data3[$key1] = explode("\t", $value1);
        }
        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data3 as $key2 => $value2) {
                preg_match('/3=(.*?)\&/', $value2[1], $temp);
                if (((int)$temp[1]) > ((int)$flag_value)) {
                    $flag_value = $temp[1];
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);
                }
            }
            $keyword3[$i][0] = $flag_key;
            $keyword3[$i][1] = $flag_value;
            $data3[$flag_index][1] = "3=0&";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_other_xuqiu_searchIndex values('{$value[0]}','{$keyword3[$i][0]}','{$keyword3[$i][1]}','{$date}')");
        }
        //来源检索词
        foreach ($data2 as $key1 => $value1) {
            $data2[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data2 as $key2 => $value2) {
                if (strstr($value2[1],"2=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"2=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("2=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword2[$i][0] = $flag_key;
            $keyword2[$i][1] = $flag_value;
            $data2[$flag_index][1] = "2=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_other_xuqiu_from values('{$value[0]}','{$keyword2[$i][0]}','{$keyword2[$i][1]}','{$date}')");
        }

        //需求图谱
        foreach ($data4 as $key1 => $value1) {
            $data4[$key1] = explode("\t", $value1);
        }

        for ($i=0; $i < 15; $i++) {
            $flag_value = 0;
            $flag_key = 0;
            $flag_index = 0;
            foreach ($data4 as $key2 => $value2) {
                if (strstr($value2[1],"0=") == false) {
                    continue;
                }
                $temp_array = explode("&",$value2[1]);
                foreach ($temp_array as $key3 => $value3) {
                    if(strstr($value3,"0=") ==false){
                        continue;
                    }else {
                        $temp = str_replace("0=","",$value3);
                        break;
                    }
                }

                if (((int)$temp) > ((int)$flag_value)) {
                    $flag_value = $temp;
                    $flag_key = $value2[0];
                    $flag_index = $key2;
                    //var_dump($flag_value);

                }
            }
            $keyword4[$i][0] = $flag_key;
            $keyword4[$i][1] = $flag_value;
            $data4[$flag_index][1] = "0=0";
        }
        for($i = 0; $i < 15; $i++){
            mysqli_query($con,"insert into bd_other_xuqiu_demand values('{$value[0]}','{$keyword4[$i][0]}','{$keyword4[$i][1]}','{$date}')");
        }
        sleep(1);
        $json = NULL;
        $data = NULL;


    }


 ?>
