<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
    $myfile = fopen("session.txt", "w") or die("Unable to open file!");
	//var_dump($_SESSION['token']);
	$txt =implode(',',$_SESSION['token']);
	//var_dump($txt);
	fwrite($myfile, $txt);
	fclose($myfile);
?>
授权完成,<a href="http://115.159.205.133">登录</a><br />
<?php
} else {
?>
授权失败。
<?php
}
?>
