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
<title>经销商中心</title>
</head>
<body>





<div class="jldb rol">
    <div class="rol jxs-tx">
        <div class="m-box">
            <div class="lt jxs-tx-txt">头像</div>
            <div class="gt">
                <a href="<?php echo U('Distri/mycont');?>"><img src="<?php echo ($list["headpic"]); ?>" alt="<?php echo ($list["myname"]); ?>"/></a>
            </div>
        </div>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('Mall/index');?>">
        <div class="m-box">
            <span class="jsx-cpzx1"></span>产品中心
        </div>
        </a>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('Distri/myorder');?>">
        <div class="m-box">
            <span class="jsx-cpzx2"></span>订单中心
        </div>
        </a>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('Distri/myciwu');?>">
        <div class="m-box">
            <span class="jsx-cpzx3"></span>账务中心
        </div>
       </a>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('Distri/mynews');?>">
        <div class="m-box">
            <span class="jsx-cpzx4"></span>信息中心
        </div>
       </a>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('Distri/myorcode');?>">
        <div class="m-box">
            <span class="jsx-cpzx5"></span>我的二维码
        </div>
       </a>
    </div>
    <div class="rol jxs-tx jxs-cpzx">
    	<a href="<?php echo U('UserInfo/exitlogin');?>">
        <div class="m-box">
            <span class="wdgwc6"></span>退出登陆
        </div>
       </a>
    </div>
</div>
<!--底部-->
<?php echo W('Base/showFooter');?>


</body>
</html>