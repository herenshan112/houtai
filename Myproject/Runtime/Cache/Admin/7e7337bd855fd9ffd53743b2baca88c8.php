<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/Admin/js/jquery.js"></script>

<script type="text/javascript">
$(function(){	
	//导航切换
	$(".menuson li").click(function(){
		$(".menuson li.active").removeClass("active")
		$(this).addClass("active");
	});
	
	$('.title').click(function(){
		var $ul = $(this).next('ul');
		$('dd').find('ul').slideUp();
		if($ul.is(':visible')){
			$(this).next('ul').slideUp();
		}else{
			$(this).next('ul').slideDown();
		}
	});
})	
</script>


</head>

<body style="background:#f0f9fd;">
	<div class="lefttop"><span></span>后台管理</div>
    
    <dl class="leftmenu">
    <!--dd>
        <div class="title">
        <span><img src="/Public/Admin/images/leftico01.png" /></span>后台首页
        </div>
        <ul class="menuson">
        <li class="active"><cite></cite><a href="/Admin/Admin/right" target="rightFrame">信息</a><i></i></li>
        <li><cite></cite><a href="<?php echo U('Admin/modpass');?>" target="rightFrame">修改密码</a><i></i></li>
        </ul>    
    </dd-->
    <?php echo W('Admin/commonMenu');?>
    </dl>
    
</body>
</html>