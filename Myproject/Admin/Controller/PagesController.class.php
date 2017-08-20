<?php
namespace Admin\Controller;
use Think\Controller;
class PagesController extends AdminAuthController {
    public function page(){
		$M_pages = M("pages");
		$M_pagedetail = M("pagedetail");
		
		if(IS_POST){
		    $p_id = I('post.id/d');
			$p_title=I('post.title');
			$p_titlepic=I('post.titlepic');
			$p_content = I('post.content');
			$p_addtime = I('post.addtime');
			
			$data['title'] = $p_title;
			$data['titlepic'] = $p_titlepic;
			$data['addtime'] = $p_addtime;
			
			$data_content['content'] = $p_content;
			
			$updatedpage = $M_pages->where(array('id'=>$p_id))->save($data);
			$updated_content = $M_pagedetail->where(array('pageid'=>$p_id, 'typeid'=>'0'))->save($data_content);
			if($updatedpage OR $updated_content){
				$this->success('修改成功');
			} else {
				$this->error('修改失败');
			}
		}else{
			$pageid = I('get.id/d');
			$page_row = $M_pages->find($pageid);
			$page_row['content'] = $M_pagedetail->where(array('pageid'=>$page_row['id'], 'typeid'=>'0'))->find();
			$this->page_row = $page_row;
			
			$this -> display();
		}
    }
}