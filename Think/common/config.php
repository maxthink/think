<?php

/* 
 * 配置文件, 作为接口框架, 基础的就是数据库和缓存
 * 
 */
[
    'Db'=>
    [
        'type'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'name'=>'test',
        'user'=>'root',
        'password'=>''
    ],
    
    'Cache'=>
    [
        'type'=>'file', //file,memcache,redis
        'port'=>'6379', //memcache default 11211 , redis default 6379
        'user'=>'',
        'pass'=>''
    ]
    
    
];
