<?php
namespace Index\Controller;
use Think\Controller;
class LectureController extends Controller {
    /**
     * 患者讲堂 
     */
    public function index() {
        $M_category = M('category');
        $cate_lists = $M_category->where('partsid=4')->order('showturn DESC')->select();
        
        $this->cate_lists = $cate_lists;
        
        $this->display();
    }
    
    /**
     * 视频中心
     */
    public function movie() {
        $M_news = M('news');
        $news_rows = $M_news->where(array('cateid'=>'14'))->order('addtime DESC')->select();
        
        $this->news_rows = $news_rows;
        
        $this->display();
    }
    
    /**
     * 视频详情
     */
    public function moviedetail($id=0) {
        $M_news = M('news');
        $movie = $M_news->where(array('id'=>$id))->find();
        if (!$movie) {
            $this->error('数据不存在！');
        }
        
        $this->movie = $movie;
        
        $this->display();
    }
    
    /**
     * 健康常识
     */
    public function news() {
        $M_news = M('news');
        $M_category = M('category');
        $p_cateid = I('get.pid', '0', 'intval');
        
        // 栏目
        $cate_row = $M_category->where(array('id'=>$p_cateid))->find();
        if (!$cate_row) $this->redirect('index');
        
        // 文章
        $news_rows = $M_news->where(array('cateid'=>$p_cateid))->order('addtime DESC')->select();
        
        $this->cate_row = $cate_row;
        $this->news_rows = $news_rows;
        
        $this->display();
    }
    
    /**
     * 下载
     */
    public function download() {
        $M_news = M('news');
        $news_rows = $M_news->where(array('cateid'=>'15'))->order('addtime DESC')->select();
        
        $this->news_rows = $news_rows;
        
        $this->display();
    }
}