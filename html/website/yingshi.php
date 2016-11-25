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
	$url="http://mp.weixin.qq.com/mp/homepage?__biz=MzIwMDMzNzcyNQ==&hid=5&sn=422b7e730e56fd2dd9736921082f8f60#wechat_redirect";
	$html=file_get_contents($url);
	$arr1=array();
	$arr2=array();
	$arr3=array();
	$preg1='/<a class="list_item js_post"[\w\W]*?>([\w\W]*?)<\/a>/';
	$preg2='/<img class="img js_img" src="([\w\W]*?)"[\w\W]*?\/>/';
	$preg3='/<h2 class="title js_title"[\w\W]*?>([\w\W]*?)<\/h2>/';
	preg_match_all($preg1, $html, $arr1);
	preg_match_all($preg2,$arr1[1][0],$arr2);
	preg_match_all($preg3,$arr1[1][0],$arr3);

	$arr4 = array();
	$preg4='/<a class="list_item js_post" href="([\w\W]*?)">[\w\W]*?<\/a>/';
	preg_match_all($preg4,$html,$arr4);
	$linkurl=$arr4[1][0];

	$title=$arr3[1][0];
	$img=$arr2[1][0];
	//$return=array("title"=>$title,"img"=>$img);
	//echo json_encode($return);
	$strtime = time()."".mt_rand(1000,9999);
	$filepath=dirname(__FILE__)."/images/".$strtime.".jpg";
	//echo $strtime;
	//echo $filepath;
	$img_content = file_get_contents("{$img}");
	file_put_contents($filepath,$img_content);


	$src = imagecreatefromjpeg($filepath);
	// Capture the original size of the uploaded image
	list($width,$height)=getimagesize($filepath);

	$newwidth=420;
	$newheight=300;
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	$imgpath = dirname(__FILE__)."/images/".time()."".mt_rand(1000,9999).".jpg";
	imagejpeg($tmp,$imgpath,100);
	imagedestroy($src);
	imagedestroy($tmp);
	unlink($filepath);
	//echo $imgpath;
	$return=array("title"=>$title,"img"=>"http://www.qzdatawin.com/website/images/".basename($imgpath),"url"=>$linkurl);
	echo json_encode($return);




?>