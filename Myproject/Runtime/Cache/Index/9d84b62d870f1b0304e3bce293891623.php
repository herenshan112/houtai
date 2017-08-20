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
<title>订单确认</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">订单确认</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>




<div class="rol qy-box">
    <div class="m-box">
        <?php if(is_array($order_list)): foreach($order_list as $key=>$order_list_item): ?><div class="qy-rol1 rol">
            <div class="lt qy-lt">
                <img src="<?php echo ($order_list_item["detail"]["titlepic"]); ?>" class="rol" alt="">
            </div>
            <div class="lt qy-gt">
                <div class="rol qy-title"><?php echo ($order_list_item["detail"]["title"]); ?></div>
                <div class="rol qy-font">
                    <div class="lt"><span style="color: #29c7ea">￥<?php echo ($order_list_item["detail"]["price"]); ?></span> <span style="color: #F00">x</span> <?php echo ($order_list_item["num"]); ?></div>
                </div>
            </div>
        </div><?php endforeach; endif; ?>
    </div>
</div>

<div class="rol qy-box3" style="height: 0"></div>

<form action="<?php echo U('order');?>" method="POST">
<div class="rol z-place-rol">
    <div class="m-box">
        <div class="rol">
            <div class="lt z-place-lt">收货人</div>
            <input class="lt z-place-gt" name="uname" id="uname" placeholder="请输入姓名" value="<?php echo ($his_detail["uname"]); ?>" />
        </div>
    </div>
</div>
<div class="rol z-place-rol">
    <div class="m-box">
        <div class="rol">
            <div class="lt z-place-lt">电话</div>
            <input class="lt z-place-gt" name="uphone" id="uphone" placeholder="请输入电话号码" value="<?php echo ($his_detail["uphone"]); ?>" />
        </div>
    </div>
</div>
<div class="rol z-place-rol">
    <div class="m-box">
        <div class="rol">
            <div class="lt z-place-lt">收货地址</div>
            <input class="lt z-place-gt" name="uaddr" id="uaddr" placeholder="请输入详细收货地址" value="<?php echo ($his_detail["uaddr"]); ?>" />
        </div>
    </div>
</div>

<div class="rol qy-box3" style="height: 0"></div>
<div class="rol z-place-rol">
    <div class="m-box">
        <div class="rol">
            <div class="lt z-place-lt" style="width: 480px; margin-right: 10px;">是否需要开具发票?(只提供电子发票)</div>
            <div class="lt z-place-gt" style="width: 160px; text-align: right;">
                <label>否<input type="radio" name="isinvoice" value="0" checked="" /></label>
                <label style="padding-left: 20px;">是<input type="radio" name="isinvoice" value="1" /></label>
            </div>
        </div>
    </div>
</div>
<div id="invoicebox" style="display: none;">
    <div class="rol z-place-rol">
        <div class="m-box">
            <div class="rol">
                <div class="lt z-place-lt" style="width: auto;">开具项目</div>
                <div class="lt z-place-gt">食品
                </div>
            </div>
        </div>
    </div>
    <div class="rol z-place-rol">
        <div class="m-box">
            <div class="rol">
                <div class="lt z-place-lt" style="width: 300px;">电子邮箱(接收电子发票)</div>
                <input class="lt z-place-gt" style="width: 330px;" name="email" id="email" placeholder="请填写电子邮箱" />
            </div>
        </div>
    </div>
    <div class="rol z-place-rol">
        <div class="m-box">
            <div class="rol">
                <div class="lt z-place-lt">发票抬头</div>
                <input class="lt z-place-gt" name="invoice" id="invoice" placeholder="请填写发票抬头" />
            </div>
        </div>
    </div>
</div>

<div class="rol qy-box3" style="height: 0"></div>

<?php if($target_arr != null): ?><input type="hidden" name="orderIds" value="<?php echo ($target_arr); ?>"><?php endif; ?>
<?php if($pid != null): ?><input type="hidden" name="productid" value="<?php echo ($pid); ?>"><?php endif; ?>
<?php if($num != null): ?><input type="hidden" name="num" value="<?php echo ($num); ?>"><?php endif; ?>
<div class="rol qy-box4">
    <div class="m-box">
        <div class="lt">总计<span>￥<?php echo ($total_money); ?></span></div>
        <button type="submit" class="gt qy-btn-3" id="doorder" style="border:none">
            立即下单
        </button>
    </div>
</div>
</form>

<script>
$(function(){
    $("input[name='isinvoice']").change(function(event) {
        if ($(this).val()==1) {
            $("#invoicebox").show();
        } else {
            $("#invoicebox").hide();
        }
    });

    $("#doorder").click(function(event) {
        var uname = $("#uname").val();
        var uphone = $("#uphone").val();
        var uaddr = $("#uaddr").val();

        var isinvoice = $("input[name='isinvoice']:checked").val();
        var email = $("#email").val();
        var invoice = $("#invoice").val();

        if(uname.length < 2) {
            alert('请输入收货人姓名！');
            $("#uname").focus();
            return false;
        }

        if(uphone.length < 8) {
            alert('请输入收货人电话！');
            $("#uphone").focus();
            return false;
        }
        if(uaddr.length < 6) {
            alert('请输入详细收货地址！');
            $("#uaddr").focus();
            return false;
        }
        if (isinvoice==1) {
            var emailreg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
            if (! emailreg.test(email) ) {
                alert('请输入正确格式的电子邮箱！');
                $("#email").focus();
                return false;
            }

            if (invoice.length < 1) {
                alert('请输入发票抬头！');
                $("#invoice").focus();
                return false;
            }
        }

        return true;
    });
});
</script>

<?php echo W('Base/showFooter');?>


</body>
</html>