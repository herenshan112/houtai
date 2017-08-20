<?php
namespace Admin\Controller;
use Think\Controller;
class MessageController extends Controller {
    /**
     * 留言列表
     */
    public function showlists($cateid=0) {
        $M_messages = M('messages');
		
		$count = $M_messages->where(array('cateid'=>$cateid))->count();
		$Page = new \Think\Page($count, 10);
		$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
		$show = $Page -> show();
		
		$message_list = $M_messages->where(array('cateid'=>$cateid))->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
		
		$this->message_list = $message_list;
		$this->page = $show;
		
		$this->display();
    }
    
    /**
     * 详情
     */
    public function detail($id=0) {
        $M_messages = M('messages');
        
        $message_row = $M_messages->find($id);
        
        $message_row['isread'] = 1;
        
        $M_messages->save($message_row);
        
        $this->message_row = $message_row;
        
        $this->display();
    }
    
    /**
     * 删除
     */
    public function del($id=0) {
        $M_messages = M('messages');
        
        $del_row = $M_messages->delete($id);
        if ($del_row) {
            $this->success("删除成功", U("showlists"));
        } else {
            $this->error("删除失败");
        }
    }

    /**
     * 删除产品信息
     */
    public function delall() {
        $p_opid = I('post.opid');
        
        $M_user = M('messages');
        
        foreach ($p_opid as $k=>$v) {
            $deled[] = $M_user->delete($v);
            
        }
        
        if($deled) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}