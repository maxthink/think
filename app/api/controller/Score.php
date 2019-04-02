<?php
/**
* Controller score 
*/

namespace app\api\controller;

class Score extends Inter {
	
    public function addscore(){
	
        $score = $this->post['score'] ?? 0 ;	//积分
        $uid = $this->post['uid'] ?? false ;
        $imei = $this->post['imei'] ?? false;	
	$platform = $this->post['plat'] ?? '';	//广告平台 id
	
	if( $score>0 ){
	    $Muser = new \app\api\model\User();
	    if( $imei !== false ){
		$uid = $Muser->getUserIdByImei($imei);
		if( false === $user ){
		    $uid =$Muser->addNewUser($imei);
		}
		ScoreModel::add_score($platform, 0, $uid, $score, '' );
		$this->json('恭喜你获得'.$score.'积分');
	    } else {
		$this->json('',1,'params error');
	    }
	}else{
	    $this->json('',1,'params error');
	}

    }


    public function getInfo() {

    	//print_r($_SERVER);
    	
		$M = new \Think\core\Model();
		$users = $M->query('select * from app_user limit 3 ');
		var_dump($users);

		$this->setData('users',$users);
		//$this->display();
    }
}