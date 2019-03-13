<?php

/*
 * 数据库连接
 * 单例模式, mysqli
 * @author maxthink
 */

class Db {
    
    public static $instace;
    public $dblink;


    private function __construct() 
    {
        $_host = C('db/host');
        $_db = C('db/db_name');
        $_user = C('db/user');
        $_pass = C('db/pass');
        $_port = C('db/port');
        $this->dblink = mysqli::__construct($_host,$_user,$_pass,$_db,$_port);
    }
    
    public static function getInstance()
    {
        if( self::$instace instanceof self)
        {
            return self::$instace;
        }else
        {
            self::$instace = new Db();
        }
    }
    
    public function __clone() {

    }
    
    protected function query($sql)
    {
        return mysqli::query($sql);
    }
    
    protected function update()
    {
        return mysqli::query(self::$dbLink, $sql);
    }
    
    /**
     * 返回最后插入行的id
     * 返回一个在最后一个查询中自动生成的带有 AUTO_INCREMENT 字段值的整数。如果数字 > 最大整数值，它将返回一个字符串。
     * 如果没有更新或没有 AUTO_INCREMENT 字段，将返回 0。
     * @return int
     */
    public function lastId()
    {
        return mysqli::mysqli_insert_id($this->dbLink);
    }
    
    
}
