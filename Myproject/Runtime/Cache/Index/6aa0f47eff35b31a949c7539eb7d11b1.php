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
<title><?php echo ($product_row["title"]); ?></title>
</head>
<body>

<header class="rol pro-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">产品详情</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>



<div class="rol pro-banner-rol">
    <img src="<?php echo ($product_row["titlepic"]); ?>" class="rol" alt="">
</div>
<div class="rol pro-1-box1">
    <div class="m-box">
        <div class="rol pro-1-title"><?php echo ($product_row["title"]); ?></div>
        <div class="rol pro-f-box">
            <span class="lt pro-money">￥<?php echo ($product_row["price"]); ?></span>
            <span class="lt pro-num">已售出：<?php echo $product_row['salenum']+$product_row['vsalenum'];?>件</span>
        </div>
        <div class="rol pro-1-font2"><?php if($product_row['postfee']) {echo '邮费：'.$product_row['postfee'].'元';} else {echo '是否包邮：包邮';} ?></div>
        <div class="rol pro-1-font2">
            <div class="lt">购买数量</div>
            <div class="lt pro-num-box">
                <div class="lt pro-num-jian">-</div>
                <form action="<?php echo U('Orders/doorder');?>" method="get" id="buyForm">
                <input type="hidden" name="productid" value="<?php echo ($product_row["id"]); ?>">
                <input type="tel" value="1" name="productnum" class="lt pro-num-input"/>
                </form>
                <div class="lt pro-num-jia">+</div>
            </div>
        </div>
        <div class="rol pro-1-font2">剩余数量：<?php echo $product_row['totalnum']-$product_row['salenum']; ?>件</div>
        <div class="rol pro-1-btn-box">
            <a href="<?php echo U('product', array('id'=>$product_row['id']));?>" class="lt pro-1-btn pro-1-btn2">查看详情</a>
            <a href="<?php echo U('comment', array('id'=>$product_row['id']));?>" class="lt pro-1-btn pro-1-btn1">评价</a>
        </div>
    </div>
</div>
<div class="rol">
    <div class="m-box">
        <?php echo (htmlspecialchars_decode($product_row["content"])); ?>
    </div>
</div>

<div class="rol" style="position: fixed;bottom: 0;left: 0">
    <div id="addcart" class="lt pro-f-btn pro-f-btn2">
        加入购物车
    </div>
    <div class="lt pro-f-btn pro-f-btn1" id="buybtn">
        立即购买
    </div>
</div>
<script>
$(function(){
    $("#addcart").click(function(event) {
        var productnum = $("input[name='productnum']").val();
        if (productnum < 1) {
            alert('请填写购买数量！');
            return false;
        }

        $.ajax({
            url: '<?php echo U("Mall/addcart");?>',
            type: 'POST',
            dataType: 'json',
            data: {'productid': '<?php echo $product_row["id"];?>', 'productnum': productnum},
        })
        .done(function(data) {
            if (data.status=='0') {
                alert(data.info);
                window.location.href = data.url;
                return false;
            }
            
            alert(data.msg);

            if (data.code=='-1') {
                window.location.href = data.url;
            }
            return false;
        });
    });

    $("#buybtn").click(function(event) {
        var productnum = $("input[name='productnum']").val();
        if (productnum < 1) {
            alert('请填写购买数量！');
            return false;
        }
        $("#buyForm").submit();
    });
});
</script>
<div class="index-fixed-box">
    <a href="<?php echo U('Index/recommend');?>" class="fixed-btn rol">
        <img src="/Public/wx/img/fixed-1.png" alt="">
    </a>
    <a href="<?php echo U('Orders/shopcart');?>" class="fixed-btn rol">
        <img src="/Public/wx/img/fixed-2.png" alt="">
    </a>
    <a href="#" class="fixed-btn rol">
        <img src="/Public/wx/img/fixed-3.png" alt="">
    </a>
</div>


</body>
</html>