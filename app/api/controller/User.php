<?php
/**
* Controller index
*/

namespace app\api\controller;

class User extends Inter{

    public function getInfo() {

    	//print_r($_SERVER);
    	$uid = $this->post['uid'] ?? false ;
    	$imei = $this->post['imei'] ?? false ;
        
	$Muser = new \app\api\model\User();
	
	if( false != $uid ){
	    $user = $Muser->getUserById($uid);
            if(false!==$user){
                $this->json($user);
            } else {
                $this->json('',1,'no this user');
            }
	    
	} elseif( false != $imei ) {
	    $user = $Muser->getUserByImei($imei);
	    if( false !== $user ){
		$this->json($user);
	    } else {
		//todo 添加该用户
		$inid = $Muser->addNewUser($imei);
                $user = $Muser->getUserById($inid);
                if( false !== $user){
                    $this->json($user);
                } else {
                    $this->json('',1,'no this user');
                }
	    }
	} else {
	    $this->json('',1,'params error');
	}
        
    }
}