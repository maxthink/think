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

class Think {

    public static $config;

    public static function run() {
        self::init();
        self::dispath();
    }

    /**
     * 定义初始常量
     */
    private static function init() {
        //定义一个时间， 老用
        define('TIME', $_SERVER['REQUEST_TIME'] ?? time() );

        //根路径常量
        define('ROOT', getcwd() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);   //
        //框架根目录
        define('FRAME_PATH', __DIR__ . DIRECTORY_SEPARATOR);

        //项目名
        if (!defined('APP_NAME')) {
            define('APP_NAME', 'app');
        }

        //项目目录地址
        if (!defined('APP_PATH')) {
            define('APP_PATH', ROOT . APP_NAME . DIRECTORY_SEPARATOR);
        }

        //配置文件
        define('CONFIG_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'common/');

        //初始化项目目录和默认代码内容
        if (!is_dir(APP_PATH)) {
            require FRAME_PATH . 'common' . DIRECTORY_SEPARATOR . 'appinit.php';
            $init = new appinit();
            $init->init();
        }

        //引入框架基础类
        require FRAME_PATH . 'common' . DIRECTORY_SEPARATOR . 'function.php';
        require FRAME_PATH . 'core' . DIRECTORY_SEPARATOR . 'Controller.php';
        require FRAME_PATH . 'core' . DIRECTORY_SEPARATOR . 'Model.php';
        require FRAME_PATH . 'core' . DIRECTORY_SEPARATOR . 'View.php';

        spl_autoload_register('Think::_autoload');
        register_shutdown_function('Think::_shutdown');
        set_error_handler('Think::_error', E_ALL);
        set_exception_handler('Think::_exception');
    }

    /**
     * 路由解析
     */
    private static function dispath() {
        //获取配置文件
        if (file_exists(CONFIG_PATH . 'config.php')) {
            self::$config = include CONFIG_PATH . 'config.php';
        } else {
            throw new Exception('配置文件不存在');
        }

        //获取查询串， 解析路由
        $query = $_SERVER['QUERY_STRING'] ?? '';

        if (strpos($query, 's=/' ) == 0) {
            $query = substr($query, 3);
        }
        //给默认模块，控制器，方法
        $module = self::$config['Project']['module_default'];
        $ctrollerName = 'Index';   //默认控制器
        $mether = 'index';   //默认方法
        //解析路由， 获取 模块， 控制器， 方法
        if ( '/' == $query || $_SERVER['DOCUMENT_URI'] == $query) {     // http://xxx.net/index.php http://xxx.net/  两种地址用默认的 index.php
            $ctrollerName = 'Index';
            $mether = 'index';
        } else {

            $paths = explode('/', $query);
            //print_r($paths);
            if ('' == $paths[0]) {
                array_shift($paths);
            }
            //从路径里找模块（路由解析最重要部分， 还需完善）
            $module = isset($paths[0]) && !empty($paths[0]) ? $paths[0] : '';

            //判断路径里找出的模块是否在配置文件里， 不在就获取配置的默认模块
            if (in_array($module, self::$config['Project']['modules'])) {
                //获取的模块在配置里， 按顺序取出控制器，方法
                $ctrollerName = isset($paths[1]) && !empty($paths[1]) ? $paths[1] : 'Index';
                $mether = isset($paths[2]) && !empty($paths[2]) ? $paths[2] : 'index';
            } else {
                $module = self::$config['Project']['module_default'];
                //获取的模块不在配置里， 默认
                $ctrollerName = isset($paths[0]) && !empty($paths[0]) ? $paths[0] : 'Index';
                $mether = isset($paths[1]) && !empty($paths[1]) ? $paths[1] : 'index';
            }
        }

        //项目模块( 这里可以处理成 根据uri自动匹配模块, ..... )
        if (!defined('MODULE_NAME')) {
            define('MODULE_NAME', $module);   //默认模块
        }
 
        //项目配置文件
        define('METHOD', $mether);  //定义方法常量， 接口校验区分用
        define('MODULE_CONFIG_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR);
        define('CONTROLLER_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR);
        define('MODEL_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR);
        define('VIEW_PATH',APP_PATH.MODULE_NAME.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR);
        
        //加载项目配置文件（模块有单独的配置文件，合并配置，覆盖app默认配置文件）
        if (file_exists(APP_PATH . MODULE_NAME . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'config.php')) {
            $config = include APP_PATH . MODULE_NAME . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'config.php';
            self::$config = array_merge(self::$config, $config);
        }

        //初始化模块, 初始化完可以删了去
//        if(!is_dir(MODULE_PATH))
//        {
//            require FRAME_PATH.'common'.DIRECTORY_SEPARATOR.'appinit.php';
//            $init = new appinit();
//            $init->init();
//        }
        //echo 'module: '.MODULE_NAME.' controller:'. $ctrollerName . ' mether:'.$mether;

        $className = APP_NAME . '\\' . MODULE_NAME . '\\controller\\' . ucfirst($ctrollerName);
        $do = new $className();
        $do->$mether();
    }

    /**
     * 注册自动加载
     * 自动查询三个规则 controller, model, lib
     */
    public static function _autoload($class) {
        //echo PHP_EOL.$class.PHP_EOL;
        $className = ucfirst(substr($class, strrpos($class, '\\') + 1));
        //echo 'classname: '.$className;
        //controller  根据类里包含 controller 找控制器类
        if (false !== strpos($class, 'controller')) {
            $classPath = APP_PATH . MODULE_NAME . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $className . '.php';
            if (file_exists($classPath)) {
                include $classPath;
            } else {
                throw new Exception(" 控制器文件没找到: " . $classPath);
            }
            return;
        }

        //model 根据类里包含 model 找模型类
        if (false !== strpos($class, 'model')) {
            $modelPath = APP_PATH . MODULE_NAME . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $className . '.php';
            if (file_exists($modelPath)) {
                include $modelPath;
            } else {
                throw new Exception(" 模型文件没找到: " . $modelPath);
            }
            return;
        }

        //lib 类库
        if (file_exists(FRAME_PATH . 'lib' . DIRECTORY_SEPARATOR . $className . '.php')) {
            include FRAME_PATH . 'lib' . DIRECTORY_SEPARATOR . $className . '.php';
            return;
        }

        throw new Exception('自动加载没有检测到要加载的文件：' . $class);
    }

    public static function _shutdown() {
        //是不是可以在这里加 中间件的  后间件
        //echo '<h6>脚本停止执行...</h6>';
    }

    public static function _error($errCode, $errMsg, $errFile, $errLine) {
        echo '<h5>出错:</h5>';
        echo '文件: ' . $errFile;
        echo '<br>行数: ' . $errLine;
        echo '<br>错误信息: ' . $errMsg;
        echo '<br>错误级别' . $errCode;
        exit;
    }

    public static function _exception($exception) {
        echo '<h5>异常:</h5>';
        echo '文件: ' . $exception->getfile();
        echo '<br>行数: ' . $exception->getLine();
        echo '<br>错误信息: ' . $exception->getMessage();
        exit;
    }

}
