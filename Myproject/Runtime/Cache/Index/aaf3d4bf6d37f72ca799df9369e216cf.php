<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="/Public/index/css/ban.css"/>
    <link rel="stylesheet" href="/Public/index/css/bootstrap.css"/>
    <link rel="stylesheet" href="/Public/index/css/style.css"/>
    <script src="/Public/index/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/index/js/js.js"></script>
    <script src="/Public/index/js/jquery.js"></script>
    <script src="/Public/index/js/iscroll.js"></script>
    <script src="/Public/index/js/myjs.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>搜索</title>
</head>
<body>



<div class="rol ss-con">
    <div class="rol sc-pro-top">
    	<form method="post" action="<?php echo U('Message/sousuo');?>" name="ssform">
        <input class="sc-pro-ss-txt" type="submit" value="搜索"/>
        <div class="cp-ss ss-list">
            <input class="sc-pro-ss-inp" type="search"/>
            <input class="sc-pro-txt ss" type="text" placeholder="请输入您要搜索的商品" name="ssgjc" />
        </div>
        </form>
    </div>
    <div class="rol djss-box">
        <div class="djss">大家都在搜</div>
        <div class="rol djss-list">
        	<?php if(is_array($djdzs)): $i = 0; $__LIST__ = $djdzs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$djdzs): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Message/sousuo',array('ssgjc'=>$djdzs['ss_title']));?>"><span><?php echo ($djdzs["ss_title"]); ?></span></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="rol lsss-list">
        <div class="m-box">
        	<?php if(is_array($wdls)): $i = 0; $__LIST__ = $wdls;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wdls): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Message/sousuo',array('ssgjc'=>$wdls['ss_title']));?>"><div class="lsss"><?php echo ($wdls["ss_title"]); ?></div></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="rol">
        <div class="qk ">
            <a href="<?php echo U('Message/qcsousuo');?>"><span></span><input class="qk-txt" type="text" placeholder="清空历史搜索"/></a>
        </div>

    </div>
</div>


</body>
</html>