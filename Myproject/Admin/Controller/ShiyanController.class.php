<?php
/*
    *查询订单
     */
    public function lookcont(){
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
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
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
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
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
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
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
                    
                    $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
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
        $this->display('index');
    }
?>