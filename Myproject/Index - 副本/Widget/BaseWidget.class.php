<?php
namespace Home\Widget;
use Think\Controller;

class BaseWidget extends Controller {
    /**
     * 底部菜单
     */
    public function showFooter() {
        $this->display('Public:showFooter');
    }
}