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
<title>登录</title>
</head>
<body>

<header class="rol signout-header">
    欢迎登陆商城中心
</header>



<div class="rol">
    <div class="m-box">
        <form action="/Index/User/login" method="POST">
        <div class="rol sing-input-box">
            <div class="rol sign-input">
                <input type="tel" name="phone" id="phone" placeholder="请输入用户名或手机号" class="rol">
            </div>
            <div class="rol sign-input">
                <input type="password" name="password" id="password" placeholder="请输入密码" class="rol">
            </div>
        </div>
        <button type="submit" class="rol sign-out1" id="golog">登陆</button>
        </form>
        <?php if(($setjxs) == "0"): ?><a href="<?php echo U('reg');?>" class="rol sign-out2">注册</a><?php endif; ?>
        <a href="<?php echo U('findpass');?>" class="rol sign-font3" style='color:#0785D1'>
            	找回密码
        </a>
    </div>
</div>
<script>
$("#golog").on('click', function(e){
    var password = $("#password").val();
    var phone = $("#phone").val();
    
    var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;

    /*if(!myreg.test(phone)) { 
        alert('请输入有效的手机号码！'); 
        $("#phone").focus();
        return false; 
    }*/
   if(phone == ''){
   		alert('请输入用户名！'); 
        $("#phone").focus();
        return false; 
   }

    if(password.length < 6) {
        alert('密码长度至少为6位！');
        $("#password").focus();
        return false;
    }

    return true;
});
</script>


</body>
</html>