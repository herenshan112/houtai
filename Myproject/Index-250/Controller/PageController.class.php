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
        
        $this->display();
    }
}