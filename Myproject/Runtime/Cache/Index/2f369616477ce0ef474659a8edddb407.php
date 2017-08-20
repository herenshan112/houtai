<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">


    <link rel="stylesheet" href="/Public/index/css/bootstrap.css"/>
    <link rel="stylesheet" href="/Public/index/css/style.css"/>
    <script src="/Public/index/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/index/js/js.js"></script>
    <script src="/Public/index/js/jquery.js"></script>
    <script src="/Public/index/js/myjs.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>搜索</title>
</head>
<body>



<div class="rol sc-pro-top">
	<form method="post" action="<?php echo U('Message/sousuo');?>" name="ssform">
    <input class="sc-pro-ss-txt" type="submit" value="搜索"/>
    <div class="cp-ss">
        <input class="sc-pro-ss-inp" type="search"/>
        <input class="sc-pro-txt sp-ss" type="text" placeholder="净水器" name="ssgjc" />
        <div class="gb"></div>
    </div>
    </form>
</div>
<div class="rol">
    <div class="zrrx-list rol">
    	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; endforeach; endif; else: echo "" ;endif; ?>
        <div class="rol cwzx-cp-list ddzx-cp-list">
        	<a href="<?php echo U('Mall/procont',array('id'=>$list['id']));?>">
            <div class="lt cwzx-cp-lt">
                <img  src="<?php echo ($list["titlepic"]); ?>" alt="<?php echo ($list["title"]); ?>" />
            </div>
            <div class="lt cwzx-cp-gt cwzx-cp-gt1">
                <div class="cwzx-cp-tit">
                    <span><?php echo ($list["tyname"]); ?></span><?php echo ($list["title"]); ?>
                </div>
                <div class="cwzx-cp-jg yx">
                    <div class="zrrx-fx-txt yx-txt">月销<?php echo ($list["salenum"]); ?>笔</div>
                    <?php if(in_array(($list["tejia"]), explode(',',"2,5,6"))): if(($list["tejiaprice"]) != "0"): ?><span  class="lt ddzx-jk">￥<?php echo ($list["tejiaprice"]); ?></span>
	               			<s class="rjg">¥ <?php echo ($list["price"]); ?></s>
	               		<?php else: ?>
	               			<span  class="lt ddzx-jk">￥<?php echo ($list["price"]); ?></span><?php endif; ?>
	               	<?php else: ?>
	               		<span  class="lt ddzx-jk">￥<?php echo ($list["price"]); ?></span><?php endif; ?>
                </div>
            </div>
           </a>
        </div>
        </volist>
        
    </div>

</div>


</body>
</html>