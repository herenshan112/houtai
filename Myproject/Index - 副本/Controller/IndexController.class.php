<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    /**
     * 首页
     */
    public function index() {
        // 15天自动确认收货
        $M_orders = M('orders');
        $M_orders->where("orderstatus=3 AND fhtime < '".date('Y-m-d H:i:s', strtotime("-15 day"))."'")->save(array('orderstatus'=>'4'));
        
        // 新闻推荐
        $M_news = M('news');
        $index_news = $M_news->where(array('tuijian'=>1, 'pid'=>'1'))->order('addtime DESC')->limit(10)->select();
        
        foreach ($index_news as $index_news_k=>$index_news_v) {
            $index_news[$index_news_k]['catename'] = Util::cateid2name($index_news_v['cateid']);
        }
        $this->index_news = $index_news;
        
        $this->display();
    }
    
    /**
     * 查询快递详情
     */
    public function express($id=0) {
        if (!$id) $this->redirect('index');
    }
    
    /**
     * 微信授权回调
     */
    public function wx_oauth() {
        $p_code = I('get.code');
        $p_phone = I('get.state');
        if (!$p_code OR !$p_phone) {
            $this->error('异常访问！', 'index');
        }
        
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . WX_APPID;
        $url .= '&secret=' . WX_APPSECRET;
        $url .= '&code=' . $p_code;
        $url .= '&grant_type=authorization_code';
        
        $wx_data = Util::curlGet($url);
        if (!$wx_data) {
            $this->error('微信授权出错！', 'index');
        }
        $jsonObj = json_decode($wx_data);
        
        $openid = $jsonObj->openid;
        
        // 更新openid
        $M_user = M('user');
        $user_row = $M_user->where(array('phone'=>$p_phone))->field('id, openid')->find();
        if (!$user_row) {
            $this->error('微信授权异常，用户不存在！', 'index');
        }
        // 更新openid
        $user_row['openid'] = $openid;
        $M_user->save($user_row);
        
        // 更新cookie
        cookie('u_wxauth', 1);
        
        $this->redirect('UserInfo/index');
    }
    
    /**
     * 微信授权回调
     */
    public function wx_oauth_sales() {
        $p_code = I('get.code');
        $p_phone = I('get.state');
        if (!$p_code OR !$p_phone) {
            $this->error('异常访问！', 'index');
        }
    
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . WX_APPID;
        $url .= '&secret=' . WX_APPSECRET;
        $url .= '&code=' . $p_code;
        $url .= '&grant_type=authorization_code';
    
        $wx_data = Util::curlGet($url);
        if (!$wx_data) {
            $this->error('微信授权出错！', 'index');
        }
        $jsonObj = json_decode($wx_data);
    
        $openid = $jsonObj->openid;
    
        // 更新openid
        $M_salesman = M('salesman');
        $salesman_row = $M_salesman->where(array('phone'=>$p_phone))->field('id, openid')->find();
        
        if (!$salesman_row) {
            $this->error('微信授权异常，业务员不存在！', 'index');
        }
        // 更新openid
        $salesman_row['openid'] = $openid;
        $M_salesman->save($salesman_row);
    
        // 更新cookie
        cookie('sales_wxauth', 1);
    
        $this->redirect('Salesman/info');
    }
    
    /**
     * 微信支付回调
     */
    public function wx_paynotify() {
        ob_clean();
        $backdata = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notifyresult = array();
        $notifyresult = (array)simplexml_load_string($backdata, 'SimpleXMLElement', LIBXML_NOCDATA);
         
        $successxmlData = "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
        $failedxmlData = "<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>";
        
        // debug notify message
        $test_str = '';
        foreach ($notifyresult as $key => $value) {
            $test_str .= $key.'='.$value.'  -  ';
        }
        $test['content'] = $test_str;
        $test['addtime'] = date('Y-m-d H:i:s');
        M('test_data')->add($test);
        
        // 查询订单状态
        $M_orders = M('orders');
        $M_wxpaylog = M('wxpaylog');
        
        // 支付成功,更新订单信息
        if ($notifyresult['return_code']=='SUCCESS') {
            $order_row = $M_orders->where(array('ordernum'=>$notifyresult['out_trade_no']))->find();
        
            // 记录支付日志
            $M_wxpaylog->add(array(
                'out_trade_no' => $notifyresult['out_trade_no'],
                'transaction_id' => $notifyresult['transaction_id'],
                'time_end' => $notifyresult['time_end'],
                'addtime' => date('Y-m-d H:i:s'),
            ));
        
            // 订单不存在
            if (!$order_row) {
                echo $failedxmlData;
                exit;
            }
        
            // 已支付
            if ($order_row['orderstatus'] != 1) {
                echo $successxmlData;
                exit;
            }
        
            // 更新订单状态
            if ($order_row['orderstatus'] == 1) {
                $order_update_data = array(
                    'orderstatus' => 2,
                    'transaction_id' => $notifyresult['transaction_id'],
                    'time_end' => $notifyresult['time_end'],
                    'total_fee' => $notifyresult['total_fee'],
                    'paytype' => 1,     // 1支付方式微信支付
                );
                $updated_order = $M_orders->where(array('ordernum'=>$notifyresult['out_trade_no']))->save($order_update_data);
                if ($updated_order) {
                    // 增加销量
                    $M_orders_buy = M('orders_buy');
                    $M_products = M('products');
                    
                    $buys = $M_orders_buy->where('ordernum='.$order_row['ordernum'])->field('productid,num')->select();
                    
                    // 购买总量
                    $total_buy_num = 0;
                    
                    foreach ($buys as $buys_k=>$buys_v) {
                        $total_buy_num += $buys_v['num'];
                        $M_products->where('id='.$buys_v['productid'])->setInc('salenum', $buys_v['num']);
                    }
                    

                    // 添加购买奖励积分
                    $M_corn = M('corn');
                    $M_user = M('user');
                    $user_row = $M_user->where('id='.$order_row['userid'])->find();
                    
                    if ($user_row['cateid']=='1') {
                        // 更新自己积分
                        $tmp_corn = $M_corn->where('uid=' . $user_row['id'])->find();
                        if ($tmp_corn) {
                            // 更新
                            $M_corn->where('uid=' . $user_row['id'])->save(array('corn'=> ($tmp_corn['corn']+$total_buy_num*200) ));
                        } else {
                            // 新建
                            $M_corn->add(array('corn'=> ($tmp_corn['corn']+$total_buy_num*200), 'uid'=>$user_row['id'], 'cateid'=>'1'));
                        }
                        
                        
                        // 更新推荐人积分
                        $M_recommend = M('recommend');
                        $rec_row = $M_recommend->where('typeid=1 AND uid='.$user_row['id'])->find();
                        if ($rec_row['cateid']=='1') {
                            // 会员 推荐 会员, 更新积分
                            $tmp_corn2 = $M_corn->where('uid=' . $rec_row['opid'])->find();
                            if ($tmp_corn2) {
                                $M_corn->where('uid=' . $rec_row['opid'])->save(array('corn'=> ($tmp_corn2['corn'] + $total_buy_num*100) ));
                            } else {
                                $M_corn->add(array('corn'=> ($tmp_corn2['corn'] + $total_buy_num*100), 'uid'=>$rec_row['opid'], 'cateid'=>'1'));
                            }
                            // $M_corn->where('uid=' . $rec_row['opid'])->setInc('corn', $total_buy_num*100);
                        }
                    }
                    
                    
                    echo $successxmlData;
                } else {
                    echo $failedxmlData;
                }
                exit;
            }
        }
    }
    
    /**
     * 获取用户
     */
    private function getUser() {
        $c_phone = cookie('u_phone');
        $c_pass = cookie('u_pass');
        $ck_phone = Util::authcode($c_pass, 'DECODE');
    
        if (!$c_phone OR $c_phone != $ck_phone) {
            cookie('u_phone', null);
            cookie('u_pass', null);
    
            $this->error('请登录后再操作！', U('User/login'));
        }
    
        $M_user = M('user');
    
        $user_row = $M_user->where(array('phone'=>$c_phone))->find();
    
        return $user_row;
    }

    /**
     * 生成二维码
     */
    public function qrcode() {
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel = "L";
        $matrixPointSize = "10";
        
        $p_code = I('get.code');
        $p_op = I('get.op');
        if ($p_op=='1' OR $p_op=='2') {
            $url = U('User/reg', array('code'=>$p_code));
        }
        
        $url = WX_URL . $url;
        
        \QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
    }

	/*
	 * 获取头像
	 */
	public function headimg() {
		$user_row = $this->getUser();
		if(substr($user_row['headpic'], 0, 4)=='http') {
			// 更新图片为本地
			$imgUrl = substr($user_row['headpic'], 0, -1) . '96';
			$headimg_data = file_get_contents($imgUrl);
		
			if ($headimg_data) {
				$file_name = 'Public/headimg/'.time().'_'.rand(10000,99999).'.jpg';
				$saved_headimg = file_put_contents($file_name, $headimg_data);
				if ($saved_headimg) {
					$user_row['headpic'] = '/' . $file_name;
					M('user')->save($user_row);
				}
			}
		}
	}
    
    /**
     * 关注公众号
     */
    public function subscribe() {
        $this->display();
    }
    
    /**
     * 微信消息认证
     */
    private function wxMsgAuth() {
        $p_signature = I('get.signature');
        $p_timestamp = I('get.timestamp');
        $p_nonce = I('get.nonce');
        $p_echostr = I('get.echostr');
        
        $token = 'd3019b8a3c92fb94af49deb9acecad1d';
        $tmpArr = array($token, $p_timestamp, $p_nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $p_signature) {
            echo $p_echostr;
        } else {
            return false;
        }
    }
    
    /**
     * 微信消息
     */
    public function wxmsg() {
        ob_clean();
        $backdata = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notifyresult = array();
        $notifyresult = (array)simplexml_load_string($backdata, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        // 关注事件
        if ($notifyresult['Event']=='subscribe') {
            // openid
            $openid = $notifyresult['FromUserName'];
            
            // 回复文本消息
            $returnTextXmlArr = array(
                'ToUserName' => $openid,
                'FromUserName' => 'gh_fb976c7dc7b4',
                'CreateTime' => time(),
                'MsgType' => 'text',
                'Content' => '感谢您关注爱乐云健康微信平台'
            );

			// 回复图文消息
			$returnXmlArr = array(
				'ToUserName' => $openid,
				'FromUserName' => 'gh_fb976c7dc7b4',
				'CreateTime' => time(),
				'MsgType' => 'news',
				'ArticleCount' => '1',
				'Articles' => array(
					array(
						'Title' => '感谢您关注爱乐云健康平台',
						'Description' => '没错！您关注了爱乐云健康平台，我们已经等君好久了，您终于回来了！在这里，您还会了解到很多最新最全的益生菌和疾病保健知识，相信我们一定不会让您失望滴！有关益生菌产品订货及产品咨询请拨打热线电话：400-680-9980（周一至周五8:00到16:30）',
						'PicUrl' => '',
						'Url' => WX_URL . U('UserInfo/index'),
					),
				)
			);
            
            $returnXmlData = '<xml>';
            foreach ($returnXmlArr as $rxa_k=>$rxa_v) {
				if (!$rxa_v) {

				} else if ($rxa_k=='CreateTime' OR $rxa_k=='ArticleCount') {
                    $returnXmlData .= '<'.$rxa_k.'>'.$rxa_v.'</'.$rxa_k.'>';
                } else if($rxa_k=='Articles') {
					$returnXmlData .= '<Articles>';
					if (is_array($rxa_v)) {
						foreach ($rxa_v as $rxa_2k=>$rxa_2v) {
							$returnXmlData .= '<item>';
							foreach ($rxa_2v as $rxa_3k=>$rxa_3v) {
								$returnXmlData .= '<'.$rxa_3k.'><![CDATA['.$rxa_3v.']]></'.$rxa_3k.'>';
							}
							$returnXmlData .= '</item>';
						}
					}
					$returnXmlData .= '<Articles>';
				} else {
                    $returnXmlData .= '<'.$rxa_k.'><![CDATA['.$rxa_v.']]></'.$rxa_k.'>';
                }
            }
            $returnXmlData .= '</xml>';
            
            echo $returnXmlData;
            
            M('test_data')->add(array(
                'content' => '【发送消息】'.$returnXmlData,
                'addtime' => date('Y-m-d H:i:s')
            ));
        }
        // debug信息
        M('test_data')->add(array(
            'content' => '【接收消息】'.$backdata,
            'addtime' => date('Y-m-d H:i:s')
        ));
        
        /* 回复文本消息 
        <xml>
        <ToUserName><![CDATA[toUser]]></ToUserName>
        <FromUserName><![CDATA[fromUser]]></FromUserName>
        <CreateTime>12345678</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[你好]]></Content>
        </xml>
        */
    }
    
    /**
     * 微信创建菜单
     */
    public function createWxMenu() {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';
        $url .= Util::getWxToken();
        
        $menuArr = array(
            'button' => array(
                array(
                    'type' => 'view',
                    'name' => '官网',
                    'url' => WX_URL,
                ),
            ),
        );
        
        //$menuJson = json_encode($menuArr);
        $menuJson = '{"button":[{"type":"view","name":"官网","url":"'.WX_URL.'"},{"type":"view","name":"业务员","url":"'.WX_URL.U('Salesman/login').'"},]}';
        $returndata = Util::curlGet($url, null, true, $menuJson);
        M('test_data')->add(array(
            'content' => '【菜单设置】'.$returndata,
            'addtime' => date('Y-m-d H:i:s')
        ));
        /*{
            "button":[
            {
                "type":"click",
                "name":"今日歌曲",
                "key":"V1001_TODAY_MUSIC"
            },
            {
                "name":"菜单",
                "sub_button":[
                {
                    "type":"view",
                    "name":"搜索",
                    "url":"http://www.soso.com/"
                },
                {
                    "type":"view",
                    "name":"视频",
                    "url":"http://v.qq.com/"
                },
                {
                    "type":"click",
                    "name":"赞一下我们",
                    "key":"V1001_GOOD"
                }]
            }]
        }*/
    }
}