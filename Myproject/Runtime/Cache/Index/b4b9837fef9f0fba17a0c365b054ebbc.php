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
<title>推荐</title>
</head>
<body>

<header class="rol place-header">
    <div class="m-box">
    	<a href="javascript:history.go(-1);" class="lt">
            <img src="/Public/wx/img/return-1.png" class="pro-return-1" alt="">
        </a>
        <div class="lt pro-header-font">个人推广二维码</div>
        <a href="<?php echo U('Index/index');?>" class="gt">
            <img src="/Public/wx/img/home-1.png" class="pro-home-1" alt="">
        </a>
    </div>
</header>



<div class="rol two-rol">
    <div class="two-ro-box">
        <div class="two-ro-box1">
            <img src="<?php echo ($user_row["headpic"]); ?>" alt="" class="rol" onerror="this.src='/Public/wx/img/nohead.jpg'">
        </div>
    </div>
</div>
<div class="rol center two-font1">昵称：<?php echo ($user_row["nickname"]); ?></div>
<div class="rol center two-font1">账号：<?php echo ($user_row["phone"]); ?></div>
<div class="rol center two-erwei">
    <img src="<?php echo U('Index/qrcode', array('code'=>$reccode, 'op'=>'1'));?>" alt="">
</div>
<div class="rol center two-font2">快来注册吧</div>
<?php echo W('Base/showFooter');?>


</body>
</html>