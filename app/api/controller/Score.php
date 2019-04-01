<?php
/**
* Controller score 
*/

namespace app\api\controller;

class Score extends Inter {
	
    public function addscore(){
	
        $score = $this->post['score'] ?? 0 ;
        $uid = $this->post['uid'] ?? false ;
        $imei = $this->post['imei'] ?? '';
	
	if( $score>0 ){
                $Muser = new \app\api\model\User();
		if($uid==false || $uid==0){
                    $user = $Muser->getUserByImei($imei);
                    if(false !== $user ){
                        add_order($platform, 0, $uid, $score, '', $waring );
                    }
                }
		

		exit( json_encode(array('status'=>0,'msg'=>'恭喜你获得'.$score.'积分') ) );
	}else{
		exit( json_encode(array('status'=>500,'msg'=>'') ) );
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