<?php
namespace Think\core;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author mljm
 */
class Model {
    protected $db;
    public function __construct() {
        $this->db = Db::getInstance();
    }
    
    public function query($sql = '')
    {
        mysqli_query($this->db, $sql);
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
