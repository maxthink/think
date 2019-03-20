<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Think\core;

/**
 * 缓存类必须实现的功能
 *
 * @author maxthink
 */
interface InterCache {
    
    //获取
    function get($cname);
    //设置
    function set($canme, $value, $secend );
    //删除
    function del($name ):bool;
    //值增加
    function inc($name, $step );
    //值减少
    function reduce($name, $step );
    
}
