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
<title>确认收货</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">确认收货</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>



<div class="rol qy-box">
    <div class="m-box">
        <div class="rol" style="font-size: 24px;">
        <p><span style="color:#29c7ea ;font-size: 30px;">订单信息：</span><br>
        <?php if(is_array($order_buy_rows)): foreach($order_buy_rows as $key=>$order_buy_rows_item): echo ($order_buy_rows_item["title"]); ?> &yen;<?php echo ($order_buy_rows_item["price"]); ?> * <?php echo ($order_buy_rows_item["num"]); ?><br><?php endforeach; endif; ?>
        </p>
        </div>
        <div class="rol" style="font-size: 24px;">
        <p><span style="color:#29c7ea ;font-size: 30px;">收货信息：</span><br>收货人：<?php echo ($order_detail_row["uname"]); ?><br>电话：<?php echo ($order_detail_row["uphone"]); ?><br>收货地址：<?php echo ($order_detail_row["uaddr"]); ?><br></p>
        </div>
        <div class="rol" style="font-size: 24px;">
        <p><span style="color:#29c7ea ;font-size: 30px;">支付信息：</span><br>支付方式：<?php if($order_row['paytype'] == 0): ?>积分兑换<br>支付积分：<?php echo ($order_row["corn"]); elseif($order_row['paytype'] == 1): ?>微信支付<br>金额：&yen;<?php echo ($order_row["money"]); elseif($order_row['paytype'] == 2): ?>支付宝支付<br>金额：&yen;<?php echo ($order_row["money"]); endif; ?><br></p>
        </div>
    </div>
</div>

<div class="rol" style="margin-top: 20px;">
    <div class="m-box">
        <form action="/Index/Orders/receive" method="POST">
        <input type="hidden" name="id" value="<?php echo ($order_row["id"]); ?>">
        <button type="submit" class="rol sign-submit" id="goreg">确认收货</button>
        </form>
    </div>
</div>

<?php echo W('Base/showFooter');?>


</body>
</html>