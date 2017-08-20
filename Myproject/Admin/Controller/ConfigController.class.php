<?php
namespace Admin\Controller;
use Think\Controller;
class ConfigController extends AdminAuthController {
    /*
     * 关于我们 地图信息修改
     */
    public function modmap(){
        $M_config = M('config');
        $config_row = $M_config->field('id,posx,posy,postitle,poscontent')->find();
        
        if(IS_POST) {
            $config_row['posx'] = I('post.posx');
            $config_row['posy'] = I('post.posy');
            $config_row['postitle'] = I('post.postitle');
            $config_row['poscontent'] = I('post.poscontent');
            
            $save_row = $M_config->save($config_row);
            if($save_row) {
                $this->success('修改成功！');
            } else {
                $this->error('修改失败！');
            }
        } else {
            $this->map_row = $config_row;
            $this->display();
        }
    }
    
    /*
     * 留言过滤
     */
    public function wordfilter() {
        $M_config = M('config');
        $config_row = $M_config->field('id,filterwords')->find();
        
        if(IS_POST) {
            $config_row['filterwords'] = I('post.filterwords');
            
            $save_row = $M_config->save($config_row);
            if($save_row) {
                $this->success('修改成功！');
            } else {
                $this->error('修改失败！');
            }
        } else {
            $this->filterwords_row = $config_row;
            $this->display();
        }
    }
}