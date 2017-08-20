<?php
namespace Index\Controller;
use Think\Controller;
class OrdersController extends AuthController {
    /**
     * 待付款
     */
    public function index() {
       $user_row = $this->getUser();
        
        // 订单状态
        $p_status = I('get.status', '1', 'intval');
        
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        
        $M_products = M('products');
        
        $order_rows = $M_orders->where(array('userid'=>$user_row['id'], 'orderstatus'=>$p_status, 'deled'=>0))->order('addtime DESC')->select();
        foreach ($order_rows as $key => $valshop) {
            $order_rows[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
        }
        
        /*foreach ($order_rows as $order_rowk=>$order_rowv) {
            $tmp_item = $M_orders_buy->where(array('ordernum'=>$order_rowv['ordernum']))->select();
            foreach ($tmp_item as $tmp_item_k=>$tmp_item_v) {
                $tmp_item[$tmp_item_k]['titlepic'] = $M_products->where('id=' . $tmp_item_v['productid'])->getField('titlepic');
            }
            $order_rows[$order_rowk]['items'] = $tmp_item;
            $order_rows[$order_rowk]['detail'] = $M_orders_detail->where(array('ordernum'=>$order_rowv['ordernum']))->find();
        }*/
        
        $this->status = $p_status;
        //var_dump($order_rows);
        //$this->order_rows = $order_rows;
        $this->assign('order_rows',$order_rows);
        $this->display();
    }
    
    /**
     * 待发货
     */
    public function daigfahuo() {

        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');

        $order_rows = $M_orders->where(array('userid'=>$user_row['id'], 'orderstatus'=>array('IN','2,5,6'), 'deled'=>0))->order('addtime DESC')->select();
        foreach ($order_rows as $key => $valshop) {
            $order_rows[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
        }
        $this->assign('order_rows',$order_rows);
        $this->display();
    }
    
    /**
     * 待收货
     */
    public function dashouhuo() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');

        $order_rows = $M_orders->where(array('userid'=>$user_row['id'], 'orderstatus'=>3, 'deled'=>0))->order('addtime DESC')->select();
        foreach ($order_rows as $key => $valshop) {
            $order_rows[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
        }
        //var_dump($order_rows);
        $this->assign('order_rows',$order_rows);
        $this->display();
    }
    
    /**
     * 待评价
     */
    public function pj() {
        $this->display();
    }
    
    /**
     * 售后
     */
    public function serv() {
        $user_row = $this->getUser();
        
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        
        $M_products = M('products');
        
        $map['orderstatus'] = array('egt', '1');
        $map['userid'] = array('eq', $user_row['id']);
        $map['deled'] = array('eq', '0');
        
        $order_rows = $M_orders->where(array('userid'=>$user_row['id'], 'deled'=>0))->order('addtime DESC')->select();
        foreach ($order_rows as $key => $valshop) {
            $order_rows[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
        }
        
        $this->order_rows = $order_rows;
        
        $this->display();
    }
    
    /**
     * 取消订单
     */
    public function cancel() {

        $user_row = $this->getUser();
        
        $p_orderid = I('get.id', '0', 'intval');
        if (!$p_orderid) {
            $this->error('请选择要取消的订单！');
            return;
        }
        
        $M_orders = M('orders');
        //$M_orders_buy = M('orders_buy');
        //$M_orders_detail = M('orders_detail');
        
        $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id']))->find();
        if (!$order_row) {
            $this->error('该订单不存在或已被删除！');
            return;
        }
        
        if ($order_row['orderstatus']==1 || $order_row['orderstatus']==5 || $order_row['orderstatus']==6) {
            // 取消订单
            $updated = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id']))->save(array('orderstatus'=>0));
            if ($updated) {
                $this->success('订单取消成功！');
                return;
            } else {
                echo 3;
                $this->success('订单取消失败！');
                return;
            }
        }
        $this->error('参数错误！');
    }
    
    /**
     * 订单详情
     */
    public function detail() {
        $user_row = $this->getUser();
        
        $p_orderid = I('get.id', '0', 'intval');
        if (!$p_orderid) {
            $this->error('请选择要查看的订单！');
        }
        
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        
        $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>2))->find();
        if (!$order_row) {
            $this->error('该订单不存在或已被删除！');
        }
        
        $order_buy_rows = $M_orders_buy->where(array('ordernum'=>$order_row['ordernum']))->select();
        
        $order_detail_row = $M_orders_detail->where(array('ordernum'=>$order_row['ordernum']))->find();
        
        $this->order_row = $order_row;
        $this->order_buy_rows = $order_buy_rows;
        $this->order_detail_row = $order_detail_row;
        
        $this->display();
    }
    
    /**
     * 物流信息
     */
    public function wuliu() {
        $user_row = $this->getUser();
    
        $p_orderid = I('get.id', '0', 'intval');
        if (!$p_orderid) {
            $this->error('请选择要查看的订单！');
        }
    
        $M_orders = M('orders');
        $M_orders_detail = M('orders_detail');
        
        $M_shipper = M('shipper');
    
        $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>3))->find();
        if (!$order_row) {
            $this->error('该订单不存在或已被删除！');
        }
        
        // 快递公司
        $order_row['shippername'] = $M_shipper->where('id=' . $order_row['shipperid'])->getField('name');
        
        $order_detail_row = $M_orders_detail->where(array('ordernum'=>$order_row['ordernum']))->find();
        
        $this->order_row = $order_row;
        
        $this->order_detail_row = $order_detail_row;
    
        $this->display();
    }
    
    /**
     * 确认收货
     */
    public function receive() {
        $user_row = $this->getUser();
    
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        
        if (IS_POST) {
            $p_orderid = I('post.id', '0', 'intval');
            
            if (!$p_orderid) {
                $this->error('请选择要确认的订单！');
            }
            
            $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>3))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
            
            $order_row['orderstatus'] = 4;
            
            $updated = $M_orders->save($order_row);
            if ($updated) {
                $this->success('确认收货成功！', U('Orders/pj'));
            } else {
                $this->error('确认收货失败！');
            }
        } else {
            $p_orderid = I('get.id', '0', 'intval');
            
            if (!$p_orderid) {
                $this->error('请选择要确认的订单！');
            }
        
            $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>3))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
        
            $order_buy_rows = $M_orders_buy->where(array('ordernum'=>$order_row['ordernum']))->select();
        
            $order_detail_row = $M_orders_detail->where(array('ordernum'=>$order_row['ordernum']))->find();
        
            $this->order_row = $order_row;
            $this->order_buy_rows = $order_buy_rows;
            $this->order_detail_row = $order_detail_row;
        
            $this->display();
        }
    }
    
    /**
     * 评价订单
     */
    public function pjorder() {
        $user_row = $this->getUser();
        
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_products = M('products');
        
        
        if (IS_POST) {
            $p_id_arr = I('post.id');
            $p_content_arr = I('post.content');
            // 拼接数组
            $prod_arr = array();
            foreach ($p_id_arr as $idk=>$idv) {
                $prod_arr[$idv] = $p_content_arr[$idk];
            }
            
            $p_orderid = I('post.orderid');

            // 评论
            $M_pcomments = M('pcomments');
            
            if (!$p_orderid) {
                $this->error('请选择要评价的订单！');
            }
            
            $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>4))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
            
            $order_buy_rows = $M_orders_buy->where(array('ordernum'=>$order_row['ordernum']))->select();
            foreach ($order_buy_rows as $tmp_item_k=>$tmp_item_v) {
                //$order_buy_rows[$tmp_item_k]['titlepic'] = $M_products->where('id=' . $tmp_item_v['productid'])->getField('titlepic');
                // 遍历所购商品
                if (key_exists($tmp_item_v['productid'], $prod_arr) AND $prod_arr[$tmp_item_v['productid']]!='') {
                    // 更新评论
                    $M_pcomments->add(array(
                        'productid' => $tmp_item_v['productid'],
                        'orderid' => $p_orderid,
                        'userid' => $user_row['id'],
                        'content' => $prod_arr[$tmp_item_v['productid']],
                        'addtime' => time(),
                    ));
                }
            }
            
            // 更新订单状态为已评价
            $order_row['commented'] = 1;
            $updated = $M_orders->save($order_row);
            if ($updated) {
                $this->success('评价成功！', U('Orders/pingjia'));
            } else {
                $this->error('评价失败！');
            }
        } else {
            $p_orderid = I('get.id', '0', 'intval');
        
            if (!$p_orderid) {
                $this->error('请选择要评价的订单！');
            }
            
            $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id'], 'orderstatus'=>4))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
            
            $order_buy_rows = $M_orders_buy->where(array('ordernum'=>$order_row['ordernum']))->select();
            foreach ($order_buy_rows as $tmp_item_k=>$tmp_item_v) {
                $order_buy_rows[$tmp_item_k]['titlepic'] = $M_products->where('id=' . $tmp_item_v['productid'])->getField('titlepic');
            }
            
            $this->order_row = $order_row;
            $this->order_buy_rows = $order_buy_rows;
            
            $this->display();
        }
        
    }
    
    /**
     * 下单
     */
    public function doorder() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        // 购物车下单
        $p_orderIds = I('get.orderIds', '');
        
        // 立即购买
        $p_productid = I('get.productid', '0', 'intval');
        $p_num = I('get.productnum', '0', 'intval');
        
        $M_shopcart = M('shopcart');
        $M_products = M('products');
        
        // 显示列表
        $order_list = array();
        $total_money = 0;
        
        if ($p_orderIds) {
            // 目标条目
            $target_arr = array();
            
            // 购物车
            $cart_arr = explode(',', $p_orderIds);
            foreach ($cart_arr as $cart_k=>$cart_v) {
                if (! intval($cart_v)) continue;
                // 查询购物车是否存在
                $exists = $M_shopcart->where(array('id'=>$cart_v, 'uid'=>$user_row['id']))->find();
                if ($exists) {
                    $target_arr[] = $cart_v;
                    
                    $prod_row = $M_products->where('id='.$exists['productid'])->field('id,titlepic,title,price')->find();
                    $exists['detail'] = $prod_row;
                    $order_list[] = $exists;
                    
                    $total_money += $prod_row['price'] * $exists['num'];
                }
            }
            
            // 购物车ID
            $this->target_arr = implode(',', $target_arr);
        } else if($p_productid AND $p_num) {
            // 立即购买
            $prod_row = $M_products->where(array('id'=>$p_productid))->field('id,titlepic,title,price')->find();
            $order_list[0]['num'] = $p_num;
            $order_list[0]['detail'] = $prod_row;
            $total_money += $prod_row['price'] * $p_num;
            
            $this->pid = $p_productid;
            $this->num = $p_num;
        } else {
            $this->error('请选择下单商品！');
        }
        
        // 查询地址
        $M_orders = M('orders');
        $his_order= $M_orders->where(array('userid'=>$user_row['id']))->field('ordernum')->order('addtime DESC')->find();
        if ($his_order) {
            $M_orders_detail = M('orders_detail');
            $his_detail = $M_orders_detail->where(array('ordernum'=>$his_order['ordernum']))->find();
        }
        
        $this->his_detail = $his_detail;
        
        $this->total_money = $total_money;
        $this->order_list = $order_list;
        
        $this->display();
    }
    
    /**
     * 订单支付
     */
    public function pay() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        $M_orders = M('orders');
        $M_corn = M('corn');
        
        if (IS_POST) {
            // 选择支付方式
            $p_orderid = I('post.id', '0', 'intval');
            $p_paytype = I('post.type', '0', 'intval');
            
            if (!$p_orderid OR !$p_paytype) {
                $this->error('请选择要支付的订单！');
            }
            
            // $p_paytype 1微信 2支付宝 3积分兑换
            if ($p_paytype==3) {
                // 积分兑换

                // 积分查询
                $corn_row = $M_corn->where(array('cateid'=>'1', 'uid'=>$user_row['id']))->find();
                if ($corn_row['corn'] < 3500) {
                    $this->error('不符合积分兑换条件！');
                }

                // 订单所需积分
                $order_row = $M_orders->where(array('userid'=>$user_row['id'], 'id'=>$p_orderid))->find();
                if ($order_row['corn'] > $corn_row['corn']) {
                    // 积分不够兑换
                    $this->error('积分不足，无法兑换商品！');
                }

				// 减积分
				$dec_corn = $M_corn->where(array('cateid'=>'1', 'uid'=>$user_row['id']))->setDec('corn', $order_row['corn']);
				if ($dec_corn) {
					// 更新订单
					$updated_order = $M_orders->where(array('userid'=>$user_row['id'], 'id'=>$p_orderid))->save(array('time_end'=>date('YmdHis'), 'paytype'=>'0', 'orderstatus'=>'2'));
					if ($updated_order) {
						$this->error('积分兑换成功！', U('Orders/send'));
					} else {
						// 回退积分
						$M_corn->where(array('cateid'=>'1', 'uid'=>$user_row['id']))->setInc('corn', $order_row['corn']);
						$this->error('积分兑换失败，请联系客服！');
					}
				} else {
					$this->error('积分兑换失败，请联系客服！');
				}
            } else if ($p_paytype == 1) {
                // 微信支付
                $this->redirect('wxpay', array('id'=>$p_orderid));
            } else if ($p_paytype == 2) {
                // 支付宝
                $this->redirect('alipay', array('id'=>$p_orderid));
            } else {
                $this->error('支付异常！');
            }
            
        } else {
            $p_orderid = I('get.id', '0', 'intval');
            if (!$p_orderid) {
                $this->error('请选择要支付的订单！');
            }
            
            
            $M_orders_buy = M('orders_buy');
            $M_orders_detail = M('orders_detail');
            
            $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id']))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
            
            $order_buy_rows = $M_orders_buy->where(array('ordernum'=>$order_row['ordernum']))->select();
            
            $order_detail_row = $M_orders_detail->where(array('ordernum'=>$order_row['ordernum']))->find();
            
            
            // 积分查询
            $corn_row = $M_corn->where(array('cateid'=>'1', 'uid'=>$user_row['id']))->find();
            
            
            $this->order_row = $order_row;
            $this->order_buy_rows = $order_buy_rows;
            $this->order_detail_row = $order_detail_row;
            
            $this->corn_row = $corn_row;
            
            $this->display();
        }
    }
    
    /**
     * 微信支付
     */
    public function wxpay() {
        $user_row = $this->getUser();
        
        $p_orderid = I('get.id', '0', 'intval');
        if (!$p_orderid) {
            $this->error('请选择要支付的订单！');
        }
        
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
        
        $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id']))->find();
        if (!$order_row) {
            $this->error('该订单不存在或已被删除！');
        }
        
        // 预支付订单
        $prepay_id = $this->doUnionOrder(array('openid'=>$user_row['openid'], 'orderNo'=>$order_row['ordernum'], 'money'=>$order_row['money'], 'body'=>$order_row['ordernum']));
        
        $prepay_data = array(
            "appId" => WX_APPID,
            "timeStamp" => time(),
            "nonceStr" => $this->getNonceStr(32),
            "package" => "prepay_id=" . $prepay_id,
            "signType" => "MD5"
        );
        
        $prepay_data_str = $this->genPrepay($prepay_data, WX_KEY);
        
        echo "<form style='display:none;' id='form1' name='form1' method='post' action='".U('Pay/index')."'><input name='apiParam' type='text' value='".base64_encode($prepay_data_str)."' /></form><script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";
    }
    
    /**
     * 微信支付
     */
    public function alipay() {
        $user_row = $this->getUser();
    
        $p_orderid = I('get.id', '0', 'intval');
        if (!$p_orderid) {
            $this->error('请选择要支付的订单！');
        }
    
        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');
        $M_orders_detail = M('orders_detail');
    
        $order_row = $M_orders->where(array('id'=>$p_orderid, 'userid'=>$user_row['id']))->find();
        if (!$order_row) {
            $this->error('该订单不存在或已被删除！');
        }
    
        /*vendor('aop.AopClient');
        $aop = new \AopClient();
        
        var_dump($aop);*/
        
        $url = 'https://openapi.alipay.com/gateway.do?';
        
        $biz_content = "{'body':'描述', 'subject':'标题', 'out_trade_no':'', 'total_amount':'', 'product_code':''}";
        
        $conf_array = array(
            'app_id' => '2017011205014981',
            'method' => 'alipay.trade.wap.pay',
            'return_url' => WX_URL . U('Orders/send'),
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'notify_url' => WX_URL . U('Index/ali_paynotify'),
            'biz_content' => $biz_content,
        );
        
        ksort($conf_array);
        
        $params_str = '';
        foreach ($conf_array as $k=>$v) {
            $params_str .= $k.'='.$v.'&';
        }
        
        $params_str = trim($params_str, '&');
        
        $priKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCqRkxFcvpc1AVhIOK7Dbi4QRD94TOaUioodjYOufE5fPVEaA3XGbS4nstbqFPzNGuwnE5CbM1m2hxBxwqTAZQexv3E28JkMucoM37w/r5ykjcpA1jfNw+MRFI6z4o0Lo6QiFbfmNj4UZ3zGPqzG7FQUedHwGq1BG0a1qy9YZcB4zdqSdWwfy4jaWxZ5HqFMpU8znl41EyBkFto7pZIGdpF41xd61sNL8fHgp8CGmA4ozhe9h984uIiu7FC2N22VRcUvKJin4hStQvyZ9GgveI1yCfK2L+54iTVV3VOjUrNK5IaObjVezY7/yVgF0S8tM/FQY8g5Wac/r9JS1iD1OzPAgMBAAECggEAWeykTS0qwamLab9HXoghnNVoFtw/N2nbL+E2LVLP7Y9rrIPTg1zlD3d1aGOealut3+i8f+IcHAxmTerDT93Oju6CiNuSbv5fC+EeUkIG7FUB4pwM7F2UzXwrfTmsAGDyE6ydTJrZQ1M/OmpfxAWFACkxHrik4hKDWGA1fBK1AF5VhVMqANyTp/bn6g5DqfoDnayKqll1ldEQAbWH1FUyonJtGuNZ3tP6PfmomXTyBRKuybHJ+mfBzCQFeVMjJUXXyYNcaZLp6IMPY1KibkLiJjnv/4rYDOVZ1XOyW0eJKOupzRmhM4DdkF1/Yr7X7C8BvjTPKouygpmBbqVdm723+QKBgQDWQAtcs9PpvHp4f5b8pjYJ70NQnI0LD33prW1n1T1J8UhrTeQzR2dDf1peuBe/wcn0yWKfzrj1nXlTTYPjiwRo2Okx4O2VSiPrLpSlptN+cJWtZaI32S5xpAfn8xobHm0KFmy+t2HPHt4W5dET8qJZozvc7fT9hekpfMjgpfn/tQKBgQDLdIO5Oa+Pi+O32kld0+EdE0XaGnl6csUZt0Mv/Pf7mr0DYaMxjKdlC/zMxROTd+5HSYGhmWV4uMjYcvbYkkCSEpbii9VSva16v3ItDpBz5reqQwy+XLsThqerAot+ysc9MZTwYBrjlIuH6VSwrpCpNEAiDbLi1ZDp/fTpiuLk8wKBgQCnMa1sN21M/ue39yGVogOTtyKZ2sF6eylwXQV+vPqqJF+2VT+bAMLF+rFEL3dhDlKS80FCoqjWC4CtkFU3EbpsM7GvWe8tzIDBPEWGC+0Jw8QaX7C60oIa7r24tY6O0SyYba2JG0R5xIXd6pRBc9xcBTVnEE9aNNEirh14qi2w7QKBgGcXiwd4K3VZNPIcMcDRc+f/tA1oDNX3Adid+/V9AUsEhXIJL2AUHV4eTOKVme3Hf32vyXfQ+pHJUDwdROdIdF+P+9SfdmCOrDDegURDdP7FkZs8jmCHLGQgfyUwDO8aYQqo7QdAK1/WDT25BajhS3vxde3LczFtdhibDlaogX/xAoGAA3SsLr5If37pL23suBHOyCs2vznhiK1GNVVM2B6AFcmJRgtz8v11tC82bumTtkykOt/6wrRhHbn5jOomPiZNFqW6BAeeUKyDcI95qTZSJgf8X41Ky98/n0mLczBg9EcCpjLnl9YvLMaI6ANvYGNIdwTRn8vX8kWFG2bBgjviYks=';
        
        
        $sign = $this->encrypt($params_str);
        
        $sign = urlencode($sign);
        
        $orderInfor = $params_str."&sign=".$sign."&sign_type=RSA2";
        redirect($url.$orderInfor);
    }
    
    /**
     * 加密方法
     * @param string $str
     * @return string
     */
    private function encrypt($str,$screct_key){
        //AES, 128 模式加密数据 CBC
        $screct_key = base64_decode($screct_key);
        $str = trim($str);
        $str = $this->addPKCS7Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
        return base64_encode($encrypt_str);
    }
    
    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    private function decrypt($str,$screct_key){
        //AES, 128 模式加密数据 CBC
        $str = base64_decode($str);
        $screct_key = base64_decode($screct_key);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
        $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC);
        $encrypt_str = trim($encrypt_str);
    
        $encrypt_str = stripPKSC7Padding($encrypt_str);
        return $encrypt_str;
    
    }
    
    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    private function addPKCS7Padding($source){
        $source = trim($source);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    
        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }
    /**
     * 移去填充算法
     * @param string $source
     * @return string
     */
    private function stripPKSC7Padding($source){
        $source = trim($source);
        $char = substr($source, -1);
        $num = ord($char);
        if($num==62)return $source;
        $source = substr($source,0,-$num);
        return $source;
    }
    
    /**
     * 下单
     */
    public function order() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        if (IS_POST) {
            // 购物车下单
            $p_orderIds = I('post.orderIds', '');
            
            // 立即购买
            $p_productid = I('post.productid', '0', 'intval');
            $p_num = I('post.num', '0', 'intval');
            
            $M_shopcart = M('shopcart');
            $M_products = M('products');
            $M_orders = M('orders');
            $M_orders_buy = M('orders_buy');
            $M_orders_detail = M('orders_detail');
            
            // 收货信息
            $p_uname = I('post.uname');
            $p_uphone = I('post.uphone');
            $p_uaddr = I('post.uaddr');
            
            // 发票信息
            $p_isinvoice = I('post.isinvoice', '0', 'intval');
            $invoice = '';
            if ($p_isinvoice) {
                $invoiceemail = I('post.email', '');
                $invoice = I('post.invoice', '');
            }
            
            // 订单编号
            $order_num = date('YmdHis') . rand(100000,999999);
            $total_money = 0;
            $total_corn = 0;
            
            // 订单id
            $insert_id = 0;
            
            if ($p_orderIds) {
                // 购物车
                $cart_arr = explode(',', $p_orderIds);
                
                // 添加订单记录
                $insert_id = $M_orders->add(array(
                    'ordernum' => $order_num,
                    'userid' => $user_row['id'],
                    'money' => 0,
                    'corn' => 0,
                    'orderstatus' => 1,
                    'invoice' => $invoice,
                    'invoiceemail' => $invoiceemail,
                    'addtime' => time(),
                ));
                
                // 商品记录
                $product_items = array();
                
                // 删除购物车
                $delcart_arr = array();
                
                foreach ($cart_arr as $cart_k=>$cart_v) {
                    if (! intval($cart_v)) continue;
                    // 查询购物车是否存在
                    $exists = $M_shopcart->where(array('id'=>$cart_v, 'uid'=>$user_row['id']))->find();
                    if ($exists) {
                        $prod_row = $M_products->where('id='.$exists['productid'])->field('id,title,price')->find();
                        $delcart_arr[] = $cart_v;
                        $product_items[] = array(
                            'ordernum' => $order_num,
                            'productid' => $prod_row['id'],
                            'title' => $prod_row['title'],
                            'num' => $exists['num'],
                            'price' => $prod_row['price']
                        );
                        
                        $total_corn += 2000 * $exists['num'];
                        
                        $total_money += $prod_row['price'] * $exists['num'];
                    }
                }
                // 添加商品记录
                $M_orders_buy->addAll($product_items);
                
                
                
                // 删除购物车
                $delcart_str = implode(',', $delcart_arr);
                $M_shopcart->where('id IN (' . $delcart_str . ')')->delete();
            } else if($p_productid AND $p_num) {
                // 立即购买
                
                // 添加订单记录
                $insert_id = $M_orders->add(array(
                    'ordernum' => $order_num,
                    'userid' => $user_row['id'],
                    'money' => 0,
                    'corn' => 0,
                    'orderstatus' => 1,
                    'invoice' => $invoice,
                    'invoiceemail' => $invoiceemail,
                    'addtime' => time(),
                ));
                
                // 查询商品
                $prod_row = $M_products->where(array('id'=>$p_productid))->field('id,title,price')->find();
                
                // 添加商品记录
                $M_orders_buy->add(array(
                    'ordernum' => $order_num,
                    'productid' => $prod_row['id'],
                    'title' => $prod_row['title'],
                    'num' => $p_num,
                    'price' => $prod_row['price'],
                ));
                
                // 总金额
                $total_corn += 2000 * $p_num;
                $total_money += $prod_row['price'] * $p_num;
            } else {
                $this->error('请选择下单商品！');
            }
            
            // 更新价格
            $M_orders->where('id=' . $insert_id)->save(array('money'=>$total_money, 'corn'=>$total_corn));
            
            // 记录收货地址
            $M_orders_detail->add(array(
                'ordernum' => $order_num,
                'uname' => $p_uname,
                'uphone' => $p_uphone,
                'uaddr' => $p_uaddr,
            ));
            
            // 跳转
            $this->redirect('pay', array('id'=>$insert_id));
        }
    }
    
    
    /**
     * 购物车
     */
    public function shopcart() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        
        $M_products = M('products');
        
        $M_shopcart = M('shopcart');
        $cart_rows = $M_shopcart->where('uid='.$user_row['id'])->order('addtime DESC')->select();
        
        foreach ($cart_rows as $cart_rows_k=>$cart_rows_v) {
            $cart_rows[$cart_rows_k]['detail'] = $M_products->field('id,titlepic,title,price')->where('id='.$cart_rows_v['productid'])->find();
        }
        $this->cart_rows = $cart_rows;
        
        $this->display();
    }

	/**
     * ajax购物车数量
     */
    public function ajaxshopcart() {
		if (IS_AJAX) {
			$user_row = $this->getUser();
			if (!$user_row) $this->redirect('User/index');
			
			$p_id = I('post.id', '0', 'intval');
			$p_num = I('post.num', '0', 'intval');

			if (!$p_id OR !$p_num) $this->ajaxreturn(array('code'=>'-1'));

			$M_shopcart = M('shopcart');
			$updated = $M_shopcart->where(array('uid'=>$user_row['id'], 'id'=>$p_id))->save(array('num'=>$p_num));
			if ($updated) {
				$this->ajaxreturn(array('code'=>'1'));
			} else {
				$this->ajaxreturn(array('code'=>'-1'));
			}
		}
    }
    
    /**
     * 删除购物车
     */
    public function delcart() {
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        // 获取参数
        $p_id = I('get.id', '0', 'intval');

        if (!$p_id) {
            $this->error('提交参数有误！');
        }

        // 检验购物车
        $M_shopcart = M('shopcart');
        
        $cart_row = $M_shopcart->where(array('id'=>$p_id, 'uid'=>$user_row['id']))->find();
        if (!$cart_row) {
            $this->error('该记录不存在或无权操作！');
        }
        
        // 删除购物车
        $deleted = $M_shopcart->where(array('id'=>$p_id, 'uid'=>$user_row['id']))->delete();
        if ($deleted) {
            $this->redirect('shopcart');
        } else {
            $this->error('删除失败！');
        }
    }
    
    /**
     * 获取用户
     */
    private function getUser() {
        /*$c_phone = cookie('u_phone');
    
        $M_user = M('user');
    
        $user_row = $M_user->where(array('phone'=>$c_phone))->find();*/
    
        return getuscont();
    }
    
    /**
     * 统一下单
     */
    private function doUnionOrder($params=null) {
        $M_uniorder = M('uniorder');
        
        $prepay_order_row = $M_uniorder->where(array('out_trade_no'=>$params['orderNo']))->find();
        
        if (!$prepay_order_row || strtotime($prepay_order_row['time_expire']) < time()) {
            // 重新下单
            $order_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        
            // 订单起止时间
            $time_start = date('YmdHis', time());
            $time_expire = date('YmdHis', time()+300);
            
            $order_data = array(
                'appid' => WX_APPID,
                'mch_id' => WX_MID,
                'nonce_str' => $this->getNonceStr(32),
                'sign_type' => 'MD5',
                'body' => $params['body'],
                //'attach' => $payid,
                'out_trade_no' => $params['orderNo'],
                'total_fee' => $params['money'] * 100,
                'spbill_create_ip' => get_client_ip(),
                'time_start' => $time_start,
                'time_expire' => $time_expire,
                'notify_url' => WX_URL . U('Index/wx_paynotify'),
                'trade_type' => 'JSAPI',
                'openid' => $params['openid'],
            );
        
            // 下单xml
            $order_xml = $this->genOrderXml($order_data, WX_KEY);
        
            $order_result = $this->postXmlCurl($order_url, $order_xml);
        
            $order_data_arr = (array)simplexml_load_string($order_result, 'SimpleXMLElement', LIBXML_NOCDATA);
        
            if ($order_data_arr['return_code']=='SUCCESS' AND $order_data_arr['result_code']=='SUCCESS') {
                // 下单成功
        
                $prepay_order_row['time_expire'] = $time_expire;
                $prepay_order_row['prepay_id'] = $order_data_arr['prepay_id'];
                $prepay_order_row['addtime'] = time();
        
                if (key_exists('out_trade_no', $prepay_order_row)) {
                    // 更新订单
                    $M_uniorder->save($prepay_order_row);
                } else {
                    //添加订单
                    $prepay_order_row['out_trade_no'] = $params['orderNo'];
                    $M_uniorder->add($prepay_order_row);
                }
        
                // 返回订单信息
                return $order_data_arr['prepay_id'];
            } else {
                // 下单失败
                $this->error('微信下单失败！' . $order_data_arr['err_code_des'], U('Orders/index'));
            }
        } else {
            // 返回原订单
            return $prepay_order_row['prepay_id'];
        }
    }
    
    /**
     * curl post
     */
    private function postXmlCurl($url, $xml, $second = 30) {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            	
            return -1;
        }
    }
    
    /**
     * 生成下单信息
     */
    private function genOrderXml($order_data=null, $key='') {
        if (!$order_data OR !is_array($order_data) OR !$key) return false;
    
        ksort($order_data);
    
        $order_data_str ='';
    
        foreach ($order_data as $order_data_k=>$order_data_v) {
            if ($order_data_k!='sign' && $order_data_k!='') {
                $order_data_str .= $order_data_k . '=' . $order_data_v . '&';
            }
        }
    
        $order_data_str .= 'key=' . $key;
    
        // 生成sign
        $order_data['sign'] = strtoupper(md5($order_data_str));
    
        // 整合xml
        $order_data_xml = '<xml>';
        foreach ($order_data as $order_data_k2=>$order_data_v2) {
            if (is_numeric($order_data_v2)){
                $order_data_xml .= "<".$order_data_k2.">".$order_data_v2."</".$order_data_k2.">";
            }else{
                $order_data_xml .= "<".$order_data_k2."><![CDATA[".$order_data_v2."]]></".$order_data_k2.">";
            }
        }
    
        return $order_data_xml .= '</xml>';
    }
    
    /**
     * 生成预支付信息
     */
    private function genPrepay($prepay_data=null, $key='') {
        if (!$prepay_data OR !is_array($prepay_data) OR !$key) return false;
    
        ksort($prepay_data);
    
        $prepay_data_str ='';
    
        foreach ($prepay_data as $prepay_data_k=>$prepay_data_v) {
            if ($prepay_data_k != 'paySign' && $prepay_data_k != '') {
                $prepay_data_str .= $prepay_data_k.'='.$prepay_data_v.'&';
            }
        }
    
        $prepay_data_str .= 'key=' . $key;
    
        // 生成paySign
        $prepay_data['paySign'] = strtoupper(md5($prepay_data_str));
    
        // 整合字符串
        $return_str = '';
        foreach ($prepay_data as $prepay_data_k2=>$prepay_data_v2) {
            $return_str .= '"'.$prepay_data_k2.'" : "'.$prepay_data_v2.'",';
        }
    
        return $return_str;
    }
    
    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    private function getNonceStr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
    
    /**
     * 生成ID
     */
    private function genID() {
        $uuid = '';
        //a6872afd-4328-4d77-a4fd-36fffdd2da48
    
        $md5 = md5(time().rand(10000, 99999));
        $uuid .= substr($md5, 1, 8);
        $uuid .= '-';
        $uuid .= substr($md5, 9, 4);
        $uuid .= '-';
        $uuid .= substr($md5, 14, 4);
        $uuid .= '-';
        $uuid .= substr($md5, 19, 4);
        $uuid .= '-';
        $uuid .= substr($md5, 20, 12);
    
        return $uuid;
    }


    //购物车新
    public function newsgwc(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        $list=M('shopcart')->alias('s')->field('s.id as sid,s.addtime as stime,s.*,p.id,p.title,p.titlepic,p.price,p.tejia,p.tejiaprice,p.type')->join('__PRODUCTS__ p ON p.id = s.productid','LEFT')->where(array('s.uid'=>$user_row['id']))->order(array('s.id'=>'desc'))->select();
        $hjjgsum=0;
        foreach ($list as $kj => $lsval) {
            switch ($lsval['tejia']) {
                case 2:
                case 5:
                case 6:
                    if($lsval['tejiaprice'] > 0){
                       $list[$kj]['jiage']= $lsval['tejiaprice'];
                    }else{
                        $list[$kj]['jiage']= $lsval['price'];
                    }
                    break;
                
                default:
                    $list[$kj]['jiage']= $lsval['price'];
                    break;
            }
            $hjjgsum+=$list[$kj]['jiage'];
        }
        
        $this->assign('shopsum',count($list));

        $this->assign('hjjgsum',$hjjgsum);
        $this->assign('list',$list);

        $this->display();
    }
    //计算商品总价
    public function jsgwczj(){
        $spid=I('post.spid')?I('post.spid'):0;
        if($spid == 0){
            echo json_encode(array('code'=>0,'msg'=>'没有获取到商品！','sum'=>0));
            return;
        }
        $zj=0;
        foreach ($spid as $valspid) {
            $zj+=shoppiceqr($valspid);
        }
        echo json_encode(array('code'=>1,'msg'=>'获取成功','sum'=>$zj));
    }

    /*
    *去结算
     */
    public function gojiesuan(){
        $uid=I('post.uid','0','intval');
        $ordcont=I('post.ordcont')?I('post.ordcont'):0;
        if($uid <= 0){
          echo json_encode(array('code'=>2,'msg'=>'请登陆'));
          return false;
        }
        if(cookie('u_idval') != $uid){
            echo json_encode(array('code'=>2,'msg'=>'请登陆'));
            return false;
        }
        if($ordcont == 0){
            echo json_encode(array('code'=>0,'msg'=>'下单失败！请重新下单！'));
            return false;
        }
        foreach ($ordcont as $key => $spary) {
            $sparyk=explode('|',$spary);
            $savegwc=M('shopcart')->where(array('id'=>$sparyk[0]))->save(array('num'=>$sparyk[1]));
        }
        echo json_encode(array('code'=>1,'msg'=>'购物车更新完成！'));
        return false;
    }

    /*
    *购物车确认订单
     */
    public function addorderxd(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $uid=I('post.uid','0','intval');
        $ordcont=I('post.ordcont')?I('post.ordcont'):0;
        if($uid <= 0){
          echo json_encode(array('code'=>2,'msg'=>'请登陆'));
          return false;
        }
        if(cookie('u_idval') != $uid){
            echo json_encode(array('code'=>2,'msg'=>'请登陆'));
            return false;
        }
        if($ordcont == 0){
            echo json_encode(array('code'=>0,'msg'=>'下单失败！请重新下单！'));
            return false;
        }

        $ordernum=ordernumndle();



        $jb=0;
        foreach ($ordcont as $key => $spary) {
            $sparyk=explode('|',$spary);
            $jb+=self::writeshopord($ordernum,$sparyk[0],$sparyk[1],$uid);
        }
        $tgly=$fhf=jumptglaiyuan();
        

        $ordaddcont=array(
            'ordernum'              =>              $ordernum,
            'userid'                =>              $uid,
            'money'                 =>              $jb,
            'orderstatus'           =>              1,
            'addtime'               =>              time(),
            'code_jxs'              =>              $tgly,
            'fahuofang'             =>              $fhf,
            //'fpsj'                  =>              time(),
            //'fhsetval'              =>              1
        );
        $adorid=M('orders')->add($ordaddcont);
        if($adorid){
            $lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'创建订单';
            $odlc=ordliucheng($adorid,$lcnr);
            echo json_encode(array('code'=>1,'msg'=>'订单生成成功！','ordnum'=>$ordernum,'jb'=>$jb));
        }else{
            $kj=self::deltordshop($ordernum);
            echo json_encode(array('code'=>0,'msg'=>'订单生成失败！','jb'=>$jb));
        }
        

    }
    /*
    *写入购买商品
     */
    static function writeshopord($ordnu=0,$gwcid=0,$num=1,$suid=0){
        if($gwcid == 0){
            return 0;
        }else{
            $gwclok=M('shopcart')->alias('s')->field('s.id as sid,s.*,p.*')->join('__PRODUCTS__ p ON p.id = s.productid','LEFT')->where(array('s.id'=>$gwcid,'s.uid'=>$suid))->find();
            $xsjage=shoppiceqr($gwclok['id']);
            $addorsbuy=array(
                'ordernum'              =>              $ordnu,
                'productid'             =>              $gwclok['productid'],
                'title'                 =>              $gwclok['title'],
                'num'                   =>              $num,
                'price'                 =>              $xsjage,
                'tejiaprice'            =>              $gwclok['tejiaprice'],
                'spyuanjia'             =>              $gwclok['price'],
                'shuxing'               =>              $gwclok['shuxing']
            );
            $adbuy=M('orders_buy')->add($addorsbuy);
            $sccz=M('shopcart')->where(array('id'=>$gwclok['sid']))->delete();
            return $xsjage*$num;
        }
    }
    /*
    *失败删除订单商品
     */
    static function deltordshop($ordnum){
        $sccz=M('orders_buy')->where(array('ordernum'=>$ordnum))->delete();
    }
    /*
    *订单结算页面
     */
    public function jiesuan(){
        $ordnum=I('get.ordnum')?I('get.ordnum'):0;

        $addid=I('get.addid')?I('get.addid'):0;


        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        if($ordnum <= 0){
            $this->redirect('Mall/index');
        }

        if($addid != 0){
            $mymrads=M('usaddress')->where(array('id'=>$addid))->find();
            //echo 1;
        }else{
            $mymrads=M('orders_detail')->where(array('ordernum'=>$ordnum))->find();
            if($mymrads){
                $mymrads=array(
                    'sheng'                     =>                $mymrads['sheng'],
                    'shi'                       =>                $mymrads['shi'],
                    'xian'                      =>                $mymrads['xian'],
                    'addresval'                 =>                $mymrads['uaddr'],
                    'name'                      =>                $mymrads['uname'],
                    'shtel'                     =>                $mymrads['uphone']
                );
                //echo 2;
            }else{
                $mymrads=M('usaddress')->where(array('setmr'=>1,'uid'=>$user_row['id']))->find();
                //echo 3;
            }
        }
        if($mymrads){
            $this->assign('myads',$mymrads['id']);
        }else{
            $this->assign('myads',0);
        }
        $shoplist=M('orders_buy')->alias('o')->field('o.*,p.id,p.titlepic')->join('__PRODUCTS__ p ON p.id=o.productid','LEFT')->where(array('o.ordernum'=>$ordnum))->select();
        $sumjiage=0;
        foreach ($shoplist as $vajg) {
            $sumjiage+=$vajg['num']*$vajg['price'];
        }
        $this->assign('list',$shoplist);
        $this->assign('sumval',count($shoplist));
        $this->assign('sumjiage',$sumjiage);
        $this->assign('ordnum',$ordnum);

        $this->display();
    }
    //确认收货
    public function qrshcz(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $id=I('get.id','0','intval');
        if($id){

            $M_orders=M('orders');

            $order_row = $M_orders->where(array('id'=>$id, 'userid'=>$user_row['id'], 'orderstatus'=>3))->find();
            if (!$order_row) {
                $this->error('该订单不存在或已被删除！');
            }
            
            $order_row['orderstatus'] = 4;
            
            
            $updated = $M_orders->save($order_row);
            if ($updated) {
                self::sjopzjxl($order_row['ordernum']);


                $lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'确认收货！';
                $odlc=ordliucheng($id,$lcnr);


                $this->success('确认收货成功！', U('Orders/pingjia'));
            } else {
                $this->error('确认收货失败！');
            }
        }else{
            $this->error('该订单不存在或已被删除！');
        }
    }
    /*
    *增加销量
     */
    static function sjopzjxl($ordnum){
        $splist=M('orders_buy')->field('id,ordernum,productid,num')->where(array('ordernum'=>$ordnum))->select();
        foreach ($splist as $key => $valsop) {
            $spmzj=M('products')->where(array('id'=>$valsop['productid']))->setInc('salenum',$valsop['num']); 
        }
    }
    //评价
    public function pingjia(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');

        $M_orders = M('orders');
        $M_orders_buy = M('orders_buy');

        $order_rows = $M_orders->where(array('userid'=>$user_row['id'], 'orderstatus'=>4, 'deled'=>0))->order('id DESC')->select();
        foreach ($order_rows as $key => $valshop) {
            $order_rows[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
        }
        //var_dump($order_rows);
        $this->assign('order_rows',$order_rows);
        $this->display();
    }
    //我的收藏
    public function myshoucang(){
        $user_row = $this->getUser();
        if (!$user_row) $this->redirect('User/index');
        $list=M('mysc')->field('sc_id,sc_uid,sc_proid,sc_time,id,title,titlepic,price,tejiaprice,tejia,shuxing,type,salenum')->join('__PRODUCTS__ ON __PRODUCTS__.id=__MYSC__.sc_proid','LEFT')->where(array('sc_uid'=>$user_row['id']))->order(array('sc_id'=>'desc'))->select();
        $this->assign('list',$list);
        $this->display();
    }
    //删除我的收藏
    public function deltsc(){
        $id=I('get.id');
        if($id){
            if(M('mysc')->where(array('sc_id'=>$id))->delete()){
                $this->success('删除成功！', U('Orders/myshoucang'));
            }else{
                $this->error('删除失败！请重新提交！');
            }
        }else{
            $this->error('参数错误！请重新提交！');
        }
    }
    /*
    *删除购物
     */
    public function deltgwc(){
        $id=I('post.id');
        if(M('shopcart')->where(array('id'=>$id))->delete()){
            echo json_encode(array('code'=>1,'msg'=>'删除成功！'));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'删除失败！'));
        }
    }
}