<?php
namespace Admin\Controller;
use Think\Controller;
class ProbesController extends AdminAuthController {
    /**
     * 物流信息
     */
    public function showlists() {
        $M_probe = M("probe");
        
        $shipper_list = $M_probe->select();
        
        $this->shipper_list = $shipper_list;
        
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add() {
        if(IS_POST){
            $M_probe = M("probe");
            
            $data = I("post.");
            
            $added = $M_probe->add($data);
            if ($added){
                $this->success("添加成功", U("showlists"));
            } else {
                $this->error("添加失败");
            }
        } else {
            $this->display();
        }
    }
    
    /**
     * 修改
     */
    public function mod() {
        $M_probe = M("probe");
        
        if(IS_POST){
            $data = I("post.");
            $moded = $M_probe->save($data);
            if($moded){
                $this -> success("修改成功", U("showlists"));
            }else{
                $this -> error("修改失败");
            }
        } else {
            $id = I("get.id");
            	
            $data = $M_probe -> where(array("id"=>$id))->find();
            
            $this->data = $data;
            	
            $this->display();
        }
    }
    
    /**
     * 删除
     */
    public function del() {
        $M_probe = M("probe");
        
        $id = I("get.id");
        
        $del_row = $M_probe->where(array('id'=>$id))->delete();
        if ($del_row) {
            $this->success("删除成功", U("showlists"));
        } else {
            $this->error("删除失败");
        }
    }
}