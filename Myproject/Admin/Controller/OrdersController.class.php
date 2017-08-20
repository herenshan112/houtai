<?php
namespace Admin\Controller;
use Think\Controller;
use PublicClass\PagesusAjax;

class OrdersController extends AdminAuthController {
    /*
    *查询订单
     */
    public function lookcont(){
        $p_ordernum = I('post.ordernum');
        if($p_ordernum==''){
            $p_ordernum = I('get.ordernum');
        }

        
        $lookset = I('post.lookset');
        if($lookset == ''){
            $lookset = I('get.lookset', '0', 'intval');
        }

        $p_status = I('get.status', '0', 'intval');

        $starttime = I('post.starttime');
        if($starttime==''){
            $starttime = I('get.starttime');
        }

        $this->p_ordernum=$p_ordernum;
        $this->lookset=$lookset;
        $this->starttime=$starttime;

        if($p_ordernum == '' && $starttime == ''){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("请输入关键字或时间，至少提供一个查询内容！");history.go(-1);</script>';
            return;
        }

        if ($p_status) {
            $map['orderstatus'] = array('eq', $p_status);
        }
        $map['deled'] = array('eq', '0');
        if($lookset == 0){
            $lookset='all';
        }
        switch ($lookset) {
            case 1:
                if($starttime != ''){
                    $bgtime=strtotime($starttime.' 0:0:0');
                    $endtime=strtotime($starttime.' 23:59:59');
                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                }
                $map['title'] = array('like', "%{$p_ordernum}%");
                $M_orders = M('orders');

                $count = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->count();
                $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));
                $orders_list=M('orders')
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->order('time_end DESC')
                        ->select();
                //var_dump($count);
                $M_orders_buy = M('orders_buy');
                $M_orders_detail = M('orders_detail');
                $M_user = M('user');
                $M_shipper = M('shipper');
                
                foreach ($orders_list as $orders_k=>$orders_v) {
                    $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
                    
                    $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented'], $orders_v['fahuofang']);
                    $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
                    
                    $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
                }
                
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $this->orders_list = $orders_list;
                $this->page = $show;
                break;
            case 2:
                if($starttime != ''){
                    $bgtime=strtotime($starttime.' 0:0:0');
                    $endtime=strtotime($starttime.' 23:59:59');
                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                }

                $ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

                
                $map['username'] = array('like', "%{$p_ordernum}%");

                

                $M_orders = M('orders');
                if($ddcxff == 1){

                    $count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            
                            ->count();
                    $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
                    $orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                           
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                }else{
                    $count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            
                            ->count();
                    $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
                    $orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                           
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                }
                //var_dump($count);
                $M_orders_buy = M('orders_buy');
                $M_orders_detail = M('orders_detail');
                $M_user = M('user');
                $M_shipper = M('shipper');
                
                foreach ($orders_list as $orders_k=>$orders_v) {
                    $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
                    
                    $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented'], $orders_v['fahuofang']);
                    $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
                    
                    $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
                }
                
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $this->orders_list = $orders_list;
                $this->page = $show;
                break;
            case 3:
                if($starttime != ''){
                    $bgtime=strtotime($starttime.' 0:0:0');
                    $endtime=strtotime($starttime.' 23:59:59');
                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                }

                $ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

                
                $map['nickname'] = array('like', "%{$p_ordernum}%");

                

                $M_orders = M('orders');
                if($ddcxff == 1){

                    $count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            
                            ->count();
                    $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
                    $orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                           
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                }else{
                    $count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            
                            ->count();
                    $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
                    $orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                           
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                }
                //var_dump($count);
                $M_orders_buy = M('orders_buy');
                $M_orders_detail = M('orders_detail');
                $M_user = M('user');
                $M_shipper = M('shipper');
                
                foreach ($orders_list as $orders_k=>$orders_v) {
                    $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
                    
                    $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented'], $orders_v['fahuofang']);
                    $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
                    
                    $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
                }
                
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $this->orders_list = $orders_list;
                $this->page = $show;
                break;
            default:
            
                $map['ordernum'] = array('like', "%{$p_ordernum}%");
                if($starttime != ''){
                    $bgtime=strtotime($starttime.' 0:0:0');
                    $endtime=strtotime($starttime.' 23:59:59');
                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                }
                $M_orders = M('orders');
        
                $count = $M_orders->where($map)->count();
                $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));
                $orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
                $M_orders_buy = M('orders_buy');
                $M_orders_detail = M('orders_detail');
                $M_user = M('user');
                $M_shipper = M('shipper');
                
                foreach ($orders_list as $orders_k=>$orders_v) {
                    $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
                    
                    $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented'], $orders_v['fahuofang']);
                    $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
                    
                    $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
                }
                
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $this->orders_list = $orders_list;
                $this->page = $show;
                break;
        }

        $this->display('showlists');
    }
    /**
     * 全部订单
     */
    public function showlists() {
        
        $p_status = I('get.status', '0', 'intval');
        
        $p_ordernum = I('get.ordernum');
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
            $map['orderstatus'] = array('eq', $p_status);
        } else {
            //$map['orderstatus'] = array('gt', '1');
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
        
        $orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
        
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        $M_user = M('user');
        $M_shipper = M('shipper');
        
        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
            
            $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
            
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented'], $orders_v['fahuofang']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
            
            $orders_list[$orders_k]['buy'] = $M_orders_buy->where('ordernum=' . $orders_v['ordernum'])->select();
        }
        
        $this->orders_list = $orders_list;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 订单发货 
     */
    public function fahuo($id=0) {
        $M_orders = M('orders');
        $M_user = M('user');
        
        if (IS_POST) {
            $p_id = I('post.id');                                       //订单id
            $p_shipperid = I('post.shipperid');                         //物流id
            $p_shippernum = I('post.shippernum');                       //物流单号

            $fhfset = I('post.fhfset');                                 //发货方
            $fahuofang = I('post.fahuofang');                           //发货方ID

            if($fhfset == 0 && $p_shippernum == ''){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请填写物流单号！");history.go(-1);</script>';
                return;
            }
            if($fhfset != 0 && $fahuofang == 0){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请指派发货经销商！");history.go(-1);</script>';
                return;
            }

            if($fhfset != 0){
                $data['orderstatus']=5;
                $data['fahuofang']=$fahuofang;
                $data['fpsj']=time();

                $lcnr='总部于'.date('Y-m-d H:i:s').'委任'.ordersources($fahuofang).'提供发货服务！';
                $odlc=ordliucheng($p_id,$lcnr);
            }else{
                $data['orderstatus']=3;
                $data['shipperid']=$p_shipperid;
                $data['shippernum']=$p_shippernum;
                $data['fahuofang']=0;
                $data['fhtime']=time();
                $data['fpsj']=time();

                $lcnr='总部于'.date('Y-m-d H:i:s').'提交发货信息！';
                $odlc=ordliucheng($p_id,$lcnr);
            }
            $data['fhsetval']=1;
            $updated = $M_orders->where(array('id'=>$p_id))->save($data);
            if ($updated) {
                $tmp_order = $M_orders->where(array('id'=>$p_id))->field('ordernum,userid')->find();
                $openid = $M_user->where(array('id'=>$tmp_order['userid']))->getField('openid');
                // 发送微信消息提醒
                $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->getWxToken();
                if($fhfset == 0){
                    /*$json_msg = '{"touser":"'.$openid.'","msgtype":"news","news":{"articles": [{"title":"您有一笔订单已发货","description":"您的订单编号为：'.$tmp_order['ordernum'].'的订单已发货。","url":"'.WX_URL.'/index.php/Orders/wuliu/id/'.$p_id.'.html","picurl":""}]}}';
                    $this->curlGet($url, null, true, $json_msg);*/
                    $this->success("发货成功！", U("showlists", array('status'=>'2')));
                }else{
                    $this->success("订单已经委托经销商发货！", U("showlists", array('status'=>'5')));
                }
                
            }else {
                $this->error("发货失败！");
            }
            
        } else {
            if (!$id) $this->error('参数有误！', U('showlists'));
            
            $id = I("get.id");

            $lokset=$M_orders->where(array('id'=>$id))->save(array('lookset'=>1));
            
            $p_status = I('get.status', '0', 'intval');
            
            $M_orders_buy = M('orders_buy');
            $M_orders_detail = M('orders_detail');
            $M_user = M('user');
            
            // 订单信息
            $order_row = $M_orders->find($id);
            
            $order_row['detail'] = $M_orders_detail->where('ordernum=' . $order_row['ordernum'])->find();
            $order_row['buy'] = $M_orders_buy->where('ordernum=' . $order_row['ordernum'])->select();
            
            // 物流信息
            $M_shipper = M('shipper');
            $shipper_rows = $M_shipper->order('showorder DESC')->select();
            
            $fhsdval=fhfjumpys($order_row['code_jxs'],$order_row['fahuofang']);

            $this->fhsdval = $fhsdval;
            $this->order_row = $order_row;
            
            $this->shipper_rows = $shipper_rows;
            
            $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
            $this->assign('prolist',$prolist);


            $this->display();
        }
    }
    
    /**
     * 删除订单
     */
    public function del() {
        $M_orders = M('orders');
        
        $id = I("get.id");
        
        $p_status = I('get.status', '0', 'intval');
        
        $del_row = $M_orders->where(array('id'=>$id))->save(array('deled'=>'1'));
        if ($del_row) {
            $this->success("删除成功", U("showlists", array('status'=>$p_status)));
        } else {
            $this->error("删除失败");
        }
    }
    
    /**
     * 订单导出
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
            ->setCellValue('L1', '发票信息')
            ->setCellValue('M1', '收货人')
            ->setCellValue('N1', '收货电话')
            ->setCellValue('O1', '收货地址')
            ->setCellValue('P1', '推广来源')
            ->setCellValue('Q1', '发货方');
            
            
            // 筛选用户信息
            $M_orders = M('orders');
            $M_orders_buy = M('orders_buy');
            $order_rows = $M_orders->join("__USER__ ON __USER__.id=__ORDERS__.userid")->join("__ORDERS_DETAIL__ ON __ORDERS_DETAIL__.ordernum=__ORDERS__.ordernum")->where($map)->order('time_end DESC')->field('ms_orders.*,ms_user.phone,ms_user.nickname,ms_orders_detail.uname,ms_orders_detail.uphone,ms_orders_detail.uaddr')->select();
            
            //var_dump($order_rows);exit;
            
            $i = 1;
            $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
            $paytype_arr = array('线下支付', '微信');
            
            foreach ($order_rows as $order_k=>$order_v) {
                $i++;
                
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
                
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }

                //来源
                $laytg=dachukk($order_v['code_jxs']);
                //发货方
                $fhtg=dachukk($order_v['fahuofang']);
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $order_v['nickname'])
                ->setCellValueExplicit('C'.$i, $order_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, date('Y-m-d H:i:s',$order_v['time_end']), \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['invoice'])
                ->setCellValue('M'.$i, $order_v['uname'])
                ->setCellValueExplicit('N'.$i, $order_v['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $order_v['uaddr'])
                ->setCellValue('P'.$i, $laytg)
                ->setCellValue('Q'.$i, $fhtg);
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
            header('Content-Disposition: attachment;filename="订单导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
            
        } else {
            $this->display();
        }
    }
    
    /**
     * 订单状态文本转换
     */
    private function trStatus($stat=0, $commented=0,$fhfset=0) {
        if (!$stat) return '已取消';
        
        if ($stat == 1) {
            return '待支付';
        } else if ($stat == 2) {
            return '待发货';
        } else if ($stat == 3) {
            return '待收货';
        } else if ($stat == 5) {
            return '委托经销商发货';
        } else if ($stat == 6) {
            if($fhfset != 0){
                return '等待经销商发货';
            }else{
                return '待发货';
            }
        } else if ($stat == 4) {
            if ($commented) {
                return '已完成';
            }
            return '待评价';
        }else{
            return '已取消';
        }
    }
    
    /**
     * 物流转换
     */
    private function trShipper($shipperid) {
        $M_shipper = M('shipper');
    }
    
    /**
     * 获取access_token
     */
    private function getWxToken() {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WX_APPID;
        $url .= '&secret=' . WX_APPSECRET;
    
        $return_data = $this->curlGet($url);
        
        if (!$return_data) return false;
    
        $json_obj = json_decode($return_data);
    
        if ($json_obj->access_token) return $json_obj->access_token;
    
        return false;
    }
    
    /**
     * curl http
     */
    private function curlGet($url, $header=null, $ispost=false, $postdata=null) {
        if(!$url) return -1;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        if($header && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
    
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0');
    
        if ($ispost AND $postdata) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        } else {
            curl_setopt($ch, CURLOPT_POST, false);
        }
        //curl_setopt($ch, CURLOPT_REFERER, 'http://mobile.umeng.com');
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
        $output = curl_exec($ch);
    
        curl_close($ch);
    
        return $output;
    }


    /*
    *渠道ajax
     */
    public function usqdhyjxs(){
        $action=I('post.action')?I('post.action'):'list';

        $jsxqdm=M('user');
        switch ($action) {
            case 'oneshop':
                $id=I('post.id');
                if($id != ''){
                    $tjz['cateid']=2;
                    $tjz['id']=$id;
                    $list=$jsxqdm
                        ->field('id,username,cateid,phone,nickname,headpic,provinces,city,county')
                        ->where($tjz)
                        ->find();
                    if($list){
                        $list['xsqu']=szcitycx($value['city'],$value['county'],'',$value['provinces'],1);
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              '获取成功',
                            'infor'             =>              array(
                                'sum'           =>                  1,
                                'cont'          =>                  $list
                            )
                        );
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '没有找到该渠道'
                        );
                    }
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '请选择渠道！'
                    );
                }
                break;
            case 'xdus':
                $id=I('post.id');
                if($id != ''){
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              ordersources($id)
                    );
                    
                    /*if($list){
                        $xsqyval=szcitycx($list['city'],$list['county'],'',$list['provinces'],1);
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              $xsqyval,
                            
                        );
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '没有找到该渠道'
                        );
                    }*/
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '请选择渠道！'
                    );
                }
                break;
            case 'shoplist':
                $pagenum=I('post.ordnum');
                if($pagenum != ''){
                    $list=M('orders_buy')->where(array('ordernum'=>$pagenum))->select();
                    foreach ($list as $key => $value) {
                        $list[$key]['imgpic']=shoppicval($value['productid']);
                    }
                    if($list){
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              '获取成功',
                            'infor'             =>              array(
                                'sum'           =>                  count($list),
                                'cont'          =>                  $list
                            )
                        );
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '没有查询到商品'
                        );
                    }
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '订单编号错误！！'
                    );
                }
                break;
            case 'eitbysop':
                $byid=I('post.byid');
                $bymony=I('post.bymony');
                if($byid != ''){
                    $tjset=1;
                    if($bymony == ''){
                        $bymony=0;
                        $tjset=0;
                    }
                    $xgdate=array(
                        'tiaojia_jxs'               =>              $tjset,
                        'price_jsx'                 =>              $bymony
                    );
                    if(M('orders_buy')->where(array('id'=>$byid))->save($xgdate)){
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              '处理完成'
                        );
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '处理失败'
                        );
                    }
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '参数错误！'
                    );
                }
                break;
            
            case 'sousuo':
                $pagenum=I('post.p');

                $sstype=I('post.sstype')?I('post.sstype'):1;
                $ssval=I('post.ssval');

                $provinces=I('post.provinces')?I('post.provinces'):-1;
                $city=I('post.city')?I('post.city'):-1;
                $county=I('post.county')?I('post.county'):-1;

                $tjz['cateid']=2;
                if($ssval == '' && $provinces == -1){
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '渠道信息和销售区域至少提供一个！谢谢'
                    );
                }else{
                    if($ssval != ''){
                        if($sstype == 1){
                            $tjz['username']=array('LIKE','%'.$ssval.'%');
                        }else{
                            $tjz['nickname']=array('LIKE','%'.$ssval.'%');
                        }
                    }
                    if($provinces != -1){
                        $tjz['provinces']=$provinces;
                        if($city != -1){
                            $tjz['city']=$city;
                            if($county != -1){
                                $tjz['county']=$county;
                            }
                        }
                    }

                    $shopsum=$jsxqdm->where($tjz)->count();
                    $page=new PagesusAjax($shopsum,1,array('action'=>$action,'sstype'=>$sstype,'ssval'=>$ssval,'provinces'=>$provinces,'city'=>$city,'county'=>$county));
                    $list=$jsxqdm
                            ->field('id,username,cateid,phone,nickname,headpic,provinces,city,county')
                            ->where($tjz)
                            ->order(array('id'=>'desc'))
                            ->limit($page->firstRow.','.$page->listRows)
                            ->select();
                    $paglist=$page->show();
                    if($list){
                        foreach ($list as $key => $value) {
                            $list[$key]['xsqu']=szcitycx($value['city'],$value['county'],'',$value['provinces'],1);
                        }
                        //var_dump($list);
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              '获取成功',
                            'infor'             =>              array(
                                'sum'           =>                  count($list),
                                'page'          =>                  $paglist,
                                'cont'          =>                  $list
                            )
                        );
                        
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '还没有渠道，请添加...'
                        );
                    }
                }
                break;
            default:
                $pagenum=I('post.p');

                $sstype=I('post.sstype')?I('post.sstype'):1;
                $ssval=I('post.ssval');

                $provinces=I('post.provinces')?I('post.provinces'):-1;
                $city=I('post.city')?I('post.city'):-1;
                $county=I('post.county')?I('post.county'):-1;

                $tjz['cateid']=2;

                $shopsum=$jsxqdm->where($tjz)->count();
                $page=new PagesusAjax($shopsum,1,array('action'=>$action,'sstype'=>$sstype,'ssval'=>$ssval,'provinces'=>$provinces,'city'=>$city,'county'=>$county));
                $list=$jsxqdm
                        ->field('id,username,cateid,phone,nickname,headpic,provinces,city,county')
                        ->where($tjz)
                        ->order(array('id'=>'desc'))
                        ->limit($page->firstRow.','.$page->listRows)
                        ->select();
                $paglist=$page->show();
                if($list){
                    foreach ($list as $key => $value) {
                        $list[$key]['xsqu']=szcitycx($value['city'],$value['county'],'',$value['provinces'],1);
                    }
                    //var_dump($list);
                    $ajaxcont=array(
                        'code'              =>              1,
                        'msg'               =>              '获取成功',
                        'infor'             =>              array(
                            'sum'           =>                  count($list),
                            'page'          =>                  $paglist,
                            'cont'          =>                  $list
                        )
                    );
                    
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '还没有渠道，请添加...'
                    );
                }
                break;
        }
        echo json_encode($ajaxcont);
    }
    
    /*
    *查看结算信息
     */
    public function lookjs(){
        $id=I('get.id');
        $M_orders = M('orders');
                $M_user = M('user');
                if (!$id) $this->error('参数有误！', U('jxsjiage'));
            

                $lokset=$M_orders->where(array('id'=>$id))->save(array('lookset'=>1));

                //echo $lokset.'=>250';
                
                $p_status = I('get.status', '0', 'intval');
                
                $M_orders_buy = M('orders_buy');
                $M_orders_detail = M('orders_detail');
                $M_user = M('user');
                
                // 订单信息
                $order_row = $M_orders->find($id);
                $M_jisuan=M('jiesuan');
                $order_row['detail'] = $M_orders_detail->where('ordernum=' . $order_row['ordernum'])->find();
                $order_row['buy'] = $M_orders_buy->where('ordernum=' . $order_row['ordernum'])->select();
                
                
                
                // 物流信息
                $M_shipper = M('shipper');
                $shipper_rows = $M_shipper->order('showorder DESC')->select();
                
                $fhsdval=fhfjumpys($order_row['code_jxs'],$order_row['fahuofang']);

                $this->fhsdval = $fhsdval;
                $this->order_row = $order_row;
                
                $jscont = $M_jisuan->where('orderid=' . $order_row['id'])->find();
                $this->jscont = $jscont;

                $this->fhzt=$this->trStatus($order_row['orderstatus'], $order_row['commented'], $order_row['fahuofang']);

                $this->shipper_rows = $shipper_rows;

                $this->assign('ddju',M('ordliucheng')->where(array('ordlc_ordid'=>$id))->find());


                $this->display();
    }
    /*
    *经销商待发货期限
     */
    public function fhqxset(){
        $action=I('get.action')?I('get.action'):'list';
        $setgqsj=M('datetime');
        switch ($action) {
            case 'clz':
                $tsid=I('post.tsid')?I('post.tsid'):0;
                $setval['setval']=I('post.setval')?I('post.setval'):1;
                if($tsid){
                    if($setgqsj->where(array('id'=>$tsid))->save($setval)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/Orders/fhqxset/action/list').'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/Orders/fhqxset/action/list').'"</script>';
                    }
                }else{
                    if($setgqsj->add($setval)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/Orders/fhqxset/action/list').'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("写入失败");location.href="'.U('Admin/Orders/fhqxset/action/list').'"</script>';
                    }
                }
                break;
            
            default:
                $list=$setgqsj->order(array('id'=>'desc'))->find();
                if($list){
                    $ts=$list['setval'];
                    $tsid=$list['id'];
                }else{
                    $ts=1;
                    $tsid=0;
                }
                $this->assign('tsz',$ts);
                $this->assign('tsid',$tsid);
                $this->assign('action','clz');
                $this->display();
                break;
        }
    }

}