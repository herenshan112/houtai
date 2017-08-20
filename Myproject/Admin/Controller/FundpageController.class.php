<?php
namespace Admin\Controller;
use Think\Controller;
class FundpageController extends Controller {
    public function showpage($id) {
        $M_fundpage = M('fundpage');
        if(IS_POST) {
            $data = I('post.');
            $saved = $M_fundpage->where(array('id'=>$id))->save($data);
            if($saved) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $page_row = $M_fundpage->find($id);
            
            $this->page_row = $page_row;
            $this->display();
        }
    }
}