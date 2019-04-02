<?php

/*
 * 配置文件, 作为接口框架, 基础的就是数据库和缓存
 *
 */
return [
    'Db' => //数据库配置
        [
        'type' => 'mysql', //数据库类型
        'host' => 'localhost', //数据库主机地址
        'port' => '3306', //mysql 端口
        'db_name' => 'aizhuanbao', //数据库
        'user' => 'aizhuanbao', //数据库用户名
        'password' => 'auje86ynj', //密码
        'charset' => 'utf8',
    ],
    'Cache' => //缓存配置
        [
        'type' => 'redis', //缓存类型 file,memcache,redis
        'host' => '127.0.0.1',
        'port' => '6379', //缓存端口, file类型没有端口 memcache default 11211 , redis default 6379
        'user' => '',
        'pass' => ''
    ],
    'Secret' =>
    [
        'token'=> 'guoqu',
    ],
    'Adplat' =>    //广告平台设置
    [
	0 => 
	['desc'=>'签到'],
	1 => 
	['desc','分享'],
	14 => 
	['desc','多盟积分'],
	15 => 
	['desc','趣米积分'],
	
    ]
];
