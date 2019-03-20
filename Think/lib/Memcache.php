<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Memcache
 *
 * @author maxthink
 */

namespace Think\lib;

class Memcache extends \Think\core\InterCache{
    
    protected $link;    //连接
    public static $instance;
    
    private function __construct() {
        
        $host = C('Cache/host');
        $port = C('Cache/port');
        $this->link = new Memcache;
        $this->link->connect($host, $port); //连接时间要不要修改为 set_time_limit
    }
    
    public static function getInstance(){
        if( !($this->instance instanceof self) ){
            $this->instance = new Memcache();
        }
        return $this->instance;
    }
    
    private function __clone(){}
    
    //获取  value or false(bool)
    function get($cname=''){
        //$this->link->get( $cname );
        return memcache_get( $this->link, $cname);
    }
    
    //设置 value or false(bool)  secend 为秒数时不能超过 2592000秒（30天）
    function set($cname='', $value='', $secend=0 ){
        //$this->link->set( $cname, $value, 0, $secend );
        return memcache_set( $this->link, $cname, $value, 0, $secend);
    }

    //删除
    function del($cname ):bool{
        memcache_delete($this->link, $cname, 0);
    }
    
    //值增加 new value or false 
    function inc($name, $step ){
        return memcache_increment( $this->link, $name, intval($step) );
    }
    
    //值减少 return new value or false ,  new value will not lt 0
    function reduce($name, $step ){
        return memcache_decrement( $this->link, $name, intval($step) );
    }
        
}
