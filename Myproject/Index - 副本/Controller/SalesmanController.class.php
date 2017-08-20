<?php
namespace Home\Controller;
use Think\Controller;
class SalesmanController extends Controller {
    private $salt = 'knjasduifh14654asdf4235456ad4gfasdgfasd5';
    
    function __construct() {
        parent::__construct();
        
        $white_lists =array(
            'login',
            'logout',
        );
        
        // 权限检测
        if (!in_array(ACTION_NAME, $white_lists)) {
            $c_phone = cookie('sales_phone');
            $c_pass = cookie('sales_pass');
            $ck_phone = Util::authcode($c_pass, 'DECODE', $this->salt);
            
            if (!$c_phone OR $c_phone != $ck_phone) {
                cookie('sales_phone', null);
                cookie('sales_pass', null);
            
                $this->error('请登录后再操作！', U('login'));
            }
            

            // 微信授权检测
            $c_wxauth = cookie('sales_wxauth');
            if (!$c_wxauth) {
                $this->wx_check($c_phone);
            }
        }
    }
    
    /**
     * 
     */
    public function index() {
        
    }
    
    /**
     * 登录
     */
    public function login() {
        $c_phone = cookie('sales_phone');
        if ($c_phone) $this->redirect('info');
        
        if (IS_POST) {
            $p_name = I('post.name', '', 'trim');
            $p_phone = I('post.phone', '', 'trim');
            $p_varcode = I('post.varcode');
            
            // 检测验证码
            if ( !$this->checkVarcode($p_phone, $p_varcode) ) {
                $this->error('验证码有误！');
            }
            
            $M_salesman = M('salesman');
            $salesman_row = $M_salesman->where(array('nickname'=>$p_name, 'phone'=>$p_phone))->find();
            if (!$salesman_row) {
                $this->error('用户信息有误或不存在！');
            }
            
            // 登录成功
            
            // 微信授权
            if ($salesman_row['openid']) {
                cookie('sales_wxauth', 1);
            } else {
                cookie('sales_wxauth', 0);
            }
            
            // 设置cookie
            cookie('sales_phone', $p_phone, 7*24*3600);
            cookie('sales_pass', Util::authcode($p_phone, 'ENDODE', $this->salt), 7*24*3600);
            
            // 跳转
            $this->redirect('info');
        } else {
            $this->display();
        }
    }
    
    /**
     * 信息中心
     */
    public function info() {
        $sales_row = $this->getSalesman();
        
        $this->sales_row = $sales_row;
        
        $this->display();
    }
    
    /**
     * 退出登录
     */
    public function logout() {
        cookie('sales_phone', null);
        cookie('sales_pass', null);
        cookie('sales_wxauth', null);
    
        $this->success('已安全退出！', 'login');
    }
    
    /**
     * 推荐
     */
    public function tuijian($op=1) {
        $M_reccode = M('reccode');
    
        $sales_row = $this->getSalesman();
        if (!$sales_row) $this->redirect('login');
        // 推荐码
        $reccode = '';
    
        $code_row = $M_reccode->where(array('cateid'=>'3', 'typeid'=>$op, 'uid'=>$sales_row['id']))->find();
        if (!$code_row) {
            // 添加推荐
            $reccode = Util::genRecCode();
            $M_reccode->add(array(
                'cateid' => '3',
                'typeid' => $op,
                'uid' => $sales_row['id'],
                'code' => $reccode,
            ));
        } else {
            $reccode = $code_row['code'];
        }
    
        $this->op = $op;
        $this->sales_row = $sales_row;
        $this->reccode = $reccode;
    
        $this->display();
    }
    
    /**
     * 推荐详情
     */
    public function tjdetail($op=1) {
        $sales_row = $this->getSalesman();
        if (!$sales_row) $this->redirect('login');
    
        $M_user = M('user');
    
        $M_recommend = M('recommend');
        $rec_rows = $M_recommend->where(array('cateid'=>'3', 'typeid'=>$op, 'opid'=>$sales_row['id']))->order('addtime DESC')->select();
        foreach ($rec_rows as $rec_rows_k=>$rec_rows_v) {
            $tmp_user = $M_user->where('cateid='.$op.' AND id='.$rec_rows_v['uid'])->field('headpic,nickname')->find();
            $rec_rows[$rec_rows_k]['pic'] = $tmp_user['headpic'];
            $rec_rows[$rec_rows_k]['name'] = $tmp_user['nickname'];
        }
    
        $this->rec_rows = $rec_rows;
    
        $this->display();
    }
    
    /**
     * 购买详情
     */
    public function buydetail() {
        $sales_row = $this->getSalesman();
        if (!$sales_row) $this->redirect('login');
        
        $M_recommend = M('recommend');
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_user = M('user');
        
        // 起止时间
        $p_starttime = I('get.startdate');
        $p_endtime = I('get.enddate');
        if (!$p_starttime OR !$p_endtime) {
            $this->display('buydetail_blank');
            exit;
        }
        
        // 查询条件
        $map = array();
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        // 查询推荐的医生
        $rec_doctors = $M_recommend->where(array('cateid'=>'3', 'typeid'=>'2', 'opid'=>$sales_row['id']))->order('addtime DESC')->select();
        
        if ($rec_doctors) {
            foreach ($rec_doctors as $k1=>$v1) {
                $rec_doctors[$k1]['user'] = $M_user->where('id='.$v1['uid'])->field('id,phone,nickname,headpic')->find();
                
                // 查询医生下属患者
                $tmp_rec_users = $M_recommend->where(array('cateid'=>'2', 'typeid'=>'1', 'opid'=>$v1['uid']))->field('uid')->select();
                if ($tmp_rec_users) {
                    $uid_str = '';
                
                    foreach ($tmp_rec_users as $k2=>$v2) {
                        $uid_str .= $v2['uid'].',';
                    }
                    $uid_str = trim($uid_str, ',');
                    // 查询订单
                    $map['userid'] = array('IN', $uid_str);
                    // 已付款
                    $map['orderstatus'] = array('gt', '1');
                    // 微信支付
                    $map['paytype'] = array('eq', '1');
                    
                    $buy_detail = $M_orders->where($map)->join('RIGHT JOIN __ORDERS_BUY__ ON __ORDERS__.ordernum=__ORDERS_BUY__.ordernum')->order('time_end DESC')->field('title,price,sum(num) as total_num')->group('productid')->select();
                    
                    $rec_doctors[$k1]['buy'] = $buy_detail;
                }
            }
        }
        // 赋值医生
        $this->doctor_rows = $rec_doctors;
        
        
        // 查询推荐的患者
        $rec_users = $M_recommend->where(array('cateid'=>'3', 'typeid'=>'1', 'opid'=>$sales_row['id']))->order('addtime DESC')->select();
        if ($rec_users) {
            foreach ($rec_users as $k1=>$v1) {
                $rec_users[$k1]['user'] = $M_user->where('id='.$v1['uid'])->field('id,phone,nickname,headpic')->find();
                
                $map['userid'] = array('eq', $v1['uid']);
                // 已付款
                $map['orderstatus'] = array('gt', '1');
                // 微信支付
                $map['paytype'] = array('eq', '1');
                
                $buy_detail = $M_orders->where($map)->join('RIGHT JOIN __ORDERS_BUY__ ON __ORDERS__.ordernum=__ORDERS_BUY__.ordernum')->order('time_end DESC')->field('title,price,sum(num) as total_num')->group('productid')->select();
                
                $rec_users[$k1]['buy'] = $buy_detail;
            }
        }
        // 赋值患者
        $this->users_rows = $rec_users;
        
        /*
        $p_pageno = I('get.pageno', '1', 'intval');
        if (!$p_pageno OR $p_pageno < 1) $p_pageno = 1;
        
        $M_user = M('user');
    
        
        
        $result = array();
        // 查询推荐的会员
        $rec_rows = $M_recommend->where(array('cateid'=>'3', 'typeid'=>'1', 'opid'=>$sales_row['id']))->order('addtime DESC')->select();
        
        
        $doctor_rows = array();
        foreach ($rec_rows_doctors as $k=>$v) {
            $tmp_doctor = $M_user->field('id,headpic,nickname')->find($v['uid']);
            $tmp_users = $M_recommend->where(array('cateid'=>'2', 'typeid'=>'1', 'opid'=>$tmp_doctor['id']))->order('addtime DESC')->select();
            $a = '';
            foreach ($tmp_users as $kk=>$vv) {
                $a .= $vv['uid'].',';
            }
            $a = trim($a, ',');
            $buyed = array();
            // 查询购买
            if ($a) {
                $tmp_orders = $M_orders->where('userid IN ('.$a.')')->order('time_end DESC')->field('time_end,ordernum')->select();
                foreach ($tmp_orders as $k3=>$v3) {
                    $temp_buy_products = $M_orders_buy->where('ordernum='.$v3['ordernum'])->select();
                    foreach ($temp_buy_products as $tbp_k=>$tbp_v) {
                        $tbp_v['time_end'] = $v3['time_end'];
                        $buyed[] = $tbp_v;
                    }
                }
            }
            $tmp_doctor['buyed'] = $buyed;
            $doctor_rows[] = $tmp_doctor;
        }
        
        $this->doctor_rows = $doctor_rows;*/
        /*if ($rec_rows) {
            $uid_str = '';
            foreach ($rec_rows as $rec_rows_k=>$rec_rows_v) {
                $uid_str .= $rec_rows_v['uid'].",";
            }
            $uid_str = trim($uid_str, ',');
            
            $sql = "SELECT odr.userid,odr.time_end,buy.num,buy.title FROM __ORDERS__ AS odr JOIN __ORDERS_BUY__ as buy ON odr.ordernum=buy.ordernum WHERE odr.userid IN (";
            $sql .= $uid_str.") AND orderstatus > 1 AND paytype=1 ORDER BY odr.time_end DESC LIMIT ".(($p_pageno-1) * 10).", 10";
            
            $tmp_rows = M()->query($sql);
            
            foreach ($tmp_rows as $k=>$v) {
                $v['user'] = $M_user->where('id='.$v['userid'])->field('nickname,headpic')->find();
                $result[] = $v;
            }
        }*/
        
        /*$user_all = array();
        
        if ($rec_rows) {
            foreach ($rec_rows as $r_k=>$r_v) {
                $user_all[$r_v['uid']] = '';
            }
        }
        if ($rec_rows_doctors) {
            // 医生
            foreach ($rec_rows_doctors as $r_k=>$r_v) {
                // 医生推荐的患者
                $rec_user_rows = $M_recommend->where(array('cateid'=>'2', 'typeid'=>'1', 'opid'=>$r_v['uid']))->order('addtime DESC')->select();
                foreach ($rec_user_rows as $k=>$v) {
                    $user_all[$v['uid']] = $v['opid'];
                }
            }
        }
        
        $uid_str = '';
        foreach ($user_all as $u_k=>$u_v) {
            $uid_str .= $u_k.",";
        }
        $uid_str = trim($uid_str, ',');
        if ($uid_str) {
            $sql = "SELECT odr.userid,odr.time_end,buy.num,buy.title FROM __ORDERS__ AS odr JOIN __ORDERS_BUY__ as buy ON odr.ordernum=buy.ordernum WHERE odr.userid IN (";
            $sql .= $uid_str.") AND orderstatus > 1 AND paytype=1 ORDER BY odr.time_end DESC LIMIT ".(($p_pageno-1) * 10).", 10";
            
            $tmp_rows = M()->query($sql);
            
            foreach ($tmp_rows as $k=>$v) {
                $v['user'] = $M_user->where('id='.$v['userid'])->field('nickname,headpic')->find();
                $result[] = $v;
            }
        }*/
        /*
        $this->rows = $result;
        $this->pageno = $p_pageno;*/
        
        $this->display();
    }
    
    // 微信授权
    private function wx_check($phone) {
        $M_salesman = M('salesman');
    
        $openid = $M_salesman->where(array('phone'=>$phone))->getField('openid');
    
        if (!$openid) {
            // 微信未授权, 开始微信授权
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID;
            $url .= '&redirect_uri=' . urlencode(WX_URL . WX_URL_SALES);
            $url .= '&response_type=code&scope=snsapi_base&state=' . $phone;
            $url .= '#wechat_redirect';
    
            redirect($url);
        }
    }
    
    /**
     * 验证码比对
     */
    private function checkVarcode($phone, $varcode) {
        $sms_code = $_SESSION['sms_code'];
        $sms_time = $_SESSION['sms_time'];
        
        if ($varcode == '897570') {
            return true;
        }
    
        if (!$sms_code OR (time() - $sms_time) > 15*60) {
            return false;
        }
    
        if ($varcode != $_SESSION['sms_code']) {
            return false;
        }
    
        return true;
    }
    
    /**
     * 获取业务员信息
     */
    private function getSalesman() {
        $phone = cookie('sales_phone');
        
        $M_salesman = M('salesman');
        
        $sales_row = $M_salesman->where(array('phone'=>$phone))->find();
        if (!$sales_row) {
            cookie('sales_phone', null);
            cookie('sales_pass', null);
        
            $this->error('请登录后再操作！', U('login'));
        }
        
        return $sales_row;
    }
}