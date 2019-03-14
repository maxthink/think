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
        //根路径常量
        define('ROOT', getcwd().'/../' );   //
        
        //框架根目录
        define('FRAME_PATH',__DIR__.'/');
        echo FRAME_PATH;
        
        //项目(应用)名
        if(!defined('APP_NAME')){
            define('APP_NAME','app');
        }        
        
        //应用目录地址
        if(!defined('APP_PATH')){
            define('APP_PATH',ROOT.APP_NAME);           
        }
        
        //应用模块( 这里可以处理成 根据uri自动匹配模块, ..... )
        if(!defined('APP_MODULE'))
        {
            define('APP_MODULE', 'home');   //默认创建 home 模块(前端显示模块)
        }
        
        //应用配置文件
        define('CONFIG_PATH',APP_PATH.'/'.APP_MODULE.'/common/');
        define('CONTROLLER_PATH',APP_PATH.'/'.APP_MODULE.'/controller/');
        define('MODEL_PATH',APP_PATH.'/'.APP_MODULE.'/model/');
        define('VIEW_PATH',APP_PATH.'/'.APP_MODULE.'/view/');

        //初始化项目目录内容
        if(!is_dir(APP_PATH))
        {
            self::_initApp();
        }
        
        //引入框架基础类
        require FRAME_PATH.'common/function.php';
        require FRAME_PATH.'core/controller.php';
        require FRAME_PATH.'core/model.php';
        require FRAME_PATH.'core/view.php';
        
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
        $c = 'index';   //默认控制器
        $a = 'index';   //默认方法
        
        if($uri=='/' || $uri=='/index.php')     // http://xxx.net/index.php http://xxx.net/  两种地址用默认的 index
        {
            $c = 'index';
            $a = 'index';
        }
        else
        {
            echo '------';
            $paths = explode($uri, '/');
            var_dump($paths);
            exit(2);
            if(strpos('/index.php',$uri)===0)   // http://xxx.net/index.php?/index/index   这种地址
            {
                $c = $paths[1];
                $a = $paths[2];
            }else                               // http://xxx.net/index/index   这种地址
            {
                $c = $paths[0];
                $a = $paths[1];
            }
        }
        //use APP_NAME\APP_MODULE;
        $do = new $c();
        $do->$a();
    }
    
    
    /**
     * 注册自动加载
     */
    public static function _autoload($classname)
    {
        echo '_autoload: '.$classname;
        if(  false !== strpos($classname) )
        {
            include CONTROLLER_PATH.$classname.'.php';
            return;
        }

        if(  false !== strpos($classname) )
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
        mkdir( APP_PATH );                  //应用 目录
        mkdir( APP_PATH.'/'.APP_MODULE );   //应用->模块 目录
        mkdir( CONFIG_PATH );               //应用->模块->配置文件,公共方法 目录
        mkdir( CONTROLLER_PATH );           //应用->模块->控制器 目录
        mkdir( MODEL_PATH );                //应用->模块->模型 目录
        mkdir( VIEW_PATH );                 //应用->模块->视图 目录
        
        //初始化文件和
        file_put_contents( CONTROLLER_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\controller;'."\n/**\n* Controller index \n*/\nclass indexController{\n\tpublic function index(){\n\t\techo 'Think iframe ! ';\n\t}\n}");
        file_put_contents( MODEL_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\model;'."\n/**\n* model index\n*/\nclass index{\n\tpublic function index(){\n\t\techo 'Think iframe ! ';\n\t}\n}");
        file_put_contents( VIEW_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\view;'."\n/**\n* view index \n */\nclass index{\n\tpublic function index(){\n\t\t echo 'Think iframe ! ';\n\t}\n}");

        copy(FRAME_PATH.'init/common/config.php', CONFIG_PATH.'config.php');
        //copy(FRAME_PATH.'init/controller/index.php', CONTROLLER_PATH.'index.php');  //没解决配置命名空间问题
        //copy(FRAME_PATH.'init/model/index.php', MODEL_PATH.'index.php');
        //copy(FRAME_PATH.'init/view/index.php', VIEW_PATH.'index.php');
        
    }
}
 