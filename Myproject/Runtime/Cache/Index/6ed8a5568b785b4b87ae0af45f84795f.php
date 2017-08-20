<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">


    <link rel="stylesheet" href="/Public/index/css/bootstrap.css"/>
    <link rel="stylesheet" href="/Public/index/css/style.css"/>
    <script src="/Public/index/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/index/js/js.js"></script>
    <script src="/Public/index/js/jquery.js"></script>
    <script src="/Public/index/js/myjs.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>个人中心</title>
</head>
<body>





<div class="rol grzx-bac">
    <div class="grzx-top-mb">
    	<a href="<?php echo U('UserInfo/mycont');?>">
       <div class="m-box">
           <img class="grzx-tx" src="<?php echo ($user_row["headpic"]); ?>" alt=""/>
           <div class="grzx-yhm rol">
              <b></b><span><?php echo ($user_row["myname"]); ?></span><b></b>
           </div>
       </div>
       </a>
    </div>
</div>
<div class="rol">
    <div class="m-box">
        <a href="<?php echo U('Orders/serv');?>" class="wddd">
           <div class="lt wddd-lt">
               <span></span>  我的订单
           </div>
           <div class="gt wddd-gt">
                查看全部订单<span></span>
           </div>
        </a>
    </div>
</div>
<div class="rol grzx-nav">
    <a href="<?php echo U('Orders/index');?>">
        <b class="grzx-tb1"></b>
        <div>待付款</div>
    </a>
    <a href="<?php echo U('Orders/daigfahuo');?>">
        <b class="grzx-tb2"></b>
        <div>待发货</div>
    </a>
    <a href="<?php echo U('Orders/dashouhuo');?>">
        <b class="grzx-tb3"></b>
        <div>待收货</div>
    </a>
    <a href="<?php echo U('Orders/pingjia');?>">
        <b class="grzx-tb4"></b>
        <div>已完成</div>
    </a>
</div>
<div class="rol grzx-gwc">
    <a href="<?php echo U('Orders/newsgwc');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc1"></span>我的购物车
            </div>
            <b class="gt grzx-rjt"></b>
        </div>

    </a>
    <a href="<?php echo U('Orders/myshoucang');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc2"></span>我的收藏
            </div>
            <b class="gt grzx-rjt"></b>
        </div>
    </a>
    <a href="<?php echo U('User/myxiaoxi');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc3"></span>我的消息
            </div>
            <b class="gt grzx-rjt"></b>
        </div>
    </a>
    <a href="<?php echo U('User/mydizhi');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc4"></span>我的收货地址
            </div>
            <b class="gt grzx-rjt"></b>
        </div>
    </a>
    <a href="<?php echo U('UserInfo/myeitepwd');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc5"></span>修改密码
            </div>
            <b class="gt grzx-rjt"></b>
        </div>
    </a>
    <a href="<?php echo U('UserInfo/exitlogin');?>">
        <div class="m-box">
            <div class="lt">
                <span class="wdgwc6"></span>退出登陆
            </div>
            <b class="gt grzx-rjt"></b>
        </div>
    </a>
</div>
<!--底部-->
<?php echo W('Base/showFooter');?>


</body>
</html>