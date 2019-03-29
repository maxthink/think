<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * controller
 */
function A($control=''){
    
}

/**
 * 
 */
function B()
{
    
}

/**
 * 获取配置参数
 * 例如: $db_host = C('db/host'); 获取数据库主机
 * $config = C(); //获取所有配置, 不如直接用 Think::$config;
 */
function C($c='')
{
    if($c=='')
    {
        return Think::$config;  //配置参数为空, 就返回整个配置参数
    }else
    {
        if(strpos($c,'/')!==false)  //框架获取配置数据, 就是两层
        {
            $_arr =  explode('/', $c);
            return Think::$config[$_arr[0]][$_arr[1]];  //
        } else {
            return false;
        }
    }
}

/**
*
*/
function I($data, $val='string'){
    
    list($type, $name) = explode('.', $data);
    if( 'get'==$type ){
        return $_GET[$name] ?? false ;
    } elseif( 'post'==$type ) {
        return $_POST[$name] ?? false ;
    } elseif( ''==$type ) {
        return $_GET[$name] ?? ( $_POST[$name] ?? false ) ;
    }

}

/**
 * 
 */
function M()
{
    
}