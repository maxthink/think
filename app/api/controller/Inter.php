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
    
    public function __construct() {
        $json = file_get_contents('php://input');
        $data = json_decode($json,true);
        if(null==$data){
            $this->json('', 2, 'params error');
        }
        
        //验证秘钥；
        switch ($data['type']){
            case 'getinfo' :{
                
                break;
                }
            case 'addscore' :{
                
                break;
                }
            
            case '':{
                
                break;
                }
                
        
        }
        
        //赋值
        $this->post = $data['data'];
        
    }
}
