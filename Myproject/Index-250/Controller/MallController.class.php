<?php
namespace Index\Controller;
use Think\Controller;
class MallController extends Controller {
    /**
     * 商城首页
     */
    public function index() {
        $M_products = M('products');

        $p_keywords = I('get.keywords', '', 'trim');
        
        $map['title'] = array('like', "%".$p_keywords."%");
        
        $product_rows = $M_products->where($map)->order('showturn ASC')->select();
        
        $this->product_rows = $product_rows;
        
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
    
        $M_user = M('user');
    
        $user_row = $M_user->where(array('phone'=>$c_phone))->find();
    
        return $user_row;
    }
}