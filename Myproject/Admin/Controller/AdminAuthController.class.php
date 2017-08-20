<?php
namespace Admin\Controller;
use Think\Controller;
class AdminAuthController extends Controller {
    /*
     * 登录权限检测
     */
    public function __construct() {
        parent::__construct();
        
        // 白名单
        $M_whitelists = M('whitelists');
        
        $whiteActionLists = $M_whitelists->where(array('controller'=>CONTROLLER_NAME))->field('action')->select();
        if($whiteActionLists) {
            foreach ($whiteActionLists as $white_item) {
                if($white_item['action']=='*' OR ACTION_NAME==$white_item['action']) {
                    return true;
                }
            }
        }
        
        if($_SESSION['r_id']==null OR $_SESSION['r_name']==null OR $_SESSION['r_user']==null) {
            unset($_SESSION['r_id']);
            unset($_SESSION['r_name']);
            unset($_SESSION['r_user']);
            unset($_SESSION['r_auth']);
    
           // $this->redirect('Index/login');
        }
    }
}