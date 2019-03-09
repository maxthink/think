<?php
ini_set("display_errors","On");
error_reporting(E_ALL);
include_once('../lib/init.php');

$token		= isset($_REQUEST['token']) ? $_REQUEST['token'] : false;
$imei		= isset($_REQUEST['imei']) ? addslashes($_REQUEST['imei']) : false;
$version		= isset($_REQUEST['version']) ? intval($_REQUEST['version']) : 0;
$channel		= isset($_REQUEST['channel']) ? addslashes($_REQUEST['channel']) : '';

if($token===false || $imei===false ) exit( json_encode( array('status'=>500,'msg'=>'') ) );

$sig = md5($imei.'aic');

if($token==$sig){

	$user = getUserByImei($imei);

	if($user!==false){
		if(!isset($user['score'])) $user['score']=0;
		if(!isset($user['old_score'])) $user['old_score']=0;
		if(!isset($user['invitecode'])) $user['invitecode']='';
		
		$did = getDidCount($user['uid']);
		$today_score = getTodayScore($user['uid']);
		
		exit(json_encode( array(
			'status'=>'0',
			'uid'=>$user['uid'],
			'today'=>$today_score,
			'score'=>$user['score'],
			'total'=>$user['old_score'],
			'task_count'=>$user['task_count'],
			'exchange_count' => $user['exchange_count'],
			'invitecode'=>$user['invitecode'], 
			'zhifubao'=>$user['zhifubao'],
			'mobile'=>$user['mobile'],
			'timestamp'=>$_SERVER['REQUEST_TIME'],
			'show_sign'=>true,
			'show_end'=>true,
		) ) );
	}
	else	//没有该用户, 就添加用户
	{
		$invitecode = getInviteCode();
		$sql = "insert app_user set imei='$imei', invitecode='$invitecode', timeline=".$_SERVER['REQUEST_TIME'];
		$inid=insert($sql);
		exit( json_encode( array(
			'status'=>0,
			'uid'=>$inid,
			'today'=>0,
			'score'=>0,
			'total'=>0,
			'task_count'=>0,
			'exchange_count' => 0,
			'invitecode'=>$invitecode, 
			'zhifubao'=>'',
			'mobile'=>'',
			'timestamp'=>$_SERVER['REQUEST_TIME'],
			'show_sign'=>true,
			'show_end'=>true,
		) ) );
	}
	
	

}else{
	exit( json_encode( array('status'=>2 ) ) );
}


?>