<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/Public/Admin/js/jquery.js"></script>
<script type="text/javascript">
$(function(){	
	//顶部导航切换
	$(".nav li a").click(function(){
		$(".nav li a.selected").removeClass("selected")
		$(this).addClass("selected");
	})	
})	
</script>


</head>

<body style="background:url(/Public/Admin/images/topbg.gif) repeat-x;">

    <div class="topleft">
    <a href="/" target="_blank"><img src="/Public/Admin/images/logo.png" title="系统首页" /></a>
    </div>
        
    <ul class="nav">
   <!--  <li><a href="default.html" target="rightFrame" class="selected"><img src="/Public/Admin/images/icon01.png" title="工作台" /><h2>工作台 --></h2></a></li>
    <!-- <li><a href="imgtable.html" target="rightFrame"><img src="/Public/Admin/images/icon02.png" title="模型管理" /><h2>模型管理</h2></a></li>
    <li><a href="imglist.html"  target="rightFrame"><img src="/Public/Admin/images/icon03.png" title="模块设计" /><h2>模块设计</h2></a></li>
    <li><a href="tools.html"  target="rightFrame"><img src="/Public/Admin/images/icon04.png" title="常用工具" /><h2>常用工具</h2></a></li>
    <li><a href="computer.html" target="rightFrame"><img src="/Public/Admin/images/icon05.png" title="文件管理" /><h2>文件管理</h2></a></li>
    <li><a href="tab.html"  target="rightFrame"><img src="/Public/Admin/images/icon06.png" title="系统设置" /><h2>系统设置</h2></a></li> -->
    </ul>
            
    <div class="topright">    
    <ul>
   <!--  <li><span><img src="/Public/Admin/images/help.png" title="帮助"  class="helpimg"/></span><a href="#">帮助</a></li>
    <li><a href="#">关于</a></li> -->
    <li><a href="<?php echo U('orders/showlists');?>" target="rightFrame" style="font-size: 16px;">未查看订单(<font color='#ff0000' ><?php echo ($oednm); ?></font>)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('message/showlists');?>" target="rightFrame" style="font-size: 16px;">未查看留言(<font color='#ff0000'><?php echo ($mesnm); ?></font>)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Index/logout');?>" target="_parent">退出</a></li>
    </ul>
    
    <div class="user">
    <span><?php echo (session('r_name')); ?></span>
    <i>&nbsp;</i>
    <i>&nbsp;</i>
    <i>&nbsp;</i>
    
    </div>

    </div>
<script>setTimeout("location=location; ", 5000); </script>
</body>
</html>