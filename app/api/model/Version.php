<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\api\model;

/**
 * 获取apk数据
 *
 * @author maxthink
 * 
 */
class Version extends \Think\core\Model {

    private $table = 'app_version';
    private $userFields = ' version_name, version_code, url, description, timeline  ';

    //获取最新版本apk数据（缓存处理在这里？ 还是 再加一层？ 这里吧， 一直加载文件， 加载文件， 慢了吧。。。 ）
    function lastVersion() {
        
        $db = \Think\lib\Db::getInstance();
        $sql = 'select ' .$this->userFields. ' from '. $this->table . ' where status=1 ';
        $result = $db->link->query($sql);
        if($result->num_rows>0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
        
    }
    
    //获取 version_code 后最新版本apk， 或者说， 判断version-code 是不是最新版本
    function newer($version_code) {
        
        $db = \Think\lib\Db::getInstance();
        $sql = 'select ' .$this->userFields. ' from '. $this->table . ' where version_code> ? and status=1 ';
        $stmt = $db->link->stmt_init();
        $stmt->prepare($sql);
        if (false === $stmt) {
            throw new \Exception('prepare error: ' . $this->db->link->error);
        }
        $stmt->bind_param('i', $version_code );
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } elseif (-1 === $result->num_rows) {
            throw new \Exception('query error: ' . $db->link->error);
        } else {
            return false;
        }
    }

    //获取用户数据 by uid
    function getUserById($uid = '') {
        if ('' == $uid) {
            return false;
        } else {
            $db = \Think\lib\Db::getInstance();
            $sql = 'select ' . $this->userFields . ' from ' . $this->table . ' where uid=?';
            $stmt = $db->link->stmt_init();
            $stmt->prepare($sql);
            if (false === $stmt) {
                throw new \Exception('prepare error: ' . $this->db->link->error);
            }
            $stmt->bind_param('i', $uid );
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } elseif (-1 === $result->num_rows) {
                throw new \Exception('query error: ' . $db->link->error);
            } else {
                return false;
            }
        }
    }

    
}
