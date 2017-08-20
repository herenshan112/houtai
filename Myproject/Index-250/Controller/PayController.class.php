<?php
namespace Index\Controller;
use Think\Controller;
class PayController extends AuthController {
/**
     * 微信支付
     */
    public function index() {
        $apiParam = I('post.apiParam', '');
        
        if (!$apiParam) $this->redirect('Index/index');
        
        $jsApiParameters = '{' . base64_decode($apiParam) . '}';
        
        $this->jsApiParameters = $jsApiParameters;
        
        $this->display();
    }
    
    /**
     * 获取用户
     */
    private function getUser() {
        $c_phone = cookie('u_phone');
    
        $M_user = M('user');
    
        $user_row = $M_user->where(array('phone'=>$c_phone))->find();
    
        return $user_row;
    }
}