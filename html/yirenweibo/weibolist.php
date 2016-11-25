<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$myfile = fopen("session.txt", "r") or die("Unable to open file!");
$str=fread($myfile,filesize("session.txt"));
fclose($myfile);
$arr=explode(',',$str);
$_SESSION['token']['access_token']=$arr[0];

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done


$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
var_dump($ms);
echo "<br/>";
var_dump($user_message);


?>

