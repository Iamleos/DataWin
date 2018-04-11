<?php
     function getResult($webUrl){
        $flag = 0;
        do {
            $ch = curl_init($webUrl);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
            $flag++;
        } while (($result ==NULL || $result == "") && ($flag <3));
        if ($flag >= 3) {
            return NULL;
        }
        else {
            return $result;
        }
    }

    //去除亿，万，并且转化
    function yi2wan($data){
        if (strstr($data,'亿')) {
            return (string)(str_ireplace('亿','',$data)*10000);
        }
        else {
            return str_ireplace('万','',$data);
        }
    }
    //单位归“1”
    function toOne($data){
        if (strstr($data,'亿')) {
            return (string)(str_ireplace('亿','',$data)*100000000);
        }
        elseif (strstr($data,'万')) {
            return (string)(str_ireplace('万','',$data)*10000);
        }
        else{
            return $data;
        }
    }
    //单位归“万”
    function toWan($data){
        if (strstr($data,'亿')) {
            return (string)(str_ireplace('亿','',$data)*10000);
        }
        elseif (strstr($data,'万')) {
            return str_ireplace('万','',$data);
        }
        else{
            return (string)($data/10000);
        }
    }

    //去处%
    function del_percent($data){
        return str_ireplace('%','',$data);
    }
    //获取DB

    function getDB($dbname){
        $host="56a3768226622.sh.cdb.myqcloud.com";
        $name="root";
        $password="ctfoxno1";
        $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
        mysqli_query($con,"set names utf8");
        return $con;

    }

    //convert woff to ttf & get key-value
    function getKV($file_dir,$woff){
        $woff_file = fopen($file_dir."/map.woff","w");
        $woff_url = $file_dir."/map.woff";
        $ttf_url = $file_dir."/map.ttf";
        fwrite($woff_file,base64_decode($woff));
        exec("python ".$file_dir."/woff2otf.py {$woff_url} {$ttf_url}");
        sleep(1);
        exec("java -classpath ".$file_dir."/ maoyan");
        sleep(1);
        $map = array();
        $mapfile = fopen($file_dir.'/key.txt','r');
        for($i=0; $i<10; $i++){
          $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
        }
        $number = array("0","1","2","3","4","5","6","7","8","9");
        $kv[0] = $map;
        $kv[1] = $number;
        return $kv;

    }


 ?>
