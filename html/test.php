<?php
/**
 * descript:测试电影销售效率
 * @date
 * @author  XuJun
 * @version 1.0
 * @package
 */
	header("content-type:text/html;charset=utf-8");
	$arr=array();
	$arr['title']="2016年8月1日电影销售效率";
	$arr['data']=array();
	for($i=0;$i<10;$i++)
	{
		$datalist=array("name"=>"绝地逃亡","jrpp"=>"23.0%","mrpp"=>"20.2%","jrhjpp"=>"25.1%","mrhjpp"=>"21.5%","pjpj"=>"35%","gyxl"=>"11.1%","ppxl"=>"10.7%","wxbl"=>"74.0%","dbpf"=>"7.5");
		$arr['data'][$i]=$datalist;
	}
	echo json_encode($arr);

?>