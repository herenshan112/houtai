<?php
namespace Index\Controller;
use Think\Controller;
class UserInfoController extends AuthController {
    /**
     * 个人中心
     */
    public function index() {
        $user_row = $this->getUser();
        //var_dump($user_row);
        if (!$user_row) $this->redirect('User/index');
        
        if($user_row['nickname'] != ''){
            $user_row['myname']=$user_row['nickname'];
        }else{
            $user_row['myname']=$user_row['username'];
        }
        if ($user_row['cateid'] != 1){
            $this->redirect('Distri/index');
        }
        $this->user_row = $user_row;
        
        $this->display();
    }
    
    /**
     * 我的积分
     * 
     */
    public function jifen() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        $M_corn = M('corn');
        $corn_row = $M_corn->where(array('cateid'=>'1', 'uid'=>$user_row['id']))->find();
        
        $this->corn_row = $corn_row;
        
        $this->display();
    }
    
    /**
     * 推荐
     */
    public function tuijian() {
        $M_reccode = M('reccode');
        
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        // 推荐码
        $reccode = '';
        
        $code_row = $M_reccode->where(array('cateid'=>$user_row['cateid'], 'uid'=>$user_row['id']))->find();
        if (!$code_row) {
            // 添加推荐
            $reccode = Util::genRecCode();
            $M_reccode->add(array(
                'cateid' => $user_row['cateid'],
                'typeid'=>'1',
                'uid' => $user_row['id'],
                'code' => $reccode,
            ));
        } else {
            $reccode = $code_row['code'];
        }
        
        $this->user_row = $user_row;
        $this->reccode = $reccode;
        
        $this->display();
    }
    
    /**
     * 推荐详情
     */
    public function tjdetail() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        $M_user = M('user');
        
        $M_recommend = M('recommend');
        $rec_rows = $M_recommend->where(array('cateid'=>$user_row['cateid'], 'typeid'=>'1', 'opid'=>$user_row['id']))->order('addtime DESC')->select();
        foreach ($rec_rows as $rec_rows_k=>$rec_rows_v) {
            $tmp_user = $M_user->where('id='.$rec_rows_v['uid'])->field('headpic,nickname')->find();
            $rec_rows[$rec_rows_k]['pic'] = $tmp_user['headpic'];
            $rec_rows[$rec_rows_k]['name'] = $tmp_user['nickname'];
        }
        
        $this->user_row = $user_row;
        $this->rec_rows = $rec_rows;
        
        $this->display();
    }
    
    /**
     * 购买详情
     */
    public function buydetail() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
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
        
        // 查询推荐的患者
        $rec_users = $M_recommend->where(array('cateid'=>'2', 'typeid'=>'1', 'opid'=>$user_row['id']))->order('addtime DESC')->select();
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
        
        /*$p_pageno = I('get.pageno', '1', 'intval');
        if (!$p_pageno OR $p_pageno < 1) $p_pageno = 1;
        
        $M_user = M('user');
        $M_recommend = M('recommend');
        $M_orders = M('orders');
        
        $result = array();
        $rec_rows = $M_recommend->where(array('cateid'=>'2', 'typeid'=>'1', 'opid'=>$user_row['id']))->order('addtime DESC')->select();
        if ($rec_rows) {
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
        }
        
        $this->rows = $result;
        $this->pageno = $p_pageno;*/
        
        $this->display();
    }
    
    /**
     * 修改密码
     */
    public function modpass() {
        if (IS_POST) {
            $p_oldpass = I('post.oldpass');
            $p_newpass = I('post.newpass');
            
            if (!$p_oldpass OR !$p_newpass) {
                $this->error('参数提交不完整！');
            }
            
            $user_row = $this->getUser();
            if (!$user_row) $this->redirect('User/index');
            
            if ($user_row['password'] == md5($p_oldpass)) {
                $M_user = M('user');
                
                $user_row['password'] = md5($p_newpass);
                
                $updated = $M_user->save($user_row);
                if ($updated) {
                    $this->success('密码修改成功！', U('index'));
                } else {
                    $this->error('密码修改失败！');
                }
            } else {
                $this->error('旧密码有误！');
            }
        } else {
            $this->display();
        }
    }
    
    /**
     * 获取用户
     */
    private function getUser() {
        /*$c_phone = cookie('u_idval');
        
        $M_user = M('user');
        
        $user_row = $M_user->where(array('id'=>$c_phone))->find();*/
        
        return getuscont();
    }
    /*
    *我的详情
     */
    public function mycont(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'eite':
                $this->assign('list',$user_row);
                $this->display('mycontrit');
                break;
            case 'eitecl':
                $toppic=I('post.toppickjs');
                $nicheng=I('post.nicheng')?I('post.nicheng'):'';
                $telval=I('post.telval')?I('post.telval'):'0';
                $weixin=I('post.weixin')?I('post.weixin'):'';
                $xingbie=I('post.xingbie')?I('post.xingbie'):'男';
                $shengri=I('post.shengri')?I('post.shengri'):0;
                if($xingbie == '男'){
                    $xingbie = 1;
                }else{
                    $xingbie = 0;
                }
                $shengri=strtotime($shengri);
                $usdatacon=array(
                    'headpic'                   =>                  $toppic,
                    'nickname'                  =>                  $nicheng,
                    'phone'                     =>                  $telval,
                    'weixinname'                =>                  $weixin,
                    'sex'                       =>                  $xingbie,
                    'shengri'                   =>                  $shengri
                );
                //var_dump($usdatacon);
                if(M('user')->where(array('id'=>$user_row['id']))->save($usdatacon)){
                    $this->success('修改成功！', U('index'));
                }else{
                    $this->error('修改失败！');
                }
                break;
            default:
               
                
                $this->assign('list',$user_row);
                $this->display();
                break;
        }
    }
    /*
    *修改我的密码
     */
    public function myeitepwd(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'eitecl':
                $oldpwd=I('post.oldpwd')?I('post.oldpwd'):'0';
                $newpwd=I('post.newpwd')?I('post.newpwd'):'0';
                $qrpwd=I('post.qrpwd')?I('post.qrpwd'):'0';
                if($oldpwd == '0'){
                    $this->error('请输入旧密码！');
                    return;
                }
                if($newpwd=='0'){
                    $this->error('请输入新密码！');
                    return;
                }
                if($qrpwd=='0'){
                    $this->error('请输入确认密码！');
                    return;
                }
                if(strlen_utf8($newpwd) < 6){
                    $this->error('您输入的新密码太短！请输入6-16位密码！');
                    return;
                }
                if($qrpwd!=$newpwd){
                    $this->error('你两次输入的密码不一致！');
                    return;
                }
                if ($user_row['password'] == jiamimd5($oldpwd)) {
                    $M_user = M('user');
                
                    $user_row['password'] = jiamimd5($qrpwd);
                    
                    $updated = $M_user->save($user_row);
                    if ($updated) {
                        $this->success('密码修改成功！', U('index'));
                    } else {
                        $this->error('密码修改失败！');
                    }
                } else {
                    //echo $user_row['password'].'=>'.jiamimd5($oldpwd);
                    $this->error('旧密码有误！');
                }
                break;
            
            default:
                $this->display();
                break;
        }
    }
    /*
    *退出登陆
     */
    public function exitlogin(){
        cookie('u_type',null);

        cookie('u_wxauth',null);
        cookie('u_wxinfo',null);
        cookie('u_detailed',null);
        cookie('u_phone',null);
        cookie('u_pass',null);
        cookie('u_idval',null);
        $this->success('退出成功！', U('Index/index'));
    }
}