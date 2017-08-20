<?php
namespace Admin\Controller;
use Think\Controller;
class LectureController extends AdminAuthController {
    /**
     * 文档列表
     */
    public function lists() {
		$M_news = M("news");
		
		$count = $M_news->where(array('pid'=>'4'))->count();  //查出总是
		$Page = new \Think\Page($count, 10);  // 实例化 分页类
		//定制分页类
		$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
		$show = $Page -> show();
		
		$news_list = $M_news->where(array('pid'=>'4'))->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
		
		$this->news_list = $news_list;
		$this->page = $show;
		
		$this->display();
    }
	
	/**
	 * 添加新闻
	 */
	public function add() {
		if(IS_POST){
		    $M_news = M('news');
			$data = I("post.");
			$data['pid'] = '4';
			$data['addtime'] = date("Y-m-d H:i:s");
			$added = $M_news->add($data);
			if ($added){
				$this->success("添加成功");
			} else {
				$this->error("添加失败");
			}
		} else {
		    $M_parts = M('parts');
		    $M_category = M('category');
		    
		    $catelist = $M_category->where(array('partsid'=>'4'))->order('addtime ASC')->select();
		    
		    $this->catelist = $catelist;
		    
			$this->display();
		}
	}
	
	
	/**
	 * 修改新闻
	 */
	public function mod() {
		$M_news = M("news");
		if(IS_POST){
			$data = I("post.");
			$moded = $M_news->save($data);
			if($moded){
				$this -> success("修改成功", U("lists"));
			}else{
				$this -> error("修改失败");
			}
		} else {
			$id = I("get.id");
			
			$data = $M_news -> where(array("id"=>$id))->find();
			
			$M_category = M('category');
		    $catelist = $M_category->where(array('partsid'=>'4'))->order('addtime ASC')->select();
		    
		    $this->catelist = $catelist;
			$this->data = $data;
			
			$this->display();
		}
	}
	
	/**
	 * 删除新闻
	 */
	public function del() {
		$M_news = M("news");
		$id = I("get.id");
		$del_row = $M_news->where(array('id'=>$id))->delete();
		if ($del_row) {
			$this->success("删除成功", U("lists"));
		} else {
			$this->error("删除失败");
		}
	}
}