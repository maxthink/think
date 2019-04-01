<?php

/* 
 * 配置文件, 作为接口框架, 基础的就是数据库和缓存
 * 
 */
return [
    'Db'=>      //数据库配置
    [
        'type'=>'mysql',    //数据库类型
        'host'=>'localhost',    //数据库主机地址
        'port'=>'3306', //mysql 端口
        'db_name'=>'aizhuanbao', //数据库
        'user'=>'aizhuanbao', //数据库用户名
        'password'=>'auje86ynj'  //密码
    ],
    
    'Cache'=>   //缓存配置
    [
        'type'=>'file', //缓存类型 file,memcache,redis
        'host'=>'127.0.0.1',
        'port'=>'6379', //缓存端口, file类型没有端口 memcache default 11211 , redis default 6379
        'user'=>'',
        'pass'=>''
    ],
    
    'Template'=>    //模板配置
    [
        'use'=>'default',   //用哪套模板
        'file_suffix' => 'php', //模板文件后缀
        
    ]
    
    
];
