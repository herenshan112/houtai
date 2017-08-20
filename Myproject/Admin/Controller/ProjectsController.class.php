<?php
namespace Admin\Controller;
use Think\Controller;
class ProjectsController extends AdminAuthController {
    /*
     * 按分类显示项目列表
     * @param $id int 项目cateid
     */
    public function showlists($cateid){
        // 获取所有分类,id转换name
        $M_cate = M('cate');
        $cate_rows = $M_cate->where(array('catetype'=>3))->select();
        
        $cateid2catename = array();
        foreach ($cate_rows as $cate_rowitem) {
            $cateid2catename[$cate_rowitem['id']] = $cate_rowitem['catename'];
        }
        
        // 获取项目列表
        $M_projects = M('projects');
        $M_user = M('user');
        $M_user_detail = M('user_detail');
        $M_gbook = M('gbook');
        $M_projectvar = M('projectvar');
        
        if($cateid) {
            $map['cateid'] = array('eq', $cateid);
            
            $count = $M_projects->where($map)->count();
            $Page = new \Think\Page($count, 15);
            $Page->parameter['cateid'] = $cateid;
            
            $projects_rows = $M_projects->where($map)->order('modified DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        } else {
            $count = $M_projects->count();
            $Page = new \Think\Page($count, 15);
            
            $projects_rows = $M_projects->order('modified DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        // 分页
        $pageshow = $Page->show();
        
        if($projects_rows) {
            foreach ($projects_rows as $projects_rowkey=>$projects_rowitem) {
                // 发布类别
                $projects_rows[$projects_rowkey]['catename'] = $cateid2catename[$projects_rowitem['cateid']];
 
                // 发布人                
                $temp_user = $M_user_detail->where(array('userid'=>$projects_rowitem['userid']))->field('truename,team')->find();
                $projects_rows[$projects_rowkey]['truename'] = $temp_user['truename'];
                $projects_rows[$projects_rowkey]['team'] = $temp_user['team'];
                
                // 留言数量
                $projects_rows[$projects_rowkey]['gbooknum'] = $M_gbook->where(array('projectid'=>$projects_rowitem['id'], 'ischecked'=>'1'))->count();
                // 投票数量
                $projects_rows[$projects_rowkey]['votenum'] = $M_projectvar->where(array('projectid'=>$projects_rowitem['id'], 'vartype'=>'1'))->count();
                // 参加数量
                $projects_rows[$projects_rowkey]['joinnum'] = $M_projectvar->where(array('projectid'=>$projects_rowitem['id'], 'vartype'=>'2'))->count();
            }
        }
        
        $this->projects_rows = $projects_rows;
        $this->pageshow = $pageshow;
        
        $this->display();
    }
    
    /*
     * 修改项目信息
     * @param $projectid int 项目ID
     */
    public function mod($projectid) {
        $M_projects = M('projects');
        
        $project_row = $M_projects->find($projectid);
        if(!$project_row) {
            $this->error('该项目信息不存在，可能已被删除！');
        }
        
        if(IS_POST) {
            //$project_row[];
            
            $moded_project = $M_projects->save($project_row);
            if($moded_project) {
                $this->success('项目修改成功！');
            } else {
                $this->error('项目修改失败！');
            }
        } else {
            $this->project_row = $project_row;
            $this->display();
        }
    }
    
    /*
     * 删除项目信息
     * @param $projectid int 项目ID
     */
    public function del($projectid) {
        $projectid = intval($projectid);
        
        $M_projects = M('projects');
        
        $project_row = $M_projects->find($projectid);
        if(!$project_row) {
            $this->error('该项目信息不存在，可能已被删除！');
        }
        
        $deled_project = $M_projects->delete($projectid);
        if($deled_project) {
            $this->success('项目删除成功！');
        } else {
            $this->error('项目删除失败！');
        }
    }
    
    /*
     * 显示项目详细信息
     */
    public function detail($projectid) {
        $M_projects = M('projects');
        
        $project_row = $M_projects->find($projectid);
        if(!$project_row) {
            $this->error('该项目信息不存在，可能已被删除！');
        }
        
        $this->project_row = $project_row;
        
        $this->display();
    }
}