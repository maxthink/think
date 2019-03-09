<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Redis
 *
 * @author maxthink 
 * @email   maxthink@live.com
 */
class Redis {
    
    
    //初始化redis
    private function __construct() {
        try {
            $r = new Redis();
            $r->connect('127.0.0.1', 6379);
            return $r;
        } catch (Exception $ex) {
            self::log('redis : '.$ex->getMessage() );
            return false;
        }
    }
}
