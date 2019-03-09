<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
include_once('../lib/init.php');


$version_code	= isset($_REQUEST['version_code']) ? intval($_REQUEST['version_code']) : false;
$time			= $_SERVER['REQUEST_TIME'];


if( $version_code===false ){
	echo json_encode(array('status'=>'1','desc'=>'未知版本'));
	exit;
}

$sql = 'select * from app_version where version_code>'.$version_code.' and status=0 order by version_code desc limit 1';
//echo $sql;
$db->query($sql);
$res = $db->getrow();

$out['status'] = 0;
if( !empty($res) ){

	$out['status'] = 1;
	$out['info']['version_name'] = $result['version_name'];
	$out['info']['upgradeDesc'] = $result['description'];
	$out['info']['dwUrl'] = $result['down_url'];
	$out['info']['md5'] = $result['md5'];
	$out['info']['version_code'] = $result['version_code'];

	
	//echo json_encode(array('status'=>'0','url'=>$res['url'],'new_version'=>$res['version_code'],'desc'=>$res['description']));
}else{
	$out['status'] = 1;
	$out['info']['version_name'] = $result['version_name'];
	$out['info']['upgradeDesc'] = $result['description'];
	$out['info']['dwUrl'] = $result['down_url'];
	$out['info']['md5'] = $result['md5'];
	$out['info']['version_code'] = $result['version_code'];

	// echo json_encode(array('status'=>'1','desc'=>'最新版本不用更新'));
}

echo json_encode($out);

?>