<?php
/**
 * apk 版本获取，下载
 */

namespace app\api\controller;

class Apk extends Inter {

    //获取最后版本信息,
    public function Version() {

        $version_code = $this->post['version_code'] ?? false;

        $M = new \app\api\model\Version();

        //获取到客户端的版本号
        if (false != $version_code) {
            $version = $M->newer($version_code);
            if (false !== $version) {
                $this->json($version);
            } else {
                $this->json('', 0, 'last version');
            }
        //没有获取到版本号， 直接返回最新版本
        } else {
            $version = $M->lastVersion();
            if (false !== $version) {
                $this->json($version);
            } else {
                $this->json('', 0, 'get version error');
            }
        }
    }

}
