<?php
namespace Admin\Controller;
use Think\Controller;
class GbookController extends Controller {
    /*
     * 留言列表
     */
    public function showlists(){
        $M_gbook = M('gbook');
        $M_projects = M('projects');
        $M_user = M('user');
        $M_user_detail = M('user_detail');
        
        $gbook_rows = $M_gbook->order('posttime DESC')->select();
        
        if($gbook_rows) {
            foreach ($gbook_rows as $gbook_rowkey=>$gbook_rowitem) {
                $project_row = $M_projects->field('title')->find($gbook_rowitem['projectid']);
                $gbook_rows[$gbook_rowkey]['projectname'] = $project_row['title'];
                
                $user_row = $M_user_detail->where(array('userid'=>$gbook_rowitem['userid']))->field('team,truename')->find();
                $gbook_rows[$gbook_rowkey]['team'] = $user_row['team'];
                $gbook_rows[$gbook_rowkey]['truename'] = $user_row['truename'];
            }
        }
        
        $this->gbook_rows = $gbook_rows;
        
        $this->display();
    }
    
    /*
     * 删除留言
     * @param $gbookid int 留言ID
     */
    public function del($gbookid) {
        $gbookid = intval($gbookid);
    
        $M_gbook = M('gbook');
    
        $gbook_row = $M_gbook->find($gbookid);
        if(!$gbook_row) {
            $this->error('该条记录不存在，可能已被删除！');
        }
    
        $deled_row = $M_gbook->delete($gbookid);
        if($deled_row) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }
}