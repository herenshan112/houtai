<?php
namespace Index\Controller;
use Think\Controller;

class NewsController extends Controller {
    /**
     * 列表
     */
    public function showlists() {
        $M_category = M('category');
        
        $M_news = M('news');
        
        $news_cate = $M_category->where(array('partsid'=>1))->order('addtime ASC')->select();
        
        foreach ($news_cate as $news_cate_k=>$news_cate_v) {
            $cateid = $news_cate_v['id'];
            $news_cate[$news_cate_k]['news_rows'] = $M_news->where(array('cateid'=>$cateid))->order('addtime DESC')->limit(6)->select();
        }
        
        $this->news_cate = $news_cate;
        
        $this->display();
    }
    
    /**
     * 新闻内容
     */
    public function detail($id=0) {
        if (!$id) $this->redirect('showlists');
        
        $M_news = M('news');
        $news_row = $M_news->find($id);
        
        if (!$news_row) {
            $this->redirect('showlists');
        }
        
        // 更新阅读量
        $M_news->where(array('id'=>$id))->setInc('hits', 1);
        
        // 获取分类标题
        $news_row['catename'] = Util::cateid2name($news_row['cateid']);
        
        $this->news_row = $news_row;
        
        $this->display();
    }
    
    /**
     * 搜索
     */
    public function search() {
        $p_keywords = I('get.keywords', '', 'trim');
        if (!$p_keywords) $this->redirect('Index/index');
        
        $M_news = M('news');
        $map['title'] = array('like', "%".$p_keywords."%");
        $news_lists = $M_news->where($map)->order('addtime DESC')->select();
        
        $this->news_lists = $news_lists;
        $this->keyword = $p_keywords;
        
        $this->display();
    }
}