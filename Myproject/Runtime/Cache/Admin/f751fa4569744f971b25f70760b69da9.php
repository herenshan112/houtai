<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Admin/js/jquery.js"></script>

</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    </ul>
    </div>
    
    <div class="mainindex">
    
    
    <div class="welinfo">
    <span><img src="/Public/Admin/images/sun.png" alt="天气" /></span>
    <b><?php echo $_SESSION['r_name']; ?> 您好，欢迎使用信息管理系统</b>
    <a href="<?php echo U('Admin/modpass');?>">修改密码</a>
    </div>
    
    <div class="welinfo">
    <span><img src="/Public/Admin/images/time.png" alt="时间" /></span>
    <i><?php if(array_key_exists("msg", $show_loginlog)){ echo $show_loginlog['msg']; } else { echo '您上次登录的时间：'.date('Y-m-d H:i:s', strtotime($show_loginlog['time'])).' 登录IP：'.$show_loginlog['ip']; } ?></i>
    </div>
    
    <div class="xline"></div>
    
    <div class="uimakerinfo"><b>此网站由“前潮网络”负责开发制作，以及支持维护。</b></div>
    
    <ul class="umlist">
    <li><a href="http://www.qcw100.com" target="_blank">前潮网络</a>官方网站：http://www.qcw100.com</li>
    </ul>
    <br /><br />
    <ul class="umlist">
    <li>技术支持：电话：010-12345678 &#12288; QQ：12345678</li>
    </ul>
    </div>
    
    

</body>

</html>