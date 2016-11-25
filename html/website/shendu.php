<?php
/**
 * descript:获取晨读内容
 * @date 2016/6/16
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	header("Access-Control-Allow-Origin:*");
	set_time_limit(0);
	$host="56a3768226622.sh.cdb.myqcloud.com:4892";
    $name="root";
    $password="ctfoxno1";
    $dbname="website";

	//连接数据库
    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");
	$news = mysql_query("select * from chendu order by time desc");
	$mytitle="";
	$myimgurl="";
	$mypaperurl="";
	while($row=mysql_fetch_row($news,true))
	{
		$title=trim($row['title']);

		if(startwith($title,"深度"))
		{
			$mytitle=$title;
			$myimgurl=trim($row['imgurl']);
			$mypaperurl=trim($row['paperurl']);

			break;

		}
	}
	function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
			  return true;
		else
			  return false;
	}
	//$linkurl=$arr4[1][0];

	//$title=$arr3[1][0];
	//$img=$arr2[1][0];
	////$return=array("title"=>$title,"img"=>$img);
	////echo json_encode($return);
	$strtime = time()."".mt_rand(1000,9999);
	$filepath=dirname(__FILE__)."/images/".$strtime.".jpg";
	////echo $strtime;
	////echo $filepath;
	$img_content = file_get_contents("{$myimgurl}");
	file_put_contents($filepath,$img_content);


	$src = imagecreatefromjpeg($filepath);
	// Capture the original size of the uploaded image
	list($width,$height)=getimagesize($filepath);

	$newwidth=430;
	$newheight=300;
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	$imgpath = dirname(__FILE__)."/images/".time()."".mt_rand(1000,9999).".jpg";
	imagejpeg($tmp,$imgpath,100);
	imagedestroy($src);
	imagedestroy($tmp);
	unlink($filepath);
	//echo $imgpath;
	$return=array("title"=>$mytitle,"img"=>"http://www.qzdatawin.com/website/images/".basename($imgpath),"url"=>$mypaperurl);
	echo json_encode($return);




?>