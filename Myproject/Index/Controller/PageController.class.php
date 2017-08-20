<?php
namespace Index\Controller;
use Think\Controller;
class PageController extends Controller {
    public function index(){
    }
    
    /**
     * 关于我们
     */
    public function about() {
        $gywm=M('about')->find();

        $list=M('news')->where(array('cateid'=>17))->limit(6)->order(array('id'=>'desc'))->select();
        $this->assign('gywm',$gywm);
        $this->assign('list',$list);
        $this->display();
    }
}