<?php
    date_default_timezone_set("Asia/Shanghai");
    $date=date("Y-m-d",strtotime("$argv[1]"));
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="filmdaily";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $flag = 0;
    $filename = array();
    $filmnamefile = fopen("/var/www/html/filmdaily/maoyan_city_supplement/filmname.txt","r");
    while(!feof($filmnamefile)){
        $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
        $flag++;
    }
    array_pop($filmname);
    foreach ($filmname as $key => $value) {
        $ch = curl_init("http://piaofang.maoyan.com/movie/".$value[1]."/cityBox?date=".$date);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/url\(\/\/(.*)\)\s*format\(\'truetype\'\)/', $result, $ttf);
        if(count($ttf[1])!=0){
            preg_match_all('/<div class="t-row">\s*<div class="t-col">(.*)<\/div>\s*<\/div>/' ,$result, $city);
            preg_match_all('/<div class="t-col"><i class="cs gsBlur">(.*)<\/i><\/div>/' ,$result, $data);
            $city = $city[1];
            $data = $data[1];
            preg_match_all('/url\(\/\/(.*)\)\sformat/',$result,$ttf);
            preg_match_all('/.*\/colorstone\/(.*)/',$ttf[1][0],$keyname);
            $com = "wget -P /var/www/html/filmdaily/maoyan_city_supplement ".$ttf[1][0];
            shell_exec($com);
            sleep(3);
            shell_exec('rename '.$keyname[1][0].' map.ttf /var/www/html/filmdaily/maoyan_city_supplement/'.$keyname[1][0]);
            sleep(3);
            shell_exec("/home/jdk/bin/java -classpath /var/www/html/filmdaily/maoyan_city_supplement maoyan");
            sleep(3);
            $map = array();
            $mapfile = fopen('/var/www/html/filmdaily/maoyan_city_supplement/key.txt','r');
            for($i=0; $i<10; $i++){
              $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
            }
            $number = array("0","1","2","3","4","5","6","7","8","9");
            $data = str_ireplace($map, $number, $data);
            foreach ($city as $key1 => $value1) {
                var_dump($key1);
                //boxoffice
                if(strstr($data[$key1*9],'亿')){
                    $data[$key1*9] = ((float)str_replace('亿','',$data[$key1*9]))*10000;
                }
                elseif (strstr($data[$key1*9],'万')) {
                    var_dump("wan");
                    $data[$key1*9] = str_replace('万','',$data[$key1*9]);
                }
                else {
                    $data[$key1*9] = ((int)str_replace('万','',$data[$key1*9]))/10000;
                }
                //sumboxoffice
                if(strstr($data[$key1*9+4],'亿')){
                    $data[$key1*9+4] = ((float)str_replace('亿','',$data[$key1*9+4]))*10000;
                }
                elseif (strstr($data[$key1*9+4],'万')) {
                    $data[$key1*9+4] = str_replace('万','',$data[$key1*9+4]);
                }
                else {
                    $data[$key1*9+4] = ((int)str_replace('万','',$data[$key1*9+4]))/10000;
                }
                //people
                if(strstr($data[$key1*9+7],'亿')){
                    $data[$key1*9+7] = ((float)str_replace('亿','',$data[$key1*9+7]))*10000;
                }
                elseif (strstr($data[$key1*9+7],'万')) {
                    $data[$key1*9+7] = str_replace('万','',$data[$key1*9+7]);
                }
                else {
                    $data[$key1*9+7] = ((int)str_replace('万','',$data[$key1*9+7]))/10000;
                }
                //changci
                if(strstr($data[$key1*9+8],'万')){
                    $data[$key1*9+8] = ((float)str_replace('万','',$data[$key1*9+8]))*10000;
                }
            }
            $data = str_replace('%','',$data);
            foreach ($city as $key2 =>$value2) {
                echo mysqli_query($con,"insert into maoyan_city(name,city,piaofang,pf_rate,pp_rate,boxofficesum,seat_rate,hj_rate,people_per,people_total,session,time) values('{$value[0]}','{$value2}','{$data[$key2*9]}','{$data[$key2*9+1]}','{$data[$key2*9+2]}','{$data[$key2*9+4]}','{$data[$key2*9+5]}','{$data[$key2*9+6]}','{$data[$key2*9+3]}','{$data[$key2*9+7]}','{$data[$key2*9+8]}','{$date}');");
            }
        }
        else {
            preg_match_all('/<div class="t-row">\s*<div class="t-col">(.*)<\/div>\s*<\/div>/' ,$result, $city);
            preg_match_all('/<div class="t-col">(.*)<\/div>/' ,$result, $data);
            $city =$city[1];
            $data = $data[1];
            array_shift($data);
            array_shift($data);
            for($i = 0; $i < 54; $i++){
                array_shift($data);
            }
            foreach ($city as $key1 => $value1) {
                //boxoffice
                if(strstr($data[$key1*9],'亿')){
                    $data[$key1*9] = ((float)str_replace('亿','',$data[$key1*9]))*10000;
                }
                elseif (strstr($data[$key1*9],'万')) {
                    var_dump("wan");
                    $data[$key1*9] = str_replace('万','',$data[$key1*9]);
                }
                else {
                    $data[$key1*9] = ((int)str_replace('万','',$data[$key1*9]))/10000;
                }
                //sumboxoffice
                if(strstr($data[$key1*9+4],'亿')){
                    $data[$key1*9+4] = ((float)str_replace('亿','',$data[$key1*9+4]))*10000;
                }
                elseif (strstr($data[$key1*9+4],'万')) {
                    $data[$key1*9+4] = str_replace('万','',$data[$key1*9+4]);
                }
                else {
                    $data[$key1*9+4] = ((int)str_replace('万','',$data[$key1*9+4]))/10000;
                }
                //people
                if(strstr($data[$key1*9+7],'亿')){
                    $data[$key1*9+7] = ((float)str_replace('亿','',$data[$key1*9+7]))*10000;
                }
                elseif (strstr($data[$key1*9+7],'万')) {
                    $data[$key1*9+7] = str_replace('万','',$data[$key1*9+7]);
                }
                else {
                    $data[$key1*9+7] = ((int)str_replace('万','',$data[$key1*9+7]))/10000;
                }
                //changci
                if(strstr($data[$key1*9+8],'万')){
                    $data[$key1*9+8] = ((float)str_replace('万','',$data[$key1*9+8]))*10000;
                }
            }
            $data = str_replace('%','',$data);
            foreach ($city as $key2 =>$value2) {
                echo mysqli_query($con,"insert into maoyan_city(name,city,piaofang,pf_rate,pp_rate,boxofficesum,seat_rate,hj_rate,people_per,people_total,session,time) values('{$value[0]}','{$value2}','{$data[$key2*9]}','{$data[$key2*9+1]}','{$data[$key2*9+2]}','{$data[$key2*9+4]}','{$data[$key2*9+5]}','{$data[$key2*9+6]}','{$data[$key2*9+3]}','{$data[$key2*9+7]}','{$data[$key2*9+8]}','{$date}');");
            }


        }

    }
    mysqli_close($con);
 ?>
