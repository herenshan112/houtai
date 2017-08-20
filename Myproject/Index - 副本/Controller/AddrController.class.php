<?php
namespace Home\Controller;
use Think\Controller;
class AddrController extends AuthController {
    /**
     * 首页
     */
    public function index() {
        
        $this->display();
    }
    
    /**
     * 新地址
     */
    public function add() {
        
        $this->display();
    }
    
    /**
     * 删除地址
     */
    public function del() {
        if (IS_POST) {
            
        } else {
            $this->error('提交参数有误！');
        }
    }
}