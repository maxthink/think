<?php

/**
 * 初始化新应用, 添加默认的目录,代码等
 * 
 * @author maxthink@163.com 
 * 
 */

Class appinit {
    
    protected $errorMsg = '';
    
    public function __construct() {
        //echo ' app init ... ';
    }
    
    public function init()
    {
        //第一步, 创建目录
        $mkdir = $this->createDir();
        if(!$mkdir){
            $this->errorMsg = '目录创建出错,';
            return false;
        }
        
        $this->createCode();
        
    }


    function createDir():bool
    {
        $ok1 = mkdir( APP_PATH );                  //应用 目录
        $ok2 = mkdir( APP_PATH.'/'.APP_MODULE );   //应用->模块 目录
        $ok3 = mkdir( CONFIG_PATH );               //应用->模块->配置文件,公共方法 目录
        $ok4 = mkdir( CONTROLLER_PATH );           //应用->模块->控制器 目录
        $ok5 = mkdir( MODEL_PATH );                //应用->模块->模型 目录
        $ok6 = mkdir( VIEW_PATH );                 //应用->模块->视图 目录
        if($ok1 && $ok2 && $ok3 && $ok4 && $ok5 && $ok6){
            return true;
        }else
        {
            return false;
        }

    }
    function createCode()
    {
        //初始化文件和
        //file_put_contents( CONTROLLER_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\controller;'."\n/**\n* Controller index \n*/\nclass indexController{\n\tpublic function index(){\n\t\techo 'Think iframe ! ';\n\t}\n}");
        //file_put_contents( MODEL_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\model;'."\n/**\n* model index\n*/\nclass index{\n\tpublic function index(){\n\t\techo 'Think iframe ! ';\n\t}\n}");
        //file_put_contents( VIEW_PATH.'index.php',"<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\view;'."\n/**\n* view index \n */\nclass index{\n\tpublic function index(){\n\t\t echo 'Think iframe ! ';\n\t}\n}");

        copy(FRAME_PATH.'init/common/config.php', CONFIG_PATH.'config.php');
        copy(FRAME_PATH.'init/controller/index.php', CONTROLLER_PATH.'index.php');  //没解决配置命名空间问题
        copy(FRAME_PATH.'init/model/index.php', MODEL_PATH.'index.php');
        copy(FRAME_PATH.'init/view/index.php', VIEW_PATH.'index.php');
        
        //控制器追加命名空间
        $fp=fopen( CONTROLLER_PATH.'index.php' , 'r+');//
        $str="<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\controller;';
        $str.=fread($fp,filesize( CONTROLLER_PATH.'index.php' ));
        fseek($fp, 0);
        fwrite($fp,$str);
        fclose($fp);
        unset($fp);
        
        //模型追加命名空间
        $fp2=fopen( MODEL_PATH.'index.php' , 'r+');//
        $str="<?php\nnamespace ".APP_NAME.'\\'.APP_MODULE.'\\model;';
        $str.=fread($fp2,filesize( MODEL_PATH.'index.php' ));
        fseek($fp2, 0);
        fwrite($fp2,$str);
        fclose($fp2);
        unset($fp2);
        
        
    }
}
