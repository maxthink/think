<?php

/* 
 * 框架入口文件
 * betterThink, 更好的思考 -> 让编程者更好思考, 专注于项目开发, 不纠结框架逻辑
 * ( 更好的思考 -> 自己造一边轮子, 就知道造轮子的原理了, 看到别的轮子就知道怎么用了 *_* )
 * github:  https://github.com/maxthink/betterThink.git
 */
//namespace Think;

class Think{
    
    public static $config;

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
        define('ROOT', getcwd().'/../' );   //
        
        //框架根目录
        define('FRAME_PATH',__DIR__.'/');
        echo FRAME_PATH;
        
        
        //应用目录地址
        if(!defined('APP_PATH')){
            if(defined('APP_NAME')){
                define('APP_PATH',ROOT.APP_NAME);
            }
            else
            {
                define('APP_PATH',ROOT.'Application');
            }            
        }
        
        //应用配置文件
        define('CONFIG_PATH',APP_PATH.'/Common/config.php');
        define('CONTROLLER_PATH',APP_PATH.'/Controller/');
        define('MODEL_PATH',APP_PATH.'/Model/');
                
        //初始化项目目录内容
        if(!is_dir(APP_PATH))
        {
            self::_initApp();
        }
        
        //引入框架基础类
        require FRAME_PATH.'common/function.php';
        require FRAME_PATH.'core/Controller.php';
        require FRAME_PATH.'core/Model.php';
        require FRAME_PATH.'core/View.php';
        
        spl_autoload_register( 'Think::_autoload' );
        register_shutdown_function( 'Think::_shutdown' );
        set_error_handler( 'Think::_error' );
        set_exception_handler( 'Think::_exception' );
    }
    
    /**
     * 路由解析
     */
    private static function dispath()
    {
        self::$config = include CONFIG_PATH;
        
        $uri = $_SERVER['REQUEST_URI'];
        
        if($uri=='/' || $uri=='/index.php'){
            $c = 'indexController';
            $a = 'index';
        }else
        {
            $paths = explode($uri, '/');
            if(strpos('/index.php',$uri)===0)
            {
                $c = $paths[1].'Controller';
                $a = $paths[2];
            }else
            {
                $c = $paths[0].'Controller';
                $a = $paths[1];
            }
        }
        
        $do = new $c();
        $do->$a();
    }
    
    
    /**
     * 注册自动加载
     */
    public static function _autoload($classname)
    {
        if(  false !== strpos($classname,'Controller') )
        {
            include CONTROLLER_PATH.$classname.'.php';
            return;
        }

        if(  false !== strpos($classname,'Model') )
        {
            include MODEL_PATH.$classname.'.php';
            return;
        }
        
        if(file_exists(FRAME_PATH.'lib/'.$classname.'.php'))
        {
            include FRAME_PATH.'lib/'.$classname.'.php';
        }
        
        //self::_error('');
    }
    
    public static function _shutdown()
    {
        echo '<br>shut down';
        //var_dump($msg);
    }
    
    public static function _error($msg)
    {
        echo '<br>error:';
        var_dump($msg);
        echo '<br>';
    }
    
    public static function _exception($exception)
    {
        echo 'exception<pre>';
        var_dump($exception);
        //echo $exception->file;
        //echo $exception->message;
        
        echo '</pre>';
    }
    
    /**
     * 初始化项目路径,index 控制器
     */
    private static function _initApp()
    {
        mkdir(APP_PATH);
        mkdir( CONFIG_PATH );
        mkdir( CONTROLLER_PATH );
        file_put_contents( APP_PATH.'/Controller/indexController.php',"<?php\n/**\n* indexController \n */\nclass indexController{\n\t public function index(){\n\t\t echo 'Think iframe ! ';\n\t }\n}");
        mkdir( MODEL_PATH );
    }
}
 