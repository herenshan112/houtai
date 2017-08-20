<?php
namespace Admin\Controller;
use Think\Controller;
class ShipperController extends AdminAuthController {
    /**
     * 物流信息
     */
    public function showlists() {
        $M_shipper = M("shipper");
        
        $shipper_list = $M_shipper->order('showorder DESC')->select();
        
        $this->shipper_list = $shipper_list;
        
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add() {
        if(IS_POST){
            $M_shipper = M("shipper");
            
            $data = I("post.");
            
            $added = $M_shipper->add($data);
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
        $M_shipper = M("shipper");
        
        if(IS_POST){
            $data = I("post.");
            $moded = $M_shipper->save($data);
            if($moded){
                $this -> success("修改成功", U("showlists"));
            }else{
                $this -> error("修改失败");
            }
        } else {
            $id = I("get.id");
            	
            $data = $M_shipper -> where(array("id"=>$id))->find();
            
            $this->data = $data;
            	
            $this->display();
        }
    }
    
    /**
     * 删除
     */
    public function del() {
        $M_shipper = M("shipper");
        
        $id = I("get.id");
        
        $del_row = $M_shipper->where(array('id'=>$id))->delete();
        if ($del_row) {
            $this->success("删除成功", U("showlists"));
        } else {
            $this->error("删除失败");
        }
    }
}