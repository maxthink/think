<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\api\model;

/**
 * Description of userModel
 *
 * @author Administrator
 */
class User extends \Think\core\Model {

    private $table = 'app_user';
    private $userFields = ' uid, nick, score, total, exchange_count exchange_nums, task_count task_nums, invitecode, version, timeline ';

    //添加新用户
    function addNewUser($imei = '') {
        if ('' == $imei) {
            return false;
        } else {
            $inid = $this->getAutoIncreamentId();
            $nick = '爱宝' . str_pad($inid, 4, '0', STR_PAD_LEFT);
            //$invetecode = $this->getInviteCode();

            $db = \Think\lib\Db::getInstance();
            $sql = 'insert ' . $this->table . ' set uid=' . $inid . ', nick=\''.$nick.'\',timeline='.TIME.', imei=? ';
            $stmt = $db->link->stmt_init();
            $stmt->prepare($sql);
            $stmt->bind_param('s', $imei);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return $stmt->insert_id;                
            } elseif (-1 === $stmt->affected_rows) {
                throw new \Exception(' new user error: ' . $stmt->error);
            } else {
                return false;
            }
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

    //获取用户数据 by imei
    function getUserByImei($imei = '') {
        if ('' == $imei) {
            return false;
        } else {
            $db = \Think\lib\Db::getInstance();
            $sql = 'select ' . $this->userFields . ' from ' . $this->table . ' where imei=?';
            $stmt = $db->link->stmt_init();
            $stmt->prepare($sql);
            if (false === $stmt) {
                throw new \Exception('prepare error: ' . $this->db->link->error);
            }
            $stmt->bind_param('s', $imei);
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

    //根据用户imei获取用户id
    function getUserIdByImei($imei = '') {
        if ('' == $imei) {
            return false;
        } else {
            $db = \Think\lib\Db::getInstance();
            $sql = 'select uid from ' . $this->table . ' where imei=?';
            $stmt = $db->link->stmt_init();
            $stmt->prepare($sql);
            if (false === $stmt) {
                throw new \Exception('prepare error: ' . $this->db->link->error);
            }
            $stmt->bind_param('s', $imei);
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
    
    //获取下一自增id
    private function getAutoIncreamentId() {
        $sql = 'SELECT auto_increment FROM information_schema.`TABLES` WHERE TABLE_SCHEMA=\'' . C('Db/db_name') . '\' AND TABLE_NAME=\'' . $this->table . '\'';
        $db = \Think\lib\Db::getInstance();
        $result = $db->link->query($sql);
        $id = $result->fetch_all();
        //var_dump($id[0][0]);
        return $id[0][0];
    }

    /*
     * 	获取邀请码
     * 	return string
     */
    private function getInviteCode() {
        $go = false;
        do {
            $code = $this->generate_rand(6);

            $sql = 'select invitecode from app_user where invitecode=\'' . $code . '\'';
            $ok = $this->query($sql);
            if ($ok) {
                $go = true;
            } else {
                return strtoupper($code);
            }
        } while ($go);
    }

    /**
     * 生成随机数字
     */
    private function generate_rand($l) {
        $c = "abcdefghijklmnpqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);

        $rand = '';
        for ($i = 0; $i < $l; $i++) {
            $rand .= $c[rand() % strlen($c)];
        }
        return strtolower($rand);
    }

}
