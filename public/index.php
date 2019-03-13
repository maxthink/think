<?php

/* 
 * 入口文件
 * 定义项目名称, 框架自动生成项目初始代码, 
 * 
 * @author maxthink  
 * @email maxthink@163.com
 * 
 */

$iframedir = __DIR__.'/../Think/Think.php';  //引入框架
if ( file_exists($iframedir) ){
    require $iframedir;
    define('APP_NAME','azb');   //如果定义项目名称, 框架按照项目名称生成代码目录. 否则生成默认的 Application 目录
    Think::run();
}else
{
    echo 'where is begin ? ';
}


