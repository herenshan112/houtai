<?php
namespace Admin\Controller;
use Think\Controller;
class NewsController extends AdminAuthController {
    /**
     * 文档列表
     */
    public function showlists() {
		$M_news = M("news");
		
		$count = $M_news->where(array('pid'=>'1'))->count();  //查出总是
		$Page = new \Think\Page($count, 10);  // 实例化 分页类
		//定制分页类
		$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
		$show = $Page -> show();
		
		$news_list = $M_news->where(array('pid'=>'1'))->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
		
		$this->news_list = $news_list;
		$this->page = $show;
		
		$this->display();
    }
	
	/**
	 * 添加新闻
	 */
	public function newsadd() {
		if(IS_POST){
		    $M_news = M('news');
			$data = I("post.");
			$data['pid'] = '1';
			$data['addtime'] = date("Y-m-d H:i:s");
			$added = $M_news->add($data);
			if ($added){
				$this->success("添加成功", U("showlists"));
			} else {
				$this->error("添加失败");
			}
		} else {
		    $M_parts = M('parts');
		    $M_category = M('category');
		    
		    $catelist = $M_category->where(array('partsid'=>'1'))->order('addtime ASC')->select();
		    
		    $this->catelist = $catelist;
		    
			$this->display();
		}
	}
	
	
	/**
	 * 修改新闻
	 */
	public function newsmod() {
		$M_news = M("news");
		if(IS_POST){
			$data = I("post.");
			$moded = $M_news->save($data);
			if($moded){
				$this -> success("修改成功", U("showlists"));
			}else{
				$this -> error("修改失败");
			}
		} else {
			$id = I("get.id");
			
			$data = $M_news -> where(array("id"=>$id))->find();
			
			$M_category = M('category');
		    $catelist = $M_category->where(array('partsid'=>'1'))->order('addtime ASC')->select();
		    
		    $this->catelist = $catelist;
			$this->data = $data;
			
			$this->display();
		}
	}
	
	/**
	 * 删除新闻
	 */
	public function newsdel() {
		$M_news = M("news");
		$id = I("get.id");
		$del_row = $M_news->where(array('id'=>$id))->delete();
		if ($del_row) {
			$this->success("删除成功", U("showlists"));
		} else {
			$this->error("删除失败");
		}
	}
	//关于我们
	public function gywm(){
		$M_news = M("about");
		if(IS_POST){
			$id = I("get.id");
			$dataval=array(
				'title'					=>				I("post.title"),
				'smalltxt'				=>				I("post.smalltext"),
				'cont'					=>				I("post.content"),
				'time'					=>				time()
			);
			if($id){
				$moded = $M_news->where(array('id'=>$id))->save($dataval);
				
			}else{
				$moded = $M_news->add($dataval);

			}
			
			if($moded){
				$this -> success("修改成功", U("gywm"));
			}else{
				$this -> error("修改失败");
			}
		} else {
			$list=$M_news->order(array('id'=>'desc'))->find();
			$this->assign('list',$list);
			$this->display();
		}
	}
	/*
	*公司资质
	 */
	public function zizhiadd(){
		$action = I("get.action")?I("get.action"):'list';
		switch ($action) {
			case 'add':
				$M_category = M('category');
		    
			    $catelist = $M_category->where(array('partsid'=>'4','id'=>array('NEQ',16)))->order('addtime ASC')->select();
			    
			    $this->catelist = $catelist;
			    $this->assign('action','addcl');
				$this->display('zzcont');
				break;
			case 'addcl':
				$M_news = M('news');
				$data = I("post.");
				$data['pid'] = '4';
				$data['addtime'] = date("Y-m-d H:i:s");
				$added = $M_news->add($data);
				if ($added){
					$this->success("添加成功", U("zizhiadd"));
				} else {
					$this->error("添加失败");
				}
				break;
			case 'eite':
				$id = I("get.id");
				if($id){
					$M_news = M('news');
					$data = $M_news -> where(array("id"=>$id))->find();
			
					$M_category = M('category');
				    $catelist = $M_category->where(array('partsid'=>'4'))->order('addtime ASC')->select();
				    
				    $this->catelist = $catelist;
					$this->data = $data;
					$this->assign('action','eitecl');
					$this->assign('id',$id);
					$this->display('zzcont');
				}else{
					$this->error("参数错误！");
				}
				break;
			case 'eitecl':
				$data = I("post.");
				$M_news = M('news');
				$moded = $M_news->where(array('id'=>I('get.id')))->save($data);
				if($moded){
					$this -> success("修改成功", U("zizhiadd"));
				}else{
					$this -> error("修改失败");
				}
				break;
			default:
				$M_news = M("news");
		
				$count = $M_news->where(array('cateid'=>'17'))->count();  //查出总是
				$Page = new \Think\Page($count, 10);  // 实例化 分页类
				//定制分页类
				$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
				$Page->setConfig('prev','上一页');
				$Page->setConfig('next','下一页');
				$Page->setConfig('first','首页');
				$Page->setConfig('last','末页');
				$Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
				$show = $Page -> show();
				
				$news_list = $M_news->where(array('cateid'=>'17'))->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
				
				$this->news_list = $news_list;
				$this->page = $show;
				
				$this->display();
				break;
		}
	}

	/**
     * 删除新闻信息
     */
    public function delall() {
        $p_opid = I('post.opid');
        
        $M_user = M('news');
        
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