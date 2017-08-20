<?php
namespace Index\Controller;
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
            $this->success('您的留言已成功提交！', U('Index/index'));
        } else {
            $msg = '';
        }
        
        $this->msg = $msg;
        
        $this->display();
    }


    /*
    *搜索
     */
    public function sousuo(){
        $ssgjc=I('post.ssgjc')?I('post.ssgjc'):'';
        if($ssgjc == ''){
            $ssgjc=I('get.ssgjc')?I('get.ssgjc'):'';
        }
        $user_row = getuscont();
        $ssm=M('sousuo');
        if($ssgjc != ''){
            $ssjmp=$ssm->where(array('ss_title'=>$ssgjc))->find();
            if($ssjmp){
                $zjyi=$ssm->where(array('ss_title'=>$ssgjc))->setInc('ss_sum'); 
            }else{
                $idss=$user_row['id']?$user_row['id']:0;
                $ssdata=array(
                    'ss_title'          =>               $ssgjc,
                    'ss_uid'            =>               $idss,
                    'ss_sum'            =>                  1,
                    'ss_time'           =>                  time(),
                    'ss_set'            =>                  1
                );
                $zjyi=$ssm->add($ssdata);
            }
            $list=M('products')->alias('p')->field('p.id as pid,p.*,t.title as tyname')->join('__PRODUTYPE__ t ON t.id=p.type','LEFT')->where(array('p.title'=>array('LIKE','%'.$ssgjc.'%')))->order(array('showturn'=>'asc','p.id'=>'desc'))->select();
            //var_dump($list);
            $this->assign('list',$list);
            $this->assign('empty','<span style="float: left; width: 100%; text-align: center; line-height: 120px; color: #f00;">商品更新中，敬请期待......</span>');
            $this->display('sscont');
        }else{
            $djdzs=$ssm->where(array('ss_set'=>1))->limit(5)->order(array('ss_sum'=>'desc','ss_id'=>'desc'))->select();
            $this->assign('djdzs',$djdzs);
            $user_row = getuscont();
            $wdls=$ssm->where(array('ss_set'=>1,'ss_uid'=>$user_row['id']))->order(array('ss_id'=>'desc'))->limit(10)->select();
            $this->assign('wdls',$wdls);
            $this->display();
        }
    }
    /*
    *清楚搜索
     */
    public function qcsousuo(){
        $user_row = getuscont();
        M('sousuo')->where(array('ss_uid'=>$user_row['id']))->delete();
        $this->success('清理完成！', U('Index/index'));
    }

}