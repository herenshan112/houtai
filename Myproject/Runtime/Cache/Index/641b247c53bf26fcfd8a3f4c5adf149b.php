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
<title>购物车</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
        <a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">购物车</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>



<div class="rol buy-title">
    <div class="m-box">
        <div class="lt buy-all buy-btn-box multi_select">
            <img src="/Public/wx/img/buy-on.png" class="lt" alt="">
        </div>
        <div class="lt">全选</div>
        <div class="lt buy-font1">商品</div>
        <div class="gt">操作</div>
    </div>
</div>

<?php if(is_array($cart_rows)): foreach($cart_rows as $key=>$cart_rows_item): ?><div class="rol buy-box2">
    <div class="m-box">
        <div class="lt buy-one buy-btn-box single_select" data-id="<?php echo ($cart_rows_item["id"]); ?>">
            <img src="/Public/wx/img/buy-on.png" class="lt" alt="">
        </div>
        <div class="gt buy-sp calc_wrap">
            <div class="lt buy-img">
                <img src="<?php echo ($cart_rows_item["detail"]["titlepic"]); ?>" alt="" class="rol">
            </div>
            <div class="lt buy-box1 calc_box" data-id="<?php echo ($cart_rows_item["id"]); ?>">
                <div class="rol buy-title1">
                    <div class="lt"><?php echo ($cart_rows_item["detail"]["title"]); ?></div>
                    <div class="gt buy-d single_delete" data-id="<?php echo ($cart_rows_item["id"]); ?>">
                        <img src="/Public/wx/img/buy-d.png" alt="">
                    </div>
                </div>
                <div class="rol buy-money">￥<span class="money_number"><?php echo ($cart_rows_item["detail"]["price"]); ?></span></div>
                <div class="rol buy-title2">数量：<div class="num_btn_wrap"><div class="minus_btn num_btn num_btn1">-</div><input type="tel" data-id="<?php echo ($cart_rows_item["id"]); ?>" class="count_number" value="<?php echo ($cart_rows_item["num"]); ?>" /><div class="plus_btn num_btn num_btn2">+</div></div></div>
            </div>
        </div>
    </div>
</div><?php endforeach; endif; ?>

<div class="rol buy-footer" style="position: fixed;bottom: 0;left: 0;">
    <div class="lt buy-f-btn1">
        商品价格<span id="totalmoney">0</span>元
    </div>
    <div class="lt buy-f-btn2" id="doorder" style="cursor: pointer;">
        去下单
    </div>
</div>

<form action="<?php echo U('doorder');?>" method="GET" id="orderForm">
<input type="hidden" name="orderIds" value="">
</form>
<script>
$(function(){
	var tmp_num = 0;
	$(".minus_btn").click(function(event) {
        var next_node = $(this).next('.count_number');
		var next_val = $(this).next('.count_number').val();
        var this_id = $(this).next('.count_number').data('id');
		if (next_val > 1) {
            $.ajax({
                url: '<?php echo U('ajaxshopcart');?>',
                type: 'POST',
                dataType: 'json',
                data: {'id':this_id, 'num': (next_val-1)},
                success: function(data) {
                    if (data.code=='1') {
                        next_node.val(--next_val);
                    }
                },
            });
		}
    });
    
    $(".plus_btn").click(function(event) {
        var prev_node = $(this).prev('.count_number');
		var next_val = $(this).prev('.count_number').val();
		var this_id = $(this).prev('.count_number').data('id');

        $.ajax({
            url: '<?php echo U('ajaxshopcart');?>',
            type: 'POST',
            dataType: 'json',
            data: {'id':this_id, 'num': (parseInt(next_val)+1)},
            success: function(data) {
                if (data.code=='1') {
                    prev_node.val(++next_val);
                }
            },
        });
		//$(this).prev('.count_number').val(++next_val);
    });

    $(".count_number").focusin(function(event) {
    	tmp_num = $(this).val();
    });

    $(".count_number").focusout(function(event) {
    	var this_num = $(this).val();
        var this_id = $(this).data('id');
    	if(isNaN(this_num)){
			alert("请输入正确数量！");
			$(this).val(tmp_num);
			return false;
		} else {
            $.ajax({
                url: '<?php echo U('ajaxshopcart');?>',
                type: 'POST',
                dataType: 'json',
                data: {'id':this_id, 'num':this_num},
                success: function(data) {
                    if (data.code=='-1') {
                        $(this).val(tmp_num);
                    } else if (data.code=='1') {
                        $(this).val(this_num);
                    }
                },
            });
        }
    });
    

    // 删除
    $(".single_delete").click(function(event) {
        if (confirm("确认要删除该商品吗?")) {
            var op_id = $(this).data('id');
            window.location.href = "<?php echo U('delcart');?>?id="+op_id
        }
    });

    // 单选
    $(".single_select").click(function(event) {
        var total_num = 0;
        var id_str = '';
        $(".single_select").each(function(index, el) {
            var tmp_note = $(".single_select:eq("+index+")");
            if (tmp_note.hasClass('on')) {
                id_str += tmp_note.data('id') + ",";
                var m_num = tmp_note.next('.calc_wrap').find('.money_number').text();
                var c_num = tmp_note.next('.calc_wrap').find('.count_number').val();
                total_num += parseFloat(m_num) * parseInt(c_num);
            }
        });
        $("input[name='orderIds']").val(id_str);
        $("#totalmoney").text(total_num);
    });

    // 多选
    $(".multi_select").click(function(event) {
        if ($(this).hasClass('on')) {
            var total_num = 0;
            var id_str = '';
            $(".calc_box").each(function(index, el) {
                id_str += $(".calc_box:eq("+index+")").data('id') + ",";
                var m_num = $(".calc_box:eq("+index+")").find('.money_number').text();
                var c_num = $(".calc_box:eq("+index+")").find('.count_number').val();
                total_num += parseFloat(m_num) * parseInt(c_num);
            });
            $("#totalmoney").text(total_num);
            $("input[name='orderIds']").val(id_str);
        } else {
            $("#totalmoney").text(0);
            $("input[name='orderIds']").val('');
        }
    });

    // 下单
    $("#doorder").click(function(event) {
        var orderIds = $("input[name='orderIds']").val();
        if (orderIds) {
            $("#orderForm").submit();
        } else {
            alert('请选择下单商品！');
        }
    });
});
</script>
<style type="text/css">
.num_btn_wrap {
	border:1px solid #4d4d4d;
	position: absolute;
	margin-top: -35px;
	margin-left: 80px;
	width: 165px;
	height: 45px;
	line-height: 45px;
	font-size: 30px;
	border-radius: 5px;
	clear: both;
}
.num_btn {
	text-align: center;
	width: 45px;
	float: left;
}
.num_btn1 {
	border-right: 1px solid #4d4d4d;
}
.num_btn2 {
	border-left: 1px solid #4d4d4d;
}
.count_number {
	box-sizing: border-box;
	font-size: 30px;
	color: #4d4d4d;
	line-height: 45px;
	margin: 0;
	padding: 0;
	border: none;
	width: 73px;
	float: left;
	text-align: center;
}
</style>


</body>
</html>