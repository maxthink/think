<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Redis
 *
 * @author maxthink
 */

namespace Think\lib;

class Redis extends \Think\core\InterCache{
    
    protected $link;    //连接
    public static $instance;
    
    private function __construct() {
        
        $host = C('Cache/host');
        $port = C('Cache/port');
        $this->link = new Redis();
        try{
            $this->link->connect($host, $port);
        } catch ( \Exception $ex ) {
            echo 'Redis 连接问题: '.$ex->message;
        }
        
    }
    
    public static function getInstance(){
        if( !($this->instance instanceof self) ){
            $this->instance = new Memcache();
        }
        return $this->instance;
    }
    
    private function __clone(){}
    
    //获取
    function get($cname){
        return $this->link->get($cname);
    }
    //设置
    function set($canme, $value, $secend=0 ){
        if( 0 >= intval($secend) ){
            return $this->link->set($canme, $value );
        } else {
            return $this->link->setex($canme, intval($secend), $value);
        }
    }
    //删除
    function del($name ):bool{
        return $this->link->delete($name);
    }
    //值增加
    function inc($name, $step ){
        $this->link->incrBy( $name, intval($step) );
    }
    //值减少
    function reduce($name, $step ){
        $this->link->decrBy( $name, intval($step) );
    }
    
}
