<?php
/**
 * descript: 获取授权用户关注的微博
 * @date 2016/4/26
 * @author  XuJun
 * @version 1.0
 * @package
 */
	session_start();

	include_once( 'config.php' );
	include_once( 'saetv2.ex.class.php' );

	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

	$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );

	$url="https://api.weibo.com/2/statuses/friends_timeline.json";
	$access_token=
?>