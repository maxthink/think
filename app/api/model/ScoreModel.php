<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\api\model;

/**
 * 获取apk数据
 *
 * @author maxthink
 *
 */
class Score extends \Think\core\Model {

    private $table = 'app_score';
    private $userFields = ' version_name, version_code, url, description, timeline  ';

    //添加积分
    static function add_score($platid, $plat_record_id, $uid, $score, $descript = '', $request_time = 0) {

	$db = \Think\lib\Db::getInstance();
	if ($descript == '') {
	    $plat = C('Adplat/' . $platid);
	    $descript = $plat['desc'] ?? '积分';
	}

	//服务器积分回调的不需要 request_time
	$sql = "insert into app_user_record set uid=$uid, plat_id=$platid, plat_record_id=$plat_record_id, score=$score, timeline=" . TIME;
	//echo $sql;
	$inid = $db->link->insert($sql);

	//更新用户做任务的记录数
	$sql = "update app_user set task_count=task_count+1 where uid=$uid ";
	query($sql);

	addRecord($uid, $score, $descript, $plat); //添加用户记录
	//奖励积分
	$sql = 'select v_id,vv_id from app_user where uid=' . $uid;
	$pinfo = getrow($sql);
	if ($pinfo['v_id']) {
	    $p_score = floor($score * 10 / 100);
	    //echo 'p_score:'.$p_score;
	    if ($p_score > 0) {

		addRecord($pinfo['v_id'], $p_score, '邀请奖励', INVITE_P); //给上级添加 邀请奖励 记录
		$sql = 'insert app_user_devote set v_id=' . $pinfo['v_id'] . ', p_score=' . $p_score;
		if ($pinfo['vv_id']) {
		    $pp_score = floor($score * 5 / 100);
		    if ($pp_score > 0) {
			addRecord($pinfo['vv_id'], $pp_score, '二级邀请奖励', INVITE_PP); //给上上级添加 邀请奖励
			$sql .= ', vv_id=' . $pinfo['vv_id'] . ', pp_score=' . $pp_score;
		    }
		}
		$sql .= ', uid=' . $uid . ', income_id=' . intval($inid) . ', timeline=' . $_SERVER['REQUEST_TIME'];
		insert($sql);
	    }
	}

	$sql = 'select ' . $this->userFields . ' from ' . $this->table . ' where status=1 ';
	$result = $db->link->query($sql);
	if ($result->num_rows > 0) {
	    return $result->fetch_all(MYSQLI_ASSOC);
	} else {
	    return false;
	}
    }

    //获取 version_code 后最新版本apk， 或者说， 判断version-code 是不是最新版本
    function newer($version_code) {

	$db = \Think\lib\Db::getInstance();
	$sql = 'select ' . $this->userFields . ' from ' . $this->table . ' where version_code> ? and status=1 ';
	$stmt = $db->link->stmt_init();
	$stmt->prepare($sql);
	if (false === $stmt) {
	    throw new \Exception('prepare error: ' . $this->db->link->error);
	}
	$stmt->bind_param('i', $version_code);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if ($result->num_rows > 0) {
	    return $result->fetch_all(MYSQLI_ASSOC);
	} elseif (-1 === $result->num_rows) {
	    throw new \Exception('query error: ' . $db->link->error);
	} else {
	    return false;
	}
    }

    //获取用户数据 by uid
    function getUserById($uid = '') {
	if ('' == $uid) {
	    return false;
	} else {
	    $db = \Think\lib\Db::getInstance();
	    $sql = 'select ' . $this->userFields . ' from ' . $this->table . ' where uid=?';
	    $stmt = $db->link->stmt_init();
	    $stmt->prepare($sql);
	    if (false === $stmt) {
		throw new \Exception('prepare error: ' . $this->db->link->error);
	    }
	    $stmt->bind_param('i', $uid);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $stmt->close();
	    if ($result->num_rows > 0) {
		return $result->fetch_all(MYSQLI_ASSOC);
	    } elseif (-1 === $result->num_rows) {
		throw new \Exception('query error: ' . $db->link->error);
	    } else {
		return false;
	    }
	}
    }

}
