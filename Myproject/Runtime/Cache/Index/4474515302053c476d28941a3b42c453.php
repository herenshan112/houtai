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
<title>在线留言</title>
</head>
<body>



<div class="rol leave-message-box">
    <?php if($msg) {echo '<p style="text-align: center; color: #F00;">'.$msg.'</p>';}?>
    <form action="/Index/Message/index" method="POST">
    <div class="m-box">
        <input type="text" placeholder="您的姓名" name="name" class="rol leave-input leave-input1" />
        <input type="text" placeholder="请输入您的手机号，方便为您服务" name="phone" class="rol leave-input leave-input2"/>
        <textarea class="rol leave-input leave-input3" name="content" id="message_content">简单描述您的需求</textarea>
        <button type="submit" class="rol sign-submit">提交留言</button>
    </div>
    </form>

    
</div>
<div class="rol" style="text-align: center;margin-top: 50px; font-size: 26px;color: #999">
	客服热线：400-680-9980
</div>


</body>
</html>