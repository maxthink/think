<?php
/**
* Controller index 
*/

namespace app\api\controller;

use \Think\core;

class Index extends \Think\core\Controller{
	
    public function index() {

    	print_r($_SERVER);
    	
		$M = new \Think\core\Model();
		$users = $M->query('select * from user limit 3');
		
		$this->setData('users',$users);
		$this->display();
    }
}