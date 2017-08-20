<?php
namespace Admin\Controller;
use Think\Controller;
use PublicClass\Upload;

class IndexController extends Controller {
    public function index(){
        if($_SESSION['r_id']==null OR $_SESSION['r_name']==null OR $_SESSION['r_user']==null) {
            unset($_SESSION['r_id']);
            unset($_SESSION['r_name']);
            unset($_SESSION['r_user']);
            unset($_SESSION['r_auth']);
            
            $this->redirect('login');
        } else {
            $this->redirect('Admin/index');
        }
    }
		
	//登录
	public function login(){
		$this -> display();
	}
	
	//退出登录
	public function logout(){
	    unset($_SESSION['r_id']);
		unset($_SESSION['r_name']);
		unset($_SESSION['r_user']);
		unset($_SESSION['r_auth']);
		
		$this -> redirect("/Index/index");
	}
	
	//登录验证
	public function DoLogin(){
		
	    $M_admin = M("admin");
		
		$p_user = I('post.username','');
		$p_pass = I('post.password','');
		
		if($p_user=='' OR $p_pass=='') {
		    $this->error("数据提交异常！");
		}
		
		$row_user = $M_admin->where("username='%s'",$p_user)->find();
		if(!$row_user) {
		    $this->error("用户名或密码有误！");
		}
		
		if($row_user['password'] != jiamimd5($p_pass)) {
		    $this->error("用户名或密码有误！");
		}
		
		# 写入登录日志
		$M_loginlog = M('loginlog');
		$nowlogin['usertype'] = 'admin';
		$nowlogin['userid'] = $row_user['id'];
		$nowlogin['logip'] = get_client_ip();
		$nowlogin['logtime'] = date('Y-m-d H:i:s');
		
		if($M_loginlog->field('userid')->where(array('userid'=>$row_user['id']))->find()){
			$M_loginlog->where(array('userid'=>$row_user['id']))->save($nowlogin);
		}else{
			$M_loginlog->add($nowlogin);
		}
		
		
		# 注册session变量
		$_SESSION['r_id'] = $row_user['id'];
		$_SESSION['r_name'] = $row_user['nickname'];
		$_SESSION['r_user'] = $row_user['username'];
		$_SESSION['r_auth'] = $row_user['authority'];
		
		$this->redirect('Index/index');
	}


	//上传
    public function upload(){
    	$typeid=$_REQUEST['typeid'];
        
        switch ($typeid) {
            case '1':
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/images/',
                    'classtype'     =>       'jpg|png|gif',
                    'filesize'      =>       800,
                    'nametrue'      =>       1,
                );
                break;
            case '2':
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/void/',
                    'classtype'     =>       'mp4|avi|swf',
                    'filesize'      =>       500*1024,
                    'nametrue'      =>       1,
                );
                break;
            case '3':
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/download/',
                    'classtype'     =>       'txt|doc|rar|zip|xls|xlsx|docx',
                    'filesize'      =>       1000*1024,
                    'nametrue'      =>       1,
                );
                break;
            case '4':
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/vip/',
                    'classtype'     =>       'jpg|png|gif',
                    'filesize'      =>       800,
                    'nametrue'      =>       1,
                );
                break;
            default:
                $filecon=array(
                    'filenam'       =>       'upload_file',
                    'dir_base'      =>       './upload/',
                    'classtype'     =>       'jpg|png|gif',
                    'filesize'      =>       1,
                    'nametrue'      =>       1,
                );
                break;
        }
        //var_dump($filecon);
        echo Upload::Uploadfile($filecon);
    }


}