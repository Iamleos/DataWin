<?php
/**
 * descript:获取指定的ACCESS_TOKEN，并且访问永久素材
 * @date 2016/6/16
 * @author  XuJun
 * @version 1.0
 * @package
 */

	header("content-type:text/html;charset=utf-8");
	set_time_limit(0);
	$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx64adcb896b3ff72b&secret=ca40df5420661be6786d2bab9c07e96e";
	$html=file_get_contents($url);
	$obj=json_decode($html);
	$ACCESS_TOKEN=$obj->access_token;
	//使用方法
	$post_data = array(
	   "type"=>"news",
	   "offset"=>"0",
       "count"=>"20"
	);
	$result=send_post("https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token={$ACCESS_TOKEN}", $post_data);

	var_dump($result);

	/**
	 * 发送post请求
	 * @param string $url 请求地址
	 * @param array $post_data post键值对数据
	 * @return string
	 */
	function send_post($url, $post_data) {

	  $postdata = http_build_query($post_data);
	  $options = array(
		'http' => array(
		  'method' => 'POST',
		  'header' => 'Content-type:application/x-www-form-urlencoded',
		  'content' => $postdata,
		  'timeout' => 15 * 60 // 超时时间（单位:s）
		)
	  );
	  $context = stream_context_create($options);
	  $result = file_get_contents($url, false, $context);

	  return $result;
	}

?>