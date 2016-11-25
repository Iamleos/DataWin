<?php
/**
 * descript:计算艺人口碑
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
#! /usr/bin/php -q
	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)');

	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="yiren";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");




    mysql_query("create table if not exists yirenkoubei(yname varchar(20),typositive decimal(4,3),tqpositive decimal(4,3),dbpositive decimal(4,3),yypositive decimal(4,3),ylpositive decimal(4,3),bkfen decimal(2,1),yyfen decimal(2,1),ysfen decimal(2,1),acquitime date,primary key(yname,acquitime));",$con);

	//配置文智云api的感情分析
    error_reporting(E_ALL ^ E_NOTICE);
	require_once './src/QcloudApi/QcloudApi.php';

	$config = array(
					'SecretId'       => 'AKIDqWWvnE7LjI6RKwCItQJucvJaYpAzY6BW',
					'SecretKey'      => 'emBnTWsMp1KnV44W9dPhOVm20aysv9HH',
					'RequestMethod'  => 'POST',
					'DefaultRegion'  => 'gz');

	$wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);

    $yulequanstr=array();
	$doubanbazustr=array();
	$youyibastr=array();
	$tianyastr=array();
	$tuqustr=array();


	//将娱乐圈吧标题名字连起来
	$resyule=mysql_query("select ytitle from yuleba;",$con);
	while($row=mysql_fetch_row($resyule,true))
	{
		//var_dump($row['ytitle']);
		$yulequanstr[]=$row['ytitle'];
	}

	//var_dump($yulequanstr);

	//将豆瓣八组标题名字连起来
	$resdouban=mysql_query("select dtitle from doubanbazu;",$con);
	while($row=mysql_fetch_row($resdouban,true))
	{
		$doubanbazustr[]=$row['dtitle'];
	}

	//echo $doubanbazustr."<br/>";

	//将友谊吧标题名字连起来
	$resyouyi=mysql_query("select ytitle from youyiba;",$con);
	while($row=mysql_fetch_row($resyouyi,true))
	{
		$youyibastr[]=$row['ytitle'];
	}

	//echo $youyibastr."<br/>";

	//将天涯八卦标题名字连起来
	$restianya=mysql_query("select ttitle from tianya;",$con);
	while($row=mysql_fetch_row($restianya,true))
	{
		$tianyastr[]=$row['ttitle'];
	}

	//echo $tianyastr."<br/>";

	//将晋江兔区标题名字连起来
	$restuqu=mysql_query("select jtitle from tuqu;",$con);
	while($row=mysql_fetch_row($restuqu,true))
	{
		$tuqustr[]=$row['jtitle'];
	}

	//echo $tuqustr."<br/>";

	$result=mysql_query("select * from actname",$con);

	/**
	 * descript:提取各大圈吧命中的标题,由于时间紧张，采用的是n平方的时间复杂度，所以效率不是很高，值得改进
	 * @param $arrname,$arrtitle
	 * @return string;
	 * @date 2016/4/20
	 */
	function hittile($arrname,$arrtitle)
	{
		$str="";

		$titlenum=count($arrtitle);

		for($j=0;$j<$titlenum;$j++)
		{
			//echo $arrtitle[$j]."------";
			//var_dump($arrname[$i]);
			//var_dump( strpos($arrtitle[$j],$arrname[$i]));
			if($arrname['me']!=""&&strpos($arrtitle[$j],$arrname['me']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc1']!=""&&strpos($arrtitle[$j],$arrname['bc1']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc2']!=""&&strpos($arrtitle[$j],$arrname['bc2']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc3']!=""&&strpos($arrtitle[$j],$arrname['bc3']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc4']!=""&&strpos($arrtitle[$j],$arrname['bc4']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc5']!=""&&strpos($arrtitle[$j],$arrname['bc5']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc6']!=""&&strpos($arrtitle[$j],$arrname['bc6']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc7']!=""&&strpos($arrtitle[$j],$arrname['bc7']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc8']!=""&&strpos($arrtitle[$j],$arrname['bc8']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc9']!=""&&strpos($arrtitle[$j],$arrname['bc9']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
			if($arrname['bc10']!=""&&strpos($arrtitle[$j],$arrname['bc10']) !== false)
			{
				$str=$str.$arrtitle[$j];
			}
		}

		return $str;
	} // end func


	/**
	 * descript:提取文字的正面情感因素
	 * @param $content 文字内容
	 * @return $positive 正面情感因素
	 * @date 2016/4/20
	 */
	function positiveFactor($context)
	{
		$positive=0.0;
		global $wenzhi;

		if($context!="")
		{

			$package = array("content"=>"{$context}");

			$a = $wenzhi->TextSentiment($package);

			if ($a === false) {
				$error = $wenzhi->getError();
				echo "Error code:" . $error->getCode() . ".\n";
				echo "message:" . $error->getMessage() . ".\n";
				echo "ext:" . var_export($error->getExt(), true) . ".\n";
			} else {
				$positive=sprintf("%.3f", $a['positive']);

				//var_dump($typositive);
			}

		}
		return $positive;


	} // end func


	/**
	 * descript: 获取豆瓣网页数据
	 * @param   $url  url
	 * @return  $html 网页内容
	 * @date  2016/4/20
	 */
	function getHtml($url)
	{
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
		$dxycontent = curl_exec($ch);
		var_dump($dxycontent);

	} // end func
	while($row=mysql_fetch_row($result,true))
	{
		//var_dump($row);
		//提取五大圈吧的情感系数
        $typositive=positiveFactor(hittile($row,$tianyastr));
		$tqpositive=positiveFactor(hittile($row,$tuqustr));
		$dbpositive=positiveFactor(hittile($row,$doubanbazustr));
		$yypositive=positiveFactor(hittile($row,$youyibastr));
		$ylpositive=positiveFactor(hittile($row,$yulequanstr));

		$tianya = hittile($row,$tianyastr);
		$tuqu = hittile($row,$tuqustr);
		$doubanbazu = hittile($row,$doubanbazustr);
		$youyi = hittile($row,$youyibastr);
		$yule = hittile($row,$yulequanstr);

		if(mysql_num_rows(mysql_query("select * from hyhittitle where yname='{$row}'"))<1)
		{
			$today=date("Y-m-d");
			mysql_query("insert into hyhittitle (yname,tianya,tuqu,douban,youyi,yule,yacquitime) values ('{$row}','{$tianya}','{$tuqu}','{$douban}','{$youyi}','{$yule}','{$today}')");
			echo "insert into hyhittitle (yname,tianya,tuqu,douban,youyi,yule,yacquitime) values ('{$row}','{$tianya}','{$tuqu}','{$douban}','{$youyi}','{$yule}','{$today}')";
		}else {
		    mysql_query("update hyhittitle set tianya = '{$tianya}' where yname='{$row}'");
			mysql_query("update hyhittitle set tuqu = '{$tuqu}' where yname='{$row}'");
			mysql_query("update hyhittitle set douban = '{$douban}' where yname='{$row}'");
			mysql_query("update hyhittitle set youyi = '{$youyi}' where yname='{$row}'");
			mysql_query("update hyhittitle set yule = '{$yule}' where yname='{$row}'");

		}

		//mysql_query
		//提取艺人豆瓣电影，豆瓣音乐，豆瓣图书评分
		$url1="https://movie.douban.com/subject_search?search_text={$row['me']}&cat=1002";
		$url2="https://music.douban.com/subject_search?search_text={$row['me']}&cat=1003";
		$url3="https://book.douban.com/subject_search?search_text={$row['me']}&cat=1001";
		$html1=file_get_contents($url1);
		$html2=file_get_contents($url2);
		$html3=file_get_contents($url3);
		//echo $html;
		//$html1=getHtml($url1);
		//$html2=getHtml($url2);
		//$html3=getHtml($url3);

		//var_dump($html3);

		$arr1=array();
		$arr2=array();
		$arr3=array();
		$arr4=array();

		$preg1='/<span class="rating_nums">([\w\W]*?)<\/span>/';
		$preg2='/<span class="rating_nums">([\w\W]*?)<\/span>/';
		$preg3='/<span class="rating_nums">([\w\W]*?)<\/span>/';
		$preg4='/<span class="pl">([\w\W]*?)<\/span>/';

		$preg='/\d+/'; //用于提取字符串中的数字

		preg_match_all($preg1,$html1, $arr1);
		preg_match_all($preg2,$html2, $arr2);
		preg_match_all($preg3,$html3, $arr3);
		preg_match_all($preg4,$html1, $arr4);
		//var_dump($arr4);
		preg_match_all($preg,$arr4[1][0], $ys1);
		$dy1=$ys1[0][0];
		preg_match_all($preg,$arr4[1][1], $ys2);
		$dy2=$ys2[0][0];
		preg_match_all($preg,$arr4[1][2], $ys3);
		$dy3=$ys3[0][0];

		echo $dy1.$dy2.$dy3."<br/>";

		//var_dump($arr1);
		$bkfen=$arr3[1][0];   //图书评分

		$yyfen=$arr2[1][0];   //音乐评分

		$ysfen1=$arr1[1][0];   //影视分
		$ysfen2=$arr1[1][1];   //影视分
		$ysfen3=$arr1[1][2];   //影视分

		$ysfen=((float)$ysfen1*(float)$dy1+(float)$ysfen2*(float)$dy2+(float)$ysfen3*(float)$dy3)/((float)$dy1+(float)$dy2+(float)$dy3);
		$ysfen=sprintf("%.1f", $ysfen);

		//echo $ysfen;
		$acquitime=date("Y-m-d");
		$sqlstr="insert into yirenkoubei(yname,typositive,tqpositive,dbpositive,yypositive,ylpositive,bkfen,yyfen,ysfen,acquitime)values('{$row['me']}','{$typositive}','{$tqpositive}','{$dbpositive}','{$yypositive}','{$ylpositive}','{$bkfen}','{$yyfen}','{$ysfen}','{$acquitime}');";
        echo $sqlstr."<br/>";
		mysql_query($sqlstr);



	}
	mysql_close($con);

?>