<?php

/* 
 * 框架入口文件
 * betterThink, 更好的思考 -> 让编程者更好思考, 专注于项目开发, 不纠结框架逻辑
 * ( 更好的思考 -> 自己造一边轮子, 就知道造轮子的原理了, 看到别的轮子就知道怎么用了 *_* )
 * github:  https://github.com/maxthink/betterThink.git
 */
//namespace Think;

class Think{
    
    
    public static function run()
    {
        self::init();
        self::dispath();
    }
    
    /**
     * 定义初始常量
     */
    private static function init()
    {
        //路径常量
        define('ROOT', getcwd().'/../' );
        
        //应用目录地址
        if(!defined('APP_PATH')){
            //define( 'APP_PATH',ROOT.'/'.APP_PATH );
            define('APP_PATH',ROOT.'Application');
        }
        
        //配置文件
        define('CONFIG_PATH',APP_PATH.'/common/config.php');
        
        spl_autoload_register( 'Think::_autoload' );
        register_shutdown_function( 'Think::_shutdown' );
        set_error_handler( 'Think::_error' );
        set_exception_handler( 'Think::_excetion' );
    }
    
    /**
     * 路由解析
     */
    private static function dispath()
    {
        
    }
    
    
    /**
     * 注册自动加载
     */
    public static function _autoload()
    {
        
    }
    
    public static function _shutdown()
    {
        
    }
    
    public static function _error()
    {
        
    }
    
    public static function _excetion()
    {
        
    }
    
}
