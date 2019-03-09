<?php

ini_set("display_errors","On");
error_reporting(E_ALL);

include_once('../lib/init.php');


$imei		= isset($_POST['imei']) ? ($_POST['imei']) : false;
$token		= isset($_POST['token']) ? $_POST['token'] : false;
$version	= isset($_POST['version']) ? intval($_POST['version']) : 0;
$channel	= isset($_POST['channel']) ? addslashes($_POST['channel']) : '';

//签名错误, 时间差错误, 不显示错误原因, 防止有人找漏洞做参考用
if($token===false || $imei===false ) exit( json_encode( array('status'=>500,'msg'=>'') ) );

$sig = md5($imei.PRODUCT_KEY.$time);

if($token==$sig){

	$today = strtotime(date('Y-m-d'));
	$sql = "select count(*) from app_share where imei='$imei' and timeline>".$today;
	$share_count = getone($sql);
	
	if($share_count==false){
		$sql = "insert app_share set imei='$imei',timeline=".time();
		$db->query($sql);
		exit( json_encode( array('status'=>0,'msg'=>'分享成功，看看奖励你多少积分吧') ) );
	}else{
		
		exit( json_encode( array('status'=>1,'msg'=>'今天已分享过了，明日继续分享') ) );
		
	}
	
	add_order($config_platform['sign'], $inid, $uid, $score );

}
else
{
	
}

?>