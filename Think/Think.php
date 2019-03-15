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

        //初始化项目目录和默认代码内容
        if(!is_dir(APP_PATH))
        {
            require FRAME_PATH.'common/appinit.php';
            $init = new appinit();
            $init->init();
        }
        
        //引入框架基础类
        require FRAME_PATH.'common/function.php';
        require FRAME_PATH.'core/controller.php';
        require FRAME_PATH.'core/model.php';
        require FRAME_PATH.'core/view.php';
        
        spl_autoload_register( 'Think::_autoload' );
        register_shutdown_function( 'Think::_shutdown' );
        set_error_handler( 'Think::_error',E_ALL );
        set_exception_handler( 'Think::_exception' );
    }
    
    /**
     * 路由解析
     */
    private static function dispath()
    {
        if(file_exists(CONFIG_PATH.'config1.php')){
            self::$config = include CONFIG_PATH.'config.php';
        }else
        {
            throw new Exception('配置文件不存在');
        }
        

        $uri = isset($_SERVER['REQUEST_URI']) ?? $_SERVER['REQUEST_URI'];
        $c = 'index';   //默认控制器
        $a = 'index';   //默认方法
        
        if($uri=='/' || $uri=='/index.php')     // http://xxx.net/index.php http://xxx.net/  两种地址用默认的 index
        {
            $c = 'index';
            $a = 'index';
        }
        else
        {
            $paths = explode($uri, '/');
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
        
        $className = '\\'.APP_NAME.'\\'.APP_MODULE.'\\controller\\index';
        $do = new $className();
        $do->$a();
    }
    
    
    /**
     * 注册自动加载
     * 自动查询三个规则 controller, model, lib 
     */
    public static function _autoload($class)
    {
        $className = substr($class,intval(strrpos($class, '\\')) );

        if(  false !== strpos($class,'controller') )
        {
            if(file_exists( CONTROLLER_PATH.$className.'.php' )){
                include CONTROLLER_PATH.$className.'.php';
            }else
            {
                throw new Exception(" Controller codefile not found :".CONTROLLER_PATH.$className.'.php' );
            }
            return;
        }

        if(  false !== strpos('model') )
        {
            include MODEL_PATH.$className.'.php';
            return;
        }
        
        if(file_exists(FRAME_PATH.'lib/'.$className.'.php'))
        {
            include FRAME_PATH.'lib/'.$classname.'.php';
        }
        
        //self::_error('');
    }
    
    public static function _shutdown()
    {
        //是不是可以在这里加 中间件的  后间件
        //echo '<h1>脚本停止执行...:</h1>';
    }
    
    public static function _error($errCode, $errMsg, $errFile, $errLine)
    {
        echo '<h1>出错:</h1>';
        echo '<br>文件: '.$errFile;
        echo '<br>行数: '.$errLine;
        echo '<br>错误信息: '.$errMsg;
        echo '<br>错误级别'.$errCode;
        
    }
    
    public static function _exception($exception)
    {
        echo '<h1>异常:</h1>';
        echo '<br>文件: '.$exception->getfile();
        echo '<br>行数: '.$exception->getLine();
        echo '<br>错误信息: '.$exception->getMessage();
    }
    
    
}
 