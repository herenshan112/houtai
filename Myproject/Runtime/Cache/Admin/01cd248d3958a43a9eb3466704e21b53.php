<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>网站后台管理中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<frameset rows="88,*"  frameborder="no" border="0" framespacing="0" >
    <!--头部-->
    <frame src="<?php echo U('Admin/top');?>" name="topFrame" noresize="noresize" frameborder="0"  scrolling="no" marginwidth="0" marginheight="0"  />
    <!--主体部分-->
    <frameset cols="187,*">
        <!--主体左部分-->
        <frame src="<?php echo U('Admin/left');?>" name="leftFrame" noresize="noresize" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" />
        <!--主体右部分-->
        <frame src="<?php echo U('Admin/right');?>" name="rightFrame" frameborder="0" scrolling="auto" marginwidth="0" marginheight="0" />
	</frameset>
</frameset>
</html>