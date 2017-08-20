<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends AdminAuthController {
    /**
     * 分类列表
     */
    public function catelist() {
        $M_parts = M('parts');
        $M_category = M('category');
        
        // 查询栏目
        $parts_rows = $M_parts->where(array('isshow'=>1))->order('addtime ASC')->select();
        
        // 查询分类
        foreach ($parts_rows as $parts_rows_key=>$parts_rows_item) {
            $parts_rows[$parts_rows_key]['lists'] = $M_category->where(array('partsid'=>$parts_rows_item['id']))->select();
        }
        $this->parts_rows = $parts_rows;
        
        $this->display();
    }
    
    /**
     * 添加分类
     */
    public function cateadd() {
        if(IS_POST){
            $M_category = M('category');
            
            $data['partsid'] = I('post.partsid');
            $data['title'] = I('post.title');
            $data['smalltext'] = I('post.smalltext');
            $data['addtime'] = date("Y-m-d H:i:s");
            
            $added_row = $M_category->add($data);
            if ($added_row) {
                $this->success('添加成功', U('catelist'));
            } else {
                $this->error('添加失败');
            }
        } else {
            $M_parts = M('parts');
            $parts_rows = $M_parts->where(array('isshow'=>1))->order('addtime ASC')->select();
            
            $this->parts_rows = $parts_rows;
            
            $this->display();
        }
    }
    
    /**
     * 删除分类
     */
    public function catedel($id) {
        $id = intval($id);
    
        $M_category = M('category');
    
        $cate_row = $M_category->find($id);
        if (!$cate_row) {
            $this->error('该分类不存在！');
        }
    
        $deled_cate = $M_category->delete($id);
        if ($deled_cate) {
            $this->success('分类删除成功！');
        } else {
            $this->error('分类删除失败！');
        }
    }
    
    /**
     * 修改分类
     */
    public function catemod() {
        $M_category = M('category');
        if (IS_POST) {
            
            $id = I('post.id','','intval');
            $cate_row = $M_category->find($id);
            if (!$cate_row) {
                $this->error('该分类不存在！');
            }
            
            $cate_row['title'] = I('post.title', '');
            $cate_row['smalltext'] = I('post.smalltext', '');
            $cate_row['partsid'] = I('post.partsid', '');
            
            $updated_row = $M_category->save($cate_row);
            if ($updated_row){
                $this->success('修改成功', U('catelist'));
            } else {
                $this->error('修改失败');
            }
        } else {
            $M_parts = M('parts');
            $parts_rows = $M_parts->where(array('isshow'=>1))->order('addtime ASC')->select();
            
            $this->parts_rows = $parts_rows;
            
            $id = I('get.id','','intval');
            
            $cate_row = $M_category->where(array('id'=>$id))->find();
            if(!$cate_row) {
                $this->error('该分类不存在！');
            }
            
            $this->cate_row = $cate_row;
            
            $this->display();
        }
    }
}