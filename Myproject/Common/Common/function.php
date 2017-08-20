<?php
/*
*处理配件列表
 */
function shopspec($shopidval=0){
	$shopdb=M('products');
	$shoplist='';
	if($shopidval != 0 && $shopidval != ''){
		$sopary=explode(',', $shopidval);
		foreach ($sopary as $shopid) {
			$list=$shopdb->field('id,title')->where(array('id'=>$shopid))->find();
			if($list){
				$shoplist.='<i id="spxd'.$list['id'].'">'.$list['title'].'<a class="fform_li_a icon-remove-circle" onclick=deltqdval('.$list['id'].')>&nbsp;</a></i>';
			}
		}
	}
	return $shoplist;
}
/*
*库存状态
 */
function stocksum($stosum=0){
	$comdb=M('early');
	
	$maxval=$comdb->field('earlyval')->max('earlyval');
	if($stosum >= $maxval){
		$stovals=$comdb->field('id,earlyval,title,coloval')->where(array('earlyval'=>$maxval))->find();
		$colrval='<span style="color:'.$stovals['coloval'].'">'.$stovals['title'].'</span>';
		return $colrval;
	}
	$minval=$comdb->field('earlyval')->Min('earlyval');
	if($stosum <= $minval){
		$stovalsx=$comdb->field('id,earlyval,title,coloval')->where(array('earlyval'=>$minval))->find();
		$colrval='<span style="color:'.$stovalsx['coloval'].'">'.$stovalsx['title'].'</span>';
		return $colrval;
	}
	$stoval=$comdb->field('id,earlyval,title,coloval')->order(array('earlyval'=>'asc','id'=>'desc'))->select();
	for ($i=0; $i < count($stoval); $i++) { 
		if($stosum > $stoval[$i]['earlyval']){
			$yjid=$i;
		}
	}
	$colrval='<span style="color:'.$stoval[$yjid+1]['coloval'].'">'.$stoval[$yjid+1]['title'].'</span>';
	return $colrval;
}

/*
*加密算法
 */
function jiamimd5($valstr,$appkey=''){
	if($appkey == ''){
		$appkey=C('APP_KEY');
	}
	return md5(md5(md5($valstr.$appkey).$appkey).$appkey);
}
function post_get($name){
	$valcont=$_POST[$name];
	if(isset($valcont)){
		return $valcont;
	}
	$valcont=$_GET[$name];
	if(isset($valcont)){
		return $valcont;
	}
	return;
}
/*
*地区查询
 */
function addreslook($id){
	return M('region')->where(array('PARENT_ID'=>$id))->order(array('REGION_ID'=>'asc'))->select();
}
function lookdizicont($id){
	return M('region')->where(array('REGION_ID'=>$id))->order(array('REGION_ID'=>'asc'))->find();
}
function lookdiziname($id){
	$regary= M('region')->where(array('REGION_ID'=>$id))->order(array('REGION_ID'=>'asc'))->find();
	return $regary['region_name'];
}
/*
*所在城市
 */
function szcitycx($city,$coun,$addres='',$porval=0,$typeset=1){
	/*if($typeset == 1){
		return $addres;
	}*/
	if($porval == 0){
		return $addres;
	}
	$pornam=lookdizicont($porval);
	if($city != 0){
		$citynam=lookdizicont($city);
	}else{
		$citynam['region_name']='';
	}
	if($coun != 0){
		$counnam=lookdizicont($coun);
	}else{
		$counnam['region_name']='';
	}
	$dz=$pornam['region_name'].$citynam['region_name'].$counnam['region_name'].$addres;
	return $dz;

}

//随机编号
function foo() {

	  $o = $last = '';
	
	  do {
	
		$last = $o;
	
		usleep(10);
	
		$t = explode(' ', microtime());
	
		$o = substr(base_convert(strtr($t[0].$t[1].$t[1], '.', ''), 10, 36), 0, 12);
	
	  }while($o == $last);
	
	  return strtoupper($o);
	
}

function numndle(){
	$dingdanhao = date("Y-m-dH-i-s");
	$dingdanhao = str_replace("-","",$dingdanhao);
	$dingdanhao .= rand(1000,9999999999);
	return $dingdanhao;
}

function ordernumndle(){
	$dingdanhao = date("Y-m-dH-i-s");
	$dingdanhao = str_replace("-","",$dingdanhao);
	$dingdanhao .= rand(10000000,99999999);
	$dingdanhao .=time();
	return $dingdanhao;
}

//验证数字随机编号
function numsend(){
	return rand(100000,999999);
}
//订单来源
function ordersource($sourval=0){
	if($sourval == 0){
		return '总部';
	}else{
		$usly=M('user')->field('id,username,phone,nickname,address,provinces,city,county,poratename')->where(array('id'=>$sourval))->find();
		if($usly){
			return $usly['poratename'].'<br>'.szcitycx($usly['city'],$usly['county'],$usly['address'],$usly['provinces']).'<br>帐号：'.$usly['username'].'&nbsp;&nbsp;姓名：'.$usly['nickname'].'<br>电话：'.$usly['phone'];
		}else{
			return '总部';
		}
	}
}

function ordersources($sourval=0){
	if($sourval == 0){
		return '总部';
	}else{
		$usly=M('user')->field('id,username,phone,nickname,address,provinces,city,county')->where(array('id'=>$sourval))->find();
		if($usly){
			return szcitycx($usly['city'],$usly['county'],'',$usly['provinces']).'&nbsp;&nbsp;&nbsp;&nbsp;经销商帐号：'.$usly['username'].'&nbsp;&nbsp;&nbsp;&nbsp;姓名：'.$usly['nickname'].'&nbsp;&nbsp;&nbsp;&nbsp;电话：'.$usly['phone'];
		}else{
			return '总部';
		}
	}
}
//发货方信息
function fhfordersource($sourval=0){
	if($sourval == 0){
		return '总部';
	}else{
		$usly=M('user')->field('id,username,phone,nickname,address,provinces,city,county,poratename')->where(array('id'=>$sourval))->find();
		if($usly){
			return $usly['poratename'].'<br>销售区域:'.szcitycx($usly['city'],$usly['county'],$usly['address'],$usly['provinces']).'<br>帐号：'.$usly['username'].'&nbsp;&nbsp;姓名：'.$usly['nickname'].'<br>电话：'.$usly['phone'];
		}else{
			return '总部';
		}
	}
}

//发货判断(由谁发货)
function fhfjumpys($laiyuan=0,$fahuo=0){
	if($fahuo != 0){
		return $fahuo;
	}
	if($laiyuan != 0){
		return $laiyuan;
	}
	return 0;
}
//获取产品图片
function shoppicval($shopid){
	$spvl=M('products')->field('id,titlepic')->where(array('id'=>$shopid))->find();
	return $spvl['titlepic'];
}

//导出
function dachukk($sourval=0){
	if($sourval == 0){
		return '总部';
	}else{
		$usly=M('user')->field('id,username,phone,nickname,address,provinces,city,count,poratename,porateaddress')->where(array('id'=>$sourval))->find();
		if($usly){
			return '公司名称：'.$usly['poratename'].'   公司地址：'.$usly['porateaddress'].'   销售区域：'.szcitycx($usly['city'],$usly['county'],$usly['address'],$usly['provinces'])."   帐号：".$usly['username'].'   姓名：'.$usly['nickname'].'   电话：'.$usly['phone'];
		}else{
			return '总部';
		}
	}
}

//经销商价格计算
function jsxjagejs($ordnum=''){
	$jagehe=0;
	$jgjs=M('orders_buy')->field('ordernum,num,price_jsx')->where('ordernum=' . $ordnum)->select();
	if($jgjs){
		foreach ($jgjs as $key => $jsxlue) {
			$jagehe+=$jsxlue['price_jsx']*$jsxlue['num'];
		}
		
	}
	return $jagehe;
}

/**
     * 订单状态文本转换
     */
    function orderStatus($stat=0, $commented=0) {
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
*会员信息
 */
function hyxxcx($uid){
	$uidls=M('user')->field('id,phone,nickname')->where(array('id'=>$uid))->find();
	return $uidls;
}
/*
*收货信息
 */
function shouhuodizi($ordnum){
	$ordls=M('orders_detail')->where(array('ordernum'=>$ordnum))->find();
	return $ordls;
}
/*
*结算状态
 */
function jiesunexpor($ztset=0,$ordid){
	$jssmss=M('orders')->field('id,ordernum')->where(array('ordernum'=>$ordid))->find();
	switch ($ztset) {
		case 1:
			$scyj='等待经销商确认';
			$jssm=M('jiesuan')->where(array('orderid'=>$jssmss['id']))->find();
			if($jssm){
				$scyj.="  结算说明：".$jssm['jiesuanbeizhu'].'  发起时间：'.date('Y-m-d H:i:s',$jssm['jiesuantime']);
			}
			return $scyj;
			break;
		case 2:
			$scyj='完成';
			$jssm=M('jiesuan')->where(array('orderid'=>$jssmss['id']))->find();
			if($jssm){
				$scyj.="  结算说明：".$jssm['jiesuanbeizhu'].'  发起时间：'.date('Y-m-d H:i:s',$jssm['jiesuantime']);
				$scyj.="  经销商确认说明：".$jssm['qrbeizhu'].'  确认时间：'.date('Y-m-d H:i:s',$jssm['qrtime']);
			}
			return $scyj;
			break;
		default:
			return '未结算';
			break;
	}
}

//商品规格
function shopguige($id){
	$ggc=M('proguige')->where(array('gg_id'=>$id))->find();
	return $ggc['gg_title'];
}
//商品规格
function shoptype($id){
	$ggc=M('produtype')->where(array('id'=>$id))->find();
	return $ggc['title'];
}
//判断是否已经收藏
function jumpshouchang($uid,$pid){
	$jmpsc=M('mysc')->where(array('sc_uid'=>$uid,'sc_proid'=>$pid))->find();
	if($jmpsc){
		return 1;
	}else{
		return 0;
	}
}
//获取用户
function getuscont(){
	$pdtj['id']=cookie('u_idval');
    $pdtj['phone']=cookie('u_phone');
    $pdtj['_logic'] = 'OR';
    return M('user')->where($pdtj)->find();
}
function getuscoddnt($id){
	$pdtj['id']=$id;
    $hhj= M('user')->where($pdtj)->find();
    if($hhj['nickname'] != ''){
    	return $hhj['nickname'];
    }else{
    	return $hhj['username'];
    }
}

//商品最终价格确认
function shoppiceqr($sid=0){
	$spvc=M('products')->field('id,price,tejia,tejiaprice')->where(array('id'=>$sid))->find();
	switch ($spvc['tejia']) {
        case 2:
        case 5:
        case 6:
            if($spvc['tejiaprice'] > 0){
                return $spvc['tejiaprice'];
            }else{
                return $spvc['price'];
            }
            break;   
        default:
            return $spvc['price'];
            break;
    }
    return 0;
}
//判断推广来源
function jumptglaiyuan(){
	if(session('?tuiguang')){
		$vipid=session('tuiguang');
		if((time()-$vipid['time']) > 86400){
			return 0;
		}else{
			return $vipid['uid'];
		}
	}else{
		if(cookie('tuiguang')){
			return cookie('tuiguang');
		}else{
			return 0;
		}
	}
}
//手机号码隐藏
function telyincang($telval=0){
	return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $telval);
}
//utf8计算字符长度
function strlen_utf8($str) 
{ 
    $i = 0; 
    $count = 0; 
    $len = strlen($str); 
    while ($i < $len) 
    { 
        $chr = ord($str[$i]); 
        $count++; 
        $i++; 
        if ($i >= $len) 
        { 
            break; 
        } 
        if ($chr & 0x80) 
        { 
            $chr <<= 1; 
            while ($chr & 0x80) 
            { 
                $i++; 
                $chr <<= 1; 
            } 
        } 
    } 
  
    return $count; 
}
/*
*判断推广来源
 */
function jumptgly($sbm='0'){
	//echo $sbm.'<br>';
	/*session('tuiguang',array(
            'uid'           =>         2,
            'time'          =>          time()
        ));
		return 2;
*/	if($sbm == '0'){
		session('tuiguang',array(
            'uid'           =>          0,
            'time'          =>          time()
        ));
        //echo '1<br>';
		return 0;
	}else{
		$pdtj['code']=$sbm;
		$ustgy=M('user')->where($pdtj)->find();
		session('tuiguang',array(
            'uid'           =>          $ustgy['id'],
            'time'          =>          time()
        ));
        //echo '2<br>';
    	return $ustgy;
	}
}


//订单来源
function jxstgly($sourval=0){
	if($sourval == 0){
		return '总部';
	}else{
		$usly=M('user')->field('id,username,phone,nickname,address,provinces,city,county,poratename')->where(array('id'=>$sourval))->find();
		if($usly){
			$myc=getuscont();
			if($myc['id']==$usly['id']){
				return '本公司';
			}else{
				return $usly['poratename'];
			}
			
		}else{
			return '总部';
		}
	}
}

/*
*订单流程处理
 */
function ordliucheng($order=0,$ordcont){
	if($order != 0){
		$ordm=M('ordliucheng');
		$oldlc=$ordm->where(array('ordlc_ordid'=>$order))->find();

		$lcdacon['ordlc_ordid']=$order;
		$lcdacon['ordlc_time']=time();
		if($oldlc){
			$lcdacon['ordlc_cont']=$oldlc['ordlc_cont'].'<br>'.$ordcont;
			$eitodlc=$ordm->where(array('ordlc_ordid'=>$order))->save($lcdacon);
		}else{
			$lcdacon['ordlc_cont']=$ordcont;
			$addodlc=$ordm->add($lcdacon);
		}

	}
}

/*
*物流公司
 */
function wlgsmc($wlid){
	$wlcont=M('shipper')->where(array('id'=>$wlid))->find();
	return $wlcont['name'];
}
/*
*当月第一天和最后一天
*@ $date 当前时间
 */
function getthemonth($date){
   $firstday = date('Y-m-01', strtotime($date)).' 0:0:0';
   $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day")).' 23:59:59';
   return array(strtotime($firstday),strtotime($lastday));
}
/*
*上N月第一天和最后一天
*@ $date 当前时间
 */
function getsymoth($qjy=1){
	$firstday =  date('Y-m-01', strtotime('-'.$qjy.' month')).' 0:0:0';
	$lastday = date('Y-m-t', strtotime('-'.$qjy.' month')).' 23:59:59';
	return array(strtotime($firstday),strtotime($lastday));
}
/*
*获取今天是周几
 */
function getdayzhou($date){
	$weekarray=array("日","一","二","三","四","五","六"); 
	return "星期".$weekarray[date("w",'2017-7-2')];

}

function   get_week($date){
        //强制转换日期格式
        $date_str=date('Y-m-d',strtotime($date));
    
        //封装成数组
        $arr=explode("-", $date_str);
         
        //参数赋值
        //年
        $year=$arr[0];
         
        //月，输出2位整型，不够2位右对齐
        $month=sprintf('%02d',$arr[1]);
         
        //日，输出2位整型，不够2位右对齐
        $day=sprintf('%02d',$arr[2]);
         
        //时分秒默认赋值为0；
        $hour = $minute = $second = 0;   
         
        //转换成时间戳
        $strap = mktime($hour,$minute,$second,$month,$day,$year);
         
        //获取数字型星期几
        $number_wk=date("w",$strap);
         
        //自定义星期数组
        $weekArr=array("周日","周一","周二","周三","周四","周五","周六");
         
        //获取数字对应的星期
        return $weekArr[$number_wk];
    }
/*
*计算一单经销商价格
 */
function jsjxjiage($ordnum){
	$jxjs=M('orders_buy')->where(array('ordernum'=>$ordnum))->select();
	if($jxjs){
		$zmsum=0;
		foreach ($jxjs as $key => $jgue) {
			$zmsum+=$jgue['price_jsx']*$jgue['num'];
		}
		return $zmsum;
	}else{
		return 0;
	}
}
/*
*订单状态
 */
function ddztkj($stat=0, $commented=0,$fhfset=0) {
        if (!$stat) return '';
        
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
        }else if ($stat == 4) {
            if ($commented) {
                return '交易完成';
            }
            return '交易完成';
        }
}
/*
*取得微信授权
 */
function getweixinopenid(){
	$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID;
    $url .= '&redirect_uri=' . urlencode(WX_URL . WX_URL_REDIRECT);
    $url .= '&response_type=code&scope=snsapi_base&state=' . $phone;
    $url .= '#wechat_redirect';
    return redirect($url);
}
/*
*判断发货是否超时
 */
function jumpfhcs(){
	$cssj=M('datetime')->order(array('id'=>'desc'))->find();
	if($cssj){
		$cstime=$cssj['setval']*86400;
	}else{
		$cstime=2*86400;
	}
	$ordls=M('orders')->field('id,orderstatus,deled,addtime,code_jxs,fahuofang,fpsj,fhsetval')->where(array('orderstatus'=>array('IN','5,6'),'deled'=>0))->select();
	foreach ($ordls as $key => $fhsjpd) {
		if($fhsjpd['fhsetval']==1 && (time()-$fhsjpd['fhsetval']) > $cstime){
			$twozk=qingkuanonr($fhsjpd['id']);
		}
		if($fhsjpd['fhsetval']==0 && $fhsjpd['fahuofang']!=0 && (time()-$fhsjpd['addtime']) > $cstime){
			$twozk=qingkuanonr($fhsjpd['id']);
		}
	}
	//var_dump($ordls);
}
//情况一
function qingkuanonr($id){
	$gbz=array(
		'fhsetval'					=>				0,
		'fpsj'						=>				0,
		'fahuofang'					=>				0,
		'orderstatus'				=>				2
	);
	$ordgbols=M('orders')->where(array('id'=>$id))->save($gbz);
	if($ordgbols){
		$lcnr='由于经销商超过待发货期限，现由总部处理订单！系统处理时间'.date('Y-m-d H:i:s');
        $odlc=ordliucheng($id,$lcnr);
	}
}

/*
*发送短信
 */
function NewSms($phone,$msg)
{
			$url="http://service.winic.org:8009/sys_port/gateway/index.asp?";
			$data = "id=%s&pwd=%s&to=%s&content=%s&time=";
			$id = 'psd105';
			$pwd = '6622793749';
			$to = $phone; 
			$content = iconv("UTF-8","GB2312",$msg);
			$rdata = sprintf($data, $id, $pwd, $to, $content);
			
			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = substr($result,0,3);
			return $result;
}
/*
*产生验证码
 */
function shengchancode(){
	$randStr = str_shuffle('1234567890');  
    $code = substr($randStr,0,6);
    session('codelinsz',array(
    	'code'		=>			$code 
    ));
    return $code;
}

?>