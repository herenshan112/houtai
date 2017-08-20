<?php
namespace Index\Controller;
use Think\Controller;
use think\Model;
use PublicClass\PagesusAjax;

class MallController extends Controller {
    /**
     * 商城首页
     */
    public function index() {
        
        $codeval=I('get.code')?I('get.code'):0;
        //echo $codeval;
        $jumtg=jumptgly($codeval);
        /*echo '<br><br><br><br><br>';
        var_dump($jumtg);
        echo '<br><br><br><br><br>';
        var_dump(session('tuiguang'));*/
        //热门分类
        $this->assign('typelist',M('produtype')->where(array('setval'=>1))->order(array('xuhao'=>'asc','id'=>'desc'))->select());
        //昨日热销
        $proum=M('products');
        $this->assign('zrhotx',$proum->alias('p')->field('p.id as pid,p.*,t.title as tyname')->join('__PRODUTYPE__ t ON t.id=p.type','LEFT')->where(array('tejia'=>1))->order(array('salenum'=>'desc','showturn'=>'asc','p.id'=>'desc'))->limit(4)->select());
        //新品推荐
        $this->assign('xinptj',$proum->alias('p')->field('p.id as pid,p.*,t.title as tyname')->join('__PRODUTYPE__ t ON t.id=p.type','LEFT')->where(array('tuijian'=>1))->order(array('showturn'=>'asc','p.id'=>'desc'))->limit(4)->select());
        
        $this->display();
    }
    
    /**
     * 商品页面
     */
    public function product() {
        $M_products = M('products');
        
        $id = I('get.id', '', 'intval');
        
        if (!$id) $this->redirect('index');
        
        $product_row = $M_products->find($id);
        if (!$product_row) $this->redirect('index');
        
        $this->product_row = $product_row;
        
        $this->display();
    }
    
    /**
     * 商品评论
     */
    public function comment() {
        $M_products = M('products');
    
        $id = I('get.id', '', 'intval');
    
        if (!$id) $this->redirect('index');
    
        $product_row = $M_products->find($id);
        if (!$product_row) $this->redirect('index');
        
        $M_user = M('user');
        
        // 获取评论
        $M_pcomments = M('pcomments');
        $comment_rows = $M_pcomments->where(array('productid'=>$product_row['id'], 'isshow'=>1))->order('addtime DESC')->limit(30)->select();
        
        foreach ($comment_rows as $k=>$v) {
            $comment_rows[$k]['info'] = $M_user->where('id=' . $v['userid'])->field('nickname, headpic')->find();
        }
    
        $this->product_row = $product_row;
        
        $this->comment_rows = $comment_rows;
    
        $this->display();
    }
    
    /**
     * 加入购物车
     */
    public function addcart() {
        if (IS_AJAX) {
            $user_row = $this->getUser();
    
            if (!$user_row) {
                $this->ajaxReturn(array(
                    'code' => '-1',
                    'msg' => '请登录后再操作！',
                    'url' => U('User/index')
                ));
            }
    
            // 获取参数
            $p_pid = I('post.productid', '0', 'intval');
            $p_num = I('post.productnum', '1', 'intval');
    
            if (!$p_pid OR !$p_num) {
                $this->ajaxReturn(array(
                    'code' => '-2',
                    'msg' => '参数提交有误！'
                ));
            }
            
            // 检验商品
            $M_products = M('products');
            $product_row = $M_products->where(array('id'=>$p_pid))->find();
            if (!$product_row OR ($product_row['totalnum']-$product_row['salenum'])<=0) {
                $this->ajaxReturn(array(
                    'code' => '-2',
                    'msg' => '商品不存在或已下架！'
                ));
            }
            
            // 加入购物车
            $M_shopcart = M('shopcart');
            $cart_row = $M_shopcart->where(array('uid'=>$user_row['id'], 'productid'=>$p_pid))->find();
            if ($cart_row) {
                // 更新数量
                $cart_row['num'] = $cart_row['num'] + $p_num;
                $added = $M_shopcart->save($cart_row);
                
            } else {
                // 新增
                $added = $M_shopcart->add(array(
                    'uid' => $user_row['id'],
                    'productid'=>$p_pid,
                    'num' => $p_num,
                    'addtime' => date('Y-m-d H:i:s')
                ));
            }
            if ($added) {
                $this->ajaxReturn(array(
                    'code' => '-2',
                    'msg' => '加入购物车成功！'
                ));
            } else {
                $this->ajaxReturn(array(
                    'code' => '-2',
                    'msg' => '加入购物车失败！'
                ));
            }
        } else {
            $this->ajaxReturn(array(
                'code' => '-2',
                'msg' => '数据提交异常！'
            ));
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
    
        //$M_user = M('user');
    
        //$user_row = $M_user->where(array('phone'=>$c_phone))->find();
    
        return getuscont();
    }
    /*
    *商品列表
     */
    public function prolist(){
        $action=I('get.action')?I('get.action'):'all';
        $proum=M('products');
        $mod=new Model();
        $tj='';
        $cptitle='产品中心';
        switch ($action) {
            case 'tj':
                $tj=' AND p1.tejia=6 ';
                break;
            case 'xp':
                $tj=' AND p1.tejia=3 ';
                break;
            case 'tuij':
                $tj=' AND p1.tuijian=1 ';
                break;
            case 'type':
                $tpval=I('get.tpval');
                $tj=' AND p1.type='.$tpval;
                $this->assign('tpval',$tpval);
                $cpmc=M('produtype')->where(array('id'=>$tpval))->find();
                $cptitle=$cpmc['title'];
                break;
            default:
                $tj='';
                break;
        }
        $orders_list=$mod->query('SELECT p1.id as pid,p1.*,t.title as tyname FROM ms_products p1 LEFT JOIN ms_produtype t ON t.id=p1.type,ms_products p2 WHERE p1.salenum < p2.totalnum AND p1.totalnum >p2.salenum AND p1.id=p2.id '.$tj.' ORDER BY id DESC LIMIT 10');
        $this->assign('action',$action);
        $this->assign('list',$orders_list);
        $this->assign('cptitle',$cptitle);
        $this->display();
    }
    /*
    *商品多条件排序
     */
    public function paixulist(){
        $action=I('post.action')?I('post.action'):'all';
        $page=I('post.p')?I('post.p'):1;

        $setlst=I('post.setlst')?I('post.setlst'):0;
        $tpval=I('post.tpval');

        $action=$_POST['action']?$_POST['action']:'all';
        $page=$_POST['page']?$_POST['page']:1;
        $setlst=$_POST['setlst']?$_POST['setlst']:0;
        $tpval=$_POST['tpval'];

        $proum=M('products');
        $mod=new Model();
        $tj='';
        $px='';
        switch ($action) {
            case 'tj':
                $tj=' AND p1.tejia=6 ';
                break;
            case 'xp':
                $tj=' AND p1.tejia=3 ';
                break;
            case 'tuij':
                $tj=' AND p1.tuijian=1 ';
                break;
            case 'type':
                
                $tj=' AND p1.type='.$tpval;
                //$this->assign('tpval',$tpval);
                break;
            default:
                $tj='';
                break;
        }
        switch ($setlst) {
            case 1:
                $px=' salenum DESC,id DESC ';
                break;
            case 2:
                $px=' tejiaprice asc,price asc,id DESC ';
                break;
            case 3:
                $px=' salenum DESC,id DESC ';
                break;
            default:
                $px=' id DESC';
                break;
        }
        $countdd=$orders_list=$mod->query('SELECT p1.id as pid,p1.*,t.title as tyname FROM ms_products p1 LEFT JOIN ms_produtype t ON t.id=p1.type,ms_products p2 WHERE p1.salenum < p2.totalnum AND p1.totalnum >p2.salenum AND p1.id=p2.id '.$tj.' ORDER BY '.$px);
        $count=count($countdd);
        $Page = new PagesusAjax($count, 10);

        $orders_list=$mod->query('SELECT p1.id as pid,p1.*,t.title as tyname FROM ms_products p1 LEFT JOIN ms_produtype t ON t.id=p1.type,ms_products p2 WHERE p1.salenum < p2.totalnum AND p1.totalnum >p2.salenum AND p1.id=p2.id '.$tj.' ORDER BY '.$px.' LIMIT '.$Page->firstRow.','.$Page->listRows);
        if($orders_list){
            $ajaxcont=array(
                'code'          =>              1,
                'msg'           =>              '查询成功！',
                'infor'         =>              array(
                    'sum'           =>              count($orders_list),
                    'cont'          =>              $orders_list
                )
            );
            echo json_encode($ajaxcont);
        }else{
            echo json_encode(array('code'=>0,'msg'=>'没有查询到商品'));
        }
    }
    /*
    *商品详情
     */
    public function procont(){
        $id=I('get.id');
        if(!$id){
            $this->error('参数提交有误！');
            return;
        }

        $list=M('products')->alias('p')->field('p.id as pid,p.title as ptel,p.*,t.id as tid,t.*')->join('__PRODUTYPE__ t ON t.id=p.type','LEFT')->where(array('p.id'=>$id))->find();
        //配件
        if($list['parts'] != '0'){
            $pjls=M('products')->where(array('id'=>array('IN',$list['parts'])))->select();
        }else{
            $pjls='';
        }
        
        $uidv=0;
        if(cookie('u_idval')){
            $sctbxs=jumpshouchang(cookie('u_idval'),$id);
            $uidv=cookie('u_idval');
        }else{
            $sctbxs=0;
        }

        $this->assign('sctbxs',$sctbxs);
        $this->assign('uidv',$uidv);
        //$this->pjls=$pjls;
        $this->assign('pjls',$pjls);
        $this->assign('pjlsfb',$pjls);
        $this->assign('list',$list);
        $this->display();
    }
    /*
    *收藏处理
     */
    public function shoucangcl(){
        $uid=I('post.uid')?I('post.uid'):0;
        $pid=I('post.pid')?I('post.pid'):0;
        if($uid == 0 || $pid == 0){
            echo json_encode(array('code'=>0,'msg'=>'收藏失败！'));
            return false;
        }
        if(cookie('u_idval') != $uid){
            echo json_encode(array('code'=>3,'msg'=>'收藏失败！'));
            return false;
        }
        
        $scm=M('mysc');
        $data['sc_uid']=$uid;
        $data['sc_proid']=$pid;
        if($scm->where($data)->find()){
            if($scm->where($data)->delete()){
                echo json_encode(array('code'=>2,'msg'=>'取消收藏成功'));
                return false;
            }else{
                echo json_encode(array('code'=>0,'msg'=>'取消收藏失败'));
                return false;
            }
        }else{
            $data['sc_time']=time();
            if($scm->add($data)){
                echo json_encode(array('code'=>1,'msg'=>'收藏成功'));
                return false;
            }else{
                echo json_encode(array('code'=>0,'msg'=>'收藏失败！'));
                return false;
            }
        }
    }
    /*
    *加入购物车
    *  @uid        会员id
    *  @shopid     商品id
    *  @shopsum    商品数量
    *  @shoppj     配件
     */
    public function addgwc(){
        $uid=I('post.uid', '0', 'intval');
        $shopid=I('post.shopid', '0', 'intval');
        $shopsum=I('post.shopsum', '1', 'intval');
        $shoppj=I('post.shoppj')?I('post.shoppj'):0;
        
        if($uid <= 0){
          echo json_encode(array('code'=>2,'msg'=>'请登陆'));
          return false;
        }
        if(cookie('u_idval') != $uid){
            echo json_encode(array('code'=>2,'msg'=>'请登陆'));
            return false;
        }
        if($shopid <= 0){
          echo json_encode(array('code'=>0,'msg'=>'加入购物车失败！请重新选择商品！'));
          return false;
        }
        $jmpshop=M('products')->where(array('id'=>$shopid))->find();
        if($jmpshop){
            if($jmpshop['totalnum']-$jmpshop['salenum'] <= 0){
                echo json_encode(array('code'=>0,'msg'=>'商品不存在或已下架！'));
                return false;
            }
        }else{
            echo json_encode(array('code'=>0,'msg'=>'商品不存在或已下架！'));
            return false;
        }
        // 加入购物车
        $M_shopcart = M('shopcart');
        //判断是否存在
        $cart_row = $M_shopcart->where(array('uid'=>$uid, 'productid'=>$shopid))->find();
        if ($cart_row) {

            if($shoppj != 0){
                $oldpj = explode(',',$shoppj);
                foreach ($oldpj as $valpj) {
                    self::peijiadabao($uid,$valpj);
                }
            }
            // 更新数量
            $savedata['num'] = $cart_row['num'] + $shopsum;

            $savedata['addtime']=time();
            $added = $M_shopcart->where(array('uid'=>$uid, 'productid'=>$shopid))->save($savedata);
        }else{
            $adddata=array(
                'uid'                   =>                  $uid,
                'productid'             =>                  $shopid,
                'num'                   =>                  $shopsum,

                'addtime'               =>                  time()
            );
            if($shoppj != 0){
                $oldpj = explode(',',$shoppj);
                foreach ($oldpj as $valpj) {
                    self::peijiadabao($uid,$valpj);
                }
            }
            $added = $M_shopcart->add($adddata);
        }
        if ($added) {
            echo json_encode(array('code'=>1,'msg'=>'加入购物车成功！'));
            return false;
        }else{
            echo json_encode(array('code'=>0,'msg'=>'加入购物车失败！'));
            return false;
        }
    }

    //配件打包
    static function peijiadabao($puid=0,$ppid=0){
        $M_shrtpj = M('shopcart');
        $carpj = $M_shrtpj->where(array('uid'=>$puid, 'productid'=>$ppid))->find();
        if ($carpj) {
            $carpj['num'] = $carpj['num'] + 1;
            $carpj['addtime']=time();
            $dedpj = $M_shrtpj->where(array('uid'=>$puid, 'productid'=>$ppid))->save($carpj);
        }else{
            $adatpj=array(
                'uid'                   =>                  $puid,
                'productid'             =>                  $ppid,
                'num'                   =>                  1,
                'addtime'               =>                  time()
            );
            $dedpj = $M_shrtpj->add($adatpj);
        }
    }


    /*
    *立即购买
    *  @uid        会员id
    *  @shopid     商品id
    *  @shopsum    商品数量
    *  @shoppj     配件
     */
    public function ljxdcl(){
        $uid=I('post.uid', '0', 'intval');
        $shopid=I('post.shopid', '0', 'intval');
        $shopsum=I('post.shopsum', '1', 'intval');
        $shoppj=I('post.shoppj')?I('post.shoppj'):0;
        
        if($uid <= 0){
          echo json_encode(array('code'=>2,'msg'=>'请登陆'));
          return false;
        }
        /*if(cookie('u_idval') != $uid){
            echo json_encode(array('code'=>2,'msg'=>'请登陆'));
            return false;
        }*/
        if($shopid <= 0){
          echo json_encode(array('code'=>0,'msg'=>'生成订单失败！请重新选择商品！'));
          return false;
        }
        $jmpshop=M('products')->where(array('id'=>$shopid))->find();
        if($jmpshop){
            if($jmpshop['totalnum']-$jmpshop['salenum'] <= 0){
                echo json_encode(array('code'=>0,'msg'=>'商品不存在或已下架！'));
                return false;
            }
        }else{
            echo json_encode(array('code'=>0,'msg'=>'商品不存在或已下架！'));
            return false;
        }

        $ordernum=ordernumndle();
        $tgly=$fhf=jumptglaiyuan();

        $jb=0;

        if($shoppj != 0){
            $sppjary=explode(',',$shoppj);
            foreach ($sppjary as $key => $spary) {
                //echo $spary;
                $jb+=self::writeshopord($ordernum,$spary,1,$uid);
            }
        }
        

        $ljgmlok=M('products')->field('id,title,tejiaprice,price,shuxing')->where(array('id'=>$shopid))->find();
        $ljmjage=shoppiceqr($ljgmlok['id']);
        $addljmbuy=array(
            'ordernum'              =>              $ordernum,
            'productid'             =>              $ljgmlok['id'],
            'title'                 =>              $ljgmlok['title'],
            'num'                   =>              $shopsum,
            'price'                 =>              $ljmjage,
            'tejiaprice'            =>              $ljgmlok['tejiaprice'],
            'spyuanjia'             =>              $ljgmlok['price'],
            'shuxing'               =>              $ljgmlok['shuxing']
        );
        $adlmbuy=M('orders_buy')->add($addljmbuy);

        $jb+=$ljmjage*$shopsum;

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
        $ddxr=M('orders')->add($ordaddcont);
        if($ddxr){
            $user_row = getuscont();
            $lcnr='客户：'.$user_row['username'].'&nbsp;'.$user_row['nickname'].'&nbsp;'.$user_row['phone'].'于'.date('Y-m-d H:i:s').'向系统下单';
            $odlc=ordliucheng($ddxr,$lcnr);
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
            $gwclok=M('products')->field('id,title,tejiaprice,price,shuxing')->where(array('id'=>$gwcid))->find();
            $xsjage=shoppiceqr($gwclok['id']);
            $addorsbuy=array(
                'ordernum'              =>              $ordnu,
                'productid'             =>              $gwclok['id'],
                'title'                 =>              $gwclok['title'],
                'num'                   =>              $num,
                'price'                 =>              $xsjage,
                'tejiaprice'            =>              $gwclok['tejiaprice'],
                'spyuanjia'             =>              $gwclok['price'],
                'shuxing'               =>              $gwclok['shuxing']
            );
            $adbuy=M('orders_buy')->add($addorsbuy);
            return $xsjage*$num;
        }
    }
    /*
    *失败删除订单商品
     */
    static function deltordshop($ordnum){
        $sccz=M('orders_buy')->where(array('ordernum'=>$ordnum))->delete();
    }









}