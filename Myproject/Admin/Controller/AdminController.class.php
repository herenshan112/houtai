<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends AdminAuthController {
    /**
     * 框架基础页
     */
    public function index() {
        $this->display('base');
    }
    
    /**
     * 用户名验证
     */
    public function validuser($v_phone=null) {
        if(IS_AJAX) {
            if(!$v_phone) {
                $data = array('code'=>'-2', 'msg'=>'参数提交不完整');
                $this->ajaxReturn($data, 'json');
            }
            $M_salesman = M('salesman');
            
            $is_valid = $M_salesman->where(array('phone'=>$v_phone))->count();
            if(!$is_valid) {
                $data = array('code'=>'1');
            } else {
                $data = array('code'=>'-1');
            }
            $this->ajaxReturn($data, 'json');
        }
        $data = array('code'=>'-3');
        $this->ajaxReturn($data, 'json');
    }
    
    /**
     * 管理员列表
     */
    public function adlists() {
        $M_admin = M('admin');
        
        $admin = $M_admin->order('id ASC')->select();
        
        $this->lists = $admin;
        $this->display();
    }
    
    /**
     * 添加管理员
     */
    public function adduser() {
        $M_admin = M('admin');
        
        if(IS_POST) {
            $data = I('post.');
            $data['password'] = $this->password($data['password']);
            $added = $M_admin->add($data);
            if($added) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        } else {
            $this->display();
        }
    }
    
    /**
     * 修改管理员信息
     */
    public function moduser($id=0) {
        $user = $this->getUser($id);
        if(!$user){
            $this->error('数据异常或用户不存在!');
        }
        
        $M_admin = M('admin');
        
        if(IS_POST) {
            $data = I('post.');
            
            unset($data['username']);
            
            $data['password'] = $this->password($data['password']);
            //$data['authority'] = 'admin';
            $added = $M_admin->where(array('id'=>$id))->save($data);
            if($added) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $this->admin = $user;
            
            $this->display();
        }
    }
    
    /**
     * 删除管理员
     */
    public function deluser($id=0) {
        $user = $this->getUser($id);
        if(!$user){
            $this->error('数据异常或用户不存在!');
        }
        
        if($user['username']=='admin'){
            $this->error('主管理员不可删除!');
        }
        
        $M_admin = M('admin');
        $deled = $M_admin->delete($id);
        if($deled) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    /**
     * 查询用户
     */
    private function getUser($userid=0) {
        $M_admin = M('admin');
        if(!$userid) {
            return false;
        }
        $user = $M_admin->find($userid);
        if(!$user) {
            return false;
        }
        
        return $user;
    }
    
    /**
     * 修改密码
     */
    public function modpass(){
        $M_admin = M('admin');
        
        $admin_id = $_SESSION['r_id'];
        $admin = $M_admin->where(array('id'=>$admin_id))->find();
        
        if(IS_POST){
            $p_oldpass = I('post.oldpassword');
            $p_newpass = I('post.password');
            
            if($this->password($p_oldpass) != $admin['password']){
                $this->error('旧密码有误！');
            }else{
                $admin['password'] = $this->password($p_newpass);
                $updated = $M_admin->save($admin);
                if($updated){
                    unset($_SESSION['r_id']);
                    unset($_SESSION['r_name']);
                    unset($_SESSION['r_user']);
                    unset($_SESSION['r_auth']);
                    
                    $this->success('修改成功,请重新登录！', U('Index/login'));
                }else{
                    $this->error('修改失败');
                }
            }
            
        }else{
            $this->admin = $admin;
            
            $this->display();
        }
    }
    
    /**
     * 密码加密
     */
    private function password($pass_str='') {
        return jiamimd5($pass_str);
    }
    
    /**
     * 头部
     */
    public function top() {
        $this->assign('oednm',M('orders')->where(array('lookset'=>0))->count());
        $this->assign('mesnm',M('messages')->where(array('isread'=>0))->count());
        $this->display();
    }

    /**
     * 左边导航部分
     */
    public function left() {
        $this->display();
    }
    
    /*
    *授权大类写入
     */
    static function sqbigtype($id,$key){
        $bigm=M('menucate');
        $kjcn=$bigm->where(array('id'=>$id))->find();
        if($kjcn){
            if(in_array($key,explode('|',$kjcn['authtype']))){
                $xrz['authtype']=$kjcn['authtype'];
            }else{
                $xrz['authtype']=$kjcn['authtype'].$key.'|';
            }
            $kjcxrn=$bigm->where(array('id'=>$id))->save($xrz);
            return 1;
        }else{
            return 0;
        }
    }
    /*
    *授权大类写入
     */
    static function sqsmalltype($id,$key){
        $smallm=M('menuitem');
        $kjsmcn=$smallm->where(array('id'=>$id))->find();
        if($kjsmcn){
            if(in_array($key,explode('|',$kjsmcn['authtype']))){
                $xsmrz['authtype']=$kjsmcn['authtype'];
            }else{
                $xsmrz['authtype']=$kjsmcn['authtype'].$key.'|';
            }
            $kjcsnxrn=$smallm->where(array('id'=>$id))->save($xsmrz);
            return 1;
        }else{
            return 0;
        }
    }
    /**
     * 授权列表
     */
    public function authlist() {
        
        $M_menucate = M('menucate');
        $M_admin = M('admin');
        $M_menuitem = M('menuitem');
        if ($_SESSION['r_auth'] != 'admin') $this->error('无权查看此页面！');
        
        if (IS_POST) {
            $p_menucate = I('post.menucate');
            $p_menuitem = I('post.menuitem');
            $menucate_arr = array();

            foreach ($p_menucate as $key => $bigty) {
                $temp_menucate_arr = explode('||', $bigty);
                //echo $temp_menucate_arr[0].'=>'.$temp_menucate_arr[1].'<br>';
                $hsdj=  self::sqbigtype($temp_menucate_arr[1],$temp_menucate_arr[0]).'<br>';
            }

            foreach ($p_menuitem as $key => $smalty) {
                $temp_small_arr = explode('||', $smalty);
                //echo $temp_small_arr[0].'=>'.$temp_small_arr[1].'<br>';
                $hj= self::sqsmalltype($temp_small_arr[1],$temp_small_arr[0]).'<br>';
            }
            $this->success('分配完成');
            /*$temp_menucate_arr = explode('||', $p_menucate);

            var_dump($temp_menucate_arr);*/

            /*foreach ($p_menucate as $p_menucate_item) {
                $temp_menucate_arr = explode('||', $p_menucate_item);
                //$temp_menucate_arr[0];
                //$temp_menucate_arr[1];
                $menucate_arr['key'][] = $temp_menucate_arr[1];
                $menucate_arr['val'][] = $temp_menucate_arr[0].'|';
                
                //echo implode(',', $menucate_arr['key']);
                M()->query("UPDATE __MENUCATE__ SET authtype='|admin|' WHERE id NOT IN (".implode(',', $menucate_arr['key']).")");
                
                $temp_menucate_rows = $M_menucate->where('id IN ('.implode(',', $menucate_arr['key']).')')->select();
                foreach ($temp_menucate_rows as $temp_menucate_rows_k => $temp_menucate_rows_v) {
                    if (strpos($temp_menucate_rows_v['authtype'], '|'.$menucate_arr['val'][$temp_menucate_rows_v['id']].'|') !== false) {
                        
                    }
                    
                    $M_menucate->data($temp_menucate_rows_v)->save();
                }
                $temp_menucate_row['authtype'];
            }*/
        } else {
            $auth_rows = $M_admin->distinct('authority')->where("authority!='admin'")->field('authority')->select();
            
            $menucate_rows = $M_menucate->field('id,name,authtype')->order('listorder DESC, addtime DESC')->where('isshow=1')->select();
            foreach ($menucate_rows as $menucate_rows_key=>$menucate_rows_item) {
                $menuitem_rows = $M_menuitem->where(array('menucateid'=>$menucate_rows_item['id'], 'isshow'=>'1'))->field('id,name,authtype')->select();
                $menucate_rows[$menucate_rows_key]['subitem'] = $menuitem_rows;
            }
            
            $this->auth_rows = $auth_rows;
            
            $this->menucate_rows = $menucate_rows;
            
            $this->display();
        }
    }
    
    /**
     * 参数显示
     */
    public function params() {
        $cateid = I('get.id');
        $M_params = M('params');
        
        if(IS_POST) {
            $p_data = I('post.');
            foreach ($p_data as $params_key=>$params_item) {
                $M_params->where(array('name'=>$params_key))->save(array('value'=>$params_item));
            }
            $this->success('修改成功');
        } else {
            $params_rows = $M_params->where(array('cateid'=>$cateid, 'isshow'=>1))->order('addtime ASC')->select();
            
            $this->params_rows = $params_rows;
            
            $this->display();
        }
    }
    
    /**
     * 右边内容部分
     */
    public function right() {
        # 获取上次登录信息
        $log_show = array();
        $M_loginlog = M('loginlog');
        
        $logrows = $M_loginlog->where(array('usertype'=>'admin','userid'=>$_SESSION['r_id']))->order('logtime DESC')->limit(2)->select();
        if(count($logrows)==1) {
            # 首次登录
            $log_show['msg'] = '首次登录, 欢迎您使用本系统。';
        } else {
            $log_show['ip'] = $logrows[1]['logip'];
            $log_show['time'] = $logrows[1]['logtime'];
        }
        $this->show_loginlog = $log_show;
        
        /*# 删除30天之前的登录记录
        $delcond['usertype'] = array('eq','admin');
        $delcond['userid'] = array('eq',$_SESSION['r_id']);
        $delcond['logtime'] = array('lt', date('Y-m-d 00:00:00',strtotime('-30 day')));
        $M_loginlog->where($delcond)->delete();*/
        
        $this->display();
    }
    
    /**
     * 登录日志
     */
    public function loglists() {
        $M_loginlog = M('loginlog');
        $M_admin = M('admin');
        
        $log_where = array();
        
        // 是否可删除一个月之前的记录,如果删除之后没有记录,则不删除
        $log_where['userid'] = array('eq', $_SESSION['r_id']);
        
        $log_where['logtime'] = array('gt', date('Y-m-d H:i:s', strtotime('-1 month')));
        
        $can_delete = $M_loginlog->where($log_where)->count();
        if($can_delete) {
            // 删除一个月之前的记录
            $log_where['logtime'] = array('lt', date('Y-m-d H:i:s', strtotime('-1 month')));
            $M_loginlog->where($log_where)->delete();
        }
        
        // 所有用户,用于用户名转换
        $admin_user = $M_admin->select();
        $username_lists = array();
        foreach ($admin_user as $user_item) {
            $username_lists[$user_item['id']]['username'] = $user_item['username'];
            $username_lists[$user_item['id']]['nickname'] = $user_item['nickname'];
        }
        unset($log_where['logtime']);
        
        if($_SESSION['r_auth']=='admin') {
            unset($log_where['userid']);
        }
        
        $count = $M_loginlog->where($log_where)->count();
        $Page = new \Think\Page($count,16);
        $pageshow = $Page->show();
        
        $log_lists = $M_loginlog->where($log_where)->order('logtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $this->username_lists = $username_lists;
        $this->log_lists = $log_lists;
        
        $this->pageshow = $pageshow;
        
        $this->display();
    }
    
    /**
     * ajax筛选层级
     */
    public function ajaxlevel() {
        if (IS_AJAX) {
            $p_id = I('post.id', '0', 'intval');
            $p_op = I('post.op', '0', 'intval');
            
            if (!$p_id OR !$p_op) $this->ajaxReturn(array('code'=>'-1', 'msg'=>'参数提交不完整！'));
            
            $M_sale_agent = M('sale_agent');
            
            $where = array(
                'pid' => $p_id,
                'typeid' => $p_op,
            );
            $rows = $M_sale_agent->where($where)->field('id,title,code')->select();
            
            $this->ajaxReturn(array('code'=>'1', 'rows'=>$rows));
        }
    }
}