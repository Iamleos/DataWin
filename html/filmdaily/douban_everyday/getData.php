<?php
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="TV";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    for($i = 0; $i < 10; $i++){
        $url = "https://movie.douban.com/j/search_subjects?type=tv&tag=%E5%9B%BD%E4%BA%A7%E5%89%A7&sort=time&page_limit=20&page_start=".$i*20;
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
        $result = json_decode($result);
        $tv = $result->subjects;
        foreach ($tv as $key => $value) {
            $url = $value->url;
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

            $playable = (string)$value->title;
            preg_match_all('/<span class="pl">首播:<\/span> <span property="v:initialReleaseDate" content="(.{0,10}).*">/', $result, $firstShow);
	var_dump($firstShow);
            preg_match_all('/<strong class="ll rating_num" property="v:average">(.*)<\/strong>/',$result, $pingfen);
            preg_match_all('/<a href="collections" class="rating_people"><span property="v:votes">(.*)<\/span>/', $result, $peopleNum);
            if(count($pingfen[0]) == 0){
                $pingfen = "NULL";
            }else {
                $pingfen = $pingfen[1][0];
            }

            if (count($peopleNum[0]) == 0) {
                $peopleNum = "NULL";
            }else {
                $peopleNum = $peopleNum[1][0];
            }
            $diff = date_diff(date_create($date),date_create($firstShow[1][0]));
            if (count($firstShow[0]) != 0 &&  $diff->format("%a") <= 60) {
                mysqli_query($con, "insert into douban_everyday values('{$value->title}', '{$firstShow[1][0]}', '{$pingfen}', '{$peopleNum}', '{$playable}','{$date}');");
                var_dump($value->title);
            }
            else {
                break 2;
            }
            sleep(2);
        }
    }
 ?>
