<?php
/**
 * Controller index
 */

namespace app\api\controller;

class User extends Inter {

    /**
     * 获取用户信息
     */
    public function getInfo() {

	//print_r($_SERVER);
	$uid = $this->post['uid'] ?? false;
	$imei = $this->post['imei'] ?? false;

	$Muser = new \app\api\model\User();

	//从uid获取用户信息
	if (false != $uid) {
	    $user = $Muser->getUserById($uid);
	    if (false !== $user) {
		$this->json($user);
	    } else {
		$this->json('', 1, 'no this user');
	    }
	    //从imei号获取用户信息
	} elseif (false != $imei) {
	    $user = $Muser->getUserByImei($imei);
	    if (false !== $user) {
		$this->json($user);
	    } else {
		//todo 添加该用户
		$inid = $Muser->addNewUser($imei);
		$user = $Muser->getUserById($inid);
		if (false !== $user) {
		    $this->json($user);
		} else {
		    $this->json('', 1, 'no this user');
		}
	    }
	} else {
	    $this->json('', 1, 'params error');
	}
    }

}
