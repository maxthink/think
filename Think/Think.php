<?php
/* 
 * 框架入口文件
 * betterThink, 更好的思考 -> 让编程者更好思考, 专注于项目开发, 不纠结框架逻辑
 * ( 更好的思考 -> 自己造一边轮子, 就知道造轮子的原理了, 看到别的轮子就知道怎么用了 *_* )
 * github:  https://github.com/maxthink/Think.git
 * 
 * 框架原理, 为了方便, 做好规则...
 * 
 * 第一步, 初始化: 定义一堆常量, 项目路径, 框架路径, 引入基础类
 * 第二步, 路由分析, 加载cmv 
 */


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
        //定义一个时间， 老用
        define('TIME',$_SERVER['REQUEST_TIME'] ?? time() );
        
        //根路径常量
        define('ROOT', getcwd().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR );   //
        
        //框架根目录
        define('FRAME_PATH',__DIR__.DIRECTORY_SEPARATOR);
        
        //项目名
        if(!defined('APP_NAME')){
            define('APP_NAME','app');
        }        
        
        //项目目录地址
        if(!defined('APP_PATH')){
            define('APP_PATH',ROOT.APP_NAME.DIRECTORY_SEPARATOR);           
        }
        
        //项目模块( 这里可以处理成 根据uri自动匹配模块, ..... )
        if(!defined('MODULE_NAME')) {
            define('MODULE_NAME', 'home');   //默认创建 home 模块(前端显示模块)
        }

        //模块地址
        if(!defined('MODULE_PATH')){
            define('MODULE_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR);           
        }

        
        //项目配置文件
        define('CONFIG_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR);
        define('CONTROLLER_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR);
        define('MODEL_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR);
        define('VIEW_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR);

        //初始化项目目录和默认代码内容
        if(!is_dir(APP_PATH))
        {
            require FRAME_PATH.'common'.DIRECTORY_SEPARATOR.'appinit.php';
            $init = new appinit();
            $init->init();
        }

        //初始化模块
        if(!is_dir(MODULE_PATH))
        {
            require FRAME_PATH.'common'.DIRECTORY_SEPARATOR.'appinit.php';
            $init = new appinit();
            $init->init();
        }
        
        //引入框架基础类
        require FRAME_PATH.'common'.DIRECTORY_SEPARATOR.'function.php';
        require FRAME_PATH.'core'.DIRECTORY_SEPARATOR.'Controller.php';
        require FRAME_PATH.'core'.DIRECTORY_SEPARATOR.'Model.php';
        require FRAME_PATH.'core'.DIRECTORY_SEPARATOR.'View.php';
        
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
        if(file_exists(CONFIG_PATH.'config.php')){
            self::$config = include CONFIG_PATH.'config.php';
        }else
        {
            throw new Exception('配置文件不存在');
        }

        $query =  $_SERVER['QUERY_STRING'] ?? '';
        $ctrollerName = 'Index';   //默认控制器
        $mether = 'index';   //默认方法
        
        if( '/'==$query || $_SERVER['DOCUMENT_URI']==$query )     // http://xxx.net/index.php http://xxx.net/  两种地址用默认的 index.php
        {
            $ctrollerName = 'Index';
            $mether = 'index';
        } else {
            
            $paths = explode('/', $query);

            if( ''==$paths[0] ){
                array_shift($paths);
            }

            $ctrollerName = $paths[0];
            $mether = $paths[1];

            // if(strpos($_SERVER['DOCUMENT_URI'],$query)===0)   // http://xxx.net/index.php?/index/index   这种地址
            // {
            //     $c = $paths[1];
            //     $a = $paths[2];
            // }else                               // http://xxx.net/index/index   这种地址
            // {
            //     $c = $paths[0];
            //     $a = $paths[1];
            // }
        }

        //echo 'controller:'. $c . ' mether:'.$a;

        $className = APP_NAME.'\\'.MODULE_NAME.'\\controller\\'.ucfirst($ctrollerName);
        $do = new $className();
        $do->$mether();
    }
    
    
    /**
     * 注册自动加载
     * 自动查询三个规则 controller, model, lib 
     */
    public static function _autoload($class)
    {
        //echo $class.PHP_EOL;
        //$className = basename($class,'\\');
        $className = ucfirst(substr($class, strrpos($class,'\\')+1 ));
        //echo 'classname: '.$className;

        //controller
        if(  false !== strpos($class,'controller') ) {
            $classPath = CONTROLLER_PATH.$className.'.php';
            if (file_exists( $classPath )) {
                include $classPath;
            } else {
                throw new Exception(" 控制器文件没找到: ".$classPath );
            }
            return;
        }

        //model
        if(  false !== strpos($class,'model') ) {
            $modelPath = MODEL_PATH.$className.'.php';
	       if ( file_exists( $modelPath )) {
                include $modelPath;
            } else {
                throw new Exception(" Model codefile not found : ".$modelPath );
            }
            return;
        }
        
        //lib 类库
        if(file_exists(FRAME_PATH.'lib'.DIRECTORY_SEPARATOR.$className.'.php'))
        {
            include FRAME_PATH.'lib'.DIRECTORY_SEPARATOR.$className.'.php';
            return;
        }
        
        throw new Exception('自动加载没有检测到要加载的文件：'.$class);
    }
    
    public static function _shutdown()
    {
        //是不是可以在这里加 中间件的  后间件
        //echo '<h6>脚本停止执行...</h6>';
    }
    
    public static function _error($errCode, $errMsg, $errFile, $errLine)
    {
        echo '<h5>出错:</h5>';
        echo '文件: '.$errFile;
        echo '<br>行数: '.$errLine;
        echo '<br>错误信息: '.$errMsg;
        echo '<br>错误级别'.$errCode;
        exit;
    }
    
    public static function _exception($exception)
    {
        echo '<h5>异常:</h5>';
        echo '文件: '.$exception->getfile();
        echo '<br>行数: '.$exception->getLine();
        echo '<br>错误信息: '.$exception->getMessage();
        exit;
    }
    
    
}
 