<?php
namespace Admin\Controller;
use think\Controller;
use think\Model;
use PublicClass\PagesusAjax;
/*
*统计
 */
/**
* 
*/
class StatisController extends AdminAuthController
{
	
	/*
	*总部推广信息
	 */
	public function index(){
		if(I('get.ly') == 1){
			$map['fahuofang']	=	0;
			$map['orderstatus']	=	array('IN','3,4');
			$this->ly=1;
			$M_orders = M('orders');
			$count = $M_orders->where($map)->count();
		}else{
			$map['code_jxs']	=	0;
			$this->ly=0;
			$mod=new Model();
			$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0  ORDER BY time_end DESC');
			$count=count($ord_list);
		}
		
        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        if(I('get.ly') == 1){
        	$orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
    	}else{
    		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0 ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
    	}
        
        
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
        
        $this->orders_list = $orders_list;
        $this->page = $show;
        $this->lookset=0;
		$this->display();
	}

	/**
     * 订单状态文本转换
     */
    static function trStatus($stat=0, $commented=0) {
        if (!$stat) return '';
        
        if ($stat == 1) {
            return '待支付';
        } else if ($stat == 2) {
            return '待发货';
        } else if ($stat == 3) {
            return '待收货';
        } else if ($stat == 5) {
            return '委托经销商发货';
        } else if ($stat == 4) {
            if ($commented) {
                return '已完成';
            }
            return '待评价';
        }
    }

    /*
    *查询订单
     */
    public function lookcont(){
    	if(I('get.ly') == 1){
			$this->ly=1;
		}else{
			$this->ly=0;
		}
		//关键字
        $p_ordernum = I('post.ordernum');
        if($p_ordernum==''){
            $p_ordernum = I('get.ordernum');
        }

       	//查询属性 
        $lookset = I('post.lookset');
        if($lookset == ''){
            $lookset = I('get.lookset', '0', 'intval');
        }
        //发货状态
        $p_status = I('get.status', '0', 'intval');
        //查询日期
        $starttime = I('post.starttime');
        if($starttime==''){
            $starttime = I('get.starttime');
        }

        $this->p_ordernum=$p_ordernum;
        $this->lookset=$lookset;
        $this->starttime=$starttime;

        if($lookset == 0){
            $lookset='all';
        }
        if($p_ordernum == '' && $starttime == ''){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("请输入关键字或时间，至少提供一个查询内容！");history.go(-1);</script>';
            return;
        }
        $bgtime=strtotime($starttime.' 0:0:0');
        $endtime=strtotime($starttime.' 23:59:59');
        switch ($lookset) {
        	case 1:
        		if($this->ly == 1){
					$map['fahuofang']	=	0;
					$map['orderstatus']	=	array('IN','3,4');
					$this->ly=1;
					$map['title'] = array('like', "%{$p_ordernum}%");
					if($starttime != ''){
                    	$map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                	}
					$M_orders = M('orders');
					$count = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->count();
				}else{
					$tj='';
					if($p_ordernum != ''){
						$tj.=" AND b.title LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum ,ms_orders p2  WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0 '.$tj.' GROUP BY b.ordernum ORDER BY time_end DESC');
					$count=count($ord_list);
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));

				if($this->ly == 1){
                	$orders_list = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->order('time_end DESC')
                        ->select();
            	}else{
            		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum ,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0 '.$tj.' GROUP BY b.ordernum ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
            	}

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
        		
        		if($this->ly == 1){
					$map['fahuofang']	=	0;
					$map['orderstatus']	=	array('IN','3,4');
					$this->ly=1;
					$map['ordernum'] = array('like', "%{$p_ordernum}%");
					if($starttime != ''){
                    	$map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
                	}
					$M_orders = M('orders');
					$count = $M_orders->where($map)->count();
				}else{
					$tj='';
					if($p_ordernum != ''){
						$tj.=" AND p1.ordernum LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0 '.$tj.' ORDER BY time_end DESC');
					$count=count($ord_list);
					
				}

                $Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));
                if($this->ly == 1){
                	$orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
            	}else{
            		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
            	}

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

    /*
    *经销商
     */
    public function jxslist(){
    	/*if(I('get.ly') == 1){
			$map['fahuofang']	=	array('NEQ',0);
			$map['orderstatus']	=	array('IN','3,4,5');
			$this->ly=1;
		}else{
			$map['code_jxs']	=	array('NEQ',0);
			$this->ly=0;
		}
		$M_orders = M('orders');
		$count = $M_orders->where($map)->count();*/

		//$mod=new Model();
		//$ord_list=$mod->query('select p1.* from ms_orders p1,ms_orders p2 where p1.code_jxs<>p2.fahuofang and p1.fahuofang<>p2.code_jxs and p1.id=p2.id and p1.code_jxs <> 0');

		if(I('get.ly') == 1){
			$map['fahuofang']	=	array('NEQ',0);
			$map['orderstatus']	=	array('IN','3,4,5');
			$this->ly=1;
			$M_orders = M('orders');
			$count = $M_orders->where($map)->count();
		}else{
			$map['code_jxs']	=	array('NEQ',0);
			$this->ly=0;
			$mod=new Model();
			$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 ORDER BY time_end DESC ');
			$count=count($ord_list);
		}



        $Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        if(I('get.ly') == 1){
        	$orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
    	}else{
    		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
    	}
        
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
        
        $this->orders_list = $orders_list;
        $this->page = $show;
        $this->lookset=0;
		$this->display();
    }
    /*
    *查询订单
     */
    public function lookcontjxs(){
    	if(I('get.ly') == 1){
			$this->ly=1;
		}else{
			$this->ly=0;
		}
		//关键字
        $p_ordernum = I('post.ordernum');
        if($p_ordernum==''){
            $p_ordernum = I('get.ordernum');
        }

       	//查询属性 
        $lookset = I('post.lookset');
        if($lookset == ''){
            $lookset = I('get.lookset', '0', 'intval');
        }
        //发货状态
        $p_status = I('get.status', '0', 'intval');
        //查询日期
        $starttime = I('post.starttime');
        if($starttime==''){
            $starttime = I('get.starttime');
        }

        $this->p_ordernum=$p_ordernum;
        $this->lookset=$lookset;
        $this->starttime=$starttime;

        if($lookset == 0){
            $lookset='all';
        }
        if($p_ordernum == '' && $starttime == ''){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("请输入关键字或时间，至少提供一个查询内容！");history.go(-1);</script>';
            return;
        }
        $bgtime=strtotime($starttime.' 0:0:0');
        $endtime=strtotime($starttime.' 23:59:59');
        switch ($lookset) {
        	case 1:
        		if($this->ly == 1){
					$map['fahuofang']	=	array('NEQ',0);
					$map['orderstatus']	=	array('IN','3,4,5');
					$this->ly=1;

					$map['title'] = array('like', "%{$p_ordernum}%");
	                if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }

					$M_orders = M('orders');
					$count = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->count();
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.title LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					$count=count($ord_list);
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));

				if($this->ly == 1){
                	$orders_list = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->order('time_end DESC')
                        ->select();
            	}else{
            		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum ,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' GROUP BY b.ordernum ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
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
        	case 2:
        		$ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

        		if($this->ly == 1){

        			$map['username'] = array('like', "%{$p_ordernum}%");
        			if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }
	                $M_orders = M('orders');
					if($ddcxff == 1){
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->count();
					}else{
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
					}
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.username LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					if($ddcxff == 1){
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}else{
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.fahuofang <> 0 '.$tj);
					}
					$count=count($ord_list);
					
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
				if($this->ly == 1){
					if($ddcxff == 1){
						$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }else{
                        	$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }
				}else{
					if($ddcxff == 1){
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}else{
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.fahuofang <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}
				}
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
        		$ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

        		if($this->ly == 1){

        			$map['nickname'] = array('like', "%{$p_ordernum}%");
        			if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }
	                $M_orders = M('orders');
					if($ddcxff == 1){
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->count();
					}else{
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
					}
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.nickname LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					if($ddcxff == 1){
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}else{
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.fahuofang <> 0 '.$tj);
					}
					$count=count($ord_list);
					
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
				if($this->ly == 1){
					if($ddcxff == 1){
						$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }else{
                        	$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }
				}else{
					if($ddcxff == 1){
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}else{
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.fahuofang <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}
				}
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
        		if($this->ly == 1){
					$map['fahuofang']	=	array('NEQ',0);
					$map['orderstatus']	=	array('IN','3,4,5');
					$this->ly=1;

					$map['ordernum'] = array('like', "%{$p_ordernum}%");
	                if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }

					$M_orders = M('orders');
					$count = $M_orders->where($map)->count();
				}else{
					if($p_ordernum != ''){
						$tj.=" AND p1.ordernum LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					$count=count($ord_list);
				}



		        $Page = new \Think\Page($count, 10);
		        //定制分页类
		        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
		        $Page->setConfig('prev','上一页');
		        $Page->setConfig('next','下一页');
		        $Page->setConfig('first','首页');
		        $Page->setConfig('last','末页');
		        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
		        $show = $Page -> show();
		        
		        if(I('get.ly') == 1){
		        	$orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
		    	}else{
		    		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
		    	}
		        
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
		        
		        $this->orders_list = $orders_list;
		        $this->page = $show;
		        $this->lookset=0;
        		break;
        }
        $this->display('jxslist');
    }
    /*
    *经销商价格统计
     */
    public function jxsjiage(){
        $action=I('get.action')?I('get.action'):'all';
        switch ($action) {
            case 'wei':
                $map['sfjsset']=0;
                break;
            case 'deng':
                $map['sfjsset']=1;
                break;
            case 'wan':
                $map['sfjsset']=2;
                break;
            default:
                # code...
                break;
        }
    	$map['fahuofang']	=	array('NEQ',0);
		$map['orderstatus']	=	array('IN','4,5');
		$map['paytype']	=	array('EQ',1);

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

        $M_jisuan = M('jiesuan');

        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
            
            $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
            
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
            
            $orders_list[$orders_k]['buy'] = $M_orders_buy->field('ordernum,title,num,price_jsx,price')->where('ordernum=' . $orders_v['ordernum'])->select();

            $orders_list[$orders_k]['jisuan'] = $M_jisuan->where('id=' . $orders_v['id'])->find();
        }
        /*echo jsxjagejs($orders_v['ordernum']);*/
        $this->orders_list = $orders_list;
        $this->page = $show;
        $this->lookset=0;
        $this->ly=1;
		$this->display();
    }
    /*
    *结算操作
     */
    public function jiesuan(){
    	$action=I('get.action')?I('get.action'):'list';
    	switch ($action) {
    		case 'js':
    			$id=I('post.id');
    			$jsbz=I('post.jsbz');
    			if(!$id){
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
    			}
    			if(!$jsbz){
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入结算备注");history.go(-1);</script>';
                    return;
    			}
    			$jsdata=array(
    				'orderid'				=>				$id,
    				'jiesuantime'			=>				time(),
    				'jiesuanbeizhu'			=>				$jsbz,
    				'jiesuanid'				=>				$_SESSION['r_id']
    			);
    			$orjszt=array(
    				'sfjsset'			=>					1
    			);
    			if(M('jiesuan')->add($jsdata)){
    				M('orders')->where(array('id'=>$id))->save($orjszt);
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    				echo '<script>alert("操作完成！");location.href="'.U('jxsjiage').'"</script>';
    			}else{
    				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
    			}
    			break;
    		
    		default:
    			$id=I('get.id');
    			$M_orders = M('orders');
        		$M_user = M('user');
        		if (!$id) $this->error('参数有误！', U('jxsjiage'));
            
	            $id = I("get.id");
	            
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
    			$this->display();
    			break;
    	}
    }
    /*
    *查看结算信息
     */
    public function lookjs(){
    	$id=I('get.id');
    	$M_orders = M('orders');
        		$M_user = M('user');
        		if (!$id) $this->error('参数有误！', U('jxsjiage'));
            
	            $id = I("get.id");
	            
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

	            $this->fhzt=$this->trStatus($order_row['orderstatus'], $order_row['commented']);

	            $this->shipper_rows = $shipper_rows;
    			$this->display();
    }
    /*
    *佣金结算
     */
    public function jxsyongjin(){
    	
    	$mod=new Model();
    	$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0');
    	//$ord_list=$mod->query('select p1.* from ms_orders p1,ms_orders p2 where p1.code_jxs<>p2.fahuofang and p1.fahuofang<>p2.code_jxs and p1.id=p2.id');
		
    	$count=count($ord_list);
    	
    	$Page = new \Think\Page($count, 10);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 LIMIT '.$Page->firstRow.','.$Page->listRows);
        //$orders_list=$mod->query('select p1.* from ms_orders p1,ms_orders p2 where p1.code_jxs<>p2.fahuofang and p1.fahuofang<>p2.code_jxs and p1.id=p2.id  limit '.$Page->firstRow.','.$Page->listRows);
        
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        $M_user = M('user');
        $M_shipper = M('shipper');

        $M_jisuan = M('jiesuan');

        foreach ($orders_list as $orders_k=>$orders_v) {
            $orders_list[$orders_k]['user'] = $M_user->field('id,phone,nickname')->find($orders_v['userid']);
            
            $orders_list[$orders_k]['shippername'] = $M_shipper->where('id=' . $orders_v['shipperid'])->getField('name');
            
            $orders_list[$orders_k]['stat'] = $this->trStatus($orders_v['orderstatus'], $orders_v['commented']);
            $orders_list[$orders_k]['detail'] = $M_orders_detail->where('ordernum=' . $orders_v['ordernum'])->find();
            
            $orders_list[$orders_k]['buy'] = $M_orders_buy->field('ordernum,title,num,price_jsx,price')->where('ordernum=' . $orders_v['ordernum'])->select();

            $orders_list[$orders_k]['jisuan'] = $M_jisuan->where('id=' . $orders_v['id'])->find();
        }
        //var_dump($orders_list);
        $this->orders_list = $orders_list;
        $this->page = $show;
        $this->lookset=0;
        $this->display();
    }





    /*
    *经销商价格查询
     */
    public function lookjxsjgx(){
		if(I('get.ly') == 1){
			$this->ly=1;
		}else{
			$this->ly=0;
		}
		//关键字
        $p_ordernum = I('post.ordernum');
        if($p_ordernum==''){
            $p_ordernum = I('get.ordernum');
        }

       	//查询属性 
        $lookset = I('post.lookset');
        if($lookset == ''){
            $lookset = I('get.lookset', '0', 'intval');
        }
        //发货状态
        $p_status = I('get.status', '0', 'intval');
        //查询日期
        $starttime = I('post.starttime');
        if($starttime==''){
            $starttime = I('get.starttime');
        }

        $this->p_ordernum=$p_ordernum;
        $this->lookset=$lookset;
        $this->starttime=$starttime;

        if($lookset == 0){
            $lookset='all';
        }
        if($p_ordernum == '' && $starttime == ''){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("请输入关键字或时间，至少提供一个查询内容！");history.go(-1);</script>';
            return;
        }
        $bgtime=strtotime($starttime.' 0:0:0');
        $endtime=strtotime($starttime.' 23:59:59');
        switch ($lookset) {
        	case 1:
        		if($this->ly == 1){
					$map['fahuofang']	=	array('NEQ',0);
					$map['orderstatus']	=	array('IN','3,4,5');
					$this->ly=1;

					$map['title'] = array('like', "%{$p_ordernum}%");
	                if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }
	                $map['paytype']	=	array('EQ',1);
					$M_orders = M('orders');
					$count = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->count();
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.title LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					$count=count($ord_list);
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime));

				if($this->ly == 1){
					$map['paytype']	=	array('EQ',1);
                	$orders_list = $M_orders
                        ->alias('o')
                        ->field('o.*,b.ordernum,b.title')
                        ->join('__ORDERS_BUY__ b ON b.ordernum = o.ordernum','LEFT')
                        ->where($map)
                        ->group('b.ordernum')
                        ->limit($Page->firstRow.','.$Page->listRows)
                        ->order('time_end DESC')
                        ->select();
            	}else{
            		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_orders_buy b ON b.ordernum = p1.ordernum ,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' GROUP BY b.ordernum ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
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
        	case 2:
        		$ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

        		if($this->ly == 1){

        			$map['username'] = array('like', "%{$p_ordernum}%");
        			if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }

	                $M_orders = M('orders');
					if($ddcxff == 1){
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->count();
					}else{
						$map['paytype']	=	array('EQ',1);
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
					}
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.username LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					if($ddcxff == 1){
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}else{
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}
					$count=count($ord_list);
					
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
				if($this->ly == 1){
					if($ddcxff == 1){
						$map['paytype']	=	array('EQ',1);
						$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }else{
                        	$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }
				}else{
					if($ddcxff == 1){
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}else{
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}
				}
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
        		$ddcxff = I('post.ddcxff');
                if($ddcxff == ''){
                    $ddcxff = I('get.ddcxff', '1', 'intval');
                }

                $this->ddcxff=$ddcxff;

        		if($this->ly == 1){

        			$map['nickname'] = array('like', "%{$p_ordernum}%");
        			if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['o.addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }
	                $M_orders = M('orders');
					if($ddcxff == 1){
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->count();
					}else{
						$map['paytype']	=	array('EQ',1);
						$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
					}
				}else{
					if($p_ordernum != ''){
						$tj.=" AND b.nickname LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					if($ddcxff == 1){
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}else{
						$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					}
					$count=count($ord_list);
					
				}
				$Page = new \Think\Page($count, 10,array('ordernum'=>$p_ordernum,'lookset'=>$lookset,'starttime'=>$starttime,'ddcxff'=>$ddcxff));
				if($this->ly == 1){
					if($ddcxff == 1){
						$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.code_jxs','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }else{
                        	$map['paytype']	=	array('EQ',1);
                        	$orders_list=M('orders')
                            ->alias('o')
                            ->field('o.*,b.id,b.nickname')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->limit($Page->firstRow.','.$Page->listRows)
                            ->order('time_end DESC')
                            ->select();
                        }
				}else{
					if($ddcxff == 1){
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}else{
						$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.fahuofang,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
					}
				}
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
        		if($this->ly == 1){
					$map['fahuofang']	=	array('NEQ',0);
					$map['orderstatus']	=	array('IN','3,4,5');
					$this->ly=1;
					$map['paytype']	=	array('EQ',1);
					$map['ordernum'] = array('like', "%{$p_ordernum}%");
	                if($starttime != ''){
	                    $bgtime=strtotime($starttime.' 0:0:0');
	                    $endtime=strtotime($starttime.' 23:59:59');
	                    $map['addtime'] = array('BETWEEN', $bgtime.",".$endtime);
	                }

					$M_orders = M('orders');
					$count = $M_orders->where($map)->count();
				}else{
					if($p_ordernum != ''){
						$tj.=" AND p1.ordernum LIKE '%".$p_ordernum."%'";
					}
					if($starttime != ''){
	                    $tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
	                }
					$this->ly=0;
					$mod=new Model();
					$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
					$count=count($ord_list);
				}



		        $Page = new \Think\Page($count, 10);
		        //定制分页类
		        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
		        $Page->setConfig('prev','上一页');
		        $Page->setConfig('next','下一页');
		        $Page->setConfig('first','首页');
		        $Page->setConfig('last','末页');
		        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
		        $show = $Page -> show();
		        
		        if(I('get.ly') == 1){
		        	$orders_list = $M_orders->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('time_end DESC')->select();
		    	}else{
		    		$orders_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj.' ORDER BY time_end DESC LIMIT '.$Page->firstRow.','.$Page->listRows);
		    	}
		        
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
		        
		        $this->orders_list = $orders_list;
		        $this->page = $show;
		        $this->lookset=0;
        		break;
        }
        $this->display('jxsjiage');
    }
}
?>