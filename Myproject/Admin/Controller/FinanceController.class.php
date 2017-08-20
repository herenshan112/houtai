<?php
namespace Admin\Controller;
use Think\Controller;
class FinanceController extends AdminAuthController {
    /**
     * 销售报表
     */
    public function selllists() {

        $p_status = I('get.status', '0', 'intval');
        
        $p_ordernum = I('get.ordernum');
        
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        // 筛选条件
        $map = array();
        // 订单号
        if ($p_ordernum) {
            $map['ordernum'] = array('like', "%{$p_ordernum}%");
        } else {
            $map['ordernum'] = array('like', "%%");
        }
        
        // 状态
        if ($p_status) {
            if ($p_status=='2') {
                $map['orderstatus'] = array('eq', $p_status);
            } else {
                $map['orderstatus'] = array('egt', $p_status);
            }
        } else {
            $map['orderstatus'] = array('gt', '1');
        }
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
            
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        // 未删除
        $map['deled'] = array('eq', '0');
        
        $M_orders = M('orders');
        
        $count = $M_orders->where($map)->count();
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
        
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        $M_user = M('user');
        $M_shipper = M('shipper');
        
        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
        
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
        
            $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
        }
        
        $this->orders_list = $orders_list;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 产品统计
     */
    public function productlists() {
        $M_orders = M('orders');
        $M_products = M('products');
        $M_user = M('user');
        
        // 产品编号
        $p_productid = I('get.productid', '0', 'intval');
        
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        $map = array();
        
        $map['orderstatus'] = array('gt', 1);
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        $map['deled'] = array('eq', '0');
        
        $join_str = '__ORDERS_BUY__ ON __ORDERS_BUY__.ordernum=__ORDERS__.ordernum';
        if ($p_productid) {
            $join_str .= ' AND __ORDERS_BUY__.productid='.$p_productid;
        }
        
        $count = $M_orders->join($join_str, 'RIGHT')->where($map)->order('time_end DESC')->count();
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $orders_list = $M_orders->join($join_str, 'RIGHT')->where($map)->order('time_end DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        
        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('phone,nickname')->find($orders_v['userid']);
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
        }
        $this->orders_list = $orders_list;
        
        // 所有产品
        
        
        $product_rows = $M_products->field('id,title')->order('showturn DESC')->select();
        
        $this->product_rows = $product_rows;
        $this->page = $show;
        
        /*$M = M();
        // 产品ID
        $p_productid = I('get.productid', '0', 'intval');
        
        
        $sql = 'SELECT ord.*,buy.* FROM  __ORDERS__ AS ord JOIN __ORDERS_BUY__ AS buy ON';
        $sql .= ' buy.ordernum=ord.ordernum';
        if ($p_productid) {
            $sql .= ' AND buy.productid='.$p_productid;
        }
        $sql .= ' AND ord.orderstatus > 2';
        $sql .= ' ORDER BY ord.time_end DESC';
        
        $orders_list = $M->query($sql);
        
        
        $this->orders_list = $orders_list;
        
        echo $M->getLastSql();*/
        
        /*$p_status = I('get.status', '0', 'intval');
        
        $p_ordernum = I('get.ordernum');
        
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        // 筛选条件
        $map = array();
        
        // 状态
        if ($p_status) {
            if ($p_status=='2') {
                $map['orderstatus'] = array('eq', $p_status);
            } else {
                $map['orderstatus'] = array('egt', $p_status);
            }
        } else {
            $map['orderstatus'] = array('gt', '1');
        }
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        // 未删除
        $map['deled'] = array('eq', '0');
        
        
        
        $M_orders = M('orders');
        
        $count = $M_orders->where($map)->count();
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
        
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        $M_user = M('user');
        $M_shipper = M('shipper');
        
        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
        
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
        
            $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
        }
        
        
        $this->orders_list = $orders_list;
        $this->page = $show;*/
        
        $this->display();
    }
    
    /**
     * 支付统计
     */
    public function paylists() {
        // 支付方式
        $p_paytype = I('get.paytype', '0', 'intval');
        
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        $map = array();
        

        if ($p_paytype) {
            $p_paytype -= 1;
            $map['paytype'] = array('eq', $p_paytype);
        }
        
        $map['orderstatus'] = array('gt', 1);
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        // 未删除
        $map['deled'] = array('eq', '0');
        
        $M_orders = M('orders');
        
        $count = $M_orders->where($map)->count();
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
        
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        $M_user = M('user');
        
        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
        
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
        
            $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
        }
        
        $this->orders_list = $orders_list;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 
     */
    public function reclists() {
        $M_sale_agent = M('sale_agent');
        $M_salesman = M('salesman');
        $M_user = M('user');
        
        // 条件
        $p_zone = I('get.zone', '0', 'intval');
        $p_agent = I('get.agent', '0', 'intval');
        $p_subagent = I('get.subagent', '0', 'intval');
        
        $p_typeid = I('get.typeid', '0', 'intval');
        
        $salesman_id_arr = NULL;
        $sale_map = array();

        $agent_rows_str = '';
        if ($p_subagent) {
            // 查询二级
            $agent_rows_str = $p_subagent;
            
            $subagent_row = $M_sale_agent->find($p_subagent);
            // 赋值代理
            $agent_row = $M_sale_agent->where('id='.$subagent_row['pid'])->find();
            $agent_rows = $M_sale_agent->where('pid='.$agent_row['pid'])->select();
            
            $this->agent_rows = $agent_rows;
            
            // 赋值二级
            $subagent_rows = $M_sale_agent->where('pid='.$agent_row['id'])->select();
            
            $this->subagent_rows = $subagent_rows;
        } else {
            if ($p_agent) {
                // 查询代理
                $agent_row = $M_sale_agent->find($p_agent);
                $agent_rows = $M_sale_agent->where('pid='.$agent_row['id'])->field('id')->select();
                foreach ($agent_rows as $k=>$v) {
                    $agent_rows_str .= $v['id'] . ',';
                }
                
                // 赋值代理
                $zone_rows = $M_sale_agent->where('pid='.$agent_row['pid'])->select();
                
                $this->agent_rows = $zone_rows;
                
            } else {
                if ($p_zone) {
                    // 查询大区
                    $zone_row = $M_sale_agent->find($p_zone);
                    $zone_rows = $M_sale_agent->where('pid='.$zone_row['id'])->select();
                    
                    foreach ($zone_rows as $kk=>$vv) {
                        $agent_rows = $M_sale_agent->where('pid='.$vv['id'])->field('id')->select();
                        foreach ($agent_rows as $k=>$v) {
                            $agent_rows_str .= $v['id'] . ',';
                        }
                    }
                } else {
                    // 全部
                }
            }
        }
        if ($agent_rows_str) {
            $agent_rows_str = trim($agent_rows_str, ',');
            $sale_map['cateid'] = array('IN', $agent_rows_str);
        }
        $salesman_id_arr = $M_salesman->where($sale_map)->field('id')->select();
        
        // 业务员ID
        $salesman_id_str = '';
        foreach ($salesman_id_arr as $kkk=>$vvv) {
            $salesman_id_str .= $vvv['id'].',';
            
        }
        $salesman_id_str = trim($salesman_id_str, ',');


        $map = array();
        
        // 推荐类型
        if ($p_typeid) {
            $map['typeid'] = array('eq', $p_typeid);
        }
        
        $map['cateid'] = array('eq', '3');
        if ($salesman_id_str) {
            $map['opid'] = array('IN', $salesman_id_str);
        }
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['addtime'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['addtime'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['addtime'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        $zone_rows = $M_sale_agent->where(array('typeid' => '1', 'pid' => '0'))->field('id,title,code')->select();
        
        // 查询推荐信息
        $M_recommend = M('recommend');
        
        $count = $M_recommend->where($map)->count();
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $rec_rows = $M_recommend->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
        
        foreach ($rec_rows as $rec_k=>$rec_v) {
            // 查询被推荐人
            $rec_rows[$rec_k]['user'] = $M_user->where('id='.$rec_v['uid'])->field('id, cateid, nickname,phone')->find();
            
            $tmp_salesman = $M_salesman->where('id='.$rec_v['opid'])->field('id, cateid, nickname')->find();
            $rec_rows[$rec_k]['salesman'] = $tmp_salesman;
            
            $subagent = $M_sale_agent->find($tmp_salesman['cateid']);
            
            $agent = $M_sale_agent->find($subagent['pid']);
            
            $zone = $M_sale_agent->find($agent['pid']);
            
            $rec_rows[$rec_k]['zone'] = $zone['title'];
            $rec_rows[$rec_k]['agent'] = $agent['title'];
            $rec_rows[$rec_k]['subagent'] = $subagent['title'];
        }
        
        $this->rec_rows = $rec_rows;
        $this->zone_rows = $zone_rows;
        
        $this->typeid = $p_typeid;
        
        $this->zoneid = $p_zone;
        $this->agentid = $p_agent;
        $this->subagentid = $p_subagent;
        
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 订单状态文本转换
     */
    private function trStatus($stat=0, $commented=0) {
        if (!$stat) return '';
    
        if ($stat == 1) {
            return '待支付';
        } else if ($stat == 2) {
            return '待发货';
        } else if ($stat == 3) {
            return '已发货';
        }  else if ($stat == 4) {
            return '已发货';
        }
    }
    
    /**
     * 销售报表导出
     */
    public function exportsell() {
        $p_ordernum = I('get.ordernum');
        $p_status = I('get.status');
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');


        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));

            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }

        if ($p_ordernum) {
            $map['ms_orders.ordernum'] = array('eq', $p_ordernum);
        }
        
        if ($p_status) {
            $map['orderstatus'] = array('eq', $p_status);
        } else {
            $map['orderstatus'] = array('egt', '2');
        }
        
        $map['deled'] = array('eq', '0');

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
        ->setCellValue('A1', '订单编号')
        ->setCellValue('B1', '经销商编码')
        ->setCellValue('C1', '业务员')
        ->setCellValue('D1', '医生')
        ->setCellValue('E1', '会员姓名')
        ->setCellValue('F1', '会员手机号')
        ->setCellValue('G1', '商品信息')
        ->setCellValue('H1', '订单金额')
        ->setCellValue('I1', '下单时间')
        ->setCellValue('J1', '状态')
        ->setCellValue('K1', '支付方式')
        ->setCellValue('L1', '支付金额')
        ->setCellValue('M1', '支付编号')
        ->setCellValue('N1', '支付时间')
        ->setCellValue('O1', '发货时间')
        ->setCellValue('P1', '收货人')
        ->setCellValue('Q1', '收货电话')
        ->setCellValue('R1', '收货地址');


        // 筛选用户信息
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $order_rows = $M_orders->join("__USER__ ON __USER__.id=__ORDERS__.userid")->join("__ORDERS_DETAIL__ ON __ORDERS_DETAIL__.ordernum=__ORDERS__.ordernum")->where($map)->order('time_end DESC')->field('ms_orders.*,ms_user.phone,ms_user.nickname,ms_orders_detail.uname,ms_orders_detail.uphone,ms_orders_detail.uaddr')->select();
        
        $M_salesman = M('salesman');
        $M_user = M('user');
        $M_recommend = M('recommend');
        
        //var_dump($order_rows);exit;

        $i = 1;
        $status_arr = array('', '', '待发货', '已发货', '已发货');
        $paytype_arr = array('积分兑换', '微信');

        foreach ($order_rows as $order_k=>$order_v) {
            $i++;
            
            // 业务员信息
            $diynum = '';
            $salename = '';
            $docname = '';
            
            $rec_row = $M_recommend->where(array('uid'=>$order_v['userid']))->field('cateid,opid')->find();
            if ($rec_row) {
                if ($rec_row['cateid']==2) {
                    // 医生
                    $doc_row = $M_user->where(array('id'=>$rec_row['opid']))->field('id,nickname')->find();
                    $docname = $doc_row['nickname'];
                    
                    // 业务员
                    $rec_row2 = $M_recommend->where(array('uid'=>$doc_row['id']))->field('opid')->find();
                    if ($rec_row2) {
                        $sale_row = $M_salesman->where(array('id'=>$rec_row2['opid']))->field('diynum,nickname')->find();
                        if ($sale_row) {
                            $diynum = $sale_row['diynum'];
                            $salename = $sale_row['nickname'];
                        }
                    }
                } else if ($rec_row['cateid']==3) {
                    // 业务员
                    $sale_row = $M_salesman->where(array('id'=>$rec_row['opid']))->field('diynum,nickname')->find();
                    if ($sale_row) {
                        $diynum = $sale_row['diynum'];
                        $salename = $sale_row['nickname'];
                    }
                }
            }

            // 商品
            $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();

            $temp_buys = "";
            foreach ($buy_rows as $k=>$v) {
                if ($k) $temp_buys .= "\r\n";
                $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】【单价：'.$v['price'].'】 【金额：'.bcmul($v['num'],$v['price'],2).'】';
            }

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('B'.$i, $diynum, \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C'.$i, $salename)
            ->setCellValue('D'.$i, $docname)
            ->setCellValue('E'.$i, $order_v['nickname'])
            ->setCellValueExplicit('F'.$i, $order_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('G'.$i, $temp_buys)
            ->setCellValue('H'.$i, $order_v['money'])
            ->setCellValue('I'.$i, $order_v['addtime'])
            ->setCellValue('J'.$i, $status_arr[$order_v['orderstatus']])
            ->setCellValue('K'.$i, $paytype_arr[$order_v['paytype']])
            ->setCellValue('L'.$i, bcdiv($order_v['total_fee'], 100, 2))
            ->setCellValueExplicit('M'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('N'.$i, $order_v['time_end'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('O'.$i, $order_v['fhtime'])
            ->setCellValue('P'.$i, $order_v['uname'])
            ->setCellValueExplicit('Q'.$i, $order_v['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('R'.$i, $order_v['uaddr']);
        }

        //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('订单统计');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
         $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="订单统计'.date('Y-m-d_H_i_s').rand(1000, 9999).'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save( 'php://output');
    }
    
    /**
     * 销售报表导出
     */
    public function exportproduct() {
        $M_orders = M('orders');
        $M_products = M('products');
        $M_user = M('user');
        
        // 产品编号
        $p_productid = I('get.productid', '0', 'intval');
        
        // 起止时间
        $p_starttime = I('get.starttime');
        $p_endtime = I('get.endtime');
        
        $map = array();
        
        $map['orderstatus'] = array('gt', 1);
        
        if ($p_starttime) {
            $p_starttime = date('Ymd000000', strtotime($p_starttime));
        
            if ($p_endtime) {
                $p_endtime = date('Ymd000000', strtotime($p_endtime));
                $map['time_end'] = array('between', array($p_starttime, $p_endtime));
            } else {
                $map['time_end'] = array('egt', $p_starttime);
            }
        } else {
            if ($p_endtime) {
                $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
            }
        }
        
        $map['deled'] = array('eq', '0');
        
        $join_str = '__ORDERS_BUY__ ON __ORDERS_BUY__.ordernum=__ORDERS__.ordernum';
        if ($p_productid) {
            $join_str .= ' AND __ORDERS_BUY__.productid='.$p_productid;
        }
    
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
        ->setCellValue('A1', '订单编号')
        ->setCellValue('B1', '经销商编码')
        ->setCellValue('C1', '业务员')
        ->setCellValue('D1', '医生')
        ->setCellValue('E1', '会员姓名')
        ->setCellValue('F1', '会员手机号')
        ->setCellValue('G1', '产品名称')
        ->setCellValue('H1', '数量')
        ->setCellValue('I1', '单价')
        ->setCellValue('J1', '金额')
        ->setCellValue('K1', '订单金额')
        ->setCellValue('L1', '下单时间')
        ->setCellValue('M1', '状态')
        ->setCellValue('N1', '支付方式')
        ->setCellValue('O1', '支付金额')
        ->setCellValue('P1', '支付编号')
        ->setCellValue('Q1', '支付时间')
        ->setCellValue('R1', '发货时间')
        ->setCellValue('S1', '收货人')
        ->setCellValue('T1', '收货电话')
        ->setCellValue('U1', '收货地址');
    
        
        // 筛选用户信息
        $M_orders_buy = M('orders_buy');
        $order_rows = $M_orders->join($join_str, 'RIGHT')->join("__USER__ ON __USER__.id=__ORDERS__.userid")->join("__ORDERS_DETAIL__ ON __ORDERS_DETAIL__.ordernum=__ORDERS__.ordernum")->where($map)->order('time_end DESC')->select();
        //echo $M_orders->getLastSql();exit;
        
        $M_salesman = M('salesman');
        $M_recommend = M('recommend');
        //var_dump($order_rows);exit;
    
        $i = 1;
        $status_arr = array('', '', '待发货', '已发货', '已发货');
        $paytype_arr = array('积分兑换', '微信');
    
        foreach ($order_rows as $order_k=>$order_v) {
            $i++;
    
            // 业务员信息
            $diynum = '';
            $salename = '';
            $docname = '';
    
            $rec_row = $M_recommend->where(array('uid'=>$order_v['userid']))->field('cateid,opid')->find();
            if ($rec_row) {
                if ($rec_row['cateid']==2) {
                    // 医生
                    $doc_row = $M_user->where(array('id'=>$rec_row['opid']))->field('id,nickname')->find();
                    $docname = $doc_row['nickname'];
    
                    // 业务员
                    $rec_row2 = $M_recommend->where(array('uid'=>$doc_row['id']))->field('opid')->find();
                    if ($rec_row2) {
                        $sale_row = $M_salesman->where(array('id'=>$rec_row2['opid']))->field('diynum,nickname')->find();
                        if ($sale_row) {
                            $diynum = $sale_row['diynum'];
                            $salename = $sale_row['nickname'];
                        }
                    }
                } else if ($rec_row['cateid']==3) {
                    // 业务员
                    $sale_row = $M_salesman->where(array('id'=>$rec_row['opid']))->field('diynum,nickname')->find();
                    if ($sale_row) {
                        $diynum = $sale_row['diynum'];
                        $salename = $sale_row['nickname'];
                    }
                }
            }
    
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('B'.$i, $diynum, \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('C'.$i, $salename)
            ->setCellValue('D'.$i, $docname)
            ->setCellValue('E'.$i, $order_v['nickname'])
            ->setCellValueExplicit('F'.$i, $order_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('G'.$i, $order_v['title'])
            ->setCellValue('H'.$i, $order_v['num'])
            ->setCellValue('I'.$i, $order_v['price'])
            ->setCellValue('J'.$i, bcmul($order_v['num'],$order_v['price'],2))
            ->setCellValue('K'.$i, $order_v['money'])
            ->setCellValue('L'.$i, $order_v['addtime'])
            ->setCellValue('M'.$i, $status_arr[$order_v['orderstatus']])
            ->setCellValue('N'.$i, $paytype_arr[$order_v['paytype']])
            ->setCellValue('O'.$i, bcdiv($order_v['total_fee'], 100, 2))
            ->setCellValueExplicit('P'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit('Q'.$i, $order_v['time_end'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('R'.$i, $order_v['fhtime'])
            ->setCellValue('S'.$i, $order_v['uname'])
            ->setCellValueExplicit('T'.$i, $order_v['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('U'.$i, $order_v['uaddr']);
        }
    
        //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
    
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('产品统计');
    
    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
    
        /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
         $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="产品统计'.date('Y-m-d_H_i_s').rand(1000, 9999).'.xlsx"');
        header('Cache-Control: max-age=0');
    
        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save( 'php://output');
    }
    
    /**
     * 销售报表导出
     */
    public function export() {
        if (IS_POST) {
            $p_status = I('post.status');
            $p_starttime = I('post.starttime');
            $p_endtime = I('post.endtime');
            
            
            if ($p_starttime) {
                $p_starttime = date('Ymd000000', strtotime($p_starttime));
            
                if ($p_endtime) {
                    $p_endtime = date('Ymd000000', strtotime($p_endtime));
                    $map['time_end'] = array('between', array($p_starttime, $p_endtime));
                } else {
                    $map['time_end'] = array('egt', $p_starttime);
                }
            } else {
                if ($p_endtime) {
                    $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
                }
            }
            
            
            if ($p_status) {
                $map['orderstatus'] = array('eq', $p_status);
            } else {
                $map['orderstatus'] = array('egt', '2');
            }
            
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
            ->setCellValue('A1', '订单编号')
            ->setCellValue('B1', '会员姓名')
            ->setCellValue('C1', '会员手机号')
            ->setCellValue('D1', '商品信息')
            ->setCellValue('E1', '订单金额')
            ->setCellValue('F1', '下单时间')
            ->setCellValue('G1', '状态')
            ->setCellValue('H1', '支付方式')
            ->setCellValue('I1', '支付金额')
            ->setCellValue('J1', '支付编号')
            ->setCellValue('K1', '支付时间')
            ->setCellValue('L1', '发货时间')
            ->setCellValue('M1', '收货人')
            ->setCellValue('N1', '收货电话')
            ->setCellValue('O1', '收货地址');
            
            
            // 筛选用户信息
            $M_orders = M('orders');
            $M_orders_buy = M('orders_buy');
            $order_rows = $M_orders->join("__USER__ ON __USER__.id=__ORDERS__.userid")->join("__ORDERS_DETAIL__ ON __ORDERS_DETAIL__.ordernum=__ORDERS__.ordernum")->where($map)->order('time_end DESC')->field('ms_orders.*,ms_user.phone,ms_user.nickname,ms_orders_detail.uname,ms_orders_detail.uphone,ms_orders_detail.uaddr')->select();
            
            //var_dump($order_rows);exit;
            
            $i = 1;
            $status_arr = array('', '', '待发货', '已发货', '已发货');
            $paytype_arr = array('积分兑换', '微信');
            
            foreach ($order_rows as $order_k=>$order_v) {
                $i++;
            
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
            
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $order_v['nickname'])
                ->setCellValueExplicit('C'.$i, $order_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, $order_v['addtime'])
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, $order_v['time_end'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['fhtime'])
                ->setCellValue('M'.$i, $order_v['uname'])
                ->setCellValueExplicit('N'.$i, $order_v['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $order_v['uaddr']);
            }
            
            //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
            //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
            //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('订单信息导出');
            
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
             $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="销售报表'.date('Y-m-d_H_i_s').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
        } else {
            $this->display();
        }
    }
    
    /**
     * 支付报表导出
     */
    public function export1() {
        if (IS_POST) {
            $p_status = I('post.status');
            $p_starttime = I('post.starttime');
            $p_endtime = I('post.endtime');
            
            
            if ($p_starttime) {
                $p_starttime = date('Ymd000000', strtotime($p_starttime));
            
                if ($p_endtime) {
                    $p_endtime = date('Ymd000000', strtotime($p_endtime));
                    $map['time_end'] = array('between', array($p_starttime, $p_endtime));
                } else {
                    $map['time_end'] = array('egt', $p_starttime);
                }
            } else {
                if ($p_endtime) {
                    $map['time_end'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
                }
            }
            
            
            $map['orderstatus'] = array('egt', '2');
            
            if ($p_status=='2') {
				// 微信
                $map['paytype'] = array('eq', '1');
            } else if ($p_status=='3') {
				// 积分
                $map['paytype'] = array('eq', '0');
            }
            
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
            ->setCellValue('A1', '订单编号')
            ->setCellValue('B1', '会员姓名')
            ->setCellValue('C1', '会员手机号')
            ->setCellValue('D1', '商品信息')
            ->setCellValue('E1', '订单金额')
            ->setCellValue('F1', '下单时间')
            ->setCellValue('G1', '状态')
            ->setCellValue('H1', '支付方式')
            ->setCellValue('I1', '支付金额')
            ->setCellValue('J1', '支付编号')
            ->setCellValue('K1', '支付时间')
            ->setCellValue('L1', '发货时间')
            ->setCellValue('M1', '收货人')
            ->setCellValue('N1', '收货电话')
            ->setCellValue('O1', '收货地址');
            
            
            // 筛选用户信息
            $M_orders = M('orders');
            $M_orders_buy = M('orders_buy');
            $order_rows = $M_orders->join("__USER__ ON __USER__.id=__ORDERS__.userid")->join("__ORDERS_DETAIL__ ON __ORDERS_DETAIL__.ordernum=__ORDERS__.ordernum")->where($map)->order('time_end DESC')->field('ms_orders.*,ms_user.phone,ms_user.nickname,ms_orders_detail.uname,ms_orders_detail.uphone,ms_orders_detail.uaddr')->select();
            
            //var_dump($order_rows);exit;
            
            $i = 1;
            $status_arr = array('', '', '待发货', '已发货', '已发货');
            $paytype_arr = array('积分兑换', '微信');
            
            foreach ($order_rows as $order_k=>$order_v) {
                $i++;
            
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
            
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $order_v['nickname'])
                ->setCellValueExplicit('C'.$i, $order_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, $order_v['addtime'])
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, $order_v['time_end'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['fhtime'])
                ->setCellValue('M'.$i, $order_v['uname'])
                ->setCellValueExplicit('N'.$i, $order_v['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $order_v['uaddr']);
            }
            
            //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
            //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
            //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('支付信息导出');
            
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
             $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="支付导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
        } else {
            $this->display();
        }
    }
    
    /**
     * 推荐报表导出
     */
    public function export2() {
        $M_sale_agent = M('sale_agent');
        $M_salesman = M('salesman');
        $M_user = M('user');
        
        if (IS_POST) {
            $p_status = I('post.status');
            $p_starttime = I('post.starttime');
            $p_endtime = I('post.endtime');
            
            // 条件
            $p_zone = I('post.zone', '0', 'intval');
            $p_agent = I('post.agent', '0', 'intval');
            $p_subagent = I('post.subagent', '0', 'intval');
            
            $salesman_id_arr = NULL;
            $sale_map = array();
            
            $agent_rows_str = '';
            if ($p_subagent) {
                // 查询二级
                $agent_rows_str = $p_subagent;
            
                $subagent_row = $M_sale_agent->find($p_subagent);
                // 赋值代理
                $agent_row = $M_sale_agent->where('id='.$subagent_row['pid'])->find();
                $agent_rows = $M_sale_agent->where('pid='.$agent_row['pid'])->select();
            
                $this->agent_rows = $agent_rows;
            
                // 赋值二级
                $subagent_rows = $M_sale_agent->where('pid='.$agent_row['id'])->select();
            
                $this->subagent_rows = $subagent_rows;
            } else {
                if ($p_agent) {
                    // 查询代理
                    $agent_row = $M_sale_agent->find($p_agent);
                    $agent_rows = $M_sale_agent->where('pid='.$agent_row['id'])->field('id')->select();
                    foreach ($agent_rows as $k=>$v) {
                        $agent_rows_str .= $v['id'] . ',';
                    }
            
                    // 赋值代理
                    $zone_rows = $M_sale_agent->where('pid='.$agent_row['pid'])->select();
            
                    $this->agent_rows = $zone_rows;
            
                } else {
                    if ($p_zone) {
                        // 查询大区
                        $zone_row = $M_sale_agent->find($p_zone);
                        $zone_rows = $M_sale_agent->where('pid='.$zone_row['id'])->select();
            
                        foreach ($zone_rows as $kk=>$vv) {
                            $agent_rows = $M_sale_agent->where('pid='.$vv['id'])->field('id')->select();
                            foreach ($agent_rows as $k=>$v) {
                                $agent_rows_str .= $v['id'] . ',';
                            }
                        }
                    } else {
                        // 全部
                    }
                }
            }
            if ($agent_rows_str) {
                $agent_rows_str = trim($agent_rows_str, ',');
                $sale_map['cateid'] = array('IN', $agent_rows_str);
            }
            $salesman_id_arr = $M_salesman->where($sale_map)->field('id')->select();
            
            // 业务员ID
            $salesman_id_str = '';
            foreach ($salesman_id_arr as $kkk=>$vvv) {
                $salesman_id_str .= $vvv['id'].',';
            
            }
            $salesman_id_str = trim($salesman_id_str, ',');
            
            $map = array();
            
            // 推荐类型
            if ($p_status) {
                $map['typeid'] = array('eq', $p_status);
            }
            
            $map['cateid'] = array('eq', '3');
            if ($salesman_id_str) {
                $map['opid'] = array('IN', $salesman_id_str);
            }

            // 起止时间
            $p_starttime = I('get.starttime');
            $p_endtime = I('get.endtime');
            
            if ($p_starttime) {
                $p_starttime = date('Ymd000000', strtotime($p_starttime));
            
                if ($p_endtime) {
                    $p_endtime = date('Ymd000000', strtotime($p_endtime));
                    $map['addtime'] = array('between', array($p_starttime, $p_endtime));
                } else {
                    $map['addtime'] = array('egt', $p_starttime);
                }
            } else {
                if ($p_endtime) {
                    $map['addtime'] = array('elt', date('Ymd000000', strtotime($p_starttime)));
                }
            }
            
            
            // 开始导出
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
            ->setCellValue('A1', '大区')
            ->setCellValue('B1', '代理商')
            ->setCellValue('C1', '二级分销')
            ->setCellValue('D1', '业务员')
            ->setCellValue('E1', '推荐类型')
            ->setCellValue('F1', '姓名')
            ->setCellValue('G1', '手机号')
            ->setCellValue('H1', '推荐时间');
            
            // 查询推荐信息
            $M_recommend = M('recommend');
            // 筛选用户信息
            $rec_rows = $M_recommend->where($map)->order('addtime DESC')->select();
            
            $i = 1;
            $type_arr = array('', '患者', '医生');
            
            foreach ($rec_rows as $rec_k=>$rec_v) {
                $i++;
                
                // 查询被推荐人
                $tmp_user = $M_user->where('id='.$rec_v['uid'])->field('id, cateid, nickname,phone')->find();
                
                $tmp_salesman = $M_salesman->where('id='.$rec_v['opid'])->field('id, cateid, nickname')->find();
                
                $subagent = $M_sale_agent->field('title')->find($tmp_salesman['cateid']);
                $agent = $M_sale_agent->field('title')->find($subagent['pid']);
                $zone = $M_sale_agent->field('title')->find($agent['pid']);
                
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $zone['title'])
                ->setCellValue('B'.$i, $agent['title'])
                ->setCellValue('C'.$i, $subagent['title'])
                ->setCellValue('D'.$i, $tmp_salesman['nickname'])
                ->setCellValue('E'.$i, $type_arr[$tmp_user['cateid']])
                ->setCellValue('F'.$i, $tmp_user['nickname'])
                ->setCellValueExplicit('G'.$i, $tmp_user['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('H'.$i, $rec_v['addtime']);
                
            }
            //var_dump($order_rows);exit;
            
            //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
            //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
            //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
            
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('推荐信息导出');
            
            
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            
            /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
             $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="推荐导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
        } else {
            
            $zone_rows = $M_sale_agent->where(array('typeid' => '1', 'pid' => '0'))->field('id,title,code')->select();
            
			$this->zone_rows = $zone_rows;

            $this->display();
        }
    }
}