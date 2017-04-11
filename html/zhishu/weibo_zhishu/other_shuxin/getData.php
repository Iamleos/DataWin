    <?php
    date_default_timezone_set("Asia/Shanghai");
    $date = date("Y-m-d");
    //获取艺人名单
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");
    $other = mysqli_query($con, "select name from linshi_word");
    $other = mysqli_fetch_all($other);
    mysqli_close($con);

    //入库
    $host="56a3768226622.sh.cdb.myqcloud.com";
    $name="root";
    $password="ctfoxno1";
    $dbname="zhishu";
    $con=mysqli_connect($host,$name,$password,$dbname,4892) or die("Can't connect mysql!".mysqli_connect_error() );
    mysqli_query($con,"set names utf8");

    //通过python脚本爬取数据，并且筛选出来
    foreach ($other as $key => $value) {

        $othername = $value[0];
        exec("python /var/www/html//zhishu/weibo_zhishu/other_shuxin/getId.py $othername", $id);
        sleep(1);
        if ($id[0] == 'error') {
            $id = NULL;
            continue;
        }
        exec("python /var/www/html//zhishu/weibo_zhishu/other_shuxin/getData.py $id[0] $id[1]", $json);
        sleep(1);
        $data = json_decode($json[0]);
	$sex = json_decode( json_encode($data->sex->key2),true);
        $age = json_decode( json_encode($data->age->key2->{0}),true);
        $tag =  json_decode( json_encode( $data->tag->key2->{0}),true);
        $star = json_decode( json_encode( $data->star->key2->{0}),true);
        $tag_name = array_keys($tag);
	if(count($sex) == 1 && count($age) == 1 && count($tag) == 1 && count($star) == 2){
		$json = NULL;
		exec("python /var/www/html//zhishu/weibo_zhishu/other_shuxin/getData.py $id[0] $id[1]", $json);
	        sleep(1);
	        $data = json_decode($json[0]);
	        $sex = json_decode( json_encode($data->sex->key2),true);
	        $age = json_decode( json_encode($data->age->key2->{0}),true);
	        $tag =  json_decode( json_encode( $data->tag->key2->{0}),true);
	        $star = json_decode( json_encode( $data->star->key2->{0}),true);
	        $tag_name = array_keys($tag);

	}
        if(count($tag_name) < 5){
            for ($i=count($tag_name); $i < 5; $i++) {
                $tag_name[$i] = "NULL".$i;
                $tag["NULL".$i] = "NULL";
            }
        }
        foreach ($star as $key1 => $value1) {
            $star[$key1] = (string)$value1;
        }
        $star_name = array_keys($star);
        //插入库
        mysqli_query($con, "insert into wzs_other_shuxin values('{$othername}','{$sex["man"]}','{$sex["woman"]}','{$age["0-12"]}','{$age["12-18"]}','{$age["19-24"]}'
        ,'{$age["25-34"]}','{$age["35-50"]}','{$age["other"]}',
        '{$tag_name[0]}','{$tag[$tag_name[0]]}','{$tag_name[1]}','{$tag[$tag_name[1]]}','{$tag_name[2]}','{$tag[$tag_name[2]]}','{$tag_name[3]}',
        '{$tag[$tag_name[3]]}','{$tag_name[4]}','{$tag[$tag_name[4]]}',
        '{$star[$star_name[0]]}','{$star[$star_name[1]]}','{$star[$star_name[2]]}','{$star[$star_name[3]]}','{$star[$star_name[4]]}','{$star[$star_name[5]]}',
        '{$star[$star_name[6]]}','{$star[$star_name[7]]}','{$star[$star_name[8]]}','{$star[$star_name[9]]}','{$star[$star_name[10]]}','{$star[$star_name[11]]}',
        '{$date}');");
        $id = NULL;
        $json = NULL;
        var_dump($othername);
    }

 ?>
