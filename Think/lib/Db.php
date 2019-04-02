<?php
/*
 * 数据库连接
 * 单例模式, mysqli
 * @author maxthink
 */

namespace Think\lib;

class Db {
    
    public static $instace;
    public $link;
    public $errmsg;

    private function __construct() 
    {
        $dbconfig['host'] = C('Db/host');
        $dbconfig['db']   = C('Db/db_name');
        $dbconfig['user'] = C('Db/user');
        $dbconfig['pass'] = C('Db/password');
        $dbconfig['port'] = C('Db/port');
	
	try {
	    $this->link = \mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['db'], $dbconfig['port'] );
	    mysqli_set_charset($this->link, C('Db/charset') );
	    unset($dbconfig);
	} catch (Exception $exc) {
	    $this->errmsg = '数据库连接错误：'.\mysqli_connect_error();	    
	}
    }
    
    public static function getInstance()
    {
        if ( self::$instace instanceof self){
            return self::$instace;
        } else {
            self::$instace = new Db();
            return self::$instace;
        }
    }
    
    public function __clone() 
    {

    }
    
    protected function query($sql)
    {
        return mysqli::query($this->link, $sql);
    }
    
    protected function update($sql)
    {
        return mysqli::query($this->link, $sql);
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
    
    public function insert($sql){
	if( mysqli::query($this->link, $sql) ){
	    return $this->lastId();
	} else {
	    return false;
	}
    }
    
    
}
