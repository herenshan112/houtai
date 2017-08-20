<?php
namespace Admin\Controller;
use Think\Controller;
class SalesmanController extends AdminAuthController {
    /**
     * 业务员列表
     */
    public function lists() {
        $M_salesman = M('salesman');
        
        $where['phone'] = array('LIKE','%%');
        $p_user = I('get.user');
        
        if($p_user) {
            $user_where['phone'] = array('like', "%$p_user%");
        }
        
        $count = $M_salesman->where($user_where)->count();
        
        $Page = new \Think\Page($count, 15);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        
        $salesman = $M_salesman->where($user_where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $M_sale_agent = M('sale_agent');
        foreach ($salesman as $k=>$v) {
            $subagent = $M_sale_agent->find($v['cateid']);
            $salesman[$k]['subagent'] = $subagent['title'];
            
            $agent = $M_sale_agent->find($subagent['pid']);
            $salesman[$k]['agent'] = $agent['title'];
            
            $salesman[$k]['zone'] = $M_sale_agent->where('id='.$agent['pid'])->getField('title');
        }
        
        $this->lists = $salesman;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 添加业务员
     */
    public function add() {
        if (IS_POST) {
            $p_subagent = I('post.subagent');
            $p_phone = I('post.phone');
            $p_nickname = I('post.nickname');
            $p_idno = I('post.idno');
            $p_city = I('post.city');
            $p_jobs = I('post.jobs');
            
            $M_salesman = M('salesman');
            
            $salesman_data = array(
                'cateid' => $p_subagent,
                'phone' => $p_phone,
                'nickname' => $p_nickname,
                'idno' => $p_idno,
                'city' => $p_city,
                'jobs' => $p_jobs,
                'addtime' => date('Y-m-d H:i:s')
            );
            
            $exists = $M_salesman->where('phone='.$p_phone)->count();
            if($exists) {
                $this->error("该手机号已存在,请更换手机号重试！");
            }
            
            $userid = $M_salesman->add($salesman_data);
            if($userid) {
                $this->success("添加成功" , U('lists'));
            } else {
                $this->error("添加失败");
            }
        } else {
            
            $M_sale_agent = M('sale_agent');
            
            $zone_rows = $M_sale_agent->where(array('typeid' => '1', 'pid' => '0'))->field('id,title,code')->select();
            
            $this->zone_rows = $zone_rows;
            
            $this->display();
        }
    }
    
    /**
     * 业务员详情
     */
    public function detail($id=0) {
        $M_salesman = M('salesman');
        $salesman = $M_salesman->find($id);
    
        $M_corn = M('corn');
        $salesman['corn'] = $M_corn->where('cateid=3 AND uid='.$id)->getField('corn');
        
        $this->user = $salesman;
        $this->display();
    }
    
    /**
     * 修改信息
     */
    public function mod() {
        $M_salesman = M('salesman');
        if(IS_POST){
            $data = I("post.");
            
            $p_subagent = I('post.subagent');
            
            $data['cateid'] = $p_subagent;
            
            $moded = $M_salesman->save($data);
            if($moded){
                $this->success("修改成功", U("lists"));
            }else{
                $this->error("修改失败");
            }
        } else {
            $id = I("get.id");
            	
            $salesman = $M_salesman->find($id);
            
            /*
            // 级别
            $M_sale_agent = M('sale_agent');
            // 二级分销
            $subagent_row = $M_sale_agent->find($salesman['cateid']);
            // 大区
            $agent_row = $M_sale_agent->find($subagent_row['pid']);
            
            
            // 二级分销商列表
            $subagent_rows = $M_sale_agent->where('pid='.$subagent_row['pid'])->select();
            
            // 代理列表
            $agent_rows = $M_sale_agent->where('pid='.$agent_row['pid'])->select();
            
            // 大区列表
            $zone_rows = $M_sale_agent->where('pid=0')->select();
            
            
            $this->subagent = $subagent_row;
            $this->agent = $agent_row;
            
            $this->subagent_rows = $subagent_rows;
            $this->agent_rows = $agent_rows;
            $this->zone_rows = $zone_rows;
            */
            
            $this->user = $salesman;
            	
            $this->display();
        }
    }
    
    /**
     * 大区列表
     */
    public function zonelists() {
        $M_sale_agent = M('sale_agent');
        
        $sale_agents = $M_sale_agent->where(array('pid'=>0))->order('id DESC')->select();
        
        foreach ($sale_agents as $k=>$v) {
            $agent_rows = $M_sale_agent->where(array('pid'=>$v['id']))->select();
            foreach ($agent_rows as $kk=>$vv) {
                $agent_rows[$kk]['subagent'] = $M_sale_agent->where(array('pid'=>$vv['id']))->select();
            }
            
            $sale_agents[$k]['agent'] = $agent_rows;
        }
        
        $this->lists = $sale_agents;
        
        $this->display();
    }
    
    /**
     * 修改大区信息
     */
    public function zonemod() {
        $M_sale_agent = M('sale_agent');
        
        if(IS_POST){
            $data = I("post.");
    
            $moded = $M_sale_agent->save($data);
            if($moded){
                $this->success("修改成功", U("zonelists"));
            }else{
                $this->error("修改失败");
            }
        } else {
            $id = I("get.id");
             
            $sale_agent = $M_sale_agent->find($id);
    
            $this->sale_agent = $sale_agent;
             
            $this->display();
        }
    }
    
    /**
     * 添加大区
     */
    public function zoneadd() {
        if (IS_POST) {
            $p_title = I('post.title');
    
            $M_sale_agent = M('sale_agent');
    
            $agent_data = array(
                'pid' => '0',
                'typeid' => '1',
                'title' => $p_title,
            );
            
            $userid = $M_sale_agent->add($agent_data);
            if($userid) {
                $this->success("添加成功" , U('zonelists'));
            } else {
                $this->error("添加失败");
            }
        } else {
            $this->display();
        }
    }
    
    /**
     * 添加二级
     */
    public function agentadd() {
        if (IS_POST) {
            $p_zone = I('post.zone');
            $p_title = I('post.title');
    
            $M_sale_agent = M('sale_agent');
    
            $agent_data = array(
                'pid' => $p_zone,
                'typeid' => '2',
                'title' => $p_title,
            );
    
            $userid = $M_sale_agent->add($agent_data);
            if($userid) {
                $this->success("添加成功" , U('zonelists'));
            } else {
                $this->error("添加失败");
            }
        } else {
            $M_sale_agent = M('sale_agent');
            
            $zone_rows = $M_sale_agent->where('pid=0')->select();
            
            $this->zone_rows = $zone_rows;
            
            $this->display();
        }
    }
    
    /**
     * 添加二级
     */
    public function subagentadd() {
        if (IS_POST) {
            $p_agent = I('post.agent');
            $p_title = I('post.title');
    
            $M_sale_agent = M('sale_agent');
    
            $agent_data = array(
                'pid' => $p_agent,
                'typeid' => '3',
                'title' => $p_title,
            );
    
            $userid = $M_sale_agent->add($agent_data);
            if($userid) {
                $this->success("添加成功" , U('zonelists'));
            } else {
                $this->error("添加失败");
            }
        } else {
            $M_sale_agent = M('sale_agent');
    
            $zone_rows = $M_sale_agent->where('pid=0')->select();
    
            $this->zone_rows = $zone_rows;
    
            $this->display();
        }
    }
    
    /**
     * 删除信息
     */
    public function zonedel() {
        $p_id = I('get.id');

        $M_sale_agent = M('sale_agent');
        $M_salesman = M('salesman');
        
        $agent_row = $M_sale_agent->find($p_id);
        
        $deled = 0;
        
        if ($agent_row['typeid']==1 OR $agent_row['typeid']==2) {
            $existed = $M_sale_agent->where(array('pid'=>$agent_row['id']))->count();
            if ($existed) {
                $this->error("包含子级别代理或二级分销，不允许删除！<br>请先删除子级别代理或二级分销，再操作！");
            }
            
            $deled = $M_sale_agent->delete($p_id);
        } else if ($agent_row['typeid']==3) {
            $existed = $M_salesman->where(array('cateid'=>$agent_row['id']))->count();
            if ($existed) {
                $this->error("该二级分销下存在业务员，不允许删除！<br>请先删除业务员，再操作！");
            }
            
            $deled = $M_sale_agent->delete($p_id);
        }
        
        if($deled) {
            $this->success("添加成功" , U('zonelists'));
        } else {
            $this->error("添加失败");
        }
    }
    
    /**
     * 导出信息
     */
    public function export($typeid=1) {
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
    
        require_once LIB_PATH. 'Org/Util/PHPExcel.class.php';
    
        $objPHPExcel = new \PHPExcel();
    
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("PHPExcel Test Document")
        ->setSubject("PHPExcel Test Document")
        ->setDescription("Test document for PHPExcel, generated using PHP classes.")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Test result file");
    
        // 设置标题
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '经销商编码')
        /*->setCellValue('B1', '大区')
        ->setCellValue('C1', '代理商')
        ->setCellValue('D1', '二级分销')*/
        
        ->setCellValue('B1', '手机号')
        ->setCellValue('C1', '姓名')
        ->setCellValue('D1', '身份证号')
        ->setCellValue('E1', '所在城市')
        ->setCellValue('F1', '所在企业')
        ->setCellValue('G1', '添加时间')
        ->setCellValue('H1', '微信授权');
    
    
        // 筛选用户信息
        $M_salesman = M('salesman');
        $user_rows = $M_salesman->order('addtime DESC')->select();
    
        $M_sale_agent = M('sale_agent');
        $i = 1;
        $wx = '否';
        foreach ($user_rows as $user_k=>$user_v) {
            $i++;
    
            if ($user_v['openid']) {
                $wx = '是';
            }
            
            $subagent = $M_sale_agent->where(array('id'=>$user_v['cateid']))->find();
            $agent = $M_sale_agent->where(array('id'=>$subagent['pid']))->find();
            $zone = $M_sale_agent->where(array('id'=>$agent['pid']))->find();
            
            $objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A'.$i, $user_k+1)
            ->setCellValueExplicit('A'.$i, $user_v['diynum'], \PHPExcel_Cell_DataType::TYPE_STRING)
            /*->setCellValue('B'.$i, $zone['title'])
            ->setCellValue('C'.$i, $agent['title'])
            ->setCellValue('D'.$i, $subagent['title'])*/
            ->setCellValueExplicit('B'.$i, $user_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C'.$i, $user_v['nickname'])
            ->setCellValueExplicit('D'.$i, $user_v['idno'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('E'.$i, $user_v['city'])
            ->setCellValue('F'.$i, $user_v['jobs'])
            ->setCellValue('G'.$i, $user_v['addtime'])
            ->setCellValue('H'.$i, $wx);
        }
    
        //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
    
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('业务员信息导出');
    
    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
    
        /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
         $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
        header('Cache-Control: max-age=0');
    
        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save( 'php://output');
    }
}