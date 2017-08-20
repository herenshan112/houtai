<?php
namespace Admin\Controller;
use Think\Controller;
class MembersController extends AdminAuthController {
	/*
	 * 会员列表
	 */
	public function showlists() {
        $M_user = M('user');
        $M_user_detail = M('user_detail');
        $users_rows = $M_user->select();
		foreach ($users_rows as $users_key=>$users_item) {
		    $users_rows[$users_key]['detail'] = $M_user_detail->where(array('userid'=>$users_item['id']))->find();
		}
		
		// 分页
		$count = $M_user->count();
		$Page = new \Think\Page($count,15);
		$pageshow = $Page->show();
        
        $this->users_rows = $users_rows;
		$this->pageshow = $pageshow;
        
		$this->display();
	}
    
	/*
	 * 添加用户
	 */
	public function add() {
		$this->display();
	}
	
	/*
	 * 批量导入
	 */
	public function leadin() {
	    $this->display();
	}
	
	/*
	 * 用户详情
	 */
	public function detail($memberid=0) {
	    if(!$memberid) {
	        $this->error('提交数据异常!');
	    }
	    
	    $M_user = M('user');
	    $M_user_detail = M('user_detail');
	    
	    $user_data = $M_user->find($memberid);
	    if(!$user_data) {
	        $this->error('无此用户，可能已被删除!');
	    }
	    
	    $user_data['detail'] = $M_user_detail->where(array('userid'=>$user_data['id']))->find();
	    
	    $this->user_data = $user_data;
	    
	    $this->display();
	}
	
	/**
	 * 修改授权
	 */
	public function authuser() {
	    $userid = I('post.id');
	    $authid = I('post.authid/d');
	    
	    if(!$userid) {
	        $this->error('提交数据异常!');
	    }
	    
	    $M_user = M('user');
	     
	    $user_data = $M_user->find($userid);
	    if(!$user_data) {
	        $this->error('无此用户，可能已被删除!');
	    }
	    
	    $user_data['authid'] = $authid;
	    
	    $authed = $M_user->save($user_data);
	    if($authed) {
	        $this->success('授权成功!');
	    } else {
	        $this->error('授权失败!');
	    }
	}
	
	
	public function doadd(){
		$_POST['authority'] = $_POST['authority'] ? $_POST['authority'] : 1 ;
		$_POST['lockstatus'] = 2 ;
		//调用编号方法
		$_POST['varstr'] = $this -> number();
		$_POST['vartime'] = time();
		$M_user = M('user');
		$username = $_POST['username'];
		$phonenum = $_POST['phonenum'];
		$ucount1 = $M_user -> where("username='$username'") -> count();
		$ucount2 = $M_user -> where("phonenum='$phonenum'") -> count();
		if($ucount2 > 0){
			$this -> error('电话号码已存在');
			die();
		}
		if($ucount1 > 0){
			$this -> error('用户名已存在');
		}else{
			$M_user -> create();
			$res = $M_user -> add();
			if($res){
				$this -> success('添加成功','showlists');
			}else{
				$this -> error("添加失败");
			}
		}
	}
	
	/*
	 * 修改用户信息
	 */
	public function mod(){
		//取出用户原信息
		$id = $_GET['id'];
		$M_user = M('user');
		$data = $M_user -> where("id=$id") -> find();
		$this -> assign('data',$data);
		
		$this -> display();
	}
	
	public function domod(){
		$M_user = M('user');
		if(!empty($_GET['id'])){
			$id = intval($_GET['id']);
			$arr['lockstatus'] = intval($_GET['lockstatus']);
			$res = $M_user -> where("id=$id") -> save($arr);
			if($res){
				$this -> redirect('showlists');
			}else{
				$this -> error('操作失败');
			}
		}else{
			$id = $_POST['id'];
			$M_user -> create();
			$res = $M_user -> where("id=$id") -> save();
			if($res){
				$this -> success('修改成功','showlists');
			}else{
				$this -> error('修改失败');
			}
		}
	}

	/*
	 * 删除会员
	 */
	public function del(){
        $M_user = M('user');
        $M_user_detail = M('user_detail');
        
        $id = I('get.memberid/d');

		$deled = $M_user->delete($id);
		if($deled) {
		    $M_user_detail->where(array('userid'=>$id))->delete();
		    
            $this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
}