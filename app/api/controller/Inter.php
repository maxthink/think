<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\api\controller;

/**
 * Description of Inter
 *
 * @author mljm
 */
class Inter extends \Think\core\Controller {
    //{"type":"index","token":"ade812bd99ff76c9da095f6cf6738446","param":[{"imei":"866321032005772"}]}
    
    protected  $post; //请求数据
    protected  $token; //秘钥串

    public function __construct() {
	
	//获取post数据
        $json = file_get_contents('php://input');
	
	if (empty($json)) {
	    if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
		$json = $GLOBALS['HTTP_RAW_POST_DATA'];
	    }
	}
	if (empty($json)) {
	    if (isset($HTTP_RAW_POST_DATA)) {
		$json = $HTTP_RAW_POST_DATA;
	    }
	}
	if (empty($json)) {
	    $this->json('',2,'params error');
	}

        $data = json_decode($json,true);
        if(null==$data){
            $this->json('', 2, 'params error');
        }
        
        //如果没有时间， 直接返回错误, 如果时间差太大， 也返回错误
        if(!isset($data['data']['time'])){
            //$this->json('',2,'params error');     //调试接口状态不加时间
        } elseif( abs($data['data']['time'] - TIME ) > 20 ) {
            //$this->json('',2,'params error');
        }
        
        //校验
        $this->auth($data);
        
        //赋值
        $this->post = $data['data'];
        $this->token = $data['token'];
        
        
    }
    
    //接口验证
    protected function auth( &$data )
    {
        //验证秘钥, METHOD 是在Think.PHP 脚本里定义的
        switch ( METHOD ){
            case 'version':     //获取apk版本
            case 'addscore' :   //加积分
            case 'getinfo' :{   //获取用户信息
                    ksort( $data['data'] );
                    $token = md5( C('Secret/token').implode('&', $data['data'] ) );
                    if( $token !== $data['token'] ) {
                        $this->json($token,3,'token error');
                    }
                break;
                }
            default :{
                $this->json('',3,'params error');
            }
        }
    }


    private function sign($arr){
	ksort($arr,SORT_REGULAR );
	return implode('#*', $arr);
    }
    
}
