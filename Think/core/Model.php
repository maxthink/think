<?php
/**
 * 核心模型
 * 可以做成支持多种数据库， 暂时想做项目， 简单先只支持mysql吧
 * @author maxthink
 */
namespace Think\core;

class Model {
    protected $db;
    public function __construct() {
        $this->db = \Think\lib\Db::getInstance();  //can be to lazyload ? 。。。。 how to do ?
    }
    
    public function query($sql = '')
    {
	if ( false!==$this->db->link ){
	    return mysqli_query($this->db->link, $sql);
	} else {
	    throw new \Exception( $this->db->errmsg );
	}
    }
    
    public function select()
    {
        
    }
    
    public function update()
    {
        
    }
    
    public function find()
    {
        
    }
}
