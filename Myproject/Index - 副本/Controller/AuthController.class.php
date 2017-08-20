<?php
namespace Home\Controller;
use Think\Controller;
class AuthController extends Controller {
    /**
     * 权限验证
     */
    function __construct() {
        parent::__construct();
        
        $c_phone = cookie('u_phone');
        $c_pass = cookie('u_pass');
        $ck_phone = Util::authcode($c_pass, 'DECODE');
        
        if (!$c_phone OR $c_phone != $ck_phone) {
            cookie('u_phone', null);
            cookie('u_pass', null);
            
            $this->error('请登录后再操作！', U('User/login'));
        }
        
        // 微信授权检测
        $c_wxauth = cookie('u_wxauth');
        if (!$c_wxauth) {
            $this->wx_check($c_phone);
        }
        
        // 获取头像
        $c_wxinfo = cookie('u_wxinfo');
        if (!$c_wxinfo) {
            $this->wx_userinfo($c_phone);
        }
        
        // 填写详细信息
        $c_detailed = cookie('u_detailed');
        if (!$c_detailed) {
            $this->redirect('User/detail');
        }
    }
    
    // 微信授权
    private function wx_check($phone) {
        $M_user = M('user');
        
        $openid = $M_user->where(array('phone'=>$phone))->getField('openid');
        
        if (!$openid) {
            // 微信未授权, 开始微信授权
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID;
            $url .= '&redirect_uri=' . urlencode(WX_URL . WX_URL_REDIRECT);
            $url .= '&response_type=code&scope=snsapi_base&state=' . $phone;
            $url .= '#wechat_redirect';
            
            redirect($url);
        }
    }
    
    /**
     * 获取微信用户信息
     */
    private function wx_userinfo($phone) {
        // 获取用户信息
        $M_user = M('user');
        $user_row = $M_user->where(array('phone'=>$phone))->field('id,openid,headpic')->find();
        
        // 获取token
        $access_token = Util::getWxToken();
        
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token;
        $url .= '&openid=' . $user_row['openid'];
        $url .= '&lang=zh_CN';
        
        $return_data = Util::curlGet($url);
        
        if (!$return_data) return false;
        
        $json_obj = json_decode($return_data);
        
        // 前往关注
        if (! $json_obj->subscribe) {
            // 引导关注公众号
            $this->redirect('Index/subscribe');
        }
        
        if ($json_obj->headimgurl) {
			// 更新头像数据
			$user_row['headpic'] = $json_obj->headimgurl;
			$M_user->save($user_row);
			
			// 更新cookie
			cookie('u_wxinfo', 1);
        }
    }
	
	/**
	 * 异步处理
	 */
	private function curl_get($imgpath) {
		$task_url = WX_URL . U('Index/headimg', array('url'=>urlencode($imgpath)));

		$mh = curl_multi_init();
        $ch1 = curl_init();
        
        // 设置URL和相应的选项
        curl_setopt($ch1, CURLOPT_URL, $task_url);
        curl_setopt($ch1, CURLOPT_HEADER, 0);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        //$running = curl_exec($ch1);
        // 增加2个句柄
        curl_multi_add_handle($mh, $ch1);
        
        $running = null;
        do {
            $a = curl_multi_exec($mh, $running);
        } while ($running);

        curl_multi_remove_handle($mh, $ch1);
        curl_multi_close($mh);
	}
}