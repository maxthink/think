<?php
ini_set("display_errors","On");
error_reporting(E_ALL);
include_once('../lib/init.php');


file_put_contents("kk.txt", print_r($_REQUEST,1) );

$token		= isset($_REQUEST['token']) ? $_REQUEST['token'] : false;
$uid		= isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : false;
$imei		= isset($_REQUEST['imei']) ? addslashes($_REQUEST['imei']) : false;
$score		= isset($_REQUEST['score']) ? intval($_REQUEST['score']) : 0;
$platform	= isset($_REQUEST['platform']) ? intval($_REQUEST['platform']) : false;
$version	= isset($_REQUEST['versioncode']) ? intval($_REQUEST['versioncode']) : 0;
$time		= isset($_REQUEST['time']) ? intval($_REQUEST['time']) : 0;

$waring = 0;
if( abs($_SERVER['REQUEST_TIME']-$time)>20 ) $waring=$time;

$key = md5($uid.'as'.$score.PRODUCT_KEY.$platform.$time);

if($token==$key && $score>0 ){

	if($uid==false || $uid==0) $uid = getUserIdByImei($imei);
	add_order($platform, 0, $uid, $score, '', $waring );
	
	exit( json_encode(array('status'=>0,'msg'=>'恭喜你获得'.$score.'积分') ) );
}else{
	exit( json_encode(array('status'=>500,'msg'=>'') ) );
}


?>