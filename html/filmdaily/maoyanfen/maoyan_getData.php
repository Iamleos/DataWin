<?php
    include "/var/www/html/filmdaily/lib_function/function.php";
  $host="56a3768226622.sh.cdb.myqcloud.com";
  $name="root";
  $password="ctfoxno1";
  $dbname="filmdaily";
  $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
  mysqli_query($con,"set names utf8");
  mysqli_query($con, "drop table if exists maoyanfen");
  mysqli_query($con, "create table maoyanfen(mname varchar(30), mfen varchar(30), mfennum varchar(30), mwantnum varchar(30), macquitime varchar(30));");
  date_default_timezone_set("Asia/Shanghai");
  $date = date("Y-m-d",time());
  $flag = 0;
  $filename = array();
  $filmnamefile = fopen(__DIR__."/filmname.txt","r");
  while(!feof($filmnamefile)){
    $filmname[$flag] = explode(" ",str_replace("\n","",fgets($filmnamefile)));
    $flag++;
  }
  array_pop($filmname);
  foreach ($filmname as $key => $value) {
    $url = "http://piaofang.maoyan.com/movie/".$value[1]."?_v_=yes";
    $result = getResult($url);
    preg_match_all('/<p class=\"score-num \">\s*<i class=\"cs\">(.*)<\/i>/', $result, $scorenum);
    preg_match_all('/<span class=\"wish-num \">\s*<i class=\"cs\">(.*)<\/i>/',$result, $wantnum);
    preg_match_all('/charset=utf\-8;base64,(.*)\) format/', $result, $ttf);
    $scorenum = $scorenum[1][0];
    $wantnum = $wantnum[1][0];
    if(count($ttf[1])==0){
        shell_exec("php ".__DIR__."/maoyan_getData2.php");
    }
    else{
        $woff = $ttf[1][0];
        $woff_file = fopen(__DIR__."/map.woff","w");
        $woff_url = __DIR__."/map.woff";
        $ttf_url = __DIR__."/map.ttf";
        fwrite($woff_file,base64_decode($woff));
        exec("python ".__DIR__."/woff2otf.py {$woff_url} {$ttf_url}");
        sleep(1);
        exec("java -classpath ".__DIR__."/ maoyan");
        sleep(1);
        $map = array();
        $mapfile = fopen(__DIR__.'/key.txt','r');
        for($i=0; $i<10; $i++){
          $map[$i] = "&#x".str_replace("\r\n","",fgets($mapfile)).";";
        }
        $number = array("0","1","2","3","4","5","6","7","8","9");
        $scorenum = str_ireplace($map, $number, $scorenum);
        $wantnum = str_ireplace($map, $number, $wantnum);
        var_dump($map);
        var_dump($number);
        var_dump($wantnum);
        var_dump($value[0]);
        mysqli_query($con, "insert into maoyanfen(mname, mfen, mfennum, mwantnum, macquitime) values('{$value[0]}','{$scorenum}','{$wantnum}','{$wantnum}','{$date}');");
        sleep(3);
    }
  }
























 ?>
