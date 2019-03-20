<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cache
 *
 * @author maxthink
 */

namespace Think\lib;

class Cache {
    
    public $cache;
    
    public function __construct() {
        $cache_type = C('Cache/type');
        switch ( strtolower($cache_type) )
        {
            case 'file':
                break;
            
            case 'redis':
                $this->cache = Think\core\Redis::getInstance();
                break;
            case 'memcache':
                $this->cache = Think\core\Memcache::getInstance();
                break;
            default:
                throw new Exception('未知使用缓存类型. . . 请设置缓存配置');
        }
    }
    
    
    
    
}
