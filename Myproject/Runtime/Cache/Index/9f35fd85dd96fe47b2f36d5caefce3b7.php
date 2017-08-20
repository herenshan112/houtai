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
<title>产品中心</title>
</head>
<body>

<div class="rol ind-top">
    <div class="m-box scsy-top"><a href="<?php echo U('Index/index');?>">
        <img class="lt scsy-logo" src="/Public/index/img/logo.png" alt=""/></a>
        <form method="post" action="<?php echo U('Message/sousuo');?>" name="ssform">
	        <input class="scsy-ss" type="text" placeholder="请输入您要搜索的商品" name="ssgjc" />
	        <input class="scsy-ss-txt" type="submit" value="搜索"/>
        </form>
    </div>
</div>



<!--banner-->
<div class="rol">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="text-align:center">
            <div class="item active">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
            <div class="item">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
            <div class="item">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
        </div>
        <!-- Controls -->
    </div>
</div>


<!--导航-->
<div class="rol">
    <div class="m-box ">
        <div class="rol scsy-nav1">
            <a href="<?php echo U('prolist',array('action'=>'tj'));?>">
                <b class="tj1"></b>
                <div>特价</div>
            </a>
            <a href="<?php echo U('prolist',array('action'=>'xp'));?>">
                <b class="tj2"></b>
                <div>新品</div>
            </a>
            <a href="<?php echo U('prolist',array('action'=>'tuij'));?>">
                <b class="tj3"></b>
                <div>推荐</div>
            </a>
            <a href="<?php echo U('prolist');?>">
                <b class="tj4"></b>
                <div>产品</div>
            </a>
        </div>
        <img class="img" src="/Public/index/img/scsy1.jpg" alt=""/>
        <div class="scsy-rmfl-nav">
            <div class="scsy-rmfl-tit-ch"><b></b><span>热门分类</span><b></b></div>
            <div class="scsy-rmfl-tit-en">Hot translation</div>
        </div>
    </div>
</div>
<!--分类1-->
<div class="rol scsy-rm-nav-box">
    <div class="m-box scsy-rm-nav">
    	<?php if(is_array($typelist)): $jb = 0; $__LIST__ = $typelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$typelist): $mod = ($jb % 2 );++$jb;?><a href="<?php echo U('prolist',array('action'=>'type','tpval'=>$typelist['id']));?>"><div ><?php echo ($typelist["title"]); ?></div></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>

<!--昨日热销-->
<div class="rol">
    <div class="zrrx-img"><img src="/Public/index/img/zrrx.png" alt=""/></div>
    <div class="zrrx-list">
    	<?php if(is_array($zrhotx)): $i = 0; $__LIST__ = $zrhotx;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$zrhotx): $mod = ($i % 2 );++$i;?><a href="<?php echo U('procont',array('id'=>$zrhotx['id']));?>">
	            <img src="<?php echo ($zrhotx["titlepic"]); ?>" alt="<?php echo ($zrhotx["title"]); ?>"/>
	            <div class="zrrx-list-txt"><span><?php echo ($zrhotx["tyname"]); ?></span><?php echo ($zrhotx["title"]); ?></div>
	            <div class="zrrx-list-bot">
	               <div class="lt zrrx-jh">
	               	<?php if(in_array(($zrhotx["tejia"]), explode(',',"2,5,6"))): if(($zrhotx["tejiaprice"]) != "0"): ?><span>￥<?php echo ($zrhotx["tejiaprice"]); ?></span>
	               			<s class="rjg">¥ <?php echo ($zrhotx["price"]); ?></s>
	               		<?php else: ?>
	               			<span>￥<?php echo ($zrhotx["price"]); ?></span><?php endif; ?>
	               	<?php else: ?>
	               		<span>￥<?php echo ($zrhotx["price"]); ?></span><?php endif; ?>
	               </div>
	                <div class="gt zrrx-fx">
	                    <span class="zrrx-fx-img"></span>
	                    <div class="zrrx-fx-txt">月销<?php echo ($zrhotx["salenum"]); ?>笔</div>
	                </div>
	            </div>
	            <?php switch($zrhotx["tejia"]): case "1": ?><span class="rm">热卖</span><?php break;?>    
	            	<?php case "2": ?><span class="rm">促销</span><?php break;?>   
	            	<?php case "3": ?><span class="rm">新品</span><?php break;?>   
	            	<?php case "4": ?><span class="rm">超值</span><?php break;?>   
	            	<?php case "5": ?><span class="rm">打折</span><?php break;?>   
	            	<?php case "6": ?><span class="rm">特价</span><?php break;?>   
	            	<?php default: endswitch;?>
	        </a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<!--新品推荐-->
<div class="rol ind-new">
    <div class="zrrx-img"><img src="/Public/index/img/xptj.png" alt=""/></div>
    <div class="zrrx-list">
    	<?php if(is_array($xinptj)): $i = 0; $__LIST__ = $xinptj;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$xinptj): $mod = ($i % 2 );++$i;?><a href="<?php echo U('procont',array('id'=>$xinptj['id']));?>">
            <img src="<?php echo ($xinptj["titlepic"]); ?>" alt=""/>
            <div class="zrrx-list-txt"><span><?php echo ($xinptj["tyname"]); ?></span><?php echo ($xinptj["title"]); ?></div>
            <div class="zrrx-list-bot">
                <div class="lt zrrx-jh pro-jg2">
                	<?php if(in_array(($xinptj["tejia"]), explode(',',"2,5,6"))): if(($xinptj["tejiaprice"]) != "0"): ?><span>￥<?php echo ($xinptj["tejiaprice"]); ?></span>
	               			<s class="rjg">¥ <?php echo ($xinptj["price"]); ?></s>
	               		<?php else: ?>
	               			<span>￥<?php echo ($xinptj["price"]); ?></span><?php endif; ?>
	               	<?php else: ?>
	               		<span>￥<?php echo ($xinptj["price"]); ?></span><?php endif; ?>
                </div>
                <div class="gt zrrx-fx">
                    <span class="zrrx-fx-img"></span>
                    <div class="zrrx-fx-txt">月销<?php echo ($xinptj["salenum"]); ?>笔</div>
                </div>
            </div>
            <?php switch($xinptj["tejia"]): case "1": ?><span class="rm">热卖</span><?php break;?>    
	            	<?php case "2": ?><span class="rm">促销</span><?php break;?>   
	            	<?php case "3": ?><span class="rm">新品</span><?php break;?>   
	            	<?php case "4": ?><span class="rm">超值</span><?php break;?>   
	            	<?php case "5": ?><span class="rm">打折</span><?php break;?>   
	            	<?php case "6": ?><span class="rm">特价</span><?php break;?>    
            	<?php default: endswitch;?>
        </a><?php endforeach; endif; else: echo "" ;endif; ?>
        
    </div>
</div>
<script src="/Public/index/js/jquery-3.0.0.min.js"></script>
<script src="/Public/index/js/bootstrap.min.js"></script>

<?php echo W('Base/showFooter');?>


</body>
</html>