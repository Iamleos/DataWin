<?php
/**
 * descript:接收指定参数，获取html内容
 * @date 2016/6/22
 * @author  XuJun
 * @version 1.0
 * @package
 */
	$str=$_SERVER["QUERY_STRING"];

	$html=file_get_contents(substr($str, 9));
	echo substr($str, 9);
	echo $html;
	file_put_contents("/var/www/html/index01/content.html",$html);

?>