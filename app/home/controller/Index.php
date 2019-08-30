<?php
/**
* Controller index 
*/

namespace app\home\controller;

use \Think\core;

class Index extends \Think\core\Controller{
	
    public function index()
    {
	$M = new \Think\core\Model();
	$users = $M->query('select * from test');
	
	$this->setData('users',$users);
	$this->display();
    }
}
