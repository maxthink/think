<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$iframedir = '../Think/Think.php';
if ( file_exists($iframedir) ){
    require $iframedir;
    define('APP_PATH','../azb');   //项目路径
    
    Think::run();
    
}else
{
    echo 'where is begin ? ';
}


