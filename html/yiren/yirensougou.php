<?php
/**
 * descript: 艺人搜狗多维人气库
 * @date 2016/4/21
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");

	set_time_limit(0);
	ini_set('user_agent','Mozilla/5.0 (Windows NT 10.0; WOW64; rv:45.0)');
    $host="56a3768226622.sh.cdb.myqcloud.com:4892";

    $name="root";
    $password="ctfoxno1";

    $dbname="yiren";

	$con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
	mysql_query("set names utf8");
	mysql_query("create table if not exists yirensougou(yname varchar(30),ywbfs decimal(6,1),yincrewbfs decimal(4,1),yzhjh int(5),yzhwt int(5),yzhgz int(8),yqqht int(8),yqqgz int(8),yincreqqht int(5),yincreqqgz int(5),yacquitime date,primary key(yname,yacquitime));",$con);

	$result=mysql_query("select me from actname order by me limit 10",$con);

	while($row=mysql_fetch_row($result,true))
	{
		sleep(5);
		$yname=$row['me'];
		//$yname="黄晓明";
		$stamptime=time();
		$stamptime2=time()-6000;
		$sut=mt_rand(1000,9999);
		$w=mt_rand(0,99999999);
		$url="https://www.sogou.com/web?query={$yname}&_asf=www.sogou.com&_ast=&w=01019900&p=4000100&ie=utf8&sut={$sut}&sst0={$stamptime}&lkt=1!{$stamptime2}%{$stamptime2}";
		//echo $url;
		//$html=file_get_contents($url);



		////初始化
		//$ch = curl_init();
        ////设置选项，包括URL
	    //curl_setopt($ch, CURLOPT_URL, "https://www.baidu.com");
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_HEADER, 0);
        ////执行并获取HTML文档内容
	    //$html = curl_exec($ch);
        ////释放curl句柄
	    //curl_close($ch);
        ////打印获得的数据
	    //print_r($html);

		$html = curltest($url,"GET");
		echo $html;


		$arr1=array();
		$arr2=array();
		$arr3=array();
		$arr4=array();
		$arr5=array();
		$arr6=array();
		$arr7=array();


		//$preg1='/<span class="s2">([\w\W]*?)<\/span>/';

		$preg1='/<div class="xlwb-p1">([\w\W]*?)<\/div>/';

		$preg2='/<div class="str-box-v4">([\w\W]*?)<\/div>/';

		$preg3='/<span[\w\W]*?id="sogou_vr_11008601_topic_url_[\w\W]*?"[\w\W]*?>([\w\W]*?)<\/span>/';

		$preg4='/<span[\w\W]*?id="sogou_vr_11008601_question_url_[\w\W]*?"[\w\W]*?>([\w\W]*?)<\/span>/';

		$preg5='/<span[\w\W]*?id="sogou_vr_11008601_followed_url_[\w\W]*?"[\w\W]*?>([\w\W]*?)<\/span>/';

		$preg6='/<div class="vr-qq-interest151030">([\w\W]*?)<\/div>/';




		preg_match_all($preg1, $html,$arr1);
		//preg_match_all($preg2, $html,$arr2);
		//preg_match_all($preg3, $arr2[0][0],$arr3);
		//preg_match_all($preg4, $arr2[0][0],$arr4);
		//preg_match_all($preg5, $arr2[0][0],$arr5);
		//preg_match_all($preg6, $html,$arr6);

		var_dump($arr1[1][0]);
		echo "<br/>";
		//var_dump($arr2);

			//var_dump($arr6);
		//preg_match_all('/\d+/',$arr6[1][0],$arr7);

		//$yqqgz=$arr7[0][0];
		//$yqqht=$arr7[0][1];

        ////echo $yqqgz;
		////echo $yqqht;
		//$yzhjh=getNumber($arr3[1][0]);         //知乎精华问题
		//$yzhwt=getNumber($arr4[1][0]);		  //知乎提问
		//$yzhgz=getNumber($arr5[1][0]);		  //知乎关注

		////var_dump($arr8);

		////echo $yzhjh." ".$yzhwt." ".$yzhgz."<br/>";

		//$ywbfs=trim($arr1[1][2],"<>span万 "); //微博粉丝数量

		////echo $ywbfs;

		//$yacquitime=date("Y-m-d");

		//$yincreqqht=0;
		//$yincreqqgz=0;
		//$yincrewbfs=0;

		//$yinfo=mysql_query("select yqqht,yqqgz,ywbfs from yirensougou where yname='{$yname}'",$con);

        //while($row=mysql_fetch_row($yinfo))
        //{

            //$yincreqqht=$yqqht-$row[0];
            //$yincreqqgz=$yqqgz-$row[1];
            //$yincrewbfs=(float)$ywbfs-(float)$row[2];

        //}


        //mysql_query("delete  from yirensougou where yname='{$yname}'");

		//$sqlinsert="insert into yirensougou(yname,ywbfs,yincrewbfs,yzhjh,yzhwt,yzhgz,yqqht,yqqgz ,yincreqqht ,yincreqqgz,yacquitime)values('{$yname}','{$ywbfs}','{$yincrewbfs}','{$yzhjh}','{$yzhwt}','{$yzhgz}','{$yqqht}','{$yqqgz}','{$yincreqqht}','{$yincreqqgz}','{$yacquitime}')";
        //echo $sqlinsert."<br/>";
        //mysql_query($sqlinsert,$con);




	}

    /**
     * descript:  提取字符串的数字
     * @param   string
     * @return string
     * @date  2016/4/21
     */
    function getNumber($str)
    {
		preg_match_all('/\d+/',$str,$arr);
		return $arr[0][0];
    } // end func

	function curltest($url,$method="POST",$data=array(),$header=array(),$head=0,$body=0,$timeout = 30)
	{

		$ip = "115.28.".rand(1, 255).".".rand(1, 255);
		$headers = array("X-FORWARDED-FOR:$ip");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if (strpos($url, "https") !== false ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}
		}
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		switch ($method) {
		case 'POST':
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			break;
		case 'GET':
			break;
		case 'PUT':
			curl_setopt($ch, CURLOPT_PUT, 1);
			curl_setopt($ch, CURLOPT_INFILE, '');
			curl_setopt($ch, CURLOPT_INFILESIZE, 10);
			break;
		case 'DELETE':
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
		}


		curl_setopt($ch, CURLOPT_COOKIE, "username=test;password=test");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, $headers);
		curl_setopt($ch, CURLOPT_NOBODY, $body);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$rtn = curl_exec($ch); //获得返回
		if (curl_errno($ch)) {
			echo 'Errno'.curl_error($ch);//捕抓异常
		}
		curl_close($ch);
		return $rtn;
	}
	mysql_close();
?>