<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="/Public/wx/css/jquery.bxslider.css">
<link rel="stylesheet" href="/Public/wx/css/style.css">
<script src="/Public/wx/js/jquery-1.9.1.min.js"></script>
<script src="/Public/wx/js/jquery.bxslider.js"></script>
<script src="/Public/wx/js/js.js"></script>
<meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>我的积分</title>
</head>
<body>

<header class="rol cre-header">
    <div class="cre-font1">当前积分为：</div>
    <div class="cre-font2"><?php echo (intval($corn_row["corn"])); ?></div>
</header>



<div class="rol cre-title">
    <div class="m-box">积分详情</div>
</div>
<div class="rol cre-box1">
    <div class="rol cre-rol1">
        <div class="m-box">
            <div class="lt cre-font3">推荐好友</div>
            <div class="lt cre-font4">+100</div>
        </div>
    </div>
    <div class="rol cre-rol1">
        <div class="m-box">
            <div class="lt cre-font3">购买商品</div>
            <div class="lt cre-font4">+200</div>
        </div>
    </div>
</div>
<div class="rol cre-title">
    <div class="m-box">积分说明</div>
</div>
<div class="rol cre-box2">
    <div class="m-box">
        <div class="rol cre-title2">如何获取积分</div>
        <div class="rol cre-font5">1.推荐注册获取积分</div>
        <div class="rol cre-font5">2.购物获取积分</div>
    </div>
</div>
<div class="rol cre-title">
    <div class="m-box">积分如何使用</div>
</div>
<div class="rol cre-box3">
    <div class="m-box">在商城购物时，总积分超过3500分，可兑换相应产品。</div>
</div>

<?php echo W('Base/showFooter');?>


</body>
</html>