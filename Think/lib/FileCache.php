<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Think\lib;

/**
 * 文件缓存类
 *
 * @author maxthink
 */
class FileCache extends Think\core\InterCache{
    
    public static $instance;
    
    private $cachePatch;


    private function __construct() {
        
        
        $this->cachePatch = APP_PATH.'runtime\cache';
        if(!is_dir($this->cachePatch)){
            
            mkdir($this->cachePatch);
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
        
    }
    //设置
    function set($canme, $value, $secend );
    //删除
    function del($name ):bool;
    //值增加
    function inc($name, $step );
    //值减少
    function reduce($name, $step );
    
    private function getFileName($cname){
        return $this->cachePatch.'/'.$cname.'.cache';
    }
    
    private function getFileContent($filename){
        if(file_exists($filename)){
            $handle = fopen($filename, 'r');
            $str = fread($handle, filesize($filename));
            return unserialize($str);
        } else {
            return false;
        }
    }
    
    private function putFileContent($filename, $value ){
        if(file_exists($filename)){
            $handle = fopen($filename, 'w');
            $str = fread($handle, filesize($filename));
            return unserialize($str);
        }else {
            return false;
        }
    }
    
}
