<?php
/**
 * descript:接收上传的图片
 * @date 2016/4/20
 * @author  XuJun
 * @version 1.0
 * @package
 */
	//设置浏览器的浏览编码方式
	header("content-type:text/html;charset=utf-8");
	$host="115.28.209.109:3306";
    $name="root";
    $password="123456";
    $dbname="homework";

    $con=mysql_connect($host,$name,$password) or die("Can't connect mysql!");
    mysql_select_db($dbname,$con);
    mysql_query("set names utf8");

	$imgid = time();
    $input=file_get_contents("php://input");
	$path="/var/www/html/uploads/".$imgid.".jpg";
	mysql_query("insert into imageinfo (imgid,imgpath)values('{$imgid}','{$path}')");
	echo "insert into imageinfo (imgid,imgpath)values('{$imgid}','{$path}')";
	var_dump($input);

	mysql_close();

?>