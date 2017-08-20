<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎登录后台管理系统</title>
<link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/cloud.js" type="text/javascript"></script>

<script language="javascript">
$(function(){
    if(window!=top) top.location.href = location.href;

    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
    var win_height = $(window).height();
    var top_height = $('.logintop').height();
    var box_height = $('.loginbox').height();
    if((nouse_height = (win_height-top_height)) > box_height) {
        $('.loginbox').css('top', ((nouse_height-box_height)*0.382)+"px");
    }

    $(window).resize(function(){  
        $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
        var win_height = $(window).height();
        var top_height = $('.logintop').height();
        var box_height = $('.loginbox').height();
        if((nouse_height = (win_height-top_height)) > box_height) {
            $('.loginbox').css('top', ((nouse_height-box_height)/5*2)+"px");
        }
    });
});  
</script> 

</head>

<body style="background-color:#1c77ac; background-image:url(.images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">



    <div id="mainBody">
      <div id="cloud1" class="cloud"></div>
      <div id="cloud2" class="cloud"></div>
    </div>  


<div class="logintop">    
    <span>欢迎登录后台管理界面平台</span>    
    <ul>
    <li><a href="#">回首页</a></li>
    <li><a href="#">帮助</a></li>
    <li><a href="#">关于</a></li>
    </ul>    
    </div>
    
    <div class="loginbody">
    
    <span class="systemlogo"></span> 
       
    <div class="loginbox">
    
    <ul>
	<form action="/Admin/Index/DoLogin" method="post">
    <li><input name="username" type="text" class="loginuser" onclick="JavaScript:this.value=''"/></li>
    <li><input name="password" type="password" class="loginpwd" onclick="JavaScript:this.value=''"/></li>
    <li><input  type="submit" class="loginbtn" value="登录"  /></li>
	</form>
    </ul>
    
    </div>
    
    </div>
    
</body>

</html>