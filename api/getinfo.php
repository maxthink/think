<?php

/*
 * 	获取用户信息,
 * 	
 * 	从post获取json数据.解析数据
 */
ini_set("display_errors", "On");
error_reporting(E_ALL);
include_once('../lib/init.php');

//获取post数据
$post = file_get_contents('php://input');
if (empty($post)) {
    if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
        $post = $GLOBALS['HTTP_RAW_POST_DATA'];
    }
}
if (empty($post)) {
    if (isset($HTTP_RAW_POST_DATA)) {
        $post = $HTTP_RAW_POST_DATA;
    }
}
if (empty($post)) {
    echo json_encode(array('status' => '-101', 'msg' => '缺少数据'));
    exit;
}
//{"type":"index","token":"ade812bd99ff76c9da095f6cf6738446","param":[{"imei":"866321032005772"}]}
$arr = [
    'type'=>'index',
    'token'=>'ade812bd99ff76c9da095f6cf6738446',
    'param'=>['imei'=>'866321032005772']
];
//echo json_encode($arr);exit;

//记录测试用
file_put_contents('record.txt', $post, FILE_APPEND);

$data = json_decode($post, true);

if (isset($data['type']) && isset($data['token']) && isset($data['param'])) {
    $type = $data['type'];
    $token = $data['token'];
    $param = $data['param'];
} else {
    echo json_encode(array('status' => '-101', 'msg' => '缺少参数2'));
    exit;
}

//密钥验证
if (!checkKey($type, $token, $param)) {
    echo json_encode(array('status' => '-102', 'msg' => '验证错误'));
    exit;
}



$plat = getPlat();
//$set = getSet();

$user = getUserByImei($param['imei']);

if ($user != false) {
    if (!isset($user['score']))
        $user['score'] = 0;
    if (!isset($user['old_score']))
        $user['old_score'] = 0;
    if (!isset($user['invitecode']))
        $user['invitecode'] = '';
    if (!isset($user['nick']))
        $user['nick'] = '还未设置昵称';

    $did = getDidCount($user['uid']);
    $today_score = getTodayScore($user['uid']);

    exit(json_encode(array(
        'status' => '0',
        'uid' => $user['uid'],
        'today' => $today_score,
        'score' => $user['score'],
        'total' => $user['total'],
        'task_count' => $user['task_count'],
        'exchange_count' => $user['exchange_count'],
        'invitecode' => $user['invitecode'],
        'nick' => $user['nick'],
        'zhifubao' => $user['zhifubao'],
        'mobile' => $user['mobile'],
        'timestamp' => $_SERVER['REQUEST_TIME'],
        'plat' => $plat,
        'did' => $did,
        'show_sign' => true,
        'show_end' => true,
    )));
}
else //没有该用户, 就添加用户
{
    $invitecode = getInviteCode();
    $sql = 'insert app_user set imei=\'' . $param['imei'] . '\', invitecode=\'' . $invitecode . '\', version=\'' . $param['version_code'] . '\', channel=\'' . $param['channel'] . '\', timeline=' . $_SERVER['REQUEST_TIME'];

    if (isset($param['vid']) && is_numeric($param['vid']) && $param['vid'] != '0') { //判断邀请者
        $sql .= ', v_id=' . $param['vid'];

        //判断邀请者的邀请者
        $vinfo = getUserById($param['vid']);
        if ($vinfo != false) {
            $sql .= ', vv_id=' . $vinfo['vid'];
        }
    }

    $inid = insert($sql); //添加用户

    $nick = '爱宝' . str_pad($inid, 4, '0', STR_PAD_LEFT);
    $sql = 'update app_user set nick=\'' . $nick . '\' where uid=' . $inid;
    query($sql);

    //输出新用户数据
    exit(json_encode(array(
        'status' => 0,
        'uid' => $inid,
        'nick' => $nick,
        'today' => 0,
        'score' => 0,
        'total' => 0,
        'task_count' => 0,
        'exchange_count' => 0,
        'invitecode' => $invitecode,
        'zhifubao' => '',
        'mobile' => '',
        'timestamp' => $_SERVER['REQUEST_TIME'],
        'plat' => $plat,
        'did' => '',
        'show_sign' => true,
        'show_end' => true,
    )));
}
?>