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
<title>订单中心</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">订单中心</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>



<div class="rol order-nav-box">
    <div class="m-box">
        <a href="<?php echo U('index');?>" class="lt order-nav-btn<?php echo $status==1 ? ' on':'';?>">
            <div class="rol order-icon-rol center">
                <img src="/Public/wx/img/o1-<?php echo $status==1 ? 'on':'off';?>.png" alt="">
            </div>
            <div class="rol order-1-font">待付款</div>
        </a>
        <a href="<?php echo U('send');?>" class="lt order-nav-btn<?php echo $status==2 ? ' on':'';?>">
            <div class="rol order-icon-rol center">
                <img src="/Public/wx/img/o2-<?php echo $status==2 ? 'on':'off';?>.png" alt="">
            </div>
            <div class="rol order-1-font">待发货</div>
        </a>
        <a href="<?php echo U('recv');?>" class="lt order-nav-btn<?php echo $status==3 ? ' on':'';?>">
            <div class="rol order-icon-rol center">
                <img src="/Public/wx/img/o3-<?php echo $status==3 ? 'on':'off';?>.png" alt="">
            </div>
            <div class="rol order-1-font">待收货</div>
        </a>
        <a href="<?php echo U('pj');?>" class="lt order-nav-btn<?php echo $status==4 ? ' on':'';?>">
            <div class="rol order-icon-rol center">
                <img src="/Public/wx/img/o4-<?php echo $status==4 ? 'on':'off';?>.png" alt="">
            </div>
            <div class="rol order-1-font">待评价</div>
        </a>
        <a href="<?php echo U('serv');?>" class="lt order-nav-btn on">
            <div class="rol order-icon-rol center">
                <img src="/Public/wx/img/o5-on.png" alt="">
            </div>
            <div class="rol order-1-font">全部订单</div>
        </a>
    </div>
</div>

<?php if(is_array($order_rows)): foreach($order_rows as $key=>$order_row): ?><div class="rol order-1-box">
    <div class="rol order-1-rol1">
        <?php if(is_array($order_row['items'])): foreach($order_row['items'] as $key=>$pro_item): ?><div class="m-box">
            <div class="order-lt-1 lt">
                <img src="<?php echo ($pro_item["titlepic"]); ?>" style="max-height: 160px; max-width: 160px; vertical-align: top;" alt="">
            </div>
            <div class="order-gt-1 lt">
                <div class="rol order-1-title">
                    <div class="lt"><?php echo ($pro_item["title"]); ?></div>
                    <div class="gt">￥<?php echo ($pro_item["price"]); ?></div>
                </div>
                <!--div class="rol order-1-font2">益生菌粉固体饮料</div-->
                <div class="rol order-1-font2 right">数量：<?php echo ($pro_item["num"]); ?></div>
            </div>
        </div><?php endforeach; endif; ?>
    </div>
    <div class="rol order-1-rol2">
        <div class="m-box">
            <div class="lt" style="line-height: 55px;">订单编号：<?php echo ($order_row["ordernum"]); ?></div>

            <?php if($order_row['orderstatus'] == 1): ?><a href="<?php echo U('cancel', array('id'=>$order_row['id']));?>" class="gt or-btn1">取消订单</a>
            <a href="<?php echo U('pay', array('id'=>$order_row['id']));?>" class="gt or-btn">去付款</a>
            <?php elseif($order_row['orderstatus'] == 2): ?>
            <a href="<?php echo U('detail', array('id'=>$order_row['id']));?>" class="gt or-btn">查看订单</a>
            <?php elseif($order_row['orderstatus'] == 3): ?>
            <a href="<?php echo U('receive', array('id'=>$order_row['id']));?>" class="gt or-btn">确定收货</a>
            <a href="<?php echo U('wuliu', array('id'=>$order_row['id']));?>" class="gt or-btn">物流信息</a>
            <?php elseif($order_row['orderstatus'] == 4): ?>
                <?php if($order_row['commented'] == 0): ?><a href="<?php echo U('pjorder', array('id'=>$order_row['id']));?>" class="gt or-btn">去评价</a>
                <?php else: ?>
                <div class="gt or-btn">已完成</div><?php endif; endif; ?>
        </div>
    </div>
</div><?php endforeach; endif; ?>

<?php echo W('Base/showFooter');?>


</body>
</html>