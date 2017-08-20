<?php
namespace Index\Controller;
use Think\Controller;
class MypayController extends Controller {
	/*
	*支付
	 */
	public function index(){
		$user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');

		$sport=I('post.sport')?I('post.sport'):1;
		$ordnum=I('post.ordnum')?I('post.ordnum'):0;
		$addidval=I('post.addidval')?I('post.addidval'):0;

		if($ordnum == 0){
			$this->error('订单失效！');
			return;
		}
		if($addidval == 0){
			$this->error('请选择收货地址！');
			return;
		}
		$ordm=M('orders');
		$ordshm=M('orders_detail');
		$shdz=M('usaddress')->where(array('id'=>$addidval))->find();
		if($sport != 1){
			$xxdata['orderstatus']=6;
			$xxdata['paytype']=0;
			$xxdata['time_end']=time();


			
			$shdzdata['uname']=$shdz['name'];
			$shdzdata['uphone']=$shdz['shtel'];
			$shdzdata['uaddr']=$shdz['addresval'];
			$shdzdata['sheng']=$shdz['sheng'];
			$shdzdata['shi']=$shdz['shi'];
			$shdzdata['xian']=$shdz['xian'];

			if($ordshm->where(array('ordernum'=>$ordnum))->find()){
				$savdz=$ordshm->where(array('ordernum'=>$ordnum))->save($shdzdata);
			}else{
				$shdzdata['ordernum']=$ordnum;
				$shdzadd=$ordshm->add($shdzdata);
			}
			if($ordm->where(array('ordernum'=>$ordnum))->save($xxdata)){
				$ordidv=$ordm->field('id,ordernum')->where(array('ordernum'=>$ordnum))->find();
				$lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'选择线下交易';
                $odlc=ordliucheng($ordidv['id'],$lcnr);
				$this->display('xianxiazf');
			}else{
				$this->error('下单失败！请重新下单！');
			}
			
		}else{


			$xxdata['paytype']=1;
            $xxdata['rongcuo']=rand(0,9999999);


			
			$shdzdata['uname']=$shdz['name'];
			$shdzdata['uphone']=$shdz['shtel'];
			$shdzdata['uaddr']=$shdz['addresval'];
			$shdzdata['sheng']=$shdz['sheng'];
			$shdzdata['shi']=$shdz['shi'];
			$shdzdata['xian']=$shdz['xian'];

			if($ordshm->where(array('ordernum'=>$ordnum))->find()){
				$savdz=$ordshm->where(array('ordernum'=>$ordnum))->save($shdzdata);
			}else{
				$shdzdata['ordernum']=$ordnum;
				$shdzadd=$ordshm->add($shdzdata);
			}
			if($ordm->where(array('ordernum'=>$ordnum))->save($xxdata)){
				$ordidv=$ordm->field('id,ordernum')->where(array('ordernum'=>$ordnum))->find();
				$lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'选择微信交易';
                $odlc=ordliucheng($ordidv['id'],$lcnr);
			}else{
				$this->error('下单失败！请重新下单！');
				return;
			}




			$order_row=M('orders')->where(array('ordernum'=>$ordnum))->find();
			// 预支付订单
	        $prepay_id = $this->doUnionOrder(array('openid'=>$user_row['openid'], 'orderNo'=>$ordnum, 'money'=>$order_row['money'], 'body'=>$ordnum));
	        //return;
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
            /*$time_start = date('YmdHis', time());
            $time_expire = date('YmdHis', time()+300);*/
            $time_start =time();
            $time_expire =time()+300;
            
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
                //'time_expire' => $time_expire,
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

                $ordidv=M('orders')->field('id,ordernum')->where(array('ordernum'=>$params['orderNo']))->find();
                $lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'发起微信下单，下单成功！';
                $odlc=ordliucheng($ordidv['id'],$lcnr);
        
                // 返回订单信息
                return $order_data_arr['prepay_id'];
            } else {
                // 下单失败
                $ordidv=M('orders')->field('id,ordernum')->where(array('ordernum'=>$params['orderNo']))->find();
                $lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'发起微信下单，下单失败！';
                $odlc=ordliucheng($ordidv['id'],$lcnr);
                //var_dump($user_row);
                //echo $order_data_arr['err_code_des'].'=><br>'.$order_data_arr['return_code'].'=><br>'.$order_data_arr['result_code'];
                //return;
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
}
?>