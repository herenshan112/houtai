<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends Controller {
    /**
     * 在线留言
     */
    public function index() {
        if (IS_POST) {
            $M_messages = M('messages');
            
            $data = array(
                'name' => I('post.name'),
                'phone' => I('post.phone'),
                'content' => I('post.content'),
                'addtime' => date('Y-m-d H:i:s')
            );
            $added = $M_messages->add($data);
            if ($added) {
                $msg = '您的留言已成功提交！';
            }
        } else {
            $msg = '';
        }
        
        $this->msg = $msg;
        
        $this->display();
    }
}