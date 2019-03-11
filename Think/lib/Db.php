<?php

/*
 * 数据库连接
 * 单例模式
 * @author maxthink
 */

class Db {
    
    private static $instace;
    private static $dbLink;


    private function __construct() 
    {
        self::$dbLink = new mysqli();
    }
    
    public function getInstance()
    {
        if( self::$instace instanceof self)
        {
            return self::$instace;
        }else
        {
            self::$instace = new Db();
        }
    }
    
    private function __clone() {

    }
    
    protected function query($sql)
    {
        return mysqli_query(self::$dbLink, $sql);        
    }
    
    protected function update()
    {
        
    }
    
    
}
