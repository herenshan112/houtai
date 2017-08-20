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
<title>在线支付</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">在线支付</div>
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
        <?php if($order_row['invoice'] != ''): ?><div class="rol" style="font-size: 24px;">
        <p><span style="color:#29c7ea ;font-size: 30px;">发票信息：</span><br>抬头：<?php echo ($order_row["invoice"]); ?><br>项目：食品<br>金额：<?php echo ($order_row["money"]); ?><br></p>
        </div><?php endif; ?>
    </div>
</div>
<div class="rol qy-font1 center">
    支付方式
</div>
<div class="rol qy-box2">
    <div class="rol qy-rol2 paytype" data-paytype="1">
        <div class="m-box">
            <div class="lt qy-lt-2">
                <img src="/Public/wx/img/qy-i1.png" class="qy-i1" alt="">
            </div>
            <div class="lt" style="margin-top: 46px; margin-left:10px;font-size: 24px;">微信支付</div>
            <img src="/Public/wx/img/qy-on.png" class="gt qy-btn" alt="">
        </div>
    </div>
    <!--div class="rol qy-rol2 paytype" data-paytype="2">
        <div class="m-box">
            <div class="lt qy-lt-2">
                <img src="/Public/wx/img/qy-i2.png" class="qy-i2" alt="">
            </div>
            <div class="lt" style="margin-top: 46px; margin-left:10px;font-size: 24px;">支付宝支付</div>
            <img src="/Public/wx/img/qy-off.png" class="gt qy-btn" alt="">
        </div>
    </div-->
    <div class="rol qy-rol2 paytype" data-paytype="3">
        <div class="m-box">
            <div class="lt qy-lt-2">
                <img src="/Public/wx/img/corn_buy.png" class="qy-i2" alt="">
            </div>
            <div class="lt" style="margin-top: 46px; margin-left:10px;font-size: 24px;">积分兑换</div>
            <img src="/Public/wx/img/qy-off.png" class="gt qy-btn" alt="">
        </div>
    </div>
</div>

<div class="rol qy-box3">
    <div class="m-box">
        <div class="lt" style="font-size: 26px;">共<?php echo ($corn_row["corn"]); ?>积分，本次兑换需<?php echo ($order_row["corn"]); ?>积分</div>
        <!--input class="gt qy-btn-1" value="0"-->
    </div>
</div>

<div class="rol qy-box4">
    <div class="m-box">
        <div class="lt">总计<span>￥<?php echo ($order_row["money"]); ?></span></div>
        <form action="/Index/Orders/pay" method="POST">
            <input type="hidden" name="id" value="<?php echo ($order_row["id"]); ?>">
            <input type="hidden" name="type" value="1">
            <button type="submit" class="gt qy-btn-3" style="border: none;">立即支付</button>
        </form>
        
    </div>
</div>

<?php echo W('Base/showFooter');?>
<script>
$(function(){
    $(".paytype").click(function(event) {
        var pay_type = $(this).data('paytype');

        if (pay_type=='3') {
            if (<?php echo $corn_row['corn']; ?> < 3500) {
                alert('您当前积分为：<?php echo $corn_row['corn']; ?>分\n总积分为3500分及以上才可以兑换商品。\n您当前不符合积分兑换条件，请选择其他支付方式。');
            } else if (<?php echo $corn_row['corn']; ?> < <?php echo $order_row['corn']; ?>) {
                alert('您当前积分为：<?php echo $corn_row['corn']; ?>分\n支付商品需要<?php echo $order_row['corn']; ?>分，请选择其他支付方式。');
            } else {
                $("input[name='type']").val(pay_type);
                $('.paytype .qy-btn').attr('src', '/Public/wx/img/qy-off.png');
                $(this).children('.m-box').children('.qy-btn').attr('src', '/Public/wx/img/qy-on.png');
            }
        } else {
            $("input[name='type']").val(pay_type);

            $('.paytype .qy-btn').attr('src', '/Public/wx/img/qy-off.png');
            $(this).children('.m-box').children('.qy-btn').attr('src', '/Public/wx/img/qy-on.png');
        }
    });
});
</script>


</body>
</html>