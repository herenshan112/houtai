<?php
namespace Admin\Controller;
use think\Controller;
use think\Model;
/**
* 
*/
class ExporsitcController extends AdminAuthController
//class ExporsitcController extends Controller
{
	//protected $objPHPExcel;
	protected function _initialize(){
		ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        require_once LIB_PATH. 'Org/Util/PHPExcel.class.php';  
       
    }
    /*
    *导出总部推广统计
     */
    public function index(){
    	$type=I('post.type');
    	if($type == ''){
    		echo json_encode(array('code'=>0,'msg'=>'参数错误！'.$type));
    		return;
    	}
    	$jxsuser=I('post.jxsuser');
    	$begtimeks=I('post.begtimeks');
    	$endtimeks=I('post.endtimeks');
    	if($begtimeks != '' && $endtimeks !=''){
    		if(strtotime($endtimeks) < strtotime($begtimeks)){
    			echo json_encode(array('code'=>0,'msg'=>'结束时间不能早于开始时间！'));
    			return;
    		}
    	}
    	$mod=new Model();
    	switch ($type) {
    		case 1:
    			$tj='';
    			if($begtimeks != '' && $endtimeks !=''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($endtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    			}elseif($begtimeks != '' && $endtimeks ==''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($begtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    			}
    			$M_orders = M('orders');
    			$map['fahuofang']	=	0;
				$map['orderstatus']	=	array('IN','3,4');
				$count = $M_orders->where($map)->count();
    			if($count > 0){
    				echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downzbfh',array('bgtime'=>$bgtime,'endtime'=>$endtime))));
    			}else{
    				echo json_encode(array('code'=>0,'msg'=>'没有查询到数据！请重新设置查询条件！'));
    			}
    			break;
    		case 2:
    			$tj='';

    			if($jxsuser != ''){
					$tj.=" AND b.username LIKE '%".$jxsuser."%'";
				}
    			if($begtimeks != '' && $endtimeks !=''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($endtimeks.' 23:59:59');
    				$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    				
    			}elseif($begtimeks != '' && $endtimeks ==''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($begtimeks.' 23:59:59');
    				$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    			}
    			$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj);
    			$count=count($ord_list);
    			if($count > 0){
    				echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downjxstg',array('bgtime'=>$bgtime,'endtime'=>$endtime,'jxsuser'=>$jxsuser))));
    			}else{
    				echo json_encode(array('code'=>0,'msg'=>'没有查询到数据！请重新设置查询条件！'));
    			}
    			break;
    		case 3:
    			if($begtimeks != '' && $endtimeks !=''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($endtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    			}elseif($begtimeks != '' && $endtimeks ==''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($begtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    			}
    			if($jxsuser != ''){
    				$map['username'] = array('like', "%{$jxsuser}%");
    			}
    			$M_orders = M('orders');
    			$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
                if($count > 0){
    				echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downjxsfh',array('bgtime'=>$bgtime,'endtime'=>$endtime,'jxsuser'=>$jxsuser))));
    			}else{
    				echo json_encode(array('code'=>0,'msg'=>'没有查询到数据！请重新设置查询条件！'));
    			}
    			break;
    		case 4:
    			if($begtimeks != '' && $endtimeks !=''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($endtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    			}elseif($begtimeks != '' && $endtimeks ==''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($begtimeks.' 23:59:59');
    				$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    			}
    			if($jxsuser != ''){
    				$map['username'] = array('like', "%{$jxsuser}%");
    			}
    			$map['fahuofang']	=	array('NEQ',0);
				$map['orderstatus']	=	array('IN','4,5');
				$map['paytype']	=	array('EQ',1);
				$M_orders = M('orders');
				$count = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->count();
                if($count > 0){
    				echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downjxsjxjtj',array('bgtime'=>$bgtime,'endtime'=>$endtime,'jxsuser'=>$jxsuser))));
    			}else{
    				echo json_encode(array('code'=>0,'msg'=>'没有查询到数据！请重新设置查询条件！'));
    			}
    			break;
    		default:
    			$tj='';
    			if($begtimeks != '' && $endtimeks !=''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($endtimeks.' 23:59:59');
    				$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    				
    			}elseif($begtimeks != '' && $endtimeks ==''){
    				$bgtime=strtotime($begtimeks.' 0:0:0');
        			$endtime=strtotime($begtimeks.' 23:59:59');
    				$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    			}
    			$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0  '.$tj.' ORDER BY time_end DESC');
    			$count=count($ord_list);
    			if($count > 0){
    				//echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downzbtg',array('bgtime'=>$bgtime,'endtime'=>$endtime)));
    				echo json_encode(array('code'=>1,'msg'=>'执行下载！','downurl'=>U('downzbtg',array('bgtime'=>$bgtime,'endtime'=>$endtime))));
    			}else{
    				echo json_encode(array('code'=>0,'msg'=>'没有查询到数据！请重新设置查询条件！'));
    			}
    			break;
    	}
    }
    /*
    *执行总部推广生成下载
     */
    public function downzbtg(){
    	$begtimeks=I('get.bgtime');
    	$endtimeks=I('get.endtime');
    	$tj='';
    	if($begtimeks != '' && $endtimeks !=''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($endtimeks.' 23:59:59');
    		$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    				
    	}elseif($begtimeks != '' && $endtimeks ==''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($begtimeks.' 23:59:59');
    		$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    	}
    	$mod=new Model();
    	$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs = 0  '.$tj.' ORDER BY time_end DESC');
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
            $M_orders_buy = M('orders_buy');
            $i = 1;
            $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
            $paytype_arr = array('线下支付', '微信');
            
            foreach ($ord_list as $order_k=>$order_v) {
                $i++;
                
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
                
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
                $hyxxcont=hyxxcx($order_v['userid']);
                $shdzcont=shouhuodizi($order_v['ordernum']);
                /*var_dump($hyxxcont);
                echo '<br><br><br><br>';*/
                //来源
                $laytg=dachukk($order_v['code_jxs']);
                //发货方
                $fhtg=dachukk($order_v['fahuofang']);
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $hyxxcont['nickname'])
                ->setCellValueExplicit('C'.$i, $hyxxcont['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, date('Y-m-d H:i:s',$order_v['time_end']), \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['invoice'])
                ->setCellValue('M'.$i, $shdzcont['uname'])
                ->setCellValueExplicit('N'.$i, $shdzcont['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $shdzcont['uaddr'])
                ->setCellValue('P'.$i, $laytg)
                ->setCellValue('Q'.$i, $fhtg);
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('总部推广统计信息导出');
            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="总部推广统计导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
    }
    /*
    *总部发货统计
     */
    public function downzbfh(){
    	$begtimeks=I('get.bgtime');
    	$endtimeks=I('get.endtime');
    	if($begtimeks != '' && $endtimeks !=''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($endtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    	}elseif($begtimeks != '' && $endtimeks ==''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($begtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    	}
    	$M_orders = M('orders');
    	$map['fahuofang']	=	0;
		$map['orderstatus']	=	array('IN','3,4');
		$ord_list = $M_orders->where($map)->order('time_end DESC')->select();
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
            $M_orders_buy = M('orders_buy');
            $i = 1;
            $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
            $paytype_arr = array('线下支付', '微信');
            
            foreach ($ord_list as $order_k=>$order_v) {
                $i++;
                
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
                
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
                $hyxxcont=hyxxcx($order_v['userid']);
                $shdzcont=shouhuodizi($order_v['ordernum']);
                /*var_dump($hyxxcont);
                echo '<br><br><br><br>';*/
                //来源
                $laytg=dachukk($order_v['code_jxs']);
                //发货方
                $fhtg=dachukk($order_v['fahuofang']);
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $hyxxcont['nickname'])
                ->setCellValueExplicit('C'.$i, $hyxxcont['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, date('Y-m-d H:i:s',$order_v['time_end']), \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['invoice'])
                ->setCellValue('M'.$i, $shdzcont['uname'])
                ->setCellValueExplicit('N'.$i, $shdzcont['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $shdzcont['uaddr'])
                ->setCellValue('P'.$i, $laytg)
                ->setCellValue('Q'.$i, $fhtg);
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('总部发货统计信息导出');
            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="总部发货统计导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
    }
    /*
    *经销商推广统计
     */
    public function downjxstg(){
    	$begtimeks=I('get.bgtime');
    	$endtimeks=I('get.endtime');
    	$jxsuser=I('post.jxsuser');
    	if($begtimeks != '' && $endtimeks !=''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($endtimeks.' 23:59:59');
    		$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    				
    	}elseif($begtimeks != '' && $endtimeks ==''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($begtimeks.' 23:59:59');
    		$tj.= " AND p1.addtime BETWEEN '".$bgtime."' AND '".$endtime."'";
    	}
    	if($jxsuser != ''){
			$tj.=" AND b.username LIKE '%".$jxsuser."%'";
		}
		$mod=new Model();
		$ord_list=$mod->query('SELECT p1.* FROM ms_orders p1 LEFT JOIN ms_user b ON b.id = p1.code_jxs,ms_orders p2 WHERE p1.code_jxs<>p2.fahuofang AND p1.fahuofang<>p2.code_jxs AND p1.id=p2.id AND p1.code_jxs <> 0 '.$tj .' ORDER BY time_end DESC');
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
            $map['code_jxs']	=	array('NEQ',0);
            
            // 筛选用户信息
            $M_orders_buy = M('orders_buy');
            $i = 1;
            $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
            $paytype_arr = array('线下支付', '微信');
            
            foreach ($ord_list as $order_k=>$order_v) {
                $i++;
                
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
                
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
                $hyxxcont=hyxxcx($order_v['userid']);
                $shdzcont=shouhuodizi($order_v['ordernum']);
                /*var_dump($hyxxcont);
                echo '<br><br><br><br>';*/
                //来源
                $laytg=dachukk($order_v['code_jxs']);
                //发货方
                $fhtg=dachukk($order_v['fahuofang']);
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $hyxxcont['nickname'])
                ->setCellValueExplicit('C'.$i, $hyxxcont['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, date('Y-m-d H:i:s',$order_v['time_end']), \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['invoice'])
                ->setCellValue('M'.$i, $shdzcont['uname'])
                ->setCellValueExplicit('N'.$i, $shdzcont['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $shdzcont['uaddr'])
                ->setCellValue('P'.$i, $laytg)
                ->setCellValue('Q'.$i, $fhtg);
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('经销商推广统计信息导出');
            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="经销商推广统计导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
    }
    /*
    *经销商发货
     */
    public function downjxsfh(){
    	$begtimeks=I('get.bgtime');
    	$endtimeks=I('get.endtime');
    	$jxsuser=I('post.jxsuser');
    	if($begtimeks != '' && $endtimeks !=''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($endtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    	}elseif($begtimeks != '' && $endtimeks ==''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($begtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    	}
    	if($jxsuser != ''){
    		$map['username'] = array('like', "%{$jxsuser}%");
    	}
    	$map['fahuofang']	=	array('NEQ',0);
    	$M_orders = M('orders');
    	$ord_list = $M_orders
                            ->alias('o')
                            ->field('o.*,b.id,b.username')
                            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
                            ->where($map)
                            ->order('time_end DESC')
                            ->select();
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
            $map['code_jxs']	=	array('NEQ',0);
            
            // 筛选用户信息
            $M_orders_buy = M('orders_buy');
            $i = 1;
            $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
            $paytype_arr = array('线下支付', '微信');
            
            foreach ($ord_list as $order_k=>$order_v) {
                $i++;
                
                // 商品
                $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
                
                $temp_buys = "";
                foreach ($buy_rows as $k=>$v) {
                    $temp_buys .= $v['title'].' 【数量：'.$v['num'].'】';
                    if ($k) $temp_buys .= "\n";
                }
                $hyxxcont=hyxxcx($order_v['userid']);
                $shdzcont=shouhuodizi($order_v['ordernum']);
                /*var_dump($hyxxcont);
                echo '<br><br><br><br>';*/
                //来源
                $laytg=dachukk($order_v['code_jxs']);
                //发货方
                $fhtg=dachukk($order_v['fahuofang']);
            
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $hyxxcont['nickname'])
                ->setCellValueExplicit('C'.$i, $hyxxcont['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('G'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('H'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('I'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValueExplicit('J'.$i, $order_v['transaction_id'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K'.$i, date('Y-m-d H:i:s',$order_v['time_end']), \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('L'.$i, $order_v['invoice'])
                ->setCellValue('M'.$i, $shdzcont['uname'])
                ->setCellValueExplicit('N'.$i, $shdzcont['uphone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('O'.$i, $shdzcont['uaddr'])
                ->setCellValue('P'.$i, $laytg)
                ->setCellValue('Q'.$i, $fhtg);
            }
            
            $objPHPExcel->getActiveSheet()->setTitle('经销商发货统计信息导出');
            
            
            $objPHPExcel->setActiveSheetIndex(0);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="经销商发货统计导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
            header('Cache-Control: max-age=0');
            
            $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save( 'php://output');
    }
    /*
    *经销商价格统计
     */
    public function downjxsjxjtj(){
    	$begtimeks=I('get.bgtime');
    	$endtimeks=I('get.endtime');
    	$jxsuser=I('post.jxsuser');
    	if($begtimeks != '' && $endtimeks !=''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($endtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    				
    	}elseif($begtimeks != '' && $endtimeks ==''){
    		$bgtime=strtotime($begtimeks.' 0:0:0');
        	$endtime=strtotime($begtimeks.' 23:59:59');
    		$map['addtime']=array('BETWEEN',$bgtime.','.$endtime);
    	}
    	if($jxsuser != ''){
    		$map['username'] = array('like', "%{$jxsuser}%");
    	}
    	$map['fahuofang']	=	array('NEQ',0);
		$map['orderstatus']	=	array('IN','4,5');

		$map['paytype']	=	array('EQ',1);

		$M_orders = M('orders');
		$ord_list = $M_orders
            ->alias('o')
            ->field('o.*,b.id,b.username')
            ->join('__USER__ b ON b.id = o.fahuofang','LEFT')
            ->where($map)
            ->order('time_end DESC')
            ->select();
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
            ->setCellValue('C1', '会员电话')
            ->setCellValue('D1', '商品信息')
            ->setCellValue('E1', '订单金额')
            ->setCellValue('F1', '经销商价格')
            ->setCellValue('G1', '下单时间')
            ->setCellValue('H1', '状态')
            ->setCellValue('I1', '支付状态')
            ->setCellValue('J1', '支付金额')
            ->setCellValue('K1', '收货人')
            ->setCellValue('L1', '收货人电话')
            ->setCellValue('M1', '收货人地址')
            ->setCellValue('N1', '结算状态');
        $i = 1;
        $M_orders_buy = M('orders_buy');
        $status_arr = array('', '', '待发货', '待收货', '已完成','委托经销商发货');
        $paytype_arr = array('线下支付', '微信');
        foreach ($ord_list as $order_k=>$order_v) {
        	/*var_dump($order_v);
        	echo '<br><br><br><br><br><br><br><br>';*/
        	$i++;
        	// 商品
            $buy_rows = $M_orders_buy->where(array('ordernum'=>$order_v['ordernum']))->select();
            $temp_buys = "";
            foreach ($buy_rows as $k=>$v) {
                $temp_buys .= $v['title'].' 【数量：'.$v['num'].'  商品价格：'.$v['price'].'  经销商价格：'.$v['price_jsx'].'】';
               	if ($k) $temp_buys .= "\n";
            }
            //会员信息
            $hyxxcont=hyxxcx($order_v['userid']);
            //收货人信息
            $shdzcont=shouhuodizi($order_v['ordernum']);
            //来源
            $laytg=dachukk($order_v['code_jxs']);
            //发货方
            $fhtg=dachukk($order_v['fahuofang']);
            //结算信息
            $jsxx=jiesunexpor($order_v['sfjsset'],$order_v['ordernum']);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.$i, $order_v['ordernum'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('B'.$i, $hyxxcont['nickname'])
                ->setCellValue('C'.$i, $hyxxcont['phone'])
                ->setCellValue('D'.$i, $temp_buys)
                ->setCellValue('E'.$i, $order_v['money'])
                ->setCellValue('F'.$i, jsxjagejs($order_v['ordernum']))
                ->setCellValue('G'.$i, date('Y-m-d H:i:s',$order_v['addtime']))
                ->setCellValue('H'.$i, $status_arr[$order_v['orderstatus']])
                ->setCellValue('I'.$i, $paytype_arr[$order_v['paytype']])
                ->setCellValue('J'.$i, bcdiv($order_v['total_fee'], 100, 2))
                ->setCellValue('K'.$i, $shdzcont['uname'])
                ->setCellValue('L'.$i, $shdzcont['uphone'])
                ->setCellValue('M'.$i, $shdzcont['uaddr'])
                ->setCellValue('N'.$i, $jsxx);
        }
        $objPHPExcel->getActiveSheet()->setTitle('经销商经销价格统计信息导出');
        $objPHPExcel->setActiveSheetIndex(0);
            
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="经销商经销价格统计导出'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
        header('Cache-Control: max-age=0');
            
        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save( 'php://output');
    }
}
?>