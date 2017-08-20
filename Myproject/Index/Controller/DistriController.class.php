<?php
namespace Index\Controller;
use Think\Controller;
/**
* 经销商
*/
class DistriController extends AuthController {
	
	/**
     * 经销商中心
     */
    public function index() {
        $user_row = getuscont();
        //var_dump($user_row);
        if (!$user_row) $this->redirect('User/index',array('set'=>1));
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        
        if($user_row['nickname'] != ''){
            $user_row['myname']=$user_row['nickname'];
        }else{
            $user_row['myname']=$user_row['username'];
        }

        $this->assign('list',$user_row );
        
        $this->display();
    }
    /*
    *我的二维码
     */
    public function myorcode(){
    	$user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        if($user_row['nickname'] != ''){
            $user_row['myname']=$user_row['nickname'];
        }else{
            $user_row['myname']=$user_row['username'];
        }
        $this->assign('list',$user_row );
        
        $this->display();
    }
    /*
    *我的消息
     */
    public function mynews(){
		$user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        $tyid=I('get.tyid')?I('get.tyid'):8;
        $this->assign('tyid',$tyid);
        $list=M('news')->where(array('cateid'=>$tyid))->order(array('id'=>'desc'))->select();
        $this->assign('list',$list);
        $this->display();
        
    }
    /*
    *经销商资料
     */
    public function mycont(){
        $user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'eite':
                $this->assign('list',$user_row);
                $this->display('myconteite');
                break;
            case 'eitecl':
                $datacont['headpic']=I('post.toppickjs')?I('post.toppickjs'):'/Public/index/img/20.png';
                $datacont['nickname']=I('post.nickname');
                $datacont['email']=I('post.email');
                $datacont['phone']=I('post.phone');
                $datacont['telval']=I('post.telval');
                $datacont['sex']=I('post.sex')?I('post.sex'):'男';
                $datacont['shengri']=I('post.shengri')?I('post.shengri'):0;
                $datacont['poratename']=I('post.poratename');
                $datacont['porateaddress']=I('post.porateaddress');
                $datacont['porateipc']=I('post.toppickjsyyzz')?I('post.toppickjsyyzz'):'/Public/index/img/33.png';

                if($datacont['sex'] == '男'){
                    $datacont['sex'] = 1;
                }else{
                    $datacont['sex'] = 0;
                }
                $datacont['shengri']=strtotime($datacont['shengri']);
                if(M('user')->where(array('id'=>$user_row['id']))->save($datacont)){
                    $this->success('修改完成！',U('index'));
                }else{
                    $this->success('修改完成！',U('index'));
                }
                break;
            default:
                $this->assign('list',$user_row);
                $this->display();
                break;
        }
        
    }
    /*
    *经销商订单
     */
    public function myorder(){
        $user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'all':                             //所有订单
                $setval='all';
                $setval='dsh';

                $wheonr['code_jxs']=$user_row['id'];
                $wheonr['orderstatus']=1;

                $wheonr['fahuofang']=$user_row['id'];
                $wheonr['orderstatus']=3;


                $list=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','3')))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($list as $key => $valshop) {
                    $list[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                }
                $this->assign('list',$list);
                break;
            case 'dfk':                             //待付款
                $setval='dfk';
                $list=M('orders')->where(array('code_jxs'=>$user_row['id'],'orderstatus'=>array('IN','1')))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($list as $key => $valshop) {
                    $list[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                }
                $this->assign('list',$list);
                break;
            case 'dsh':                             //待收货
                $setval='dsh';
                $list=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','3')))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($list as $key => $valshop) {
                    $list[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                }
                $this->assign('list',$list);
                break;
            case 'ywc':                             //已完成
                $setval='ywc';
                $list=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','4')))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($list as $key => $valshop) {
                    $list[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                }
                $this->assign('list',$list);
                break;
            case 'ljfh':                            //立即发货
                $id=I('get.id')?I('get.id'):0;
                if($id == 0){
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                if(M('orders')->where(array('id'=>$id))->find()){
                    $this->assign('wlsg',M('shipper')->select());
                    $this->assign('id',$id);
                    $this->display('xzfhfs');
                    return ;
                }else{
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                break;
            case 'ljfhcl':
                $ordid=I('post.ordid')?I('post.ordid'):0;
                if($ordid == 0){
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                $fhsetval=I('post.fhsetval')?I('post.fhsetval'):1;
                $shijian=time();
                if($fhsetval == 1){
                    $fhdata=array(
                        'orderstatus'               =>                  3,
                        'fhtime'                    =>                  $shijian,
                        'shippernum'                =>                  I('post.kuaidinum'),
                        'shipperid'                 =>                  I('post.wlgsname'),
                        'fpsj'                      =>                  $shijian,
                        'fhsetval'                  =>                  1,
                    );
                    if($fhdata['shipperid'] == 0){
                        $this->error('请选择快递公司');
                        return ;
                    }
                    if($fhdata['shippernum'] == 0){
                        $this->error('请输入快递单号');
                        return ;
                    }
                }else{
                    $fhdata=array(
                        'orderstatus'               =>                  3,
                        'fhtime'                    =>                  $shijian,
                        'fpsj'                      =>                  $shijian,
                        'fhsetval'                  =>                  1,
                        'anzdizhi'                  =>                  I('post.azdizhi'),
                        'anzname'                   =>                  I('post.azname'),
                        'anztel'                    =>                  I('post.aztel')
                    );
                    if($fhdata['anzdizhi'] == ''){
                        $this->error('请输入安装地址');
                        return ;
                    }
                    if($fhdata['anzname'] == ''){
                        $this->error('请输入安装人员姓名');
                        return ;
                    }
                    if($fhdata['anztel'] == ''){
                        $this->error('请输入安装人员电话');
                        return ;
                    }
                }
                if(M('orders')->where(array('id'=>$ordid))->save($fhdata)){
                    $lcnr='商户：'.$user_row['poratename'].'&nbsp;'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'提交发货信息！';
                    $odlc=ordliucheng($ordid,$lcnr);
                    $this->success('你的发货信息提交成功！',U('myorder'));
                    return ;
                }else{
                    $this->error('你的发货信息提交失败！请重新提交');
                    return ;
                }
                break;
            case 'fqfh':                            //放弃发货
                $id=I('get.id')?I('get.id'):0;
                if($id == 0){
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                if(M('orders')->where(array('id'=>$id))->find()){
                    $this->assign('id',$id);
                    $this->display('fqfhcz');
                    return ;
                }else{
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                break;
            case 'fqfhclcx':
                $ordid=I('post.ordid')?I('post.ordid'):0;
                if($ordid == 0){
                    $this->error('该订单不存在或已被删除！');
                    return ;
                }
                $fqyy=I('post.kuaidinum');
                if($fqyy == ''){
                    $this->error('请输入放弃发货原因！');
                    return ;
                }
                $fqfhary=array(
                    'orderstatus'               =>                  2,
                    'fhtime'                    =>                  0,
                    'fahuofang'                 =>                  0,
                    'fpsj'                      =>                  0,
                    'fhsetval'                  =>                  0,
                    'sfjsset'                   =>                  0,
                    'anzdizhi'                  =>                  '',
                    'anzname'                   =>                  '',
                    'anztel'                    =>                  ''
                );
                if(M('orders')->where(array('id'=>$ordid))->save($fqfhary)){
                    $lcnr='商户：'.$user_row['poratename'].'&nbsp;'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'放弃发货！';
                    $odlc=ordliucheng($ordid,$lcnr);
                    $this->success('你的放弃发货提交成功！',U('myorder'));
                    return ;
                }else{
                    $this->error('你的放弃发货提交失败！请重新提交');
                    return ;
                }
                break;
            default:
                $setval='dfh';
                $list=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','5,6')))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($list as $key => $valshop) {
                    $list[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                }
                $this->assign('list',$list);
                break;
        }
        $this->assign('setval',$setval);
        $this->display();
    }
    /*
    *财务中心
     */
    public function myciwu(){
        $user_row = getuscont();
        if (!$user_row) $this->redirect('User/index');
        if ($user_row['cateid'] != 2) $this->redirect('User/index');
        $action=I('get.action')?I('get.action'):'list';
        $yf=I('get.yf')?I('get.yf'):'';
        $this->assign('yf',$yf);
        switch ($action) {
            case 'wdtg':
                $setval='wdtg';
                //本月订单
                $bytime=getthemonth(date('Y-m-d'));
                $dylist=M('orders')->where(array('code_jxs'=>$user_row['id'],'addtime'=>array('BETWEEN',array($bytime[0],$bytime[1]))))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($dylist as $key => $valshop) {
                    $dylist[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                    $dylist[$key]['fhtimess']=date('Y-m-d',$valshop['addtime']);
                    $dylist[$key]['jxjg']=jsjxjiage($valshop['ordernum']);
                    $dylist[$key]['ddztk']=ddztkj($valshop['orderstatus'],$valshop['commented'],$valshop['fahuofang']);
                }
                $this->assign('dylist',$dylist);

                //上月订单
                $bytime=getsymoth(1);
                $sylist=M('orders')->where(array('code_jxs'=>$user_row['id'],'addtime'=>array('BETWEEN',array($bytime[0],$bytime[1]))))->select();
                foreach ($sylist as $key => $valsdhsop) {
                    $sylist[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valsdhsop['ordernum']))->select();
                    $sylist[$key]['fhtimess']=date('Y-m-d',$valsdhsop['addtime']);
                    $sylist[$key]['jxjg']=jsjxjiage($valsdhsop['ordernum']);
                    $sylist[$key]['ddztk']=ddztkj($valsdhsop['orderstatus'],$valsdhsop['commented'],$valsdhsop['fahuofang']);
                }
                $this->assign('sylist',$sylist);


                $this->assign('setval',$setval);
                $this->display();
                break;
            case 'wdfhcont':
                $id=I('get.id')?I('get.id'):0;
                if($id == 0){
                    $this->error('参数错误！');
                    return;
                }

                $dylisst=M('orders')->where(array('id'=>$id))->find();
                $M_orders_buy = M('orders_buy');
                
                    $dylisst['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$dylisst['ordernum']))->select();
                    $dylisst['fhtimess']=date('Y-m-d',$dylisst['fhtime']);
                    $dylisst['jxjg']=jsjxjiage($dylisst['ordernum']);
                    $dylisst['ddztk']=ddztkj($dylisst['orderstatus'],$dylisst['commented'],$dylisst['fahuofang']);

                $jscary=M('jiesuan')->where(array('orderid'=>$id))->find();

                $this->assign('jscary',$jscary);
                $this->assign('dylist',$dylisst);
                $this->display('wdfhcont');
                break;
            default:
                /*var_dump(getthemonth(date('Y-m-d')));
                var_dump(getsymoth(1));*/
                //本月订单
                $bytime=getthemonth(date('Y-m-d'));
                $dylist=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','3,4'),'fhtime'=>array('BETWEEN',array($bytime[0],$bytime[1]))))->select();
                $M_orders_buy = M('orders_buy');
                foreach ($dylist as $key => $valshop) {
                    $dylist[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valshop['ordernum']))->select();
                    $dylist[$key]['fhtimess']=date('Y-m-d',$valshop['fhtime']);
                    $dylist[$key]['jxjg']=jsjxjiage($valshop['ordernum']);
                    $dylist[$key]['ddztk']=ddztkj($valshop['orderstatus'],$valshop['commented'],$valshop['fahuofang']);
                }
                $this->assign('dylist',$dylist);


                //上月订单
                $bytime=getsymoth(1);
                $sylist=M('orders')->where(array('fahuofang'=>$user_row['id'],'orderstatus'=>array('IN','3,4'),'fhtime'=>array('BETWEEN',array($bytime[0],$bytime[1]))))->select();
                foreach ($sylist as $key => $valsdhsop) {
                    $sylist[$key]['shoplist']=$M_orders_buy->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$valsdhsop['ordernum']))->select();
                    $sylist[$key]['fhtimess']=date('Y-m-d',$valsdhsop['fhtime']);
                    $sylist[$key]['jxjg']=jsjxjiage($valsdhsop['ordernum']);
                    $sylist[$key]['ddztk']=ddztkj($valsdhsop['orderstatus'],$valsdhsop['commented'],$valsdhsop['fahuofang']);
                }
                $this->assign('sylist',$sylist);

                $setval='wdfh';
                $this->assign('setval',$setval);
                $this->display();
                break;
        }
        
    }
    /*
    *确认结算页面
     */
    public function qrjsym(){
        $id=I('get.id');
        $this->assign('id',$id);
        $this->display();
    }
    /*
    *确认已经结算
     */
    public function qrjsbztx(){
        $id=I('post.ordid');
        $qrjsbz=I('post.qrjsbz');
        if($id == ''){
            $this->error('参数错误！');
            return;
        }
        if($qrjsbz == ''){
            $this->error('请输入结算备注！');
            return;
        }
        $orjszt=array(
            'sfjsset'           =>                  2
        );
        $jsdata=array(
            'qrbeizhu'         =>              $qrjsbz,
            'qrtime'            =>              time()
        );
        if(M('jiesuan')->where(array('orderid'=>$id))->save($jsdata)){
            M('orders')->where(array('id'=>$id))->save($orjszt);
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("操作完成！");location.href="'.U('myciwu').'"</script>';
        }else{
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
            return;
        }
    }
    /*
    *订单详情
     */
    public function ordercont(){
        $ordnum=I('get.ordnum');
        if($ordnum){
            $orderval=M('orders')->where(array('ordernum'=>$ordnum))->find();
            $orderbuy=M('orders_buy')->alias('b')->field('b.id as bid,b.*,p.id,p.titlepic,p.type,p.tejia')->join('__PRODUCTS__ p ON p.id=b.productid','LEFT')->where(array('ordernum'=>$ordnum))->select();
            $orderdel=M('orders_detail')->where(array('ordernum'=>$ordnum))->find();
            $this->assign('list',$orderval);
            $this->assign('orderbuy',$orderbuy);
            $this->assign('orderdel',$orderdel);
            $this->assign('setval','dfh');
            $this->display();
        }else{
            $this->error('参数错误！');
        }
    }
}
?>